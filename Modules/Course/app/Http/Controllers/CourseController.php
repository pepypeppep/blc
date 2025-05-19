<?php

namespace Modules\Course\app\Http\Controllers;

use App\Models\Quiz;
use App\Models\User;
use App\Models\Course;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\CourseChapter;
use App\Models\CourseSelectedLevel;
use App\Http\Controllers\Controller;
use App\Models\CoursePartnerInstructor;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Modules\Order\app\Models\Enrollment;
use App\Models\Instansi;
use Modules\CertificateBuilder\app\Models\CertificateBuilder;
use Modules\Course\app\Models\CourseLevel;
use Modules\Course\app\Models\CourseCategory;
use Modules\Course\app\Models\CourseLanguage;
use Modules\Course\app\Http\Requests\CourseStoreRequest;
use App\Events\UserBadgeUpdated;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    function index(Request $request): View
    {
        $query = Course::query();
        $query->when($request->keyword, fn($q) => $q->where('title', 'like', '%' . request('keyword') . '%'));
        $query->when($request->category, function ($q) use ($request) {
            $q->whereHas('category', function ($q) use ($request) {
                $q->where('id', $request->category);
            });
        });
        $query->when($request->date && $request->filled('date'), fn($q) => $q->whereDate('created_at', $request->date));
        $query->when($request->approve_status && $request->filled('approve_status'), fn($q) => $q->where('is_approved', $request->approve_status));
        $query->when($request->status && $request->filled('status'), fn($q) => $q->where('status', $request->status));
        $query->when($request->instructor && $request->filled('instructor'), function ($q) use ($request) {
            $q->where('instructor_id', $request->instructor);
        });
        $query->withCount(['enrollments as enrollments_count' => function ($q) {
            $q->where('has_access', 1);
        }]);
        $query->withCount([
            'enrollments as enrollments_pending_count' => function ($q) {
                $q->whereNull('has_access')
                    ->orWhere('has_access', '');
            }
        ]);

        $currentUserInstansi = adminAuth()->instansi_id;
        $role = getAdminAuthRole();
        if ($currentUserInstansi && $role !== 'Super Admin') {
            $query->where('instansi_id', $currentUserInstansi);
        }

        $orderBy = $request->order_by == 1 ? 'asc' : 'desc';
        $courses = $request->par_page == 'all' ?
            $query->orderBy('id', $orderBy)->get() :
            $query->orderBy('id', $orderBy)->paginate($request->par_page ?? null)->withQueryString();
        $categories = CourseCategory::where('status', 1)->get();
        $instructors = User::where('role', 'instructor')->get();
        $instansis = Instansi::get();
        return view('course::course.index', compact('courses', 'categories', 'instructors', 'instansis'));
    }

    function create()
    {
        $instansis = Instansi::orderBy('name', 'asc')->get();
        $instructors = User::where('role', 'instructor')->orderBy('name', 'asc')->get();
        return view('course::course.create', compact('instructors', 'instansis'));
    }

    function editView(string $id)
    {
        $query = Course::query();

        $currentUserInstansi = adminAuth()->instansi_id;
        $role = getAdminAuthRole();
        if ($currentUserInstansi && $role !== 'Super Admin') {
            $query->where('instansi_id', $currentUserInstansi);
        }

        $course = $query->findOrFail($id);

        if (!$course) {
            return redirect()->back()->with(['alert-type' => 'error', 'messege' => __('Course not found')]);
        }

        Session::put('course_create', $id);
        $instructors = User::where('role', 'instructor')->get();
        $instansis = Instansi::get();
        $editMode = true;
        return view('course::course.create', compact('course', 'editMode', 'instructors', 'instansis'));
    }

    function store(CourseStoreRequest $request)
    {
        if ($request->edit_mode == 1) {
            $course = Course::findOrFail($request->id);
            $course->instructor_id = $request->instructor;
            $course->instansi_id = $request->instansi;
        } else {
            $course = new Course();
            $slug = generateUniqueSlug(Course::class, $request->title);
            $course->slug = $slug;
        }

        $file = $request->file('thumbnail');
        if ($file) {
            $path = 'course/' . now()->year . '/' . now()->month . '/thumbnail/';
            $filename = $course->slug . '_' . time() . '.png';
            Storage::disk('private')->put($path . $filename, file_get_contents($file));

            $course->thumbnail = $path . $filename;
        } else {
            $course->thumbnail = $course->thumbnail;
        }

        $course->title = $request->title;
        $course->seo_description = $request->title;
        $course->demo_video_storage = 'upload';
        $course->demo_video_source = $request->demo_video_storage == 'upload' ? $request->upload_path : $request->external_path;
        $course->jp = 0;
        // $course->jp = $request->jp;
        $course->discount = $request->discount_price;
        $course->description = $request->description;
        $course->background = $request->background;
        $course->dasar_hukum = $request->dasar_hukum;
        // $course->course_type = "pdf";
        $course->instansi_id = $request->instansi;
        $course->instructor_id = $request->instructor;
        $course->save();

        // save course id in session
        Session::put('course_create', $course->id);

        return response()->json([
            'status' => 'success',
            'message' => __('Updated successfully'),
            'redirect' => route('admin.courses.edit', ['id' => $course->id, 'step' => $request->next_step])
        ]);
    }

    function edit(Request $request)
    {
        if (!Session::get('course_create')) {
            return redirect(route('admin.courses.create'));
        }

        switch (request('step')) {
            case '1':
                $course = Course::findOrFail($request->id);
                $instructors = User::where('role', 'instructor')->get();
                $instansis = Instansi::get();
                $editMode = true;
                return view('course::course.create', compact('course', 'editMode', 'instructors', 'instansis'));
                break;
            case '2':
                $courseId = request('id');
                $categories = CourseCategory::where('status', 1)->get();
                $course = Course::findOrFail($courseId);
                $levels = CourseLevel::with(['translation'])->where('status', 1)->get();
                $category = CourseCategory::find($course->category_id);
                $languages = CourseLanguage::where('status', 1)->get();
                $certificates = CertificateBuilder::get();
                return view('course::course.more-information', compact(
                    'categories',
                    'courseId',
                    'course',
                    'levels',
                    'category',
                    'languages',
                    'certificates'
                ));
                break;
            case '3':
                $course = Course::with('instructor', 'partnerInstructors')->findOrFail($request->id);
                $chapters = CourseChapter::with(['chapterItems'])->where(['course_id' => $request->id, 'status' => 'active'])->orderBy('order')->get();
                return view('course::course.course-content', compact('chapters', 'course'));
                break;
            case '4':
                $courseId = request('id');
                $course = Course::findOrFail($courseId);
                return view('course::course.finish', compact('course'));
                break;
            default:
                break;
        }
    }

    function update(Request $request)
    {
        $query = Course::query();
        $currentUserInstansi = adminAuth()->instansi_id;
        $role = getAdminAuthRole();
        if ($currentUserInstansi && $role !== 'Super Admin') {
            $query->where('instansi_id', $currentUserInstansi);
        }

        $course = $query->findOrFail(Session::get('course_create'));

        if (!$course) {
            return redirect()->back()->with(['alert-type' => 'error', 'messege' => __('Course not found')]);
        }

        switch ($request->step) {
            case '2':
                $request->validate([
                    // 'course_duration' => ['required', 'numeric', 'min:0'],
                    'course_access' => ['required'],
                    'category' => ['required'],
                    'start_date' => ['required', 'date', 'before:end_date'],
                    'end_date' => ['required', 'date', 'after:start_date'],
                    'output' => ['required'],
                    'certificate' => ['required', 'exists:certificate_builders,id'],
                    'levels' => ['required', 'min:1', function ($attribute, $value, $fail) {
                        $ids = CourseLevel::pluck('id')->toArray();
                        foreach ($value as $level) {
                            if (!in_array($level, $ids)) {
                                $fail(__('The selected levels is invalid.'));
                            }
                        }
                    }],
                ], [
                    // 'course_duration.required' => __('Course duration is required'),
                    // 'course_duration.numeric' => __('Course duration must be a number'),
                    // 'course_duration.min' => __('Course duration must be greater than or equal to 0'),
                    'course_access.required' => __('Course Access is required'),
                    'category.required' => __('Category is required'),
                    'start_date.required' => __('Start date is required'),
                    'start_date.date' => __('Start date must be a date'),
                    'start_date.before' => __('Start date must be before end date'),
                    'end_date.required' => __('End date is required'),
                    'end_date.date' => __('End date must be a date'),
                    'end_date.after' => __('End date must be after start date'),
                    'output.required' => __('Output & Outcome is required'),
                    'certificate.required' => __('Certificate is required'),
                    'certificate.exists' => __('Certificate does not exist'),
                    'levels.required' => __('Levels is required'),
                    'levels.min' => __('Levels must have at least one level'),
                ]);
                $this->storeMoreInfo($request);
                return response()->json([
                    'status' => 'success',
                    'message' => __('Updated Successfully'),
                    'redirect' => route('admin.courses.edit', ['id' => Session::get('course_create'), 'step' => $request->next_step])
                ]);
                break;
            case '3':
                return response()->json([
                    'status' => 'success',
                    'message' => __('Updated successfully'),
                    'redirect' => route('admin.courses.edit', ['id' => Session::get('course_create'), 'step' => $request->next_step])
                ]);
            case '4':
                $request->validate([
                    'status' => ['required'],
                    'message_for_reviewer' => ['nullable', 'max:1000'],
                    'participants' => ['nullable', 'array'],
                ], [
                    'status.required' => __('Status is required'),
                    'message_for_reviewer.max' => __('Message for reviewer must not exceed 1000 characters'),
                    // 'participants.required' => __('Participants is required'),
                ]);
                $this->storeFinish($request);
                return response()->json([
                    'status' => 'success',
                    'message' => __('Updated Successfully'),
                    'redirect' => $request->next_step == 4 ? route('admin.courses.index') : route('admin.courses.edit', ['id' => Session::get('course_create'), 'step' => $request->next_step])
                ]);

            default:
                # code...
                break;
        }
    }

    function storeMoreInfo(Request $request)
    {
        checkAdminHasPermissionAndThrowException('course.store');
        $course = Course::findOrFail($request->course_id);
        $course->capacity = $request->capacity;
        $course->duration = $request->course_duration;
        $course->access = $request->course_access;
        $course->category_id = $request->category;
        $course->qna = 1;
        $course->downloadable = $request->downloadable;
        $course->certificate = 1;
        $course->certificate_id = $request->certificate;
        $course->partner_instructor = $request->partner_instructor;
        $course->start_date = $request->start_date;
        $course->end_date = $request->end_date;
        $course->output = $request->output;
        $course->outcome = $request->output;
        $course->save();

        // delete unselected partner instructor
        CoursePartnerInstructor::where('course_id', $course->id)
            ->whereNotIn('instructor_id', $request->partner_instructors ?? [])->delete();

        // insert partner instructor
        foreach ($request->partner_instructors ?? [] as $instructor) {
            CoursePartnerInstructor::updateOrCreate(
                ['course_id' => $course->id, 'instructor_id' => $instructor],
            );
        }

        // insert levels
        CourseSelectedLevel::where('course_id', $course->id)
            ->whereNotIn('level_id', $request->levels ?? [])->delete();

        foreach ($request->levels ?? [] as $level) {
            CourseSelectedLevel::updateOrCreate(
                ['course_id' => $course->id, 'level_id' => $level],
            );
        }

        //insert languages
        // CourseSelectedLanguage::where('course_id', $course->id)
        //     ->whereNotIn('language_id', $request->languages ?? [])->delete();

        // foreach ($request->languages ?? [] as $language) {
        //     CourseSelectedLanguage::updateOrCreate(
        //         ['course_id' => $course->id, 'language_id' => $language],
        //     );
        // }
    }

    function storeFinish(Request $request)
    {
        // dd($request->participants);
        checkAdminHasPermissionAndThrowException('course.store');
        $course = Course::findOrFail($request->course_id);
        $course->message_for_reviewer = $request->message_for_reviewer;
        $course->status = $request->status;

        // delete and add enrollments
        $enrollments = $course->enrollments()->pluck('user_id')->toArray();
        $participants = is_array($request->participants) ? $request->participants : [];
        $newEnrollments = array_diff($participants, $enrollments);
        $removedEnrollments = array_diff($enrollments, $participants);
        foreach ($newEnrollments as $enrollment) {
            $course->enrollments()->create([
                'user_id' => $enrollment,
                'has_access' => 1
            ]);
        }
        Enrollment::whereIn('user_id', $removedEnrollments)->where('course_id', $course->id)->delete();

        $course->save();

        if (!empty($newEnrollments)) {
            event(new UserBadgeUpdated($newEnrollments));
        }

        if (!empty($removedEnrollments)) {
            event(new UserBadgeUpdated($removedEnrollments));
        }
    }

    function getInstructors(Request $request)
    {
        $instructors = User::where('role', 'instructor')
            ->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->q . '%')
                    ->orWhere('email', 'like', '%' . $request->q . '%');
            })
            ->where('id', '!=', auth()->id())
            ->get();
        return response()->json($instructors);
    }

    function statusUpdate(Request $request, string $id)
    {
        checkAdminHasPermissionAndThrowException('course.status.update');
        
        $query = Course::query();
        $currentUserInstansi = adminAuth()->instansi_id;
        $role = getAdminAuthRole();
        if ($currentUserInstansi && $role !== 'Super Admin') {
            $query->where('instansi_id', $currentUserInstansi);
        }

        try {
            $validated = $request->validate([
                'status' => 'required|in:pending,approved,rejected',
                'notes' => $request->status === 'rejected' ? 'required|string' : 'nullable|string',
            ]);

            $course = Course::findOrFail($id);
            $course->is_approved = $validated['status'];
            $course->notes = $validated['notes'] ?? null;
            $course->save();
            return response(['status' => 'success', 'message' => __('Updated successfully')]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error','message' => __('Course not found')], 404);
        }
    }

    function publishStatusUpdate(Request $request, string $id)
    {
        checkAdminHasPermissionAndThrowException('course.update');
        $query = Course::query();
        $currentUserInstansi = adminAuth()->instansi_id;
        $role = getAdminAuthRole();
        if ($currentUserInstansi && $role !== 'Super Admin') {
            $query->where('instansi_id', $currentUserInstansi);
        }

        try {
            $validated = $request->validate([
                'status' => 'required|in:pending,approved,rejected',
                'notes' => $request->status === 'rejected' ? 'required|string' : 'nullable|string',
            ]);

            $course = $query->findOrFail($id);
            $course->status = $request->status;
            $course->notes = $request->status === 'rejected' ? $validated['notes'] : null;
            $course->save();
            return response(['status' => 'success', 'message' => __('Updated successfully')]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error','message' => __('Course not found')], 404);
        }
    }

    function destroy(string $id)
    {
        checkAdminHasPermissionAndThrowException('course.delete');

        $query = Course::query();
        $currentUserInstansi = adminAuth()->instansi_id;
        $role = getAdminAuthRole();
        if ($currentUserInstansi && $role !== 'Super Admin') {
            $query->where('instansi_id', $currentUserInstansi);
        }

        try {
            $course = $query->findOrFail($id);
        } catch (\Throwable $th) {
            return redirect()->back()->with(['alert-type' => 'error', 'messege' => __('Course not found')]);
        }

        if ($course->is_approved == Course::ISAPPROVED_APPROVED && $role !== 'Super Admin') {
            return redirect()->back()->with(['alert-type' => 'error', 'messege' => __('The course cannot be deleted because it has been approved.')]);
        }
        if ($course->enrollments()->count() > 0) {
            return redirect()->back()->with(['alert-type' => 'error', 'messege' => __('The course cannot be deleted because it has enrollments.')]);
        }
        $course->delete();

        return redirect()->back()->with(['alert-type' => 'success', 'messege' => __('Course deleted successfully')]);
    }

    function getStudents(Request $request)
    {
        $students = User::where('role', 'student')
            ->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->q . '%')
                    ->orWhere('email', 'like', '%' . $request->q . '%');
            })
            ->get();
        return response()->json($students);
    }

    function duplicate(string $id)
    {
        DB::BeginTransaction();

        try {
            $course = Course::with(['chapters.chapterItems.lesson', 'levels', 'languages', 'partnerInstructors'])->findOrFail($id);
            $newCourse = $course->replicate();
            $newCourse->title = $course->title . ' - Copy';
            $newCourse->slug = generateUniqueSlug(Course::class, $course->title) . now()->timestamp;
            $newCourse->status = Course::STATUS_IS_DRAFT;
            $newCourse->is_approved = Course::ISAPPROVED_PENDING;
            $newCourse->save();

            foreach ($course->chapters as $chapter) {
                $newChapter = $chapter->replicate();
                $newChapter->course_id = $newCourse->id;
                $newChapter->save();

                foreach ($chapter->chapterItems as $chapterItem) {
                    $newChapterItem = $chapterItem->replicate();
                    $newChapterItem->chapter_id = $newChapter->id;
                    $newChapterItem->save();

                    if ($chapterItem->quiz) {
                        $newChapterItemQuiz = $chapterItem->quiz->replicate();
                        $newChapterItemQuiz->chapter_item_id = $newChapterItem->id;
                        $newChapterItemQuiz->chapter_id = $newChapter->id;
                        $newChapterItemQuiz->course_id = $newCourse->id;
                        $newChapterItemQuiz->save();
                    }

                    if ($chapterItem->lesson) {
                        $newChapterItemLesson = $chapterItem->lesson->replicate();
                        $newChapterItemLesson->chapter_item_id = $newChapterItem->id;
                        $newChapterItemLesson->chapter_id = $newChapter->id;
                        $newChapterItemLesson->course_id = $newCourse->id;
                        $newChapterItemLesson->save();
                    }
                }
            }

            foreach ($course->levels as $level) {
                $newCourseLevel = $level->replicate();
                $newCourseLevel->course_id = $newCourse->id;
                $newCourseLevel->save();
            }

            foreach ($course->languages as $language) {
                $newCourseLanguage = $language->replicate();
                $newCourseLanguage->course_id = $newCourse->id;
                $newCourseLanguage->save();
            }

            foreach ($course->partnerInstructors as $partnerInstructor) {
                $newCourse->partnerInstructors()->create([
                    'instructor_id' => $partnerInstructor->instructor_id,
                ]);
            }

            DB::commit();
            return redirect()->back()->with(['alert-type' => 'success', 'messege' => __('Course duplicated successfully')]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with(['alert-type' => 'error', 'messege' => $th->getMessage()]);
        }
    }
}
