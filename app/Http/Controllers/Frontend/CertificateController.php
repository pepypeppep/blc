<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CertificateCollection;
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
        // $result = $certificateService->getCertificatesForUser($request, $user_id);

        // return view('frontend.student-dashboard.certificate.page', [
        //     'certificates' => json_decode(json_encode($result['data'])),
        //     'totalJp' => json_decode(json_encode($result['totalJp'])),
        //     'totalJpPerTriwulan' => $result['totalJpPerTriwulan'],
        // ]);

        $sql = CertificateCollection::where('user_id', $user_id);

        if ($request->has('year')) {
            $sql->where('year', $request->year);
        }

        $result = $sql->orderBy('start_at', 'desc')->get();

        // Calculate totals before pagination
        $totalJp = array_reduce($result->toArray(), function ($total, $item) {
            return $total + $item['jp'];
        }, 0);

        $totalJpPerTriwulan = array_fill(1, 4, 0);
        foreach ($result->toArray() as $item) {
            $triwulan = $item['triwulan'];
            $totalJpPerTriwulan[$triwulan] = array_reduce(array_filter($result->toArray(), function ($certificate) use ($triwulan) {
                return $certificate['triwulan'] == $triwulan;
            }), function ($total, $item) {
                return $total + $item['jp'];
            }, 0);
        }

        return view('frontend.student-dashboard.certificate.page', [
            'certificates' => $result,
            'totalJp' => $totalJp,
            'totalJpPerTriwulan' => $totalJpPerTriwulan,
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
