<?php

namespace App\Http\Controllers\Api;

use App\Models\Course;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Models\CourseProgress;
use App\Models\CourseChapterItem;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Course\app\Models\CourseLevel;
use Modules\Course\app\Models\CourseCategory;

class CourseApiController extends Controller
{
    /**
     * Retrieves courses based on query parameters.
     *
     * @queryParam search string The value to search in course titles.
     * @queryParam main_category string The slug of the main category.
     * @queryParam category string The comma-separated IDs of categories.
     * @queryParam language string The comma-separated IDs of languages.
     * @queryParam price string The price of the course. Can be either 'paid' or 'free'.
     * @queryParam level string The comma-separated IDs of course levels.
     * @queryParam user_id string The ID of the user.
     * @queryParam order string The order of the courses. Can be either 'asc' or 'desc'.
     *
     * @response 200 {
     *     "success": true,
     *     "message": "Courses retrieved successfully",
     *     "data": {
     *         "total": 1,
     *         "per_page": 15,
     *         "current_page": 1,
     *         "last_page": 1,
     *         "first_page_url": "http://localhost/api/courses?page=1",
     *         "last_page_url": "http://localhost/api/courses?page=1",
     *         "next_page_url": null,
     *         "prev_page_url": null,
     *         "path": "http://localhost/api/courses",
     *         "from": 1,
     *         "to": 1,
     *         "data": [
     *             {
     *                 "id": 1,
     *                 "title": "Course 1",
     *                 "slug": "course-1",
     *                 "description": "This is course 1",
     *                 "price": 0,
     *                 "image": null,
     *                 "is_approved": "approved",
     *                 "status": "active",
     *                 "created_at": "2022-07-28T14:42:00.000000Z",
     *                 "updated_at": "2022-07-28T14:42:00.000000Z",
     *                 "deleted_at": null,
     *                 "instructor": {
     *                     "id": 1,
     *                     "name": "John Doe"
     *                 },
     *                 "enrollments_count": 0,
     *                 "category": {
     *                     "id": 1,
     *                     "name": "Category 1",
     *                     "parent_id": 1,
     *                     "status": 1,
     *                     "created_at": "2022-07-28T14:42:00.000000Z",
     *                     "updated_at": "2022-07-28T14:42:00.000000Z",
     *                     "deleted_at": null,
     *                     "translation": {
     *                         "id": 1,
     *                         "category_id": 1,
     *                         "locale": "en",
     *                         "name": "Category 1"
     *                     }
     *                 }
     *             }
     *         ]
     *     }
     * }
     * @response 500 {
     *     "success": false,
     *     "message": "Failed to retrieve courses",
     *     "error": "Error message"
     * }
     */
    function courses(Request $request)
    {
        try {
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

            $query->when($request->price, function ($q) use ($request) {
                if ($request->price == 'paid') {
                    $q->where('price', '>', 0);
                } else {
                    $q->where('price', 0)->orWhere('price', null);
                }
            });

            $query->when($request->level, function ($q) use ($request) {
                $levelsIds = explode(',', $request->level);
                $q->whereHas('levels', function ($q) use ($levelsIds) {
                    $q->whereIn('level_id', $levelsIds);
                });
            });

            $query->with(['instructor:id,name', 'enrollments', 'category.translation']);

            if ($request->has('user_id')) {
                $authorId = $request->user_id;
                $query->whereHas('enrollments', function ($q) use ($authorId) {
                    $q->where('user_id', $authorId);
                });
            }

            $query->orderBy('id', $request->order && $request->filled('order') ? $request->order : 'desc');
            $courses = $query->paginate();

            return response()->json([
                'success' => true,
                'message' => 'Courses retrieved successfully',
                'data' => $courses
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve courses',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function showCourse(Request $request, $slug)
    {
        try {
            $query = Course::with(['instructor:id,name', 'partnerInstructors', 'levels', 'enrollments', 'category.translation', 'chapters', 'reviews', 'lessons'])
                ->where('slug', $slug)
                ->where('status', 'active');

            if ($request->has('user_id')) {
                $authorId = $request->user_id;
                $query->whereHas('enrollments', function ($q) use ($authorId) {
                    $q->where('user_id', $authorId);
                });
            }

            $course = $query->firstOrFail();

            return response()->json([
                'success' => true,
                'message' => 'Course retrieved successfully',
                'data' => $course
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function learningCourse(Request $request, $slug)
    {
        try {
            $query = Course::with([
                'enrollments',
                'chapters',
                'chapters.chapterItems',
                'chapters.chapterItems.lesson',
                'chapters.chapterItems.quiz'
            ])->where('slug', $slug)->where('status', 'active');

            $authorId = $request->user_id;
            $query->whereHas('enrollments', function ($q) use ($authorId) {
                $q->where('user_id', $authorId);
            });

            $course = $query->firstOrFail();

            $currentProgress = CourseProgress::where('user_id', $authorId)
                ->where('course_id', $course->id)
                ->where('current', 1)
                ->orderBy('id', 'desc')
                ->first();

            $alreadyWatchedLectures = CourseProgress::where('user_id', $authorId)
                ->where('course_id', $course->id)
                ->where('type', 'lesson')
                ->where('watched', 1)
                ->pluck('lesson_id')
                ->toArray();

            $alreadyCompletedQuiz = CourseProgress::where('user_id', $authorId)
                ->where('course_id', $course->id)
                ->where('type', 'quiz')
                ->where('watched', 1)
                ->pluck('lesson_id')
                ->toArray();

            $announcements = Announcement::where('course_id', $course->id)->orderBy('id', 'desc')->get();

            $courseLectureCount = CourseChapterItem::whereHas('chapter', function ($q) use ($course) {
                $q->where('course_id', $course->id);
            })->count();

            $courseLectureCompletedByUser = CourseProgress::where('user_id', $authorId)
                ->where('course_id', $course->id)->where('watched', 1)->count();
            $courseCompletedPercent = $courseLectureCount > 0 ? ($courseLectureCompletedByUser / $courseLectureCount) * 100 : 0;

            if (!$currentProgress) {
                $lessonId = @$course->chapters?->first()?->chapterItems()?->first()?->lesson->id;
                if ($lessonId) {
                    $currentProgress = CourseProgress::create([
                        'user_id'    => $authorId,
                        'course_id'  => $course->id,
                        'chapter_id' => $course->chapters->first()->id,
                        'lesson_id'  => $lessonId,
                        'current'    => 1,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Course retrieved successfully',
                'data' => [
                    'course' => $course,
                    'currentProgress' => $currentProgress,
                    'announcements' => $announcements,
                    'courseCompletedPercent' => $courseCompletedPercent,
                    'courseLectureCount' => $courseLectureCount,
                    'courseLectureCompletedByUser' => $courseLectureCompletedByUser,
                    'alreadyWatchedLectures' => $alreadyWatchedLectures,
                    'alreadyCompletedQuiz' => $alreadyCompletedQuiz
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retrieve a course thumbnail.
     *
     * @param int $courseId The ID of the course.
     * @return \Illuminate\Http\Response
     */
    public function getCourseThumbnail($courseId)
    {
        try {
            $course = Course::findOrFail($courseId);

            return response()->file(Storage::disk('private')->path($course->thumbnail));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve course thumbnail',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retrieve course categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function categories()
    {
        try {
            $categories = CourseCategory::active()->whereNull('parent_id')->with(['translation', 'subCategories' => function ($query) {
                $query->with('translation');
            }])->get();
            $categories = $categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->translation->name,
                    'children' => $category->subCategories->map(function ($child) {
                        return [
                            'id' => $child->id,
                            'name' => $child->translation->name
                        ];
                    })->toArray()
                ];
            })->toArray();
            return response()->json([
                'success' => true,
                'message' => 'Categories retrieved successfully',
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all active levels
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function levels()
    {
        try {
            $levels = CourseLevel::with(['translation'])->where('status', 1)->get();
            return response()->json([
                'success' => true,
                'message' => 'Levels retrieved successfully',
                'data' => $levels
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve levels',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
