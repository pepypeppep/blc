<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\InstructorEvaluation\app\Models\InstructorEvaluation;

class InstructorEvaluationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/courses/{course}/instructor-evaluations",
     *     tags={"Instructor Evaluation"},
     *     summary="Get instructor evaluation form",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         description="Course ID",
     *         in="path",
     *         name="course",
     *         required=true,
     *         example=1,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Instructor ID",
     *         in="path",
     *         name="instructor",
     *         required=false,
     *         example=1,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Forbidden.")
     *         )
     *     )
     * )
     */
    public function index(Course $course, ?User $instructor = null)
    {
        $instructors = $course->getAllInstructors();

        $instructorEvaluations = InstructorEvaluation::where('course_id', $course->id)->get();


        if (filled($instructor)) {
            if (!$instructor->isInstructor()) {
                return response()->json(['message' => 'User is not instructor'], 403);
            }

            if (!$instructors->contains($instructor)) {
                return response()->json(['message' => 'User is not an instructor of the course'], 403);
            }

            $selectedInstructorEvaluation = $instructorEvaluations
                ->where('instructor_id', $instructor->id)
                ->where('student_id', Auth::user()->id)
                ->where('course_id', $course->id)
                ->first();
        }

        return response()->json([
            'instructors' => $instructors,
            'course' => $course,
            'instructorEvaluations' => $instructorEvaluations,
            'selectedInstructor' => $instructor,
            'selectedInstructorEvaluation' => $selectedInstructorEvaluation ?? null
        ]);
    }

    /**
     * @OA\Post(
     *     path="/courses/{course}/instructor-evaluations",
     *     tags={"Instructor Evaluation"},
     *     summary="Store instructor evaluation form",
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         description="Course ID",
     *         in="path",
     *         name="course",
     *         required=true,
     *         example=1,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Instructor evaluation form",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"instructor_id", "material_mastery", "knowledge_transfer_ability", "communication_and_motivation", "discussion_and_exercise_process", "feedback"},
     *             @OA\Property(property="instructor_id", type="integer", example=1),
     *             @OA\Property(property="material_mastery", type="integer", example=1),
     *             @OA\Property(property="knowledge_transfer_ability", type="integer", example=1),
     *             @OA\Property(property="communication_and_motivation", type="integer", example=1),
     *             @OA\Property(property="discussion_and_exercise_process", type="integer", example=1),
     *             @OA\Property(property="feedback", type="string", example="Comment"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful response"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Forbidden.")
     *         )
     *     )
     * )
     */
    public function store(Request $request, Course $course)
    {
        try {
            $validator = Validator::make($request->all(), [
                'instructor_id' => ['required'],
                'material_mastery' => ['required'],
                'knowledge_transfer_ability' => ['required'],
                'communication_and_motivation' => ['required'],
                'discussion_and_exercise_process' => ['required'],
                'feedback' => ['required'],
            ], [
                'instructor_id.required' => 'instructor_id is required',
                'material_mastery.required' => 'material_mastery is required',
                'knowledge_transfer_ability.required' => 'knowledge_transfer_ability is required',
                'communication_and_motivation.required' => 'communication_and_motivation is required',
                'discussion_and_exercise_process.required' => 'discussion_and_exercise_process is required',
                'feedback.required' => 'feedback is required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->messages()], 422);
            }

            $instructor = User::findOrFail($request->input('instructor_id'));

            $evaluations = InstructorEvaluation::updateOrCreate(
                [
                    'course_id' => $course->id,
                    'instructor_id' => $instructor->id,
                    'student_id' => $request->user()->id
                ],
                [
                    'material_mastery' => $request->input('material_mastery'),
                    'knowledge_transfer_ability' => $request->input('knowledge_transfer_ability'),
                    'communication_and_motivation' => $request->input('communication_and_motivation'),
                    'discussion_and_exercise_process' => $request->input('discussion_and_exercise_process'),
                    'rating' => ($request->input('material_mastery') + $request->input('knowledge_transfer_ability') + $request->input('communication_and_motivation') + $request->input('discussion_and_exercise_process')) / 4,
                    'feedback' => $request->input('feedback'),
                ]
            );

            return response()->json([
                'message' => __('Rating added successfully.')
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
