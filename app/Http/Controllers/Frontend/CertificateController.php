<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\CertificateService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Modules\Order\app\Models\Enrollment;

class CertificateController extends Controller
{
    public function index(Request $request, CertificateService $certificateService)
    {
        $user_id = Auth::user()->id;
        $result = $certificateService->getCertificatesForUser($request, $user_id);

        // if (!$result['success']) {
        //     return redirect()->back()->with('error', $result['message']);
        // }

        return view('frontend.student-dashboard.certificate.page', [
            'certificates' => json_decode(json_encode($result['data'])),
            'totalJp' => json_decode(json_encode($result['totalJp'])),
            'totalJpPerTriwulan' => $result['totalJpPerTriwulan'],
        ]);
    }

    function publicCertificate(Request $request, string $uuid)
    {
        $enrollment = Enrollment::where('uuid', $uuid)->firstOrFail();

        $pdfPath = $enrollment->certificate_path;
        if (!$pdfPath) {
            return redirect()->back()->with(['messege' => __('Certificate not found'), 'alert-type' => 'error']);
        }

        // check if file exists
        if (!Storage::disk('private')->exists($pdfPath)) {
            return redirect()->back()->with(['messege' => __('Certificate not found'), 'alert-type' => 'error']);
        }

        return Storage::disk('private')->response($pdfPath);
    }
}
