<?php

namespace Modules\InstructorEvaluation\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\InstructorEvaluation\app\Models\InstructorEvaluation;

class InstructorEvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('instructorevaluation::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Course $course, ?User $instructor = null)
    {
        $instructors = $course->getAllInstructors();

        $instructorEvaluations = InstructorEvaluation::where('course_id', $course->id)->get();


        if (filled($instructor)) {
            if (!$instructor->isInstructor()) {
                abort(403, 'User is not instructor');
            }

            if (!$instructors->contains($instructor)) {
                abort(403, 'User is not an instructor of the course');
            }

            $selectedInstructorEvaluation = $instructorEvaluations
                ->where('instructor_id', $instructor->id)
                ->where('student_id', Auth::user()->id)
                ->where('course_id', $course->id)
                ->first();
        }



        return view('instructorevaluation::create', [
            'instructors' => $instructors,
            'course' => $course,
            'instructorEvaluations' => $instructorEvaluations,
            'selectedInstructor' => $instructor,
            'selectedInstructorEvaluation' => $selectedInstructorEvaluation ?? null
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $student = Auth::user();
        $request->validate([
            'course_id' => ['required'],
            'instructor_id' => ['required'],
            'material_mastery' => ['required'],
            'knowledge_transfer_ability' => ['required'],
            'communication_and_motivation' => ['required'],
            'discussion_and_exercise_process' => ['required'],
            'feedback' => ['required'],
        ], [
            'course_id.required' => 'Course is required',
            'instructor_id.required' => 'Instructor is required',
            'material_mastery.required' => 'Rating is required',
            'knowledge_transfer_ability.required' => 'Rating is required',
            'communication_and_motivation.required' => 'Rating is required',
            'discussion_and_exercise_process.required' => 'Rating is required',
            'feedback.required' => 'Comment is required',
        ]);

        $course = Course::findOrFail($request->input('course_id'));
        $instructor = User::findOrFail($request->input('instructor_id'));

        $evaluations = InstructorEvaluation::updateOrCreate(
            [
                'course_id' => $course->id,
                'instructor_id' => $instructor->id,
                'student_id' => $student->id
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

        return redirect()->route('student.instructorevaluation.create', [$course, $instructor])->with(['messege' => __('Rating added successfully.'), 'alert-type' => 'success']);
    }
}
