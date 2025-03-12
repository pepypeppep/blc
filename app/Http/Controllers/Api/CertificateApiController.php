<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseChapterItem;
use App\Models\CourseProgress;
use Illuminate\Support\Facades\Cache;
use Modules\CertificateBuilder\app\Models\CertificateBuilder;
use Modules\CertificateBuilder\app\Models\CertificateBuilderItem;
use Modules\Order\app\Models\Enrollment;

class CertificateApiController extends Controller
{
    public function getCertificatesForStudent(Request $request)
    {
        $user_id = $request->input('user_id');
        try {
            $enrollments = Enrollment::with([
                'course' => function ($q) {
                    $q->withTrashed();
                },
                'user' => function ($q) {
                    $q->select('id', 'name');
                }
            ])
            ->where('user_id', $user_id)
            ->orderByDesc('id')
            ->get();

            if ($enrollments->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada pelatihan yang terdaftar untuk user ID ' . $user_id,
                ], 404);
            }

            $certificates = [];

            foreach ($enrollments as $enrollment) {
                $course = $enrollment->course;

                if (!$course || $course->certificate != 1) {
                    continue;
                }

                $courseLectureCount = CourseChapterItem::whereHas('chapter', function ($q) use ($course) {
                    $q->where('course_id', $course->id);
                })->count();

                $courseLectureCompletedByUser = CourseProgress::where('user_id', $user_id)
                    ->where('course_id', $course->id)
                    ->where('watched', 1)
                    ->count();

                $courseCompletedPercent = $courseLectureCount > 0 ? ($courseLectureCompletedByUser / $courseLectureCount) * 100 : 0;

                if ($courseCompletedPercent == 100) {
                    $completed_date = formatDate(CourseProgress::where('user_id', $user_id)
                        ->where('course_id', $course->id)
                        ->where('watched', 1)
                        ->latest()
                        ->first()->created_at);

                    // $certificate = CertificateBuilder::first();
                    // $certificateItems = CertificateBuilderItem::all();

                    // $html = view('frontend.student-dashboard.certificate.index', compact('certificateItems', 'certificate'))->render();

                    // $html = str_replace('[student_name]', $enrollment->user->name, $html);
                    // $html = str_replace('[platform_name]', Cache::get('setting')->app_name, $html);
                    // $html = str_replace('[course]', $course->title, $html);
                    // $html = str_replace('[date]', $completed_date, $html);
                    // $html = str_replace('[instructor_name]', $course->instructor->name, $html);

                    $certificates[] = [
                        'course' => $course->title,
                        // 'certificate_html' => $html,
                        'completed_date' => $completed_date,
                        'certificate_url' => route('student.download-certificate', $course->id)
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Daftar sertifikat ditemukan.',
                'data' => $certificates,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
