<?php

namespace Modules\CertificateRecognition\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\Instansi;
use Modules\CertificateRecognition\app\Models\CertificateRecognition;
use Modules\CertificateRecognition\app\Models\CertificateRecognitionEnrollment;
use Modules\Article\app\Models\Article;
use Illuminate\Support\Facades\DB;

class CertificateRecognitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $query = CertificateRecognition::query();

        if ($request->has('is_approved') && $request->query('is_approved') != '') {
            $is_approved = $request->query('is_approved', request('is_approved'));
            $query->where('is_approved', $is_approved);
        }

        if ($request->has('certificate_status') && $request->query('certificate_status') != '') {
            $certificate_status = $request->query('certificate_status', request('certificate_status'));
            $query->where('certificate_status', $certificate_status);
        }

        $certificateRecognitions = $query
            ->orderByDesc('updated_at')
            ->paginate(10);

        return view('certificaterecognition::index', compact('certificateRecognitions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $instansis = Instansi::all();
        $users = User::all();

        $certificateRecognition = session('certificateRecognition', null);

        return view('certificaterecognition::create', compact('instansis', 'users', 'certificateRecognition'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'instansi_id' => 'required|exists:instansis,id',
            'name' => 'required|string|max:255',
            'goal' => 'nullable|string',
            'competency' => 'nullable|string',
            'indicator_of_success' => 'nullable|string',
            'activity_plan' => 'nullable|string',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'jp' => 'nullable|integer|min:0',
            'status' => 'required|in:is_draft,active,inactive',
            'participants' => 'nullable|array',
            'participants.*' => 'nullable|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $certificate = CertificateRecognition::create([
            'instansi_id' => $request->instansi_id,
            'name' => $request->name,
            'goal' => $request->goal,
            'competency' => $request->competency,
            'indicator_of_success' => $request->indicator_of_success,
            'activity_plan' => $request->activity_plan,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
            'jp' => $request->jp ?? 0,
            'status' => $request->status,
            'is_approved' => 'pending',
            'certificate_status' => 'pending',
        ]);

        if ($request->has('participants') && is_array($request->participants)) {
            $uniqueUserIds = collect($request->participants)->unique();

            foreach ($uniqueUserIds as $userId) {
                CertificateRecognitionEnrollment::create([
                    'certificate_recognition_id' => $certificate->id,
                    'user_id' => $userId,
                ]);
            }
        }

        return redirect()->route('certificaterecognition.create')
            ->with('success', __('Certificate of Recognition created successfully.'));
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        checkAdminHasPermissionAndThrowException('certificate.recognition.view');
        $certificate = CertificateRecognition::with('instansi', 'certificate', 'users')->find($id);
        $users = $certificate->users()->paginate(10);
        return view('certificaterecognition::verify', compact('certificate', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('certificaterecognition::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        checkAdminHasPermissionAndThrowException('certificate.recognition.destroy');
        $certificate = CertificateRecognition::find($id);
        $certificate->delete();
        return redirect()->route('admin.certificate-recognition.index')->with('success', 'Successfully deleted certificate recognition');
    }

    public function verify($id)
    {
        checkAdminHasPermissionAndThrowException('certificate.recognition.verify');
        $certificate = CertificateRecognition::with('instansi', 'certificate', 'users')->find($id);
        $users = $certificate->users()->paginate(10);
        return view('certificaterecognition::verify', compact('certificate', 'users'));
    }

    public function verifyUpdate(Request $request, $id)
    {
        checkAdminHasPermissionAndThrowException('certificate.recognition.verify');
        $certificate = CertificateRecognition::find($id);

        if ($request->status === CertificateRecognition::IS_APPROVED_APPROVED) {
            $certificate->update([
                'is_approved' => CertificateRecognition::IS_APPROVED_APPROVED,
                'status' => CertificateRecognition::STATUS_PUBLISHED,
                'certificate_status' => CertificateRecognition::CERTIFICATE_STATUS_FINISH,
            ]);
        }

        if ($request->status === CertificateRecognition::IS_APPROVED_REJECTED) {
            $certificate->update([
                'is_approved' => CertificateRecognition::IS_APPROVED_REJECTED,
                'status' => CertificateRecognition::STATUS_REJECTED,
                'notes' => $request->reason,
                'certificate_status' => CertificateRecognition::CERTIFICATE_STATUS_PROCESS,
            ]);
        }

        return redirect()->route('admin.certificate-recognition.index')->with('success', 'Successfully updated certificate status');
    }
}
