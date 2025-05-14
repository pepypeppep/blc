<?php

namespace Modules\CertificateRecognition\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Article\app\Models\Article;
use Illuminate\Support\Facades\DB;
use Modules\CertificateRecognition\app\Models\CertificateRecognition;

class CertificateRecognitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $query = CertificateRecognition::query();

        if($request->has('is_approved') && $request->query('is_approved') != ''){
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
        return view('certificaterecognition::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        checkAdminHasPermissionAndThrowException('certificate.recognition.view');
        $certificate = CertificateRecognition::with('instansi', 'certificate','users')->find($id);
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
        $certificate = CertificateRecognition::with('instansi', 'certificate','users')->find($id);
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
