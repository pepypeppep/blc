<?php

namespace Modules\CertificateRecognition\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\CertificateRecognition\app\Models\PersonalCertificateRecognition;

class PersonalCertificateRecognitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PersonalCertificateRecognition::query();

        if ($request->has('is_approved') && $request->query('is_approved') != '') {
            $is_approved = $request->query('is_approved', request('is_approved'));
            $query->where('is_approved', $is_approved);
        }

        if ($request->has('certificate_status') && $request->query('certificate_status') != '') {
            $certificate_status = $request->query('certificate_status', request('certificate_status'));
            $query->where('certificate_status', $certificate_status);
        }

        if (auth()->user()->hasRole('Super Admin')) {
            $query->where('status', '!=', 'draft');
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
        return view('certificaterecognition::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        checkAdminHasPermissionAndThrowException('sertifikat.pengakuan.view');
        $pengakuan = PersonalCertificateRecognition::with('competency_development', 'article', 'user')->find($id);
        return view('certificaterecognition::show', compact('pengakuan'));
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        checkAdminHasPermissionAndThrowException('sertifikat.pengakuan.destroy');
        $certificate = PersonalCertificateRecognition::find($id);
        $certificate->delete();
        return redirect()->route('admin.certificate-recognition.index')->with('success', 'Successfully deleted certificate recognition');
    }
}
