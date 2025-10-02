<?php

namespace Modules\Coaching\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use iio\libmergepdf\Merger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\CertificateBuilder\app\Models\CertificateBuilder;
use Modules\Coaching\app\Models\Coaching;
use Modules\Coaching\app\Models\CoachingSigner;
use Modules\Coaching\App\Models\CoachingUser;
use Modules\Coaching\app\Models\CoachingUu;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CoachingCertificateController extends Controller
{
    public function list(Request $request)
    {
        $searchKeyword = $request->query('q');

        $coachingQuery = User::whereNotNull('nik')->select('id', 'name', 'jabatan')->limit(10);

        if ($searchKeyword) {
            $coachingQuery->where('name', 'like', '%' . $searchKeyword . '%');
        }

        $coachingSigners = $coachingQuery->get();

        return response()->json($coachingSigners);
    }

    // store coaching signer
    // this function receive user id and coaching id
    public function storeSigners(Request $request)
    {
        $validated = $request->validate([
            'front_tte' => 'required|exists:users,id',
            'back_tte' => 'required|exists:users,id',
            'coaching_id' => 'required|exists:coachings,id',
        ]);

        // dd($request->all());

        // front tte
        $userFrontTte = User::findOrFail($validated['front_tte']);

        // back tte
        $userBackTte = User::findOrFail($validated['back_tte']);

        try {
            DB::beginTransaction();

            // delete existing signer
            CoachingSigner::where('coaching_id', $validated['coaching_id'])->delete();

            $coachingSignerFront = CoachingSigner::create([
                'user_id' => $userFrontTte->id,
                'coaching_id' => $validated['coaching_id'],
                'step' => 1,
                'type' => CoachingSigner::TYPE_SIGN,
            ]);

            $coachingSignerBack = CoachingSigner::create([
                'user_id' => $userBackTte->id,
                'coaching_id' => $validated['coaching_id'],
                'step' => 2,
                'type' => CoachingSigner::TYPE_SIGN,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Gagal menyimpan signer');
        }

        return redirect()->back()->with('success', 'Signer berhasil disimpan');
    }

    // store choosed certificate type
    public function storeType(Request $request)
    {
        $validated = $request->validate([
            'coaching_id' => 'required|exists:coachings,id',
            'certificate_builder_id' => 'required|exists:certificate_builders,id',
        ]);

        $coaching = Coaching::findOrFail($validated['coaching_id']);
        $certificateBuilder = CertificateBuilder::findOrFail($validated['certificate_builder_id']);

        $coaching->certificate_id = $certificateBuilder->id;
        $coaching->save();

        return redirect()->back()->with('success', 'Tipe sertifikat berhasil disimpan');
    }


    /**
     * Download Signed Certificate
     * @param string $id
     * @return Response
     * @throws ModelNotFoundException
     * @throws InvalidFormatException
     * @throws BindingResolutionException
     * @throws Exception
     * @throws DOMException
     * @throws GlobalException
     */
    // function downloadCertificate(Enrollment $enrollment)
    // {
    //     // validate ownership
    //     if ($enrollment->user_id !==  Auth::user()->id) {
    //         return redirect()->back()->with(['messege' => __('Unauthorized'), 'alert-type' => 'error']);
    //     }

    //     $pdfPath = $enrollment->certificate_path;
    //     if (!$pdfPath) {
    //         return redirect()->back()->with(['messege' => __('Certificate not found'), 'alert-type' => 'error']);
    //     }

    //     // check if file exists
    //     if (!Storage::disk('private')->exists($pdfPath)) {
    //         return redirect()->back()->with(['messege' => __('Certificate file not found'), 'alert-type' => 'error']);
    //     }

    //     return Storage::disk('private')->response($pdfPath);
    // }



    /**
     * generateCertificate
     * Generate certificate pdf file and send to Bantara API endpoint
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
    function generate(Coaching $coaching)
    {
        $title = $coaching->title;
        $goal = $coaching->goal;
        $reality = $coaching->reality;
        $option = $coaching->option;
        $wayForward = $coaching->way_forward;
        $successIndicator = $coaching->success_indicator;
        $totalSession = $coaching->total_session;
        $coach = $coaching->coach;

        $certificateBuilder = CertificateBuilder::findOrFail($coaching->certificate_id);
        $coachingSigners = $coaching->signers;
        $coachingUsers = $coaching->completedCoachingUsers;
        $frontSigner = $coachingSigners->where('step', 1)->first()->user;
        $backSigner = $coachingSigners->where('step', 2)->first()->user;

        // dd($frontSigner, $backSigner);
        // Load file content


        $sessions = $coaching->coachingSessions;
        foreach ($coachingUsers as $coachingUserPivot) {
            $coachingUser = $coachingUserPivot->coachee;

            $htmlTemplate = Storage::disk('templates')->get("certificate-blue-corporate-1-sig.html");


            $now = now();
            $page1QrcodeURL =  route('public.certificate', ['uuid' => $coaching->id]);

            $page1Qrcode = QrCode::format('png')->size(200)
                ->merge('/public/backend/img/logobantul.png')
                ->generate($page1QrcodeURL);

            $page1Data = [
                '[participant_name]' => $coachingUser->name,
                '[program_name]' => $coaching->title,
                '[program_start_date]' => $coaching->start_date,
                '[program_end_date]' => $coaching->end_date,
                '[completion_date]' => $coachingUserPivot->completed_date,
                '[signer_1_name]' => $frontSigner->name,
                '[signer_1_jabatan]' => $frontSigner->jabatan,
                '[signer_1_nip]' => $frontSigner->nip,
                '[signer_2_name]' => $backSigner->name,
                '[signer_2_jabatan]' => $backSigner->jabatan,
                '[signer_2_nip]' => $backSigner->nip,
                '[signer_3_name]' => $backSigner->name,
                '[signer_3_jabatan]' => $backSigner->jabatan,
                '[signer_3_nip]' => $backSigner->nip,
                '[certification_number]' => $coaching->id,
                '[qrcode_data]' =>  'data:image/png;base64,' . base64_encode($page1Qrcode),
                '[organization_name]' => "Badan Kepegawaian Daerah Kabupaten Bantul"
            ];

            $htmlTemplate = str_replace(array_keys($page1Data), array_values($page1Data), $htmlTemplate);



            // return response($htmlTemplate)
            //     ->header('Content-Type', 'text/html');


            $pdf1Data = Pdf::loadHTML($htmlTemplate)
                ->setPaper('A4', 'landscape')
                ->output();

            Log::info('render pdf 1 took ' . now()->diffInMilliseconds($now, true) . ' ms');


            // return PDF directly
            // return response($pdf1Data, 200)
            //     ->header('Content-Type', 'application/pdf');

            //=========
            // page2
            //=========
            $now = now();
            $cover2Base64 = null;

            $page2Qrcode = QrCode::format('png')->size(200)
                ->merge('/public/backend/img/logobantul.png')
                ->generate($page1QrcodeURL);

            $count = 0;
            $totalJP = 0;

            $sessionData = [];
            foreach ($sessions as $session) {
                $count++;
                $totalJP += $session->jp ?? 0;
                $sessionData[] = (object) ['title' => sprintf('Pertemuan %s', $count), 'jp' => $session->jp ?? 0];
            }

            $page2Data = [
                'coaching' => $coaching,
                'certificateItems' => $certificateBuilder->items,
                'certificate' => $certificateBuilder,
                'courseChapers' => [],
                'cover2Base64' => $cover2Base64,
                'qrcodeData2' => 'data:image/png;base64,' . base64_encode($page2Qrcode),
                'sessions' => $sessionData,
                'totalJP' => $totalJP,
            ];

            $page2Html = view('coaching::certificate.certiticate-summary', $page2Data)->render();

            $page2Data = [
                '[tanggal_sertifikat]' => sprintf('Bantul, %s %s %s', now()->day, now()->monthName, now()->year),
                '[nama_jabatan]' => $backSigner->jabatan,
                '[nama_kepala_opd]' =>  $backSigner->name,
                '[nama_golongan]' => $backSigner->golongan,
                '[nip]' =>  $backSigner->nip,
            ];

            $page2Html = str_replace(array_keys($page2Data), array_values($page2Data), $page2Html);

            $pdf2Data = Pdf::loadHTML($page2Html)
                ->setPaper('A4', 'portrait')->setWarnings(false)->output();

            Log::info('render pdf 2 took ' . now()->diffInMilliseconds($now, true) . ' ms');

            $now = now();
            $m = new Merger();
            $m->addRaw($pdf1Data);
            $m->addRaw($pdf2Data);
            $output = $m->merge();

            Log::info('merge pdf took ' . now()->diffInMilliseconds($now, true) . ' ms');

            // save pdf to storage
            $path = sprintf('coaching/%s/%s/%s_certificate.pdf', now()->year, $coaching->id, $coachingUser->id);
            Storage::disk('private')->put($path, $output);

            // save to db
            $coachingUserPivot->certificate_path = $path;
            $coachingUserPivot->save();

            // TODO: disable this on production
            // return PDF directly
            // return response($output, 200)
            //     ->header('Content-Type', 'application/pdf');
        }

        return redirect()->route('admin.coaching.show', $coaching->id)->with(['messege' => __('Certificate generated successfully'), 'alert-type' => 'success']);
    }

    // download certificate
    public function download(string $id)
    {
        $coachingUser = CoachingUu::findOrFail($id);

        $path = $coachingUser->certificate_path;
        if (!Storage::disk('private')->exists($path)) {
            return redirect()->back()->with(['messege' => __('Certificate file not found'), 'alert-type' => 'error']);
        }

        // preview pdf directly
        return response()->file(Storage::disk('private')->path($path));
    }

    // send to Bantara API endpoint
    public function requestTTE($id)
    {
        $coaching = Coaching::findOrFail($id);
        $coachingUsers = $coaching->completedCoachingUsers;

        foreach ($coachingUsers as $coachingUserPivot) {
            $coachingUser = $coachingUserPivot->coachee;
            $signers = $coaching->signers()->orderBy('step', 'asc')->get();
            // send to Bantara API endpoint
            $url = sprintf('%s/internal/v1/tte/documents', appConfig('bantara_url'));
            $signersArray = [];
            foreach ($signers as $signer) {
                $signersArray[] =
                    [
                        'nik' => $signer->user->nik,
                        'action' => 'SIGN'
                    ];
            }
            $signersJson = json_encode($signersArray);


            $pdfPath = $coachingUserPivot->certificate_path;
            if (!Storage::disk('private')->exists($pdfPath)) {
                return redirect()->back()->with(['messege' => __('Certificate file not found'), 'alert-type' => 'error']);
            }

            $pdfData = Storage::disk('private')->get($pdfPath);

            $response = Http::attach(
                'file',
                $pdfData,
                'certificate.pdf',
                ['Content-Type' => 'application/pdf']
            )
                ->withHeaders([
                    'Authorization' => 'Bearer ' . appConfig('bantara_key'),
                ])
                ->post($url, [
                    'signers' => $signersJson,
                    'title' => sprintf("Sertifikat Coaching %s an %s", $coaching->title, $coachingUser->name),
                    'description' => $coachingUser->name,
                    'callback_url' => sprintf("%s", route('api.bantara-callback', $coachingUserPivot)),
                    'callback_key' => appConfig('bantara_callback_key'),
                ]);

            if ($response->failed()) {
                Log::error($response->body());
                return redirect()->back()->with(['messege' => 'Terjadi kesalahan dalam pengiriman sertifikat ke Bantara', 'alert-type' => 'error']);
            }

            $certificateUuid = $response->json('document_id');

            if (!$certificateUuid) {
                Log::error($response->body());
                return redirect()->back()->with(['messege' => 'Terjadi kesalahan dalam pengiriman sertifikat ke Bantara', 'alert-type' => 'error']);
            }

            $coachingUserPivot->certificate_uuid = $certificateUuid;
            $coachingUserPivot->save();
        }



        return redirect()->back()->with(['messege' => 'Sertifikat berhasil dikirim ke Bantara', 'alert-type' => 'success']);
    }

    public function listTemplate()
    {
        $templates = Storage::disk('templates')->files();

        $result = [];
        foreach ($templates as $template) {
            if (!str_ends_with($template, '.html')) {
                continue;
            }
            $result[] = $template;
        }

        return response()->json($result);
    }

    public function getHtml(string $name)
    {
        if (!str_ends_with($name, '.html')) {
            return response()->json(['error' => 'Invalid template type'], 400);
        }
        // read html from template directory, loop through each html file and read the content, use Storage::disk('template')
        $templateData = Storage::disk('templates')->get($name);
        // $templates = Storage::disk('templates')->files();
        // $templatesData = collect($templates)->map(function ($template) {
        //     return Storage::disk('templates')->get($template);
        // });

        return response($templateData)->header('Content-Type', 'text/html');
    }
}
