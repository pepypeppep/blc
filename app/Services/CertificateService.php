<?php

namespace App\Services;

use App\Models\CourseProgress;
use App\Models\CourseChapterItem;
use Illuminate\Support\Facades\Cache;
use Modules\Coaching\app\Models\Coaching;
use Modules\Mentoring\app\Models\Mentoring;
use Modules\Order\app\Models\Enrollment;

class CertificateService
{
    public function getCertificatesForUser($user_id)
    {
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
            return [
                'success' => false,
                'message' => 'Tidak ada pelatihan yang terdaftar untuk user ID ' . $user_id,
                'data' => [],
                'code' => 404
            ];
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
                    ->first()->created_at, 'Y');

                $certificates[] = [
                    'category' => 'course',
                    'name' => $course->title,
                    'date' => $completed_date,
                    'url' => route('student.download-certificate', $course->id),
                ];
            }
        }

        $mentorings = Mentoring::where('mentee_id', $user_id)->where('status', Mentoring::STATUS_DONE)->get();

        foreach ($mentorings as $data) {
            $certificates[] = [
                'category' => 'mentoring',
                'name' => $data->title,
                'date' => $data->updated_at->format('Y'),
                'url' => 'https://www.rd.usda.gov/sites/default/files/pdf-sample_0.pdf',
            ];
        }

        $coachings = Coaching::whereHas('coachees', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
            $query->where('is_joined', 1);
            $query->whereNotNull('joined_at');
            $query->whereNotNull('final_report');
        })->where('status', Coaching::STATUS_DONE)->get();

        foreach ($coachings as $data) {
            $certificates[] = [
                'category' => 'coaching',
                'name' => $data->title,
                'date' => $data->updated_at->format('Y'),
                'url' => 'https://www.rd.usda.gov/sites/default/files/pdf-sample_0.pdf',
            ];
        }

        if (count($certificates) === 0) {
            return [
                'success' => false,
                'message' => 'Sertifikat tidak ditemukan.',
                'data' => [],
                'code' => 404
            ];
        }

        return [
            'success' => true,
            'message' => 'Daftar sertifikat ditemukan.',
            'data' => $certificates,
            'code' => 200
        ];
    }
}
