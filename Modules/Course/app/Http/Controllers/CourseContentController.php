<?php

namespace Modules\Course\app\Http\Controllers;

use App\Models\Quiz;
use App\Models\Course;
use Illuminate\Support\Str;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use App\Models\CourseChapter;
use App\Models\FollowUpAction;
use App\Models\CourseChapterItem;
use App\Imports\QuizQuestionImport;
use App\Models\CourseChapterLesson;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Modules\Order\app\Models\Enrollment;
use Illuminate\Contracts\Session\Session;
use App\Http\Requests\Frontend\QuizLessonCreateRequest;
use Modules\Course\app\Http\Requests\ChapterLessonRequest;
use Yajra\DataTables\Facades\DataTables;

class CourseContentController extends Controller
{
    function chapterStore(Request $request, string $courseId): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'max:255'],
            'jp' => ['required', 'min:1'],
            'instructor' => ['nullable', 'exists:users,id'],
        ], [
            'title.required' => __('Title is required'),
            'title.max' => __('Title is too long'),
            'jp.required' => __('JP is required'),
            'jp.min' => __('JP is too short'),
            'instructor.exists' => __('Instructor is invalid'),
        ]);

        $chapter = new CourseChapter();
        $chapter->title = $request->title;
        $chapter->course_id = $courseId;
        $chapter->instructor_id = $request->instructor;
        $chapter->status = 'active';
        $chapter->order = CourseChapter::where('course_id', $courseId)->max('order') + 1;
        $chapter->jp = $request->jp;
        $chapter->save();

        $course = Course::find($courseId);
        $course->jp = $course->chapters->sum('jp');
        $course->save();

        return redirect()->back()->with(['messege' => __('Chapter created successfully'), 'alert-type' => 'success']);
    }

    function chapterEdit(string $chapterId)
    {
        $chapter = CourseChapter::find($chapterId);
        return view('course::course.partials.edit-section-modal', compact('chapter'))->render();
    }

    function chapterUpdate(Request $request, string $chapterId)
    {
        $request->validate([
            'title' => ['required', 'max:255'],
            'jp' => ['required', 'min:1']
        ], [
            'title.required' => __('Title is required'),
            'title.max' => __('Title is too long'),
            'jp.required' => __('JP is required'),
            'jp.min' => __('JP is too short'),
        ]);

        checkAdminHasPermissionAndThrowException('course.update');
        $chapter = CourseChapter::findOrFail($chapterId);
        $chapter->title = $request->title;
        $chapter->jp = $request->jp;
        $chapter->save();

        $course = Course::find($chapter->course_id);
        $course->jp = $course->chapters->sum('jp');
        $course->save();

        return redirect()->back()->with(['messege' => __('Updated successfully'), 'alert-type' => 'success']);
    }

    function chapterDestroy(string $chapterId)
    {
        checkAdminHasPermissionAndThrowException('course.delete');
        $chapter = CourseChapter::findOrFail($chapterId);
        $chapterItems = CourseChapterItem::where('chapter_id', $chapterId)->get();
        $lessonFiles = CourseChapterLesson::whereIn('chapter_item_id', $chapterItems->pluck('id'))->get();
        $quizIds = Quiz::whereIn('chapter_item_id', $chapterItems->pluck('id'))->pluck('id');
        $questionIds = QuizQuestion::whereIn('quiz_id', $quizIds)->pluck('id');

        // delete quizzes, questions, answers and lesson files
        QuizQuestion::whereIn('id', $questionIds)->delete();
        Quiz::whereIn('id', $quizIds)->delete();
        CourseChapterLesson::whereIn('id', $lessonFiles->pluck('id'))->delete();
        foreach ($lessonFiles as $lesson) {
            if (\File::exists(asset($lesson->file_path))) \File::delete(asset($lesson->file_path));
        }

        // delete chapter items and chapter
        CourseChapterItem::whereIn('id', $chapterItems->pluck('id'))->delete();
        $chapter->delete();

        return response()->json(['status' => 'success', 'message' => __('Question deleted successfully')]);
    }

    function chapterSorting(string $courseId)
    {
        $chapters = CourseChapter::where('course_id', $courseId)->orderBy('order', 'ASC')->get();
        return view('course::course.partials.chapter-sorting-index', compact('chapters', 'courseId'))->render();
    }

    function chapterSortingStore(Request $request, string $courseId)
    {
        $newOrder = $request->chapter_ids;

        foreach ($newOrder as $key => $value) {
            $chapter = CourseChapter::where('course_id', $courseId)->find($value);
            $chapter->order = $key + 1;
            $chapter->save();
        }

        return redirect()->back()->with(['messege' => __('Updated successfully'), 'alert-type' => 'success']);
    }

    function lessonCreate(Request $request)
    {

        $courseId = $request->courseId;
        $chapterId = $request->chapterId;
        $chapters = CourseChapter::where('course_id', $courseId)->get();
        $type = $request->type;
        if ($request->type == 'lesson') {
            return view('course::course.partials.lesson-create-modal', [
                'courseId' => $courseId,
                'chapterId' => $chapterId,
                'chapters' => $chapters,
                'type' => $type
            ])->render();
        } elseif ($request->type == 'document') {
            return view('course::course.partials.document-create-modal', [
                'courseId' => $courseId,
                'chapterId' => $chapterId,
                'chapters' => $chapters,
                'type' => $type
            ])->render();
        } elseif ($request->type == 'quiz') {
            return view('course::course.partials.quiz-create-modal', [
                'courseId' => $courseId,
                'chapterId' => $chapterId,
                'chapters' => $chapters,
                'type' => $type
            ])->render();

            // return view('course::course.partials.quiz-create-form', [
            //     'courseId' => $courseId,
            //     'chapterId' => $chapterId,
            //     'chapters' => $chapters,
            //     'type' => 'quiz'
            // ]);
        } elseif ($request->type == 'rtl') {
            return view('course::course.partials.rtl-create-modal', [
                'courseId' => $courseId,
                'chapterId' => $chapterId,
                'chapters' => $chapters,
                'type' => $type
            ])->render();
        }
    }

    function lessonStore(ChapterLessonRequest $request)
    {
        $chapterItem = CourseChapterItem::create([
            'instructor_id' => Course::find(session()->get('course_create'))->instructor_id,
            'chapter_id' => $request->chapter_id,
            'type' => $request->type,
            'order' => CourseChapterItem::whereChapterId($request->chapter_id)->count() + 1,
        ]);

        if ($request->type == 'lesson') {
            CourseChapterLesson::create([
                'title' => $request->title,
                'slug' => Str::slug($request->title) . '-' . time(),
                'description' => $request->description,
                'instructor_id' =>  $chapterItem->instructor_id,
                'course_id' => $request->course_id,
                'chapter_id' => $request->chapter_id,
                'chapter_item_id' => $chapterItem->id,
                'file_path' => $request->link_path,
                'storage' => detectStorageType($request->link_path),
                'file_type' => "video",
                'volume' => $request->volume,
                'duration' => $request->duration,
                'is_free' => $request->is_free,
            ]);
        } elseif ($request->type == 'document') {
            $file = $request->file('file_path');
            $year = now()->year;
            $month = now()->month;
            $fileName = Str::slug($request->title) . '-' . strtotime('now') . '.pdf';
            $path = "course/$year/$month/$chapterItem->course_id/lesson/$fileName";
            Storage::disk('private')->put($path, file_get_contents($file));

            CourseChapterLesson::create([
                'title' => $request->title,
                'slug' => Str::slug($request->title) . '-' . time(),
                'description' => $request->description,
                'instructor_id' =>  $chapterItem->instructor_id,
                'course_id' => $request->course_id,
                'chapter_id' => $request->chapter_id,
                'chapter_item_id' => $chapterItem->id,
                'file_path' => $path,
                'file_type' => "pdf",
            ]);
        } elseif ($request->type == 'quiz') {
            Quiz::create([
                'chapter_item_id' => $chapterItem->id,
                'instructor_id' => $chapterItem->instructor_id,
                'chapter_id' => $request->chapter,
                'course_id' => $request->course_id,
                'title' => $request->title,
                'time' => $request->time_limit,
                'attempt' => $request->attempts,
                'due_date' => $request->due_date,
                'pass_mark' => $request->pass_mark,
                'total_mark' => $request->total_mark,
            ]);
        } elseif ($request->type == 'rtl') {
            FollowUpAction::create([
                'chapter_item_id' => $chapterItem->id,
                'chapter_id' => $request->chapter,
                'course_id' => $request->course_id,
                'title' => $request->title,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'due_date' => $request->end_date,
            ]);
        }

        return response()->json(['status' => 'success', 'message' => __('Lesson created successfully')]);
    }

    function lessonEdit(Request $request)
    {
        $courseId = $request->courseId;
        $chapterItemId = $request->chapterItemId;
        $chapterItem = CourseChapterItem::with(['lesson', 'quiz', 'followUpAction'])->find($chapterItemId);
        $chapters = CourseChapter::where('course_id', $courseId)->get();
        if ($request->type == 'lesson') {
            return view('course::course.partials.lesson-edit-modal', [
                'chapters' => $chapters,
                'courseId' => $courseId,
                'chapterItem' => $chapterItem
            ])->render();
        } elseif ($request->type == 'document') {
            return view('course::course.partials.document-edit-modal', [
                'chapters' => $chapters,
                'courseId' => $courseId,
                'chapterItem' => $chapterItem
            ])->render();
        } elseif ($request->type == 'rtl') {
            // dd($chapterItem);

            return view('course::course.partials.rtl-edit-modal', [
                'chapters' => $chapters,
                'courseId' => $courseId,
                'chapterItem' => $chapterItem
            ])->render();
        } else {
            return view('course::course.partials.quiz-edit-modal', [
                'chapters' => $chapters,
                'courseId' => $courseId,
                'chapterItem' => $chapterItem
            ])->render();
        }
    }

    function lessonUpdate(ChapterLessonRequest $request)
    {

        checkAdminHasPermissionAndThrowException('course.update');

        $chapterItem = CourseChapterItem::findOrFail($request->chapter_item_id);

        $chapterItem->update([
            'chapter_id' => $request->chapter
        ]);

        if ($request->type == 'lesson') {
            $courseChapterLesson = CourseChapterLesson::where('chapter_item_id', $chapterItem->id)->first();

            $old_file_path = $courseChapterLesson->file_path;
            if (in_array($courseChapterLesson->storage, ['wasabi', 'aws']) && $old_file_path != $request->link_path) {
                $disk = Storage::disk($courseChapterLesson->storage);
                $disk->exists($old_file_path) && $disk->delete($old_file_path);
            }

            $courseChapterLesson->update([
                'title' => $request->title,
                'slug' => Str::slug($request->title) . '-' . time(),
                'description' => $request->description,
                'course_id' => $chapterItem->course_id,
                'chapter_id' => $chapterItem->chapter_id,
                'chapter_item_id' => $chapterItem->id,
                'file_path' => $request->link_path,
                'storage' => "youtube",
                'file_type' => "video",
                'volume' => $request->volume,
                'duration' => $request->duration,
            ]);
        } elseif ($request->type == 'document') {
            $courseChapterLesson = CourseChapterLesson::where('chapter_item_id', $chapterItem->id)->first();

            $file = $request->file('file_path');
            if ($file) {
                $year = now()->year;
                $month = now()->month;
                $fileName = Str::slug($request->title) . '-' . strtotime('now') . '.pdf';
                $path = "course/$year/$month/$chapterItem->course_id/lesson/$fileName";
                Storage::disk('private')->put($path, file_get_contents($file));
            } else {
                $path = $courseChapterLesson->file_path;
            }

            $courseChapterLesson->update([
                'title' => $request->title,
                'slug' => Str::slug($request->title) . '-' . time(),
                'description' => $request->description,
                'course_id' => $chapterItem->course_id,
                'chapter_id' => $chapterItem->chapter_id,
                'chapter_item_id' => $chapterItem->id,
                'file_path' => $path,
                'file_type' => "pdf",
            ]);
        } else {
            $quiz = Quiz::where('chapter_item_id', $chapterItem->id)->first();
            $quiz->update([
                'chapter_item_id' => $chapterItem->id,
                'title' => $request->title,
                'time' => $request->time_limit,
                'attempt' => $request->attempts,
                'due_date' => $request->due_date,
                'pass_mark' => $request->pass_mark,
                'total_mark' => $request->total_mark,
            ]);
        }

        return response()->json(['status' => 'success', 'message' => __('Lesson updated successfully')]);
    }

    function sortLessons(Request $request, string $chapterId)
    {
        $newOrder = $request->orderIds;
        foreach ($newOrder as $key => $itemId) {
            $chapterItem = CourseChapterItem::where(['chapter_id' => $chapterId, 'id' => $itemId])->first();
            $chapterItem->order = $key + 1;
            $chapterItem->save();
        }

        return response()->json(['status' => 'success', 'message' => __('Lesson sorted successfully')]);
    }

    function chapterLessonDestroy(string $chapterItemId)
    {
        checkAdminHasPermissionAndThrowException('course.delete');
        $chapterItem = CourseChapterItem::findOrFail($chapterItemId);

        if ($chapterItem->type == 'quiz') {
            $quiz = $chapterItem->quiz;
            $question = $quiz->questions;
            foreach ($question as $key => $question) {
                $question->answers()->delete();
                $question->delete();
            }
            $quiz->delete();
            $chapterItem->delete();
        } else if ($chapterItem->type == 'rtl') {
            $followUpAction = $chapterItem->followUpAction;
            if ($followUpAction) {
                $followUpAction->delete();
            }
            $chapterItem->delete();
        } else {
            if (in_array($chapterItem->lesson->storage, ['wasabi', 'aws'])) {
                $disk = Storage::disk($chapterItem->lesson->storage);
                $filePath = $chapterItem->lesson->file_path;
                $disk->exists($filePath) && $disk->delete($filePath);
            }
            // delete chapter item lesson if file exists
            if (\File::exists(asset($chapterItem->lesson->file_path))) \File::delete(asset($chapterItem->lesson->file_path));
            // delete lesson row
            $chapterItem->lesson()->delete();
            $chapterItem->delete();
        }

        return response()->json(['status' => 'success', 'message' => __('Lesson deleted successfully')]);
    }

    function createQuizQuestion(Request $request, string $quizId)
    {

        $quiz = Quiz::findOrFail($quizId);
        $questions = QuizQuestion::where('quiz_id', $quizId)
            // ->limit(15)
            ->get();

        if ($questions->count() > 0) {
            if ($request->has('questionId')) {
                // Ambil berdasarkan questionId yang dikirim request
                $questionItem = QuizQuestion::where('quiz_id', $quizId)
                    ->where('id', $request->questionId)
                    ->first();
            } else {
                // Jika tidak ada questionId, ambil pertanyaan pertama dari list
                $questionItem = $questions->first();
            }
        } else {
            $questionItem = null; // atau [] sesuai kebutuhanmu
        }

        $questionItem->image = $questionItem->image
            ? url('questions/image/' . baseName($questionItem->image))
            : null;

        // dd($questionItem);

        //question answer
        $questionAnswer = [];
        if ($questionItem) {
            $questionAnswer = $questionItem->answers()->get()->map(function ($answer) {
                return [
                    'id' => $answer->id,
                    'title' => $answer->title,
                    'correct' => $answer->correct,
                    'image'  => $answer->image
                        ? url('answers/image/' . baseName($answer->image))
                        : null,
                ];
            })->toArray();
        }


        return view('course::course.partials.quiz-question-create-form', [
            'quiz' => $quiz,
            'questions' => $questions,
            'quizId' => $quiz->id,
            'questionItem' => $questionItem,
            'questionAnswer' => $questionAnswer,
        ]);
    }

    function storeQuizQuestion(Request $request, string $quizId)
    {
        $request->validate([
            'title' => ['required'],
            'answers.*' => ['required'],
            'grade' => ['required', 'numeric', 'min:0']
        ], [
            'title.required' => __('Question title is required'),
            // 'title.max' => __('Question title should not be more than 255 characters'),
            'answers.*.required' => __('At least one answer is required'),
            // 'answers.*.max' => __('Answer should not be more than 255 characters'),
            'grade.required' => __('Grade is required'),
            'grade.numeric' => __('Grade should be a number'),
            'grade.min' => __('Grade should be greater than or equal to 0'),
        ]);

        $question = QuizQuestion::create([
            'quiz_id' => $quizId,
            'title' => $request->title,
            'grade' => $request->grade
        ]);

        foreach ($request->answers as $key => $answer) {
            $question->answers()->create([
                'title' => $answer,
                'correct' => isset($request->correct[$key]) ? 1 : 0,
                'question_id' => $question->id
            ]);
        }

        $quiz = Quiz::findOrFail($quizId);

        $users = Enrollment::where('course_id', $quiz->course_id)->pluck('user_id');
        foreach ($users as $userId) {
            $cacheKey = "quiz_{$quizId}_user_{$userId}_questions";
            Cache::forget($cacheKey);
        }

        return response()->json(['status' => 'success', 'message' => __('Question created successfully')]);
    }

    public function storeQuizQuestionOnly(Request $request, string $quizId)
    {
        $request->validate([
            'title' => ['required'],
            'weight' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $imagePath = null;

        try {

            if ($request->hasFile('image')) {
                // $image = $request->file('image');
                // $imageName = time() . '.' . $image->getClientOriginalExtension();
                // $image->move(public_path('images/question'), $imageName);
                // $imagePath = 'images/question/' . $imageName;
                $imagePath =  file_upload(file: $request->image, path: 'uploads/questions/');
            }

            $question = QuizQuestion::create([
                'quiz_id' => $quizId,
                'title' => $request->title,
                'grade' => $request->weight,
                'image' => $imagePath,
            ]);



            return response()->json([
                'status' => 'success',
                'message' => __('Question created successfully'),
                'callback_url' => route('admin.course-chapter.quiz-question.create', [
                    $quizId,
                    'questionId' => $question->id,
                ]),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    // public function storeQuizQuestionAnswer(Request $request, string $quizId)
    // {



    //     $request->validate([
    //         'question_text' => ['required', 'string'],
    //         'weight' => ['required', 'integer', 'min:1'],
    //         'question_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
    //         'answers' => ['required', 'array', 'min:2'],
    //         'answers.*.text' => ['required', 'string'],
    //     ], [
    //         'question_text.required' => 'Pertanyaan wajib diisi.',
    //         'question_text.string' => 'Pertanyaan harus berupa teks.',

    //         'weight.required' => 'Bobot wajib diisi.',
    //         'weight.integer' => 'Bobot harus berupa angka.',
    //         'weight.min' => 'Bobot minimal bernilai 1.',

    //         'image.image' => 'File harus berupa gambar.',
    //         'image.mimes' => 'Gambar harus berformat jpeg, png, jpg, atau gif.',
    //         'image.max' => 'Ukuran gambar maksimal 2MB.',

    //         'answers.required' => 'Jawaban wajib diisi.',
    //         'answers.array' => 'Jawaban harus dalam bentuk array.',
    //         'answers.min' => 'Minimal harus ada 2 jawaban.',
    //         'answers.*.text.required' => 'Teks jawaban wajib diisi.',
    //         'answers.*.text.string' => 'Teks jawaban harus berupa teks.',
    //     ]);

    //     try {
    //         $imagePath = null;

    //         // kalau ada question_id → update
    //         if ($request->filled('question_id')) {
    //             $question = QuizQuestion::where('quiz_id', $quizId)->where('id', $request->question_id)->firstOrFail();
    //             $imagePath = $question->image;

    //             if ($request->hasFile('question_image')) {
    //                 if ($imagePath && file_exists(public_path($imagePath))) {
    //                     unlink(public_path($imagePath));
    //                 }
    //                 $image = $request->file('question_image');
    //                 $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
    //                 $image->move(public_path('images/question'), $imageName);
    //                 $imagePath = 'images/question/' . $imageName;
    //             }

    //             $question->update([
    //                 'title' => $request->question_text,
    //                 'grade' => $request->weight,
    //                 'image' => $imagePath,
    //             ]);

    //             // 🔥 hapus jawaban lama
    //             $question->answers()->delete();
    //         } else {
    //             // kalau create baru
    //             if ($request->hasFile('question_image')) {
    //                 $image = $request->file('question_image');
    //                 $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
    //                 $image->move(public_path('images/question'), $imageName);
    //                 $imagePath = 'images/question/' . $imageName;
    //             }

    //             $question = QuizQuestion::create([
    //                 'quiz_id' => $quizId,
    //                 'title'   => $request->question_text,
    //                 'grade'   => $request->weight,
    //                 'image'   => $imagePath,
    //             ]);
    //         }

    //         // simpan jawaban baru (baik create maupun update)
    //         foreach ($request->answers as $answer) {
    //             $answerImagePath = null;
    //             if (!empty($answer['image']) && $answer['image'] instanceof \Illuminate\Http\UploadedFile) {
    //                 $imageName = time() . '_' . uniqid() . '.' . $answer['image']->getClientOriginalExtension();
    //                 $answer['image']->move(public_path('images/answer'), $imageName);
    //                 $answerImagePath = 'images/answer/' . $imageName;
    //             }

    //             $question->answers()->create([
    //                 'title'   => $answer['text'],
    //                 'correct' => isset($answer['is_correct']) ? 1 : 0,
    //                 'image'   => $answerImagePath,
    //             ]);
    //         }

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => $request->filled('question_id')
    //                 ? __('Question updated successfully')
    //                 : __('Question created successfully'),
    //             'callback_url' => route('admin.course-chapter.quiz-question.create', [
    //                 $quizId,
    //                 'questionId' => $question->id,
    //             ]),
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function storeQuizQuestionAnswer(Request $request, string $quizId)
    {
        $validated =  $request->validate([
            'question_text' => ['required', 'string'],
            'weight' => ['required', 'integer', 'min:1'],
            'question_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'answers' => ['required', 'array', 'min:2'],
            'answers.*.text' => ['required', 'string'],
            'answers.*.image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ], [
            'question_text.required' => 'Pertanyaan wajib diisi.',
            'question_text.string' => 'Pertanyaan harus berupa teks.',

            'weight.required' => 'Bobot wajib diisi.',
            'weight.integer' => 'Bobot harus berupa angka.',
            'weight.min' => 'Bobot minimal bernilai 1.',

            'question_image.image' => 'File harus berupa gambar.',
            'question_image.mimes' => 'Gambar harus berformat jpeg, png, jpg, atau gif.',
            'question_image.max' => 'Ukuran gambar maksimal 2MB.',

            'answers.required' => 'Jawaban wajib diisi.',
            'answers.array' => 'Jawaban harus dalam bentuk array.',
            'answers.min' => 'Minimal harus ada 2 jawaban.',
            'answers.*.text.required' => 'Teks jawaban wajib diisi.',
            'answers.*.text.string' => 'Teks jawaban harus berupa teks.',
            'answers.*.image.image' => 'File harus berupa gambar.',
            'answers.*.image.mimes' => 'Gambar jawaban harus berformat jpeg, png, jpg, atau gif.',
            'answers.*.image.max' => 'Ukuran gambar jawaban maksimal 2MB.',
        ]);

        //total question grade bandingkan dengan bobot di quiz

        $quiz = Quiz::findOrFail($quizId);

        try {
            $question = null;
            $imagePath = null;

            // UPDATE jika ada question_id
            if ($request->filled('question_id')) {
                $question = QuizQuestion::where('quiz_id', $quizId)->where('id', $request->question_id)->firstOrFail();

                $currentWeight = $quiz->questions()->sum('grade');
                // total baru = (total sekarang - grade lama pertanyaan ini) + grade baru
                $newTotal = ($currentWeight - $question->grade) + $validated['weight'];
                if ($newTotal > $quiz->pass_mark) {
                    return response()->json([
                        'message' => 'The given data was invalid.',
                        'errors' => [
                            'grade' => ['Bobot pertanyaan melebihi bobot dari bobot Kuis.'],
                        ],
                    ], 422);
                }

                $imagePath = $question->image;

                // ganti gambar pertanyaan kalau ada file baru
                if ($request->hasFile('question_image')) {
                    if ($imagePath && file_exists(public_path($imagePath))) {
                        unlink(public_path($imagePath));
                    }
                    // $image = $request->file('question_image');
                    // $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    // $image->move(public_path('images/question'), $imageName);
                    // $imagePath = 'images/question/' . $imageName;
                    $imagePath =  file_upload(file: $request->question_image, path: 'uploads/questions/');
                } else {
                    $imagePath = null;
                }

                // hapus gambar pertanyaan jika diminta
                if ($request->boolean('remove_question_image')) {
                    if ($imagePath && file_exists(public_path($imagePath))) {
                        unlink(public_path($imagePath));
                    }
                    $imagePath = null;
                }

                $question->update([
                    'title' => $request->question_text,
                    'grade' => $request->weight,
                    'image' => $imagePath,
                ]);
            } else {
                // CREATE baru

                // Hitung total bobot pertanyaan
                $currentWeight = $quiz->questions()->sum('grade');
                $newTotal = $currentWeight + $validated['weight'];

                if ($newTotal > $quiz->pass_mark) {
                    return response()->json([
                        'message' => 'The given data was invalid.',
                        'errors' => [
                            'grade' => ['Bobot pertanyaan melebihi bobot dari bobot Kuis.'],
                        ],
                    ], 422);
                }

                if ($request->hasFile('question_image')) {
                    // $image = $request->file('question_image');
                    // $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    // $image->move(public_path('images/question'), $imageName);
                    // $imagePath = 'images/question/' . $imageName;
                    $imagePath =  file_upload(file: $request->question_image, path: 'uploads/questions/');
                } else {
                    $imagePath = null;
                }

                $question = QuizQuestion::create([
                    'quiz_id' => $quizId,
                    'title'   => $request->question_text,
                    'grade'   => $request->weight,
                    'image'   => $imagePath,
                ]);
            }

            // === HANDLE JAWABAN ===
            $existingAnswerIds = collect($request->answers)->pluck('id')->filter()->toArray();
            $question->answers()->whereNotIn('id', $existingAnswerIds)->delete();

            foreach ($request->answers as $key => $answerData) {
                $answer = null;
                $answerImagePath = null;

                if (!empty($answerData['id'])) {
                    // update jawaban lama
                    $answer = $question->answers()->where('id', $answerData['id'])->first();
                    $answerImagePath = $answer->image;

                    if ($request->hasFile("answers.$key.image")) {
                        if ($answerImagePath && file_exists(public_path($answerImagePath))) {
                            unlink(public_path($answerImagePath));
                        }
                        // $img = $request->file("answers.$key.image");
                        // $imgName = time() . '_' . uniqid() . '.' . $img->getClientOriginalExtension();
                        // $img->move(public_path('images/answer'), $imgName);
                        // $answerImagePath = 'images/answer/' . $imgName;

                        $answerImagePath =  file_upload(file: $request->file("answers.$key.image"), path: 'uploads/answers/');
                    }

                    if (!empty($answerData['remove_image'])) {
                        if ($answerImagePath && file_exists(public_path($answerImagePath))) {
                            unlink(public_path($answerImagePath));
                        }
                        $answerImagePath = null;
                    }

                    $answer->update([
                        'title'   => $answerData['text'],
                        'correct' => !empty($answerData['is_correct']) ? 1 : 0,
                        'image'   => $answerImagePath,
                    ]);
                } else {
                    // tambah jawaban baru
                    if ($request->hasFile("answers.$key.image")) {
                        // $img = $request->file("answers.$key.image");
                        // $imgName = time() . '_' . uniqid() . '.' . $img->getClientOriginalExtension();
                        // $img->move(public_path('images/answer'), $imgName);
                        // $answerImagePath = 'images/answer/' . $imgName;
                        $answerImagePath =  file_upload(file: $request->file("answers.$key.image"), path: 'uploads/answers/');
                    }

                    $question->answers()->create([
                        'title'   => $answerData['text'],
                        'correct' => !empty($answerData['is_correct']) ? 1 : 0,
                        'image'   => $answerImagePath,
                    ]);
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => $request->filled('question_id')
                    ? 'Pertanyaan berhasil diperbarui'
                    : 'Pertanyaan berhasil dibuat',
                'callback_url' => route('admin.course-chapter.quiz-question.create', [
                    $quizId,
                    'questionId' => $question->id,
                ]),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    function editQuizQuestion(string $questionId)
    {
        $question = QuizQuestion::findOrFail($questionId);
        return view('course::course.partials.quiz-question-edit-modal', ['question' => $question])->render();
    }

    function updateQuizQuestion(Request $request, string $questionId)
    {
        $request->validate([
            'title' => ['required'],
            'answers.*' => ['required'],
            'grade' => ['required', 'numeric', 'min:0']
        ], [
            'title.required' => __('Question title is required'),
            // 'title.max' => __('Question title should not be more than 255 characters'),
            'answers.*.required' => __('At least one answer is required'),
            // 'answers.*.max' => __('Answer should not be more than 255 characters'),
            'grade.required' => __('Grade is required'),
            'grade.numeric' => __('Grade should be a number'),
            'grade.min' => __('Grade should be greater than or equal to 0'),
        ]);

        $question = QuizQuestion::findOrFail($questionId);
        $question->update([
            'title' => $request->title,
            'grade' => $request->grade
        ]);
        // update or delete answers
        $question->answers()->delete();
        foreach ($request->answers as $key => $answer) {
            $question->answers()->create([
                'title' => $answer,
                'correct' => isset($request->correct[$key]) ? 1 : 0,
                'question_id' => $question->id
            ]);
        }

        $quiz = Quiz::findOrFail($question->quiz_id);

        $users = Enrollment::where('course_id', $quiz->course_id)->pluck('user_id');
        foreach ($users as $userId) {
            $cacheKey = "quiz_{$quiz->id}_user_{$userId}_questions";
            Cache::forget($cacheKey);
        }

        return response()->json(['status' => 'success', 'message' => __('Question updated successfully')]);
    }

    function destroyQuizQuestion(string $questionId)
    {
        $question = QuizQuestion::findOrFail($questionId);
        $question->answers()->delete();
        $question->delete();
        return response()->json(['status' => 'success', 'message' => __('Question deleted successfully')]);
    }


    function updateFollowUpAction(Request $request, string $followUpActionId)
    {
        $request->validate([
            'title'        => ['required', 'string'],
            'description'  => ['nullable', 'string'],
            'start_date'   => ['required', 'date', 'before_or_equal:end_date'],
            'end_date'     => ['required', 'date', 'after_or_equal:start_date'],
        ], [
            'title.required'         => 'Judul wajib diisi.',
            'title.string'           => 'Judul harus berupa teks.',

            'description.string'     => 'Deskripsi harus berupa teks.',

            'start_date.required'    => 'Tanggal mulai wajib diisi.',
            'start_date.date'        => 'Tanggal mulai tidak valid.',
            'start_date.before_or_equal' => 'Tanggal mulai harus sebelum atau sama dengan tanggal selesai.',

            'end_date.required'      => 'Tanggal selesai wajib diisi.',
            'end_date.date'          => 'Tanggal selesai tidak valid.',
            'end_date.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
        ]);


        $followUpAction = FollowUpAction::findOrFail($followUpActionId);
        $followUpAction->update([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'due_date' => $request->end_date
        ]);


        return response()->json(['status' => 'success', 'message' => __('Question updated successfully')]);
    }

    public function importFileQuestion(Request $request, string $quizId)
    {
        try {
            $request->validate([
                'excel_file' => 'required|file|mimes:csv,xlsx,xls',
            ], [
                'excel_file.required' => 'File excel wajib diisi.',
                'excel_file.file' => 'File excel harus berupa file.',
                'excel_file.mimes' => 'File excel harus berekstensi .csv, .xlsx, atau .xls.',
            ]);

            Excel::import(new QuizQuestionImport($quizId), $request->file('excel_file'));

            return response()->json([
                'status' => 'success',
                'message' => 'Import berhasil!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public  function importQuizQuestion(string $quizId)
    {
        return view('course::course.partials.quiz-question-import-modal', ['quizId' => $quizId])->render();
    }

    public function quizList(Request $request)
    {
        $query = Quiz::with(['chapter', 'course', 'instructor']); // kalau ada relasi

        return DataTables::eloquent($query)
            ->addColumn('action', function ($quiz) {
                return '
                    <a href="' . route('quizzes.edit', $quiz->id) . '" class="btn btn-sm btn-warning">Edit</a>
                    <form action="' . route('quizzes.destroy', $quiz->id) . '" method="POST" style="display:inline-block">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Yakin hapus?\')">Delete</button>
                    </form>
                ';
            })
            ->editColumn('due_date', function ($quiz) {
                return $quiz->due_date ? date('d-m-Y', strtotime($quiz->due_date)) : '-';
            })
            ->make(true);
    }
}