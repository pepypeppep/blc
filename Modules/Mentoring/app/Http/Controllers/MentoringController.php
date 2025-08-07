<?php

namespace Modules\Mentoring\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use iio\libmergepdf\Merger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Mentoring\app\Models\Mentoring;
use Modules\Mentoring\app\Models\MentoringReview;
use Modules\Mentoring\app\Models\MentoringSession;
use Modules\CertificateBuilder\app\Http\Requests\CertificateUpdateRequest;
use Modules\CertificateBuilder\app\Models\CertificateBuilder;
use Modules\CertificateBuilder\app\Models\CertificateBuilderItem;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Modules\Mentoring\app\Models\MentoringFeedback;
use Modules\Mentoring\app\Models\MentoringSigner;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MentoringController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $mentors = Mentoring::with('mentor')
            ->withCount('mentee')
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            // ->where('status', '!=', 'draft')
            ->orderByDesc('id')
            ->paginate(10)
            ->appends($request->query());

        $statusCounts = Mentoring::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status');

        $totalMentors = Mentoring::count();

        return view('mentoring::index', compact('mentors', 'statusCounts', 'totalMentors', 'status'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('mentoring::create');
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
        $mentoring = Mentoring::with('mentor', 'mentee', 'mentoringSessions')->find($id);

        if (!$mentoring) {
            return redirect()->back()->with([
                'message' => 'Mentoring not found',
                'alert-type' => 'error'
            ]);
        }

        $hasIncompleteSessions = $mentoring->mentoringSessions->contains(function ($session) {
            return empty($session->activity);
        });

        // Cek apakah file surat tersedia secara fisik
        $fileExists = false;
        if ($mentoring->mentor_availability_letter) {
            $filePath = 'public/' . $mentoring->mentor_availability_letter;
            $fileExists = Storage::exists($filePath);
        }

        $certificates = CertificateBuilder::paginate();

        return view('mentoring::show', compact('mentoring', 'hasIncompleteSessions', 'certificates', 'fileExists'));
    }
    public function viewImage($id)
    {
        $session = MentoringSession::findOrFail($id);
        if (Storage::disk('private')->exists($session->image)) {
            return Storage::disk('private')->response($session->image);
        } else {
            abort(404);
        }
    }

    public function showDocument($id, $type)
    {
        $mentoring = Mentoring::findOrFail($id);
        return $mentoring->getDocumentResponse($type);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('mentoring::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    public function menteeEvaluasi(Request $request, $id, $mentorId)
    {
        // Validate mentoring session
        $mentoring = Mentoring::findOrFail($id);
        if ($mentoring->mentor_id != $mentorId) {
            return redirect()->back()->with(['messege' => 'Mentoring not found for this mentor', 'alert-type' => 'error']);
        }

        // Get review data
        $review = MentoringReview::where('mentoring_id', $mentoring->id)->first();

        return view('mentoring::mentee-evaluation', compact('mentoring', 'review'));
    }

    public function mentorEvaluasi(Request $request, $id, $mentorId)
    {
        // Validate mentoring session
        $mentoring = Mentoring::findOrFail($id);
        if ($mentoring->mentor_id != $mentorId) {
            return redirect()->back()->with(['messege' => 'Mentoring not found for this mentor', 'alert-type' => 'error']);
        }

        // Get review data
        $review = MentoringFeedback::where('mentoring_id', $mentoring->id)->first();

        return view('mentoring::mentor-evaluation', compact('mentoring', 'review'));
    }

    /**
     * Update certificate for mentoring
     */
    public function updateCertificate(Request $request, $id): JsonResponse
    {
        $request->validate([
            'certificate_id' => ['required', 'exists:certificate_builders,id'],
            'tte_depan' => ['nullable', 'exists:users,id'],
            'tte_belakang' => ['nullable', 'exists:users,id'],
        ]);

        $mentoring = Mentoring::findOrFail($id);
        $mentoring->certificate_id = $request->certificate_id;
        $mentoring->save();

        // Update TTE Depan (step 1)
        if ($request->has('tte_depan')) {
            MentoringSigner::updateOrCreate([
                'mentoring_id' => $id,
                'step' => 1,
            ], [
                'user_id' => $request->tte_depan,
                'type' => 'signature',
            ]);
        }

        // Update TTE Belakang (step 2)
        if ($request->has('tte_belakang')) {
            MentoringSigner::updateOrCreate([
                'mentoring_id' => $id,
                'step' => 2,
            ], [
                'user_id' => $request->tte_belakang,
                'type' => 'signature',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Certificate updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Generate certificate pdf file and send to signature API
     *
     * @param string $id
     * @return Response
     * @throws ModelNotFoundException
     * @throws InvalidFormatException
     * @throws BindingResolutionException
     * @throws Exception
     * @throws DOMException
     * @throws GlobalException
     */
    public function requestSignCertificate(string $id)
    {
        $mentoring = Mentoring::findOrFail($id);

        $sessions = $mentoring->mentoringSessions;

        try {
            $certificate = CertificateBuilder::findOrFail($mentoring->certificate_id);
            $certificateItems = $certificate->items;

            // Get mentoring details
            $mentee = $mentoring->mentee;
            $mentor = $mentoring->mentor;

            // Get certificate signers
            $tteDepan = $mentoring->signers()->where('step', 1)->first()?->user;
            $tteBelakang = $mentoring->signers()->where('step', 2)->first()?->user;

            if (!$tteDepan || !$tteBelakang) {
                return redirect()->back()->with(['messege' => __('TTE signers not configured'), 'alert-type' => 'error']);
            }

            // Certificate backgrounds
            $cover1Base64 = null;
            if (filled($certificate->background)) {
                if (!Storage::disk('private')->exists($certificate->background)) {
                    return redirect()->back()->with(['messege' => __('Certificate background not found'), 'alert-type' => 'error']);
                }
                $cover1Base64 = base64_encode(file_get_contents(Storage::disk('private')->path($certificate->background)));
            }

            $cover2Base64 = null;
            if (filled($certificate->background2)) {
                if (!Storage::disk('private')->exists($certificate->background2)) {
                    return redirect()->back()->with(['messege' => __('Certificate background not found'), 'alert-type' => 'error']);
                }
                $cover2Base64 = base64_encode(file_get_contents(Storage::disk('private')->path($certificate->background2)));
            }

            // Generate QR code
            $qrCodePublicURL = route('admin.mentoring.public-certificate', ['uuid' => $mentoring->id]);

            $qrcodeData = QrCode::format('png')->size(200)
                ->merge('/public/backend/img/logobantul.png')
                ->generate($qrCodePublicURL);
            $qrcodeData = 'data:image/png;base64,' . base64_encode($qrcodeData);

            // Page 1 - Certificate
            $page1Html = view('frontend.student-dashboard.certificate.index', [
                'certificateItems' => $certificateItems,
                'certificate' => $certificate,
                'cover1Base64' => $cover1Base64,
                'qrcodeData' => $qrcodeData
            ])->render();

            // Replace placeholders
            $page1Html = str_replace('[student_name]', $mentee->name, $page1Html);
            $page1Html = str_replace('[platform_name]', Cache::get('setting')->app_name, $page1Html);
            $page1Html = str_replace('[course]', 'Mentoring Program', $page1Html);
            $page1Html = str_replace('[date]', formatDate(now()), $page1Html);
            $page1Html = str_replace('[instructor_name]', $mentor->name, $page1Html);

            // TTE Depan details
            $page1Html = str_replace('[tanggal_sertifikat]', sprintf('Bantul, %s %s %s', now()->day, now()->monthName, now()->year), $page1Html);
            $page1Html = str_replace('[nama_jabatan]', $tteDepan->jabatan, $page1Html);
            $page1Html = str_replace('[nama_kepala_opd]', $tteDepan->name, $page1Html);
            $page1Html = str_replace('[nama_golongan]', $tteDepan->golongan ?? '', $page1Html);
            $page1Html = str_replace('[nip]', $tteDepan->nip ?? '', $page1Html);

            // Generate PDF
            $now = now();
            $pdf1Data = Pdf::loadHTML($page1Html)
                ->setPaper('A4', 'landscape')
                ->setWarnings(false)
                ->output();

            // Page 2 - Summary
            $page2Html = view('frontend.student-dashboard.certificate.mentoring-summary', [
                'mentoring' => $mentoring,
                'certificateItems' => $certificateItems,
                'certificate' => $certificate,
                'cover2Base64' => $cover2Base64,
                'qrcodeData' => $qrcodeData
            ])->render();

            // Replace placeholders for summary page
            $page2Html = str_replace('[student_name]', $mentee->name, $page2Html);
            $page2Html = str_replace('[course]', 'Mentoring Program', $page2Html);
            $page2Html = str_replace('[date]', formatDate(now()), $page2Html);
            $page2Html = str_replace('[instructor_name]', $mentor->name, $page2Html);

            // TTE Belakang details
            $page2Html = str_replace('[tanggal_sertifikat]', sprintf('Bantul, %s %s %s', now()->day, now()->monthName, now()->year), $page2Html);
            $page2Html = str_replace('[nama_jabatan]', $tteBelakang->jabatan, $page2Html);
            $page2Html = str_replace('[nama_kepala_opd]', $tteBelakang->name, $page2Html);
            $page2Html = str_replace('[nama_golongan]', $tteBelakang->golongan ?? '', $page2Html);
            $page2Html = str_replace('[nip]', $tteBelakang->nip ?? '', $page2Html);

            $pdf2Data = Pdf::loadHTML($page2Html)
                ->setPaper('A4', 'landscape')
                ->setWarnings(false)
                ->output();
            $now = now();
            $m = new Merger();
            $m->addRaw($pdf1Data);
            $m->addRaw($pdf2Data);
            $output = $m->merge();

            Log::info('merge pdf took ' . now()->diffInSeconds($now));

            // TODO: disable this on production
            // return PDF directly
            // return response($output, 200)
            //     ->header('Content-Type', 'application/pdf');

            // Save PDFs
            $pdf1Path = 'certificates/mentoring/' . $mentoring->id . '.pdf';

            Storage::disk('private')->put($pdf1Path, $output);

            // Send to Bantara API for signing
            $this->sendToBantaraAPI($mentoring, $output);

            // Update mentoring record
            $mentoring->certificate_path = $pdf1Path;
            $mentoring->status = Mentoring::STATUS_DONE;
            $mentoring->save();

            return redirect()->route('admin.mentoring.show', ['id' => $mentoring->id])->with(['messege' => __('Certificate sent for signing successfully'), 'alert-type' => 'success']);
        } catch (Exception $e) {
            return redirect()->route('admin.mentoring.show', ['id' => $mentoring->id])->with(['messege' => __('Error generating certificate: ') . $e->getMessage(), 'alert-type' => 'error']);
        }
    }

    /**
     * Display public certificate
     */
    function publicCertificate(Request $request, string $uuid)
    {
        $mentoring = Mentoring::where('uuid', $uuid)->firstOrFail();


        $pdfPath = $mentoring->certificate_path;
        if (!$pdfPath) {
            return redirect()->back()->with(['messege' => __('Certificate not found'), 'alert-type' => 'error']);
        }

        // check if file exists
        if (!Storage::disk('private')->exists($pdfPath)) {
            return redirect()->back()->with(['messege' => __('Certificate not found'), 'alert-type' => 'error']);
        }

        return Storage::disk('private')->response($pdfPath);
    }

    /**
     * Send certificate to Bantara API for digital signing
     */
    private function sendToBantaraAPI(Mentoring  $mentoring, $pdfData)
    {
        try {
            // Get TTE signers
            $tteDepan = $mentoring->signers()->where('step', 1)->first()?->user;
            $tteBelakang = $mentoring->signers()->where('step', 2)->first()?->user;

            if (!$tteDepan || !$tteBelakang) {
                throw new Exception('TTE signers not configured');
            }

            $signers = [];
            foreach ($mentoring->signers()->orderBy('step', 'desc')->get() as $signer) {
                $signers[] =
                    [
                        'nik' => $signer->user->nik,
                        'action' => 'SIGN'
                    ];
            }

            // Get API configuration
            $apiUrl =  sprintf('%s/internal/v1/tte/documents', appConfig('bantara_url'));
            $apiKey = appConfig('bantara_key');

            if (!$apiUrl || !$apiKey) {
                throw new Exception('Bantara API configuration missing');
            }

            // Send to Bantara API
            $response = Http::attach(
                'file',
                $pdfData,
                'certificate.pdf',
                ['Content-Type' => 'application/pdf']
            )->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
            ])->post($apiUrl, [
                'signers' => json_encode($signers),
                'title' => sprintf("Sertifikat Mentoring %s", $mentoring->title),
                'description' => $mentoring->title,
                'callback_url' => sprintf("%s", route('api.mentoring-callback', $mentoring->id)),
                'callback_key' => appConfig('bantara_callback_key'),
            ]);

            if (!$response->successful()) {
                throw new Exception('Bantara API request failed: ' . $response->body());
            }

            $responseData = $response->json();

            // Store the signing request ID for tracking
            $mentoring->signing_document_id = $responseData['document_id'] ?? null;
            $mentoring->signing_status = 'pending';
            $mentoring->save();

            // Log the signing request
            Log::info('Certificate sent to Bantara API', [
                'mentoring_id' => $mentoring->id,
                'document_id' => $responseData['document_id'],
                'signers' => [
                    'tte_depan' => $tteDepan->id,
                    'tte_belakang' => $tteBelakang->id,
                ],
            ]);
        } catch (Exception $e) {
            Log::error('Bantara API integration failed', [
                'mentoring_id' => $mentoring->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get users for TTE select2 dropdown
     */
    public function getUsers(Request $request): JsonResponse
    {
        $query = User::query()
            ->whereNotNull('nik')
            ->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->q . '%');
            });

        $users = $query->select('id', 'name', 'email', 'jabatan')
            ->limit(20)
            ->get();

        return response()->json($users);
    }
}
