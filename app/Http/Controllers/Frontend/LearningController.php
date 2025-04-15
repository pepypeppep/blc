<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Models\Quiz;
use App\Models\User;
use Firebase\JWT\JWT;
use App\Models\Course;
use App\Models\QuizResult;
use App\Models\Announcement;
use App\Models\CourseReview;
use App\Models\JitsiSetting;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use App\Models\CourseProgress;
use App\Rules\CustomRecaptcha;
use App\Models\CourseChapterItem;
use App\Models\CourseChapterLesson;
use App\Http\Controllers\Controller;
use App\Models\FollowUpAction;
use App\Models\FollowUpActionResponse;
use Illuminate\Support\Facades\Cache;
use App\Traits\GenerateSecureLinkTrait;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class LearningController extends Controller
{
    use GenerateSecureLinkTrait;
    function index(string $slug)
    {
        $course = Course::active()->with([
            'chapters',
            'chapters.chapterItems',
            'chapters.chapterItems.lesson',
            'chapters.chapterItems.quiz',
            'chapters.chapterItems.rtl',
        ])->withTrashed()->where('slug', $slug)->first();
        // dd($course->toArray());
        Session::put('course_slug', $slug);
        Session::put('course_title', $course->title);

        $currentProgress = CourseProgress::where('user_id', userAuth()->id)
            ->where('course_id', $course->id)
            ->where('current', 1)
            ->orderBy('id', 'desc')
            ->first();

        $alreadyWatchedLectures = CourseProgress::where('user_id', userAuth()->id)
            ->where('course_id', $course->id)
            // ->where('type', 'lesson')
            ->where('watched', 1)
            ->pluck('lesson_id')
            ->toArray();
        // dd($alreadyWatchedLectures);

        $alreadyCompletedQuiz = CourseProgress::where('user_id', userAuth()->id)
            ->where('course_id', $course->id)
            ->where('type', 'quiz')
            ->where('watched', 1)
            ->pluck('lesson_id')
            ->toArray();

        $alreadyCompletedRtl = CourseProgress::where('user_id', userAuth()->id)
            ->where('course_id', $course->id)
            ->where('type', 'rtl')
            ->where('watched', 1)
            ->pluck('lesson_id')
            ->toArray();

        $announcements = Announcement::where('course_id', $course->id)->orderBy('id', 'desc')->get();

        $courseLectureCount = CourseChapterItem::whereHas('chapter', function ($q) use ($course) {
            $q->where('course_id', $course->id);
        })->count();

        $courseLectureCompletedByUser = CourseProgress::where('user_id', userAuth()->id)
            ->where('course_id', $course->id)->where('watched', 1)->count();
        $courseCompletedPercent = $courseLectureCount > 0 ? ($courseLectureCompletedByUser / $courseLectureCount) * 100 : 0;

        if (!$currentProgress) {
            $lessonId = @$course->chapters?->first()?->chapterItems()?->first()?->lesson->id;
            if ($lessonId) {
                $currentProgress = CourseProgress::create([
                    'user_id'    => userAuth()->id,
                    'course_id'  => $course->id,
                    'chapter_id' => $course->chapters->first()->id,
                    'lesson_id'  => $lessonId,
                    'current'    => 1,
                ]);
            }
        }

        $userHasReviewed = CourseReview::where('course_id', $course->id)
            ->where('user_id', userAuth()->id)
            ->exists();

        return view('frontend.pages.learning-player.index', compact(
            'course',
            'currentProgress',
            'announcements',
            'courseCompletedPercent',
            'courseLectureCount',
            'courseLectureCompletedByUser',
            'alreadyWatchedLectures',
            'alreadyCompletedQuiz',
            'userHasReviewed',
            'alreadyCompletedRtl'
        ));
    }

    function getFileInfo(Request $request)
    {

        // set progress status
        CourseProgress::where('course_id', $request->courseId)->update(['current' => 0]);
        $progress = CourseProgress::updateOrCreate(
            [
                'user_id'    => userAuth()->id,
                'course_id'  => $request->courseId,
                'chapter_id' => $request->chapterId,
                'lesson_id'  => $request->lessonId,
                'type'       => $request->type,
            ],
            [
                'current' => 1,
            ]
        );

        if ($request->type == 'lesson') {
            $fileInfo = array_merge(CourseChapterLesson::select(['id', 'file_path', 'storage', 'file_type', 'downloadable', 'description'])->findOrFail($request->lessonId)->toArray(), ['type' => 'lesson']);
            if (in_array($fileInfo['storage'], ['wasabi', 'aws'])) {
                $fileInfo['file_path'] = Storage::disk($fileInfo['storage'])->temporaryUrl($fileInfo['file_path'], now()->addSeconds(30));
            }
            if ($fileInfo['storage'] == 'upload') {
                $fileInfo['file_path'] = $this->generateSecureLink($fileInfo['file_path']);
            }
            return response()->json([
                'file_info' => $fileInfo,
            ]);
        } elseif ($request->type == 'live') {
            $fileInfo = array_merge(
                CourseChapterLesson::with([
                    'course:id,instructor_id,slug',
                    'course.instructor:id',
                    'course.instructor.zoom_credential:id,instructor_id,client_id,client_secret',
                    'course.instructor.jitsi_credential:id,instructor_id,app_id,api_key,permissions',
                    'live:id,lesson_id,start_time,type,meeting_id,password,join_url',
                ])->select([
                    'id',
                    'course_id',
                    'chapter_item_id',
                    'title',
                    'description',
                    'duration',
                    'file_path',
                    'storage',
                    'file_type',
                    'downloadable',
                ])->findOrFail($request->lessonId)->toArray(),
                ['type' => 'live']
            );

            $now = Carbon::now();
            $startTime = Carbon::parse($fileInfo['live']['start_time']);
            $endTime = $startTime->clone()->addMinutes($fileInfo['duration']);
            $fileInfo['start_time'] = formattedDateTime($startTime);
            $fileInfo['end_time'] = formattedDateTime($endTime);
            $fileInfo['is_live_now'] = $now->between($startTime, $endTime);

            if ($now->lt($startTime)) {
                $fileInfo['is_live_now'] = 'not_started';
            } elseif ($now->between($startTime, $endTime)) {
                $fileInfo['is_live_now'] = 'started';
            } else {
                $fileInfo['is_live_now'] = 'ended';
            }

            return response()->json([
                'file_info' => $fileInfo,
            ]);
        } elseif ($request->type == 'document') {
            $fileInfo = array_merge(CourseChapterLesson::select(['id', 'file_path', 'storage', 'file_type', 'downloadable', 'description'])->findOrFail($request->lessonId)->toArray(), ['type' => 'document']);
            if ('pdf' == $fileInfo['file_type']) {
                return response()->json([
                    'view'      => view('frontend.pages.learning-player.partials.pdf-viewer', ['file_path' => $fileInfo['id']])->render(),
                    'file_info' => $fileInfo,
                ]);
            } elseif ('docx' == $fileInfo['file_type']) {
                return response()->json([
                    'view'      => view('frontend.pages.learning-player.partials.docx-viewer', ['file_path' => $fileInfo['id']])->render(),
                    'file_info' => $fileInfo,
                ]);
            } else {
                return response()->json([
                    'file_info' => $fileInfo,
                ]);
            }
        } else if ($request->type == 'rtl') {

            $fileInfo = array_merge(FollowUpAction::query()->findOrFail($request->lessonId)->toArray(), ['type' => 'rtl']);


            return response()->json([

                // 'view'      => view('frontend.pages.learning-player.partials.rtl-card')->render(),
                'file_info' => $fileInfo,
            ]);
        } else {
            $fileInfo = array_merge(Quiz::findOrFail($request->lessonId)->toArray(), ['type' => 'quiz']);

            return response()->json([
                'file_info' => $fileInfo,
            ]);
        }
    }

    function getDirectFile(string $id)
    {
        $file = CourseChapterLesson::findOrFail($id);
        if (Storage::disk('private')->exists($file->file_path)) {
            $file = Storage::disk('private')->get($file->file_path);
            return response($file, 200)->header('Content-Type', 'application/pdf');
        }
    }

    // function makeLessonComplete(Request $request)
    // {

    //     $progress = CourseProgress::where(['lesson_id' => $request->lessonId, 'user_id' => userAuth()->id, 'type' => $request->type])->first();
    //     if ($progress) {
    //         $progress->watched = $request->status;
    //         $progress->save();
    //         return response()->json(['status' => 'success', 'message' => __('Updated successfully.')]);
    //     } else {
    //         if ($request->status == 0) {
    //             return;
    //         }

    //         return response()->json(['status' => 'error', 'message' => __('You didnt watched this lesson')]);
    //     }
    // }
    public function makeLessonComplete(Request $request)
    {
        // Cari progress untuk lesson yang dimaksud
        $progress = CourseProgress::where([
            'lesson_id' => $request->lessonId,
            'user_id' => userAuth()->id,
            'type' => $request->type
        ])->first();

        // dd($progress);

        // Jika progress ditemukan
        if ($progress) {
            // Cek apakah lesson sebelumnya sudah selesai jika ini adalah lesson
            // if ($request->type == 'lesson') {
            // Cari lesson sebelumnya yang lebih kecil dari lesson_id saat ini
            $previousLesson = CourseProgress::where([
                'user_id' => userAuth()->id,
                'course_id' => $progress->course_id,
                'type' => $request->type,
            ])
                ->where('lesson_id', '<', $request->lessonId) // lesson_id lebih kecil
                ->orderBy('lesson_id', 'desc') // Urutkan berdasarkan lesson_id terbesar
                ->first(); // Ambil yang paling besar yang lebih kecil dari lesson_id

            // Jika ada lesson sebelumnya dan status watched-nya masih 0
            if ($previousLesson && $previousLesson->watched == 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('Please finish the previous lesson first.')
                ]);
            }


            // Update status watched berdasarkan request status
            if ($progress->watched  == 1) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('You already watched this lesson')
                ]);
            }

            $progress->watched = $request->status;
            $progress->save();

            return response()->json([
                'status' => 'success',
                'message' => __('Updated successfully.')
            ]);
        } else {
            // Jika tidak ada progress yang ditemukan dan status = 1
            if ($request->status == 0) {
                return;
            }

            return response()->json([
                'status' => 'error',
                'message' => __('You didnt watch this lesson')
            ]);
        }
    }


    function downloadResource(string $lessonId)
    {
        $resource = CourseChapterLesson::findOrFail($lessonId);
        if (!\File::exists(public_path($resource->file_path))) {
            return redirect()->back()->with(['alert-type' => 'error', 'messege' => __('Links is broke or some thing went wrong')]);
        }
        return response()->download(public_path($resource->file_path));
    }

    function quizIndex(string $id)
    {


        $attempt = QuizResult::where('user_id', userAuth()->id)->where('quiz_id', $id)->count();

        // $quiz = Quiz::query()
        //     ->with([
        //         'questions' => function ($query) {
        //             $query->inRandomOrder()->with([
        //                 'answers' => function ($query) {
        //                     $query->inRandomOrder();
        //                 }
        //             ]);
        //         }
        //     ])
        //     ->withCount('questions')
        //     ->findOrFail($id);


        $userId = userAuth()->id;
        // $numberOfQuestions = 20;
        $quiz = Quiz::withCount('questions')->findOrFail($id);


        // Cek apakah user sudah memiliki soal tersimpan di session
        if (session()->has("quiz_$id" . "_user_$userId")) {
            $questions = session("quiz_$id" . "_user_$userId");
        } else {

            // Ambil soal secara acak
            $questions = $quiz->questions()
                ->inRandomOrder()
                // ->limit($numberOfQuestions)
                ->with(['answers' => function ($query) {
                    $query->inRandomOrder();
                }])
                ->get();

            // Simpan ke session agar tidak berubah-ubah
            session(["quiz_$id" . "_user_$userId" => $questions]);
        }

        // Ambil quiz lagi untuk dikirim ke view
        $quiz = Quiz::withCount('questions')->findOrFail($id);
        $quiz->setRelation('questions', $questions);

        if (!is_null($quiz->attempt) && $attempt >= $quiz->attempt) {
            return redirect()->route('student.learning.index', Session::get('course_slug'))->with([
                'alert-type' => 'error',
                'messege' => __('You reached maximum attempt')
            ]);
        }

        // if due data lebih dari hari ini
        if (Carbon::today()->gt(Carbon::parse($quiz->due_date))) {
            return redirect()->route('student.learning.index', Session::get('course_slug'))->with([
                'alert-type' => 'error',
                'message'    => __('Batas waktu telah berakhir pada tanggal :date', ['date' => Carbon::parse($quiz->due_date)->toFormattedDateString()]),
            ]);
        }

        return view('frontend.pages.learning-player.quiz-index', compact('quiz', 'attempt'));
    }



    function quizStore(Request $request, string $id)
    {
        $grad = 0;
        $result = [];
        $quiz = Quiz::findOrFail($id);
        foreach ($request->question ?? [] as $key => $questionAns) {
            $question = QuizQuestion::findOrFail($key);
            $answer = $question->answers->where('correct', 1)->pluck('id')->toArray();

            if (in_array($questionAns, $answer)) {
                $grad += $question->grade;
            }
            $result[$key] = [
                "answer" => $questionAns,
                "correct" => in_array($questionAns, $answer),
            ];
        }

        $quizResult = QuizResult::create([
            'user_id' => userAuth()->id,
            'quiz_id' => $id,
            'result' => json_encode($result),
            'user_grade' => $grad,
            'status' => $grad >= $quiz->pass_mark ? 'pass' : 'failed',
        ]);
        return redirect()->route('student.quiz.result', ['id' => $id, 'result_id' => $quizResult->id]);
    }

    function quizResult(string $id, string $resultId)
    {
        $attempt = QuizResult::where('user_id', userAuth()->id)->where('quiz_id', $id)->count();
        $quiz = Quiz::withCount('questions')->findOrFail($id);
        $quizResult = QuizResult::findOrFail($resultId);

        return view('frontend.pages.learning-player.quiz-result', compact('quiz', 'attempt', 'quizResult'));
    }

    function rtlIndex(string $id)
    {

        $item = FollowUpAction::findOrFail($id);

        $response = FollowUpActionResponse::where('follow_up_action_id', $id)->where('participant_id', userAuth()->id)->first();

        //if due data lebih dari hari ini
        if (Carbon::parse($item->start_date)->isFuture()) {
            return redirect()->route('student.learning.index', Session::get('course_slug'))->with([
                'alert-type' => 'error',
                'message'    => __('Belum dimulai pada tanggal :date', ['date' => Carbon::parse($item->start_date)->toFormattedDateString()]),
            ]);
        }
        if (Carbon::parse($item->due_date)->isPast()) {
            return redirect()->route('student.learning.index', Session::get('course_slug'))->with([
                'alert-type' => 'error',
                // 'message'    => __('Due date expired on :date', ['date' => Carbon::parse($quiz->due_date)->toFormattedDateString()]),
                'message'    => __('Batas waktu telah berakhir pada tanggal :date', ['date' => Carbon::parse($item->due_date)->toFormattedDateString()]),
            ]);
        }

        return view('frontend.pages.learning-player.rtl-index', compact('item', 'response'));
    }

    public function rtlStore(Request $request, string $id)
    {
        // Check if a response exists (for update)
        $response = FollowUpActionResponse::where('follow_up_action_id', $id)
            ->where('participant_id', userAuth()->id)->first();

        // If no response exists, we are creating a new entry
        if (!$response) {
            $response = new FollowUpActionResponse;
            $response->follow_up_action_id = $id;
            $response->participant_id = userAuth()->id;
        }

        // If it's a new entry, the file is required
        $rules = [
            'summary' => $response->exists ? 'sometimes' : 'required',
            'file_path' => $response->exists ? 'sometimes|mimes:pdf|max:30720' : 'required|mimes:pdf|max:30720',
        ];

        // Validate the incoming requests
        $request->validate($rules, [
            'summary.required' => 'Ringkasan harus diisi',
            'file_path.required' => 'Wajib Mengunggah file pdf',
            'file_path.sometimes' => 'File opsional, jika ada harus berupa PDF',
            'file_path.mimes' => 'File harus berupa pdf',
            'file_path.max' => 'Ukuran file maksimal 30 MB',
        ]);

        // Set the participant's response summary
        $response->participant_response = $request->summary;

        // Handle file upload if there is a file
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');

            // Bersihkan karakter khusus
            $fileOriginalName = $file->getClientOriginalName();
            $fileSanitizedName = preg_replace('/[^A-Za-z0-9\-]/', '-', pathinfo($fileOriginalName, PATHINFO_FILENAME));
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = 'rtl/' . Auth::user()->name . '' . Auth::user()->id . '-' . $fileSanitizedName . '.' . $fileExtension;

            $file->storeAs('private', $fileName, 'local');

            $response->participant_file = Auth::user()->name . '' . Auth::user()->id . '-' . $fileSanitizedName . '.' . $fileExtension;
        }

        // Save the response
        if ($response->save()) {
            return redirect()->back()->with('success', 'Rencana tindak lanjut berhasil disimpan.');
        }

        return redirect()->back()->withInput()->withErrors('Rencana tindak lanjut gagal disimpan.');
    }


    function addReview(Request $request)
    {
        $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review' => ['required', 'max: 1000', 'string'],
            'g-recaptcha-response' => Cache::get('setting')->recaptcha_status === 'active' ? ['required', new CustomRecaptcha()] :
                'nullable',
        ], [
            'rating.required' => __('rating filed is required'),
            'rating.integer' => __('rating have to be an integer'),
            'review.required' => __('review filed is required'),
            'g-recaptcha-response.required' => __('Please complete the recaptcha to submit the form'),
        ]);

        $review = CourseReview::where(['course_id' => $request->course_id, 'user_id' => userAuth()->id])->first();
        if ($review) {
            return redirect()->back()->with(['alert-type' => 'error', 'message' => __('Already added review')]);
        }

        CourseReview::create([
            'course_id' => $request->course_id,
            'user_id' => userAuth()->id,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return redirect()->back()->with(['alert-type' => 'success', 'message' => __('Review added successfully')]);
    }

    function fetchReviews(Request $request, string $courseId)
    {
        $reviews = CourseReview::where('course_id', $courseId)
            ->where(function ($query) {
                $query->where('status', 1)
                    ->orWhere(function ($subQuery) {
                        $subQuery->where('user_id', auth()->id())
                            ->where('status', 0);
                    });
            })
            ->whereHas('course')
            ->whereHas('user')
            ->orderByRaw('status = 0 DESC')
            ->orderBy('id', 'desc')
            ->paginate(8, ['*'], 'page', $request->page ?? 1);

        return response()->json([
            'view' => view('frontend.pages.learning-player.partials.review-card', compact('reviews'))->render(),
            'page' => $request->page,
            'last_page' => $reviews->lastPage(),
            'data_count' => $reviews->count(),
        ]);
    }

    function liveSession(Request $request, string $slug, string $lesson_id)
    {
        $lesson = CourseChapterLesson::select('id', 'course_id', 'chapter_item_id', 'title')->with(['course' => function ($q) {
            $q->select('id', 'instructor_id', 'slug');
        }, 'course.instructor' => function ($q) {
            $q->select('id');
        }, 'course.instructor.zoom_credential' => function ($q) {
            $q->select('id', 'instructor_id', 'client_id', 'client_secret');
        }, 'chapterItem' => function ($q) {
            $q->select('id', 'type');
        }, 'live' => function ($q) {
            $q->select('id', 'lesson_id', 'start_time', 'type', 'meeting_id', 'password', 'join_url');
        }])->findOrFail($lesson_id);

        if ($lesson->live->type == 'zoom') {
            return view('frontend.pages.learning-player.partials.live.zoom', compact('lesson'));
        } else {
            $jitsi_credential = JitsiSetting::where('instructor_id', $lesson->course->instructor_id)->first();
            if ($jitsi_credential) {
                $jwt = $this->generateJwtToken($jitsi_credential);
                $roomName = "{$jitsi_credential->app_id}/{$lesson->live->meeting_id}";
                return view('frontend.pages.learning-player.partials.live.jitsi', [
                    'title' => $lesson->title,
                    'jwt' => trim($jwt),
                    'roomName' => $roomName
                ]);
            }
            return back();
        }
    }

    /**
     * Generate a JaaS JWT token.
     *
     * @return string
     */
    protected function generateJwtToken($jitsi_credential)
    {
        $user = userAuth();
        $instructor = $jitsi_credential->instructor_id == $user->id;

        $api_key = $jitsi_credential->api_key;
        $app_id = $jitsi_credential->app_id; // Your AppID (previously tenant)
        $user_email = $user->name;
        $user_name = $user->name;
        $user_is_moderator = $instructor;
        $user_avatar_url = !empty($user->image) ? asset($user->image) : "";
        $user_id = $user->id;
        $live_streaming_enabled = $instructor;
        $recording_enabled = $instructor;
        $outbound_enabled = false;
        $transcription_enabled = false;
        $exp_delay = 7200;
        $nbf_delay = 0;

        // Read your private key from file
        $private_key = file_get_contents(storage_path("app/user_{$jitsi_credential->instructor_id}/rsb_private_key.pk"));

        $payload = [
            'iss' => 'chat',
            'aud' => 'jitsi',
            'exp' => time() + $exp_delay,
            'nbf' => time() - $nbf_delay,
            'room' => '*',
            'sub' => $app_id,
            'context' => [
                'user' => [
                    'moderator' => $user_is_moderator ? "true" : "false",
                    'email' => $user_email,
                    'name' => $user_name,
                    'avatar' => $user_avatar_url,
                    'id' => $user_id,
                ],
                'features' => [
                    'recording' => $recording_enabled ? "true" : "false",
                    'livestreaming' => $live_streaming_enabled ? "true" : "false",
                    'transcription' => $transcription_enabled ? "true" : "false",
                    'outbound-call' => $outbound_enabled ? "true" : "false",
                ],
            ],
        ];

        return JWT::encode($payload, $private_key, "RS256", $api_key);
    }

    function getStudents(Request $request)
    {
        $students = User::where('role', 'student')
            ->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->q . '%')
                    ->orWhere('email', 'like', '%' . $request->q . '%');
            })
            ->where('id', '!=', userAuth()->id)
            ->get();
        return response()->json($students);
    }
}
