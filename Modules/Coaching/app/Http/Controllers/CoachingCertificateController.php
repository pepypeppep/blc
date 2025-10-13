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
    public function storeSigners(Request $request)
    {
        $validated = $request->validate([
            'front_tte' => 'required|exists:users,id',
            'back_tte' => 'required|exists:users,id',
            'coaching_id' => 'required|exists:coachings,id',
            'certificate_name' => 'required|string',
        ]);

        // front tte
        $userFrontTte = User::findOrFail($validated['front_tte']);

        // back tte
        $userBackTte = User::findOrFail($validated['back_tte']);

        try {
            DB::beginTransaction();

            $coaching = Coaching::findOrFail($validated['coaching_id']);
            $coaching->certificate_template_name = $validated['certificate_name'];
            $coaching->save();

            // delete existing signer
            CoachingSigner::where('coaching_id', $validated['coaching_id'])->delete();

            $coachingSignerBack = CoachingSigner::create([
                'user_id' => $userBackTte->id,
                'coaching_id' => $validated['coaching_id'],
                'step' => 1,
                'type' => CoachingSigner::TYPE_SIGN,
            ]);

            $coachingSignerFront = CoachingSigner::create([
                'user_id' => $userFrontTte->id,
                'coaching_id' => $validated['coaching_id'],
                'step' => 2,
                'type' => CoachingSigner::TYPE_SIGN,
            ]);

            // generate certificate
            $this->generate($coaching);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Gagal menyimpan signer');
        }

        return redirect()->back()->with('success', 'Signer berhasil disimpan');
    }


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
        $coachingSigners = $coaching->signers;
        $coachingUsers = $coaching->completedCoachingUsers;

        $frontSigner = $coachingSigners->where('step',  CoachingSigner::FRONT)->first()->user;
        $backSigner = $coachingSigners->where('step', CoachingSigner::BACK)->first()->user;

        $certicateTemplate = $coaching->certificate_template_name;

        $htmlTemplateData = Storage::disk('templates')->get($certicateTemplate);


        $sessions = $coaching->coachingSessions;
        foreach ($coachingUsers as $coachingUserPivot) {
            $coachingUser = $coachingUserPivot->coachee;

            // copy template to new variable
            $htmlTemplate = $htmlTemplateData;


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
                '[certification_number]' => $coaching->id,
                '[qrcode_data]' =>  'data:image/png;base64,' . base64_encode($page1Qrcode),
                '[organization_name]' => "Badan Kepegawaian Daerah Kabupaten Bantul"
            ];

            $htmlTemplateReplaced = str_replace(array_keys($page1Data), array_values($page1Data), $htmlTemplate);

            // return HTML directly
            // return response($htmlTemplate)
            //     ->header('Content-Type', 'text/html');

            // Send HTML to Converter service
            $response = Http::withBody($htmlTemplateReplaced, 'text/html')
                ->timeout(60) // in seconds
                ->post(config('app.html_to_pdf_endpoint'));

            if ($response->failed()) {
                throw new \Exception('Failed to generate PDF: ' . $response->body());
            }

            $pdf1Data = $response->body();

            // Log::info('render pdf 1 took ' . now()->diffInMilliseconds($now, true) . ' ms');

            // return PDF directly
            // return response($response->body(), 200)
            //     ->header('Content-Type', 'application/pdf');

            //=========
            // page2
            //=========
            $now = now();

            $count = 0;
            $totalJP = 0;

            $sessionData = [];
            foreach ($sessions as $session) {
                $count++;
                $totalJP += $session->jp ?? 0;
                $sessionData[] =  [sprintf('Pertemuan %s', $count), $session->jp ?? 0];
            }

            $page2Html = view('coaching::certificate.certiticate-summary', [
                'datas' => $sessionData,
            ])->render();


            $page2Qrcode = QrCode::format('png')->size(200)
                ->merge('/public/backend/img/logobantul.png')
                ->generate($page1QrcodeURL);


            $page2Data = [
                '[tanggal_sertifikat]' => sprintf('Bantul, %s %s %s', now()->day, now()->monthName, now()->year),
                '[nama_jabatan]' => $backSigner->jabatan,
                '[nama_kepala_opd]' =>  $backSigner->name,
                '[nama_golongan]' => $backSigner->golongan,
                '[nip]' =>  $backSigner->nip,
                '[title]' => sprintf('Sertifikat Coaching %s', $coaching->title),
                '[qrcode_data]' => 'data:image/png;base64,' . base64_encode($page2Qrcode),
            ];

            $page2Html = str_replace(array_keys($page2Data), array_values($page2Data), $page2Html);

            // return HTML directly
            // return response($page2Html)
            // ->header('Content-Type', 'text/html');


            $response2 = Http::withBody($page2Html, 'text/html')
                ->timeout(60) // in seconds
                ->post(config('app.html_to_pdf_endpoint'));

            if ($response2->failed()) {
                throw new \Exception('Failed to generate PDF: ' . $response2->body());
            }

            $pdf2Data = $response2->body();

            // return response($pdf2Data)
            //     ->header('Content-Type', 'application/pdf');

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


        try {
            DB::beginTransaction();

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
                        'callback_url' => sprintf("%s", route('api.callback.coaching', $coachingUserPivot)),
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

            // set coaching status as done
            $coaching->status = Coaching::STATUS_DONE;
            $coaching->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['messege' => 'Terjadi kesalahan dalam pengiriman sertifikat ke Bantara', 'alert-type' => 'error']);
        }

        return redirect()->back()->with(['messege' => 'Sertifikat berhasil dikirim ke Bantara', 'alert-type' => 'success']);
    }

    // get image data from template directory
    public function getImage(string $name)
    {
        // if name contains extension html, change it to jpg
        if (str_ends_with($name, '.html')) {
            $name = str_replace('.html', '.jpg', $name);
        }

        if (!str_ends_with($name, '.jpg') && !str_ends_with($name, '.png')) {
            return response()->json(['error' => 'Invalid image type'], 400);
        }


        try {
            $imageData = Storage::disk('templates')->get($name);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        return response($imageData)->header('Content-Type', 'image/jpeg');
    }

    // bantara callback
    public function bantaraCallback(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|string',
            'file' => 'required|file',
        ]);

        $coachingUser = CoachingUu::where('certificate_uuid', $validated['id'])->firstOrFail();

        $coaching = $coachingUser->coaching;

        $file = $request->file('file');

        // check if file is pdf
        if ($file->getClientOriginalExtension() !== 'pdf') {
            return response(['success' => false, 'message' => 'File must be pdf'], 400);
        }

        // check if file size is less than 1000mb
        if ($file->getSize() > 1000 * 1024 * 1024) {
            return response(['success' => false, 'message' => 'File size must be less than 1000mb'], 400);
        }

        $path = Storage::disk('private')->putFileAs(
            sprintf(
                '%s/%s/coaching/%s',
                now()->year,
                now()->month,
                $coaching->id
            ),
            $file,
            sprintf('%s-certificate.pdf', $coachingUser->id),
        );

        $coachingUser->signed_certificate_path = $path;
        $coachingUser->save();

        return response(['success' => true, 'message' => 'File uploaded successfully'], 200);
    }
}
