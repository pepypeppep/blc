<?php


use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Modules\InstructorEvaluation\app\Models\InstructorEvaluation;


// file upload method
if (!function_exists('isAllInstructorEvaluated')) {
    function isAllInstructorEvaluated(Course $course, User $user)
    {
        $instructorEvaluatedIds = InstructorEvaluation::where('course_id', $course->id)
            ->where('student_id', $user->id)
            ->get()->pluck('instructor_id');

        $primary = $course->instructor;
        $partners = $course->partnerInstructors->pluck('instructor');

        $instructorIds = collect([$primary])->merge($partners)->pluck('id');

        $isAllInstructorEvaluated = true;
        foreach ($instructorIds as $id) {
            if (!$instructorEvaluatedIds->contains($id)) {
                $isAllInstructorEvaluated = false;
                break;
            }
        }

        return $isAllInstructorEvaluated;
    }
}
