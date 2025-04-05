<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseChapter;
use App\Models\CourseChapterItem;
use App\Models\CourseChapterLesson;
use App\Models\CourseReview;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Course\app\Models\CourseCategory;
use Modules\Course\app\Models\CourseLanguage;
use Modules\Course\app\Models\CourseLevel;
use Modules\Order\app\Models\Enrollment;

class CoursePageController extends Controller
{
    function index(): View
    {
        $categories = CourseCategory::active()->whereNull('parent_id')->with(['translation'])->get();
        $languages = CourseLanguage::where('status', 1)->get();
        $levels = CourseLevel::where('status', 1)->with('translation')->get();
        return view('frontend.pages.course', compact('categories', 'languages', 'levels'));
    }

    function fetchCourses(Request $request)
    {
        $query = Course::query();
        $query->where(['is_approved' => 'approved', 'status' => 'active']);
        $query->whereHas('category.parentCategory', function ($q) use ($request) {
            $q->where('status', 1);
        });
        $query->whereHas('category', function ($q) use ($request) {
            $q->where('status', 1);
        });

        $query->when($request->search, function ($q) use ($request) {
            $q->where('title', 'like', '%' . $request->search . '%');
        });
        $query->when($request->main_category, function ($q) use ($request) {
            $q->whereHas('category', function ($q) use ($request) {
                $q->whereHas('parentCategory', function ($q) use ($request) {
                    $q->where('slug', $request->main_category);
                });
            });
        });
        $query->when($request->category && $request->filled('category'), function ($q) use ($request) {
            $categoriesIds = explode(',', $request->category);
            $q->whereIn('category_id', $categoriesIds);
        });
        $query->when($request->language && $request->filled('language'), function ($q) use ($request) {
            $languagesIds = explode(',', $request->language);
            $q->whereHas('languages', function ($q) use ($languagesIds) {
                $q->whereIn('language_id', $languagesIds);
            });
        });

        // filter by minimum jam pelajaran
        $query->when($request->jp, function ($q) use ($request) {
            if ($request->jp > 0) {
                $q->where('jp', '>', 0);
            }
        });

        $query->when($request->level, function ($q) use ($request) {
            $levelsIds = explode(',', $request->level);
            $q->whereHas('levels', function ($q) use ($levelsIds) {
                $q->whereIn('level_id', $levelsIds);
            });
        });

        $query->with(['instructor:id,name', 'enrollments', 'category.translation']);

        $query->orderBy('created_at', $request->order && $request->filled('order') ? $request->order : 'desc');
        $courses = $query->paginate(9);

        $lastPage = $courses->lastPage();
        $page = $request->page ?? 1;
        $itemCount = $courses->count();
        $data = [
            'items' => view('frontend.partials.course-card', compact('courses'))->render(),
            'lastPage' => $lastPage,
            'currentPage' => $page,
            'itemCount' => $itemCount
        ];

        // if main category is selected then show sub category card
        if ($request->main_category && $request->filled('main_category')) {
            $subCategories = CourseCategory::whereHas('parentCategory', function ($q) use ($request) {
                $q->where('slug', $request->main_category);
            })->with('translation')->get();
            $categoriesIds = explode(',', $request->category);
            $data['sidebar_items'] = view('frontend.partials.course-sidebar-item', compact('subCategories', 'categoriesIds'))->render();
        }

        return response()->json($data);
    }

    function show(string $slug)
    {
        $course = Course::active()->with(['chapters' => function ($query) {
            $query->orderBy('order', 'asc')->with(['chapterItems', 'chapterItems.lesson', 'chapterItems.quiz']);
        }])
            ->withCount(['reviews' => function ($query) {
                $query->where('status', 1)->whereHas('course')->whereHas('user');
            }])
            ->with('enrollments')
            ->where('slug', $slug)->firstOrFail();
        $courseLessonCount = CourseChapterLesson::where('course_id', $course->id)->count();
        $courseQuizCount = Quiz::where('course_id', $course->id)->count();
        $reviews = CourseReview::where('course_id', $course->id)->where('status', 1)->whereHas('course')->whereHas('user')->orderBy('created_at', 'desc')->paginate(20);

        return view('frontend.pages.course-details', compact('course', 'courseLessonCount', 'courseQuizCount', 'reviews'));
    }

    public function registerCourse(string $slug)
    {
        $course = Course::where('slug', $slug)->firstOrFail();
        $enrollment = Enrollment::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'course_id' => $course->id,
            ],
            [
                'has_access' => null,
            ]
        );

        return redirect()->back()->with(['messege' => __('Course registration successfully'), 'alert-type' => 'success']);
    }
}
