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
use Modules\CertificateRecognition\app\Models\CertificateRecognitionMaterials;

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

        if (auth()->user()->hasRole('Super Admin')) {
            $query->where('status', '!=', CertificateRecognition::STATUS_IS_DRAFT);
        } elseif (auth()->user()->hasRole('Admin OPD')) {
            $query->where('instansi_id', auth()->user()->instansi_id);
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
        $instansis  = Instansi::all();
        $users      = User::with('instansi')->where('role', 'student')->get();

        $certificateRecognition = session('certificateRecognition', null);

        return view('certificaterecognition::create', compact('instansis', 'users', 'certificateRecognition'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'instansi_id'           => 'required|exists:instansis,id',
            'name'                  => 'required|string|max:255',
            'goal'                  => 'required|string',
            'competency'            => 'required|string',
            'indicator_of_success'  => 'required|string',
            'activity_plan'         => 'required|string',
            'start_at'              => 'required|date',
            'end_at'                => 'required|date|after_or_equal:start_at',
            'status'                => 'required|in:is_draft,verification',
            'participants'          => 'required|array',
            'participants.*'        => 'required|exists:users,id',
            'documentation_link'    => 'required|url',
        ], [
            'instansi_id.required'           => 'Instansi wajib diisi.',
            'instansi_id.exists'             => 'Instansi tidak valid.',
            'name.required'                  => 'Nama wajib diisi.',
            'name.string'                    => 'Nama harus berupa string.',
            'name.max'                       => 'Nama tidak boleh lebih dari 255 karakter.',
            'goal.required'                  => 'Tujuan wajib diisi.',
            'goal.string'                    => 'Tujuan harus berupa string.',
            'competency.required'            => 'Kompetensi wajib diisi.',
            'competency.string'              => 'Kompetensi harus berupa string.',
            'indicator_of_success.required'  => 'Indikator keberhasilan wajib diisi.',
            'indicator_of_success.string'    => 'Indikator keberhasilan harus berupa string.',
            'activity_plan.required'         => 'Rencana kegiatan wajib diisi.',
            'activity_plan.string'           => 'Rencana kegiatan harus berupa string.',
            'start_at.required'              => 'Tanggal mulai wajib diisi.',
            'start_at.date'                  => 'Tanggal mulai harus berupa tanggal yang valid.',
            'end_at.required'                => 'Tanggal akhir wajib diisi.',
            'end_at.date'                    => 'Tanggal akhir harus berupa tanggal yang valid.',
            'end_at.after_or_equal'          => 'Tanggal akhir harus setelah atau sama dengan tanggal mulai.',
            'status.required'                => 'Status wajib diisi.',
            'status.in'                      => 'Status harus berupa is_draft atau verification.',
            'participants.required'          => 'Peserta wajib diisi.',
            'participants.array'             => 'Peserta harus berupa array.',
            'participants.*.required'        => 'Peserta harus diisi.',
            'participants.*.exists'          => 'Peserta tidak valid.',
            'documentation_link.required'    => 'Link dokumentasi wajib diisi.',
            'documentation_link.url'         => 'Link dokumentasi harus berupa URL yang valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $certificate = CertificateRecognition::create([
            'instansi_id'           => $request->instansi_id,
            'name'                  => $request->name,
            'goal'                  => $request->goal,
            'competency'            => $request->competency,
            'indicator_of_success'  => $request->indicator_of_success,
            'activity_plan'         => $request->activity_plan,
            'start_at'              => $request->start_at,
            'end_at'                => $request->end_at,
            'status'                => $request->status,
            'is_approved'           => 'pending',
            'certificate_status'    => 'pending',
            'documentation_link'    => $request->documentation_link
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

        $requestMaterialNames = array_unique($request->materi);
        $dataToUpsert = [];
        $jpTotal = 0;

        foreach ($request->jp as $key => $jp) {
            $dataToUpsert[] = [
                'certificate_recognition_id' => $certificate->id,
                'name' => $request->materi[$key],
                'jp' => $jp
            ];
            $jpTotal += $jp;
        }

        // Bulk upsert operation
        DB::transaction(function () use ($dataToUpsert, $certificate, $requestMaterialNames) {
            CertificateRecognitionMaterials::upsert(
                $dataToUpsert,
                ['certificate_recognition_id', 'name'],
                ['jp']
            );

            // Bulk delete obsolete records
            CertificateRecognitionMaterials::where('certificate_recognition_id', $certificate->id)
                ->whereNotIn('name', $requestMaterialNames)
                ->delete();
        });

        $certificate->update([
            'jp' => $jpTotal
        ]);

        return redirect()->route('admin.certificate-recognition.index')->with('success', 'Certificate Recognition created successfully.');
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
