<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Mentoring\app\Models\Mentoring;

class MentorApiController extends Controller
{
    public function index(Request $request)
    {
        try {
            $data = Mentoring::where('mentor_id', $request->user_id)->orderByDesc('id')->paginate(10);

            return $this->successResponse($data, 'Mentor topics fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $mentoring = Mentoring::with('mentor', 'mentoringSessions')->where('mentor_id', $request->user_id)->findOrFail($id);
            $hasIncompleteSessions = $mentoring->mentoringSessions->contains(function ($session) {
                return empty($session->activity);
            });
            return $this->successResponse(['mentoring' => $mentoring, 'hasIncompleteSessions' => $hasIncompleteSessions], 'Mentor topics fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }
}
