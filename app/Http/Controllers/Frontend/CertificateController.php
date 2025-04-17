<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\Order\app\Models\Enrollment;

class CertificateController extends Controller
{

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
