<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseChapter;
use App\Models\CourseChapterItem;
use App\Models\CourseProgress;
use App\Models\CourseReview;
use App\Models\QuizResult;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Modules\CertificateBuilder\app\Models\CertificateBuilder;
use Modules\CertificateBuilder\app\Models\CertificateBuilderItem;
use Modules\Order\app\Models\Enrollment;
use Modules\Order\app\Models\Order;
use Modules\PendidikanLanjutan\app\Models\Vacancy;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Exceptions\InvalidFormatException;
use Dompdf\Exception;
use DOMException;
use Exception as GlobalException;
use iio\libmergepdf\Merger;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\PendidikanLanjutan\app\Models\VacancySchedule;
use Modules\Pengumuman\app\Models\Pengumuman;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class StudentDashboardController extends Controller
{
    public function index(): View
    {
        $totalEnrolledCourses = Enrollment::where('user_id', userAuth()->id)->where('has_access', 1)->count();
        $totalQuizAttempts = QuizResult::where('user_id', userAuth()->id)->count();
        $totalReviews = CourseReview::where('user_id', userAuth()->id)->count();
        $orders = Order::where('buyer_id', userAuth()->id)->orderBy('id', 'desc')->take(10)->get();
        $pengumumans = Pengumuman::where('status', 'show')->get();

        return view('frontend.student-dashboard.index', compact(
            'totalEnrolledCourses',
            'totalQuizAttempts',
            'totalReviews',
            'orders',
            'pengumumans'
        ));
    }

    function enrolledCourses()
    {
        $enrolls = Enrollment::with(['course' => function ($q) {
            $q->withTrashed();
        }])
            ->with('article')
            ->where('user_id', userAuth()->id)
            ->where('has_access', 1)
            ->orderByDesc('id')
            ->paginate(10);


        return view('frontend.student-dashboard.enrolled-courses.index', compact('enrolls'));
    }

    function quizAttempts()
    {
        Session::forget('course_slug');
        $quizAttempts = QuizResult::with(['quiz'])->where('user_id', userAuth()->id)->orderByDesc('id')->paginate(10);

        return view('frontend.student-dashboard.quiz-attempts.index', compact('quizAttempts'));
    }

    function continuingEducation()
    {
        $vacancies = Vacancy::whereHas('users', function ($query) {
            $query->where('user_id', userAuth()->id); // next update with value_type, unor, dll
        })->with(['details', 'unors', 'users'])->paginate(10);

        return view('frontend.student-dashboard.continuing-education.index', compact('vacancies'));
    }

    function continuingEducationDetail($id)
    {
        $vacancy = Vacancy::with('details')->findOrFail($id);

        return view('frontend.student-dashboard.continuing-education.show', compact('vacancy'));
    }

    public function continuingEducationAttachment($id)
    {
        $vacancy = Vacancy::with('users', 'details')->findOrFail($id);

        $attachment = $vacancy->users->firstWhere('id', auth()->id())?->pivot->sk_file;


        // if (!$attachment) {
        //     return redirect()->back()->with('error', __('No attachment found.'));
        // }

        return view('frontend.student-dashboard.continuing-education.attachment', compact('vacancy', 'attachment'));
    }

    public function continuingEducationRegistration()
    {

        return view('frontend.student-dashboard.continuing-education.registration.index');
    }

    public function continuingEducationRegistrationDetail($id)
    {

        return view('frontend.student-dashboard.continuing-education.registration.show');
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
    function downloadCertificate(Enrollment $enrollment)
    {
        // validate ownership
        if ($enrollment->user_id !==  Auth::user()->id) {
            return redirect()->back()->with(['messege' => __('Unauthorized'), 'alert-type' => 'error']);
        }

        $pdfPath = $enrollment->certificate_path;
        if (!$pdfPath) {
            return redirect()->back()->with(['messege' => __('Certificate not found'), 'alert-type' => 'error']);
        }

        // check if file exists
        if (!Storage::disk('private')->exists($pdfPath)) {
            return redirect()->back()->with(['messege' => __('Certificate file not found'), 'alert-type' => 'error']);
        }

        return Storage::disk('private')->response($pdfPath);
    }



    /**
     * requestSignCertificate
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
    function requestSignCertificate(string $enrollment)

    {
        $enrollment = Enrollment::where('uuid', $enrollment)->firstOrFail();
        dd($enrollment);
        try {
            $course = $enrollment->course;

            if (null == $course) {
                return redirect()->back()->with(['messege' => __('Course not found'), 'alert-type' => 'error']);
            }

            $courseChapters = CourseChapter::where('course_id', $course->id)
                ->where('status', 'active')
                ->get();

            $courseLectureCount = CourseChapterItem::whereHas('chapter', function ($q) use ($course) {
                $q->where('course_id', $course->id);
            })->count();

            $courseLectureCompletedByUser = CourseProgress::where('user_id', userAuth()->id)
                ->where('course_id', $course->id)->where('watched', 1)->latest();

            $completed_date = formatDate($courseLectureCompletedByUser->first()?->created_at);

            $courseLectureCompletedByUser = CourseProgress::where('user_id', userAuth()->id)
                ->where('course_id', $course->id)->where('watched', 1)->count();

            $courseCompletedPercent = $courseLectureCount > 0 ? ($courseLectureCompletedByUser / $courseLectureCount) * 100 : 0;

            // TODO: enable this on production
            if ($courseCompletedPercent != 100) {
                return abort(403, __('You have not completed the course yet'));
            }


            $certificate = CertificateBuilder::findOrFail($course->certificate_id);
            $certificateItems = $certificate->items;

            // $now = now();
            $cover1Base64 = null;
            if (filled($certificate->background)) {
                if (!Storage::disk('private')->exists($certificate->background)) {
                    return redirect()->back()->with(['messege' => __('Certificate background not found'), 'alert-type' => 'error']);
                }
                $cover1Base64 = base64_encode(file_get_contents(Storage::disk('private')->path($certificate->background)));
            }


            $qrCodePublicURL = route('public.certificate', ['uuid' => $enrollment->uuid]);

            $qrcodeData = QrCode::format('png')->size(200)
                ->merge('/public/backend/img/logobantul.png')
                ->generate(
                    $qrCodePublicURL
                );
            $qrcodeData = 'data:image/png;base64,' . base64_encode($qrcodeData);


            $page1Html = view('frontend.student-dashboard.certificate.index', [
                'certificateItems' => $certificateItems,
                'certificate' => $certificate,
                'cover1Base64' => $cover1Base64,
                'qrcodeData' => $qrcodeData
            ])->render();

            $page1Html = str_replace('[student_name]', userAuth()->name, $page1Html);
            $page1Html = str_replace('[platform_name]', Cache::get('setting')->app_name, $page1Html);
            $page1Html = str_replace('[course]', $course->title, $page1Html);
            $page1Html = str_replace('[date]', formatDate($completed_date), $page1Html);
            $page1Html = str_replace('[instructor_name]', $course->instructor->name, $page1Html);

            // $pdf1Data = Pdf::loadHTML($page1Html)
            //     ->setPaper('A4', 'landscape')->setWarnings(false)->output();
            // Log::info('render pdf 1 took ' . now()->diffInSeconds($now));

            //=========
            // page2
            //=========
            $cover2Base64 = null;
            if (filled($certificate->background2)) {
                if (!Storage::disk('private')->exists($certificate->background2)) {
                    return redirect()->back()->with(['messege' => __('Certificate background not found'), 'alert-type' => 'error']);
                }
                $cover2Base64 = base64_encode(file_get_contents(Storage::disk('private')->path($certificate->background2)));
            }


            $qrcodeData2 = QrCode::format('png')->size(200)
                ->merge('/public/backend/img/logobantul.png')
                ->generate(
                    $qrCodePublicURL
                );
            $qrcodeData2 = 'data:image/png;base64,' . base64_encode($qrcodeData2);


            $page2Html = view('frontend.student-dashboard.certificate.summary', [
                'course' => $course,
                'certificateItems' => $certificateItems,
                'certificate' => $certificate,
                'courseChapers' => $courseChapters,
                'cover2Base64' => $cover2Base64,
                'qrcodeData2' => $qrcodeData2
            ])->render();
            $pdf2Data = Pdf::loadHTML($page2Html)
                ->setPaper('A4', 'portrait')->setWarnings(false)->output();

            $m = new Merger();
            // $m->addRaw($pdf1Data);
            $m->addRaw($pdf2Data);
            $output = $m->merge();

            // return output directly
            return response($output, 200)
                ->header('Content-Type', 'application/pdf');


            // send to Bantara API endpoint
            $url = sprintf('%s/internal/v1/tte/documents', appConfig('bantara_url'));

            $response = Http::attach(
                'file',
                $output,
                'certificate.pdf',
                ['Content-Type' => 'application/pdf']
            )
                ->withHeaders([
                    'Authorization' => 'Bearer ' . appConfig('bantara_key'),
                ])
                ->post($url, [
                    'signer_nik' => appConfig('bantara_nik'),
                    'title' => sprintf("Sertifikat Pelatihan %s an %s", $course->title, $enrollment->user->name),
                    'description' => $enrollment->user->name,
                    'callback_url' => sprintf("%s", route('api.bantara-callback', $enrollment)),
                    'callback_key' => appConfig('bantara_callback_key'),
                ]);

            if ($response->failed()) {
                Log::error($response->body());
                return redirect()->back()->with(['messege' => 'Terjadi kesalahan dalam pengiriman sertifikat ke Bantara', 'alert-type' => 'error']);
            }


            $enrollment->certificate_status = 'requested';
            $enrollment->save();

            return redirect()->back()->with(['messege' => 'Sertifikat berhasil dikirim ke Bantara', 'alert-type' => 'success']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with(['messege' => $e->getMessage(), 'alert-type' => 'error']);
        }
    }
}
