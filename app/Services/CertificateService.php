<?php

namespace App\Services;

use App\Models\CourseProgress;
use App\Models\CourseChapterItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Coaching\app\Models\Coaching;
use Modules\Mentoring\app\Models\Mentoring;
use Modules\Order\app\Models\Enrollment;

class CertificateService
{
    public function getCertificatesForUser(Request $request, $user_id)
    {
        $year = $request->year ?? date('Y');

        $enrollments = Enrollment::with([
            'course' => function ($q) use ($year) {
                $q->withTrashed()
                    ->whereYear('start_date', $year)
                    ->whereYear('end_date', $year);
            },
            'user' => function ($q) {
                $q->select('id', 'name');
            }
        ])
            ->where('user_id', $user_id)
            ->whereHas('course', function ($q) use ($year) {
                $q->whereYear('start_date', $year)
                    ->whereYear('end_date', $year);
            })
            ->orderByDesc('id')
            ->get();

        $certificates = [];
        if (!$enrollments->isEmpty()) {
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
                        'jp' => $course->jp,
                        'date' => $completed_date,
                        'periode' => $course->start_date . ' - ' . $course->end_date,
                        'triwulan' => (int) ceil((date('n', strtotime($course->start_date)) - 1) / 3) + 1,
                        'url' => route('student.download-certificate', $course->id),
                    ];
                }
            }
        }

        $mentorings = Mentoring::with('mentoringSessions')->where('mentee_id', $user_id)
            ->where('status', Mentoring::STATUS_DONE)
            ->whereHas('mentoringSessions', function ($q) use ($year) {
                $q->whereYear('mentoring_date', $year);
            })->get();

        foreach ($mentorings as $data) {
            $certificates[] = [
                'category' => 'mentoring',
                'name' => $data->title,
                'jp' => 0,
                'date' => $data->updated_at->format('Y'),
                'periode' => $data->mentoringSessions->first()->mentoring_date . ' - ' . $data->mentoringSessions->last()->mentoring_date,
                'triwulan' => (int) ceil((date('n', strtotime($data->mentoringSessions->first()->mentoring_date)) - 1) / 3) + 1,
                'url' => $data->certificate_url,
            ];
        }

        $coachings = Coaching::with('coachingSessions')->whereHas('coachees', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
            $query->where('is_joined', 1);
            $query->whereNotNull('joined_at');
            $query->whereNotNull('final_report');
        })->where('status', Coaching::STATUS_DONE)
            ->whereHas('coachingSessions', function ($q) use ($year) {
                $q->whereYear('coaching_date', $year);
            })->get();

        foreach ($coachings as $data) {
            $certificates[] = [
                'category' => 'coaching',
                'name' => $data->title,
                'jp' => 0,
                'date' => $data->updated_at->format('Y'),
                'periode' => $data->coachingSessions->first()->coaching_date . ' - ' . $data->coachingSessions->last()->coaching_date,
                'triwulan' => (int) ceil((date('n', strtotime($data->mentoringSessions->first()->coaching_date)) - 1) / 3) + 1,
                'url' => $data->certificate_url,
            ];
        }

        if (count($certificates) === 0) {
            return [
                'success' => false,
                'message' => 'Sertifikat tidak ditemukan.',
                'data' => [],
                'totalJp' => 0,
                'totalJpPerTriwulan' => [],
                'code' => 404
            ];
        }

        $totalJp = array_reduce($certificates, function ($total, $item) {
            return $total + $item['jp'];
        }, 0);

        $totalJpPerTriwulan = [];
        foreach ($certificates as $item) {
            $triwulan = $item['triwulan'];
            $totalJpPerTriwulan[$triwulan] = array_reduce(array_filter($certificates, function ($certificate) use ($triwulan) {
                return $certificate['triwulan'] == $triwulan;
            }), function ($total, $item) {
                return $total + $item['jp'];
            }, 0);
        }

        return [
            'success' => true,
            'message' => 'Daftar sertifikat ditemukan.',
            'data' => $certificates,
            'totalJp' => $totalJp,
            'totalJpPerTriwulan' => $totalJpPerTriwulan,
            'code' => 200
        ];
    }
}
