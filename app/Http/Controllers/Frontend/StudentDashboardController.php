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
use iio\libmergepdf\Merger;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class StudentDashboardController extends Controller
{
    public function index(): View
    {
        $totalEnrolledCourses = Enrollment::where('user_id', userAuth()->id)->count();
        $totalQuizAttempts = QuizResult::where('user_id', userAuth()->id)->count();
        $totalReviews = CourseReview::where('user_id', userAuth()->id)->count();
        $orders = Order::where('buyer_id', userAuth()->id)->orderBy('id', 'desc')->take(10)->get();
        
        return view('frontend.student-dashboard.index', compact(
            'totalEnrolledCourses',
            'totalQuizAttempts',
            'totalReviews',
            'orders'
        ));
    }

    function enrolledCourses()
    {
        $enrolls = Enrollment::with(['course' => function ($q) {
            $q->withTrashed();
        }])->where('user_id', userAuth()->id)->orderByDesc('id')->paginate(10);
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


    function downloadCertificate(string $id)
    {
        $course = Course::withTrashed()->findOrFail($id);

        $courseChapers = CourseChapter::where('course_id', $course->id)
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
        // if ($courseCompletedPercent != 100) {
        //     return abort(404);
        // }


        $certificate = CertificateBuilder::findOrFail($course->certificate_id);
        $certificateItems = $certificate->items;


        // return view('frontend.student-dashboard.certificate.summary',  compact('course', 'certificateItems', 'certificate', 'courseChapers'));

        // $now = now();
        $page1Html = view('frontend.student-dashboard.certificate.index', compact('certificateItems', 'certificate'))->render();

        $page1Html = str_replace('[student_name]', userAuth()->name, $page1Html);
        $page1Html = str_replace('[platform_name]', Cache::get('setting')->app_name, $page1Html);
        $page1Html = str_replace('[course]', $course->title, $page1Html);
        $page1Html = str_replace('[date]', formatDate($completed_date), $page1Html);
        $page1Html = str_replace('[instructor_name]', $course->instructor->name, $page1Html);

        $pdf1Data = Pdf::loadHTML($page1Html)
            ->setPaper('A4', 'landscape')->setWarnings(false)->output();
        // Log::info('render pdf 1 took ' . now()->diffInSeconds($now));

        $page2Html = view('frontend.student-dashboard.certificate.summary', compact('course', 'certificateItems', 'certificate', 'courseChapers'))->render();
        $pdf2Data = Pdf::loadHTML($page2Html)
            ->setPaper('A4', 'portrait')->setWarnings(false)->output();

        $m = new Merger();
        $m->addRaw($pdf1Data);
        $m->addRaw($pdf2Data);
        $output = $m->merge();

        // $fallback = $this->fallbackName($filename);


        return new Response($output, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline',
        ]);
    }
}
