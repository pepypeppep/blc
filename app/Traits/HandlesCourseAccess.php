<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;

trait HandlesCourseAccess
{
    protected function getAuthenticatedUser(Request $request)
    {
        $user = $request->user('sso-api');
        if (!$user) {
            return $this->errorResponse('Authorization failed', [], 401);
        }

        return User::find($user->id);
    }

    protected function getCourseBySlug(string $slug)
    {
        $course = Course::where('slug', $slug)->first();
        if (!$course) {
            return $this->errorResponse('Course not found', [], 404);
        }

        return $course;
    }

    protected function getCourseById($id)
    {
        $course = Course::find($id);
        if (!$course) {
            return $this->errorResponse('Course not found', [], 404);
        }

        return $course;
    }

    protected function checkEnrollment($user, $course)
    {
        if (!$user->isEnrolledInCourse($course)) {
            return $this->errorResponse('You are not enrolled in this course', [], 403);
        }
        return true;
    }
}