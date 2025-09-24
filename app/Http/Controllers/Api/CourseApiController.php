<?php

namespace App\Http\Controllers\Api;

use App\Models\Course;
use App\Models\LessonReply;
use App\Traits\ApiResponse;
use iio\libmergepdf\Merger;
use App\Models\Announcement;
use App\Models\CourseReview;
use Illuminate\Http\Request;
use App\Models\CourseChapter;
use App\Models\CourseProgress;
use App\Models\LessonQuestion;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\CourseChapterItem;
use App\Models\CourseChapterLesson;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Modules\Course\app\Models\CourseTos;
use Modules\Order\app\Models\Enrollment;
use Modules\Course\app\Models\CourseLevel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Modules\Course\app\Models\CourseCategory;
use Modules\CertificateBuilder\app\Models\CertificateBuilder;

class CourseApiController extends Controller
{
    use ApiResponse;

    /**
     * @OA\Get(
     *     path="/courses",
     *     tags={"Courses"},
     *     summary="Get courses list",
     *     description="Get courses list",
     *     @OA\Parameter(
     *         description="Search by course title",
     *         in="query",
     *         name="search",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Filter by main category",
     *         in="query",
     *         name="main_category",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Filter by category",
     *         in="query",
     *         name="category",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Filter by language",
     *         in="query",
     *         name="language",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Filter by minimum Jam Pelajaran",
     *         in="query",
     *         name="jp",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             enum={"free", "paid"}
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Filter by level",
     *         in="query",
     *         name="level",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Sort by id",
     *         in="query",
     *         name="order",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Number of records per page",
     *         in="query",
     *         name="per_page",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="User ID",
     *         in="query",
     *         name="user_id",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function courses(Request $request)
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

            $query->when($request->access, function ($q) use ($request) {
                $q->where('access', $request->access);
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

            // filter by jp (jam pelajaran)
            $query->when($request->jp, function ($q) use ($request) {
                if ($request->jp > 0) {
                    $q->where('jp', '>', $request->jp);
                }
            });

            $query->when($request->level, function ($q) use ($request) {
                $levelsIds = explode(',', $request->level);
                $q->whereHas('levels', function ($q) use ($levelsIds) {
                    $q->whereIn('level_id', $levelsIds);
                });
            });

            if ($request->has('user_id')) {
                $authorId = $request->user_id;

                $query->with([
                    'instructor:id,name',
                    'enrollments' => function ($q) use ($authorId) {
                        $q->where('user_id', $authorId)
                            ->with('article');
                    },
                    'category.translation',
                    'category' => function ($query) {
                        $query->withCount('courses');
                    }
                ]);

                $query->whereHas('enrollments', function ($q) use ($authorId) {
                    $q->where('user_id', $authorId);
                    $q->where('has_access', 1);
                });
            } else {
                $query->with([
                    'instructor:id,name',
                    'enrollments',
                    'category.translation',
                    'category' => function ($query) {
                        $query->withCount('courses');
                    }
                ]);
            }

            $query->orderBy('id', $request->order && $request->filled('order') ? $request->order : 'desc');

            $courses = $query->paginate($request->per_page ?? 10);

            if ($request->has('user_id')) {
                $userId = $request->user_id;
                $courses->each(function ($course) use ($userId) {
                    $course->setAttribute('course_user_progress_api', $course->getCourseUserProgressApi($userId));
                });
            }

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
     * @OA\Get(
     *     path="/courses/{slug}",
     *     summary="Get course by slug",
     *     tags={"Courses"},
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         description="Course slug",
     *         required=true,
     *         example="laravel-fundamentals"
     *     ),
     *     @OA\Parameter(
     *         description="User ID",
     *         in="query",
     *         name="user_id",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Course retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Course retrieved successfully"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     example="Laravel Fundamentals"
     *                 ),
     *                 @OA\Property(
     *                     property="slug",
     *                     type="string",
     *                     example="laravel-fundamentals"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     example="This course covers the basics of Laravel and how to build a simple CRUD application."
     *                 ),
     *                 @OA\Property(
     *                     property="jp",
     *                     type="integer",
     *                     example=0
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     type="string",
     *                     example="https://example.com/course.jpg"
     *                 ),
     *                 @OA\Property(
     *                     property="instructor",
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="John Doe"
     *                     ),
     *                 ),
     *                 @OA\Property(
     *                     property="enrollments",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(
     *                             property="id",
     *                             type="integer",
     *                             example=1
     *                         ),
     *                         @OA\Property(
     *                             property="user",
     *                             type="object",
     *                             @OA\Property(
     *                                 property="id",
     *                                 type="integer",
     *                                 example=1
     *                             ),
     *                             @OA\Property(
     *                                 property="name",
     *                                 type="string",
     *                                 example="Jane Doe"
     *                             ),
     *                         ),
     *                         @OA\Property(
     *                             property="created_at",
     *                             type="string",
     *                             example="2022-01-01 00:00:00"
     *                         ),
     *                     ),
     *                 ),
     *                 @OA\Property(
     *                     property="category",
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="Laravel"
     *                     ),
     *                 ),
     *                 @OA\Property(
     *                     property="levels",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(
     *                             property="id",
     *                             type="integer",
     *                             example=1
     *                         ),
     *                         @OA\Property(
     *                             property="name",
     *                             type="string",
     *                             example="Beginner"
     *                         ),
     *                     ),
     *                 ),
     *                 @OA\Property(
     *                     property="chapters",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(
     *                             property="id",
     *                             type="integer",
     *                             example=1
     *                         ),
     *                         @OA\Property(
     *                             property="title",
     *                             type="string",
     *                             example="Chapter 1"
     *                         ),
     *                         @OA\Property(
     *                             property="description",
     *                             type="string",
     *                             example="This is the first chapter of the course."
     *                         ),
     *                         @OA\Property(
     *                             property="created_at",
     *                             type="string",
     *                             example="2022-01-01 00:00:00"
     *                         ),
     *                     ),
     *                 ),
     *                 @OA\Property(
     *                     property="reviews",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(
     *                             property="id",
     *                             type="integer",
     *                             example=1
     *                         ),
     *                         @OA\Property(
     *                             property="rating",
     *                             type="integer",
     *                             example=5
     *                         ),
     *                         @OA\Property(
     *                             property="comment",
     *                             type="string",
     *                             example="This course is very good."
     *                         ),
     *                         @OA\Property(
     *                             property="created_at",
     *                             type="string",
     *                             example="2022-01-01 00:00:00"
     *                         ),
     *                     ),
     *                 ),
     *                 @OA\Property(
     *                     property="lessons",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(
     *                             property="id",
     *                             type="integer",
     *                             example=1
     *                         ),
     *                         @OA\Property(
     *                             property="title",
     *                             type="string",
     *                             example="Lesson 1"
     *                         ),
     *                         @OA\Property(
     *                             property="description",
     *                             type="string",
     *                             example="This is the first lesson of the course."
     *                         ),
     *                         @OA\Property(
     *                             property="created_at",
     *                             type="string",
     *                             example="2022-01-01 00:00:00"
     *                         ),
     *                     ),
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Course not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Course not found"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to retrieve course",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Failed to retrieve course"
     *             ),
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Internal Server Error"
     *             )
     *         )
     *     )
     * )
     */
    public function showCourse(Request $request, $slug)
    {
        try {
            $query = Course::with([
                'instructor:id,name',
                'partnerInstructors',
                'levels',
                'enrollments',
                'category.translation',
                'chapters.chapterItems.lesson',
                'reviews',
                'lessons',
                'category' => function ($query) {
                    $query->withCount('courses');
                }
            ])
                ->where('slug', $slug)
                ->where('status', 'active');

            // if ($request->has('user_id')) {
            //     $authorId = $request->user_id;
            //     $query->whereHas('enrollments', function ($q) use ($authorId) {
            //         $q->where('user_id', $authorId);
            //         $q->where('has_access', 1);
            //     });
            // }

            $course = $query->firstOrFail();

            if ($request->has('user_id')) {
                $userId = $request->user_id;
                $course->course_user_progress_api = $course->getCourseUserProgressApi($userId);
                $course->enrollments->where('user_id', $userId)->first();
            }

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

    /**
     * @OA\Get(
     *     path="/courses/{slug}/learning",
     *     summary="Get course detail for learning",
     *     description="Get course detail for learning",
     *     tags={"Courses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         description="Slug of course",
     *         in="path",
     *         name="slug",
     *         required=true,
     *         example="course-1",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="User ID",
     *         in="query",
     *         name="user_id",
     *         required=true,
     *         example="1",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="internal server error"
     *     )
     * )
     */
    public function learningCourse(Request $request, $slug)
    {
        try {
            $query = Course::with([
                'chapters',
                'chapters.chapterItems',
                'chapters.chapterItems.lesson',
                'chapters.chapterItems.quiz'
            ])->where('slug', $slug)->where('status', 'active');

            $authorId = $request->user_id;
            $query->whereHas('enrollments', function ($q) use ($authorId) {
                $q->where('user_id', $authorId);
                $q->where('has_access', 1);
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
     * @OA\Post(
     *     path="/courses/{slug}/join",
     *     summary="Join course",
     *     description="Join course",
     *     tags={"Courses"},
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         description="Course slug",
     *         required=true,
     *         example="laravel-fundamentals"
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="User ID",
     *         required=true,
     *         example=1
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successfully joined course, waiting for approval",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Successfully joined course, waiting for approval"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     example="Laravel Fundamentals"
     *                 ),
     *                 @OA\Property(
     *                     property="slug",
     *                     type="string",
     *                     example="laravel-fundamentals"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     example="This course covers the basics of Laravel and how to build a simple CRUD application."
     *                 ),
     *                 @OA\Property(
     *                     property="jp",
     *                     type="integer",
     *                     example=0
     *                 ),
     *                 @OA\Property(
     *                     property="level",
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="Beginner"
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="category",
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="Laravel"
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="main_category",
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="Programming"
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="chapters",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(
     *                             property="id",
     *                             type="integer",
     *                             example=1
     *                         ),
     *                         @OA\Property(
     *                             property="name",
     *                             type="string",
     *                             example="Introduction"
     *                         ),
     *                         @OA\Property(
     *                             property="description",
     *                             type="string",
     *                             example="This chapter covers the basics of Laravel and how to build a simple CRUD application."
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to join course",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Failed to join course"
     *             ),
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Something went wrong"
     *             )
     *         )
     *     )
     * )
     */
    public function joinCourse(Request $request, $slug)
    {
        try {
            $course = Course::where('slug', $slug)
                ->where('status', 'active')
                ->where('access', 'public')
                ->firstOrFail();
            $enrollment = Enrollment::firstOrCreate(
                [
                    'course_id' => $course->id,
                    'user_id' => $request->user_id,
                    'has_access' => null,
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Successfully joined course, waiting for approval',
                'data' => $course
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to join course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/courses-popular",
     *     summary="Get popular courses",
     *     description="Get popular courses. This endpoint returns a list of courses sorted by the number of enrollments in descending order.",
     *     tags={"Courses"},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of courses to return per page",
     *         required=false,
     *         example=10
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Popular courses retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     example="Laravel Fundamentals"
     *                 ),
     *                 @OA\Property(
     *                     property="slug",
     *                     type="string",
     *                     example="laravel-fundamentals"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     example="This course covers the basics of Laravel and how to build a simple CRUD application."
     *                 ),
     *                 @OA\Property(
     *                     property="instructor",
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="John Doe"
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="category",
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="Programming"
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="enrollments_count",
     *                     type="integer",
     *                     example=100
     *                 ),
     *                 @OA\Property(
     *                     property="created_at",
     *                     type="string",
     *                     format="date-time",
     *                     example="2021-01-01T00:00:00.000000Z"
     *                 ),
     *                 @OA\Property(
     *                     property="updated_at",
     *                     type="string",
     *                     format="date-time",
     *                     example="2021-01-01T00:00:00.000000Z"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to retrieve popular courses",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Failed to retrieve popular courses"
     *             ),
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Something went wrong"
     *             )
     *         )
     *     )
     * )
     */
    public function popularCourses(Request $request)
    {
        try {
            $courses = Course::with('instructor', 'category.translation')
                ->where('status', 'active')
                ->withCount(['enrollments' => function ($query) {
                    $query->where('has_access', 1);
                }])
                ->orderByDesc('enrollments_count')
                ->paginate($request->per_page ?? 10);

            return response()->json([
                'success' => true,
                'message' => 'Retrieved popular courses',
                'data' => $courses
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve popular courses',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/courses-favourite",
     *     summary="Get favourite courses",
     *     description="Get favourite courses",
     *     tags={"Courses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         description="User ID",
     *         in="query",
     *         name="user_id",
     *         required=true,
     *         example=1
     *     ),
     *     @OA\Parameter(
     *         description="Items per page",
     *         in="query",
     *         name="per_page",
     *         required=false,
     *         example=10
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Favourite courses retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Favourite courses not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function favouriteCourses(Request $request)
    {
        try {
            $courses = Course::with('instructor:id,name', 'category.translation')
                ->where('status', 'active')
                ->whereHas('favoriteBy', function ($query) use ($request) {
                    $query->where('user_id', $request->user_id);
                })
                ->paginate($request->per_page ?? 10);

            return response()->json([
                'success' => true,
                'message' => 'Retrieved favourite courses',
                'data' => $courses
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve popular courses',
                'error' => $e->getMessage()
            ], 500);
        }
    }

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
     * @OA\Get(
     *     path="/courses-categories",
     *     tags={"Courses"},
     *     summary="Get course categories",
     *     description="Get course categories. This endpoint returns a tree of categories with their sub categories.",
     *     @OA\Response(
     *         response=200,
     *         description="Categories retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     example="Laravel"
     *                 ),
     *                 @OA\Property(
     *                     property="children",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(
     *                             property="id",
     *                             type="integer",
     *                             example=2
     *                         ),
     *                         @OA\Property(
     *                             property="name",
     *                             type="string",
     *                             example="Laravel Fundamentals"
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function categories()
    {
        try {
            // $categories = CourseCategory::active()->whereNull('parent_id')->with(['translation', 'subCategories' => function ($query) {
            //     $query->with('translation');
            // }])->get();
            // $categories = $categories->map(function ($category) {
            //     return [
            //         'id' => $category->id,
            //         'name' => $category->translation->name,
            //         'children' => $category->subCategories->map(function ($child) {
            //             return [
            //                 'id' => $child->id,
            //                 'name' => $child->translation->name
            //             ];
            //         })->toArray()
            //     ];
            // })->toArray();
            $categories = CourseCategory::active()
                ->with(['translation', 'subCategories.translation'])
                ->whereNull('parent_id')
                ->get()
                ->each(function ($category) {
                    $category->loadCount('courses');
                    $category->subCategories->each->loadCount('courses');
                    $category->courses_count = $category->courses_count + $category->subCategories->sum('courses_count');
                });

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
     * @OA\Get(
     *     path="/courses-child-categories",
     *     summary="Get course child categories",
     *     description="Get course child categories",
     *     tags={"Courses"},
     *     @OA\Response(
     *         response=200,
     *         description="Child categories retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     example="Laravel Fundamentals"
     *                 ),
     *                 @OA\Property(
     *                     property="parent_id",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="children",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(
     *                             property="id",
     *                             type="integer",
     *                             example=2
     *                         ),
     *                         @OA\Property(
     *                             property="name",
     *                             type="string",
     *                             example="Laravel Fundamentals"
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function childCategories()
    {
        try {
            $categories = CourseCategory::active()
                ->with(['translation', 'parentCategory.translation'])
                ->whereNotNull('parent_id')
                ->get()
                ->each(function ($category) {
                    $category->loadCount('courses');
                    $category->courses_count;
                });

            return response()->json([
                'success' => true,
                'message' => 'Child categories retrieved successfully',
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve child categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/courses-levels",
     *     summary="Get course levels",
     *     description="Get course levels",
     *     tags={"Courses"},
     *     @OA\Response(
     *         response=200,
     *         description="Levels retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     example="Laravel"
     *                 ),
     *                 @OA\Property(
     *                     property="slug",
     *                     type="string",
     *                     example="laravel"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     example="This course covers the basics of Laravel"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to retrieve levels",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Failed to retrieve levels"
     *             ),
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Something went wrong while retrieving levels"
     *             )
     *         )
     *     )
     * )
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

    /**
     * @OA\Get(
     *     path="/courses/{slug}/reviews",
     *     tags={"Courses"},
     *     summary="Get all reviews for a course",
     *     description="Get all reviews for a course",
     *     @OA\Parameter(
     *         description="slug of the course",
     *         in="path",
     *         name="slug",
     *         required=true,
     *         example="laravel",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="User ID",
     *         in="query",
     *         name="user_id",
     *         required=false,
     *         example=1,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Page number",
     *         in="query",
     *         name="page",
     *         required=false,
     *         example=1,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reviews retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="course_id",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="user_id",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="rating",
     *                         type="integer",
     *                         example=4
     *                     ),
     *                     @OA\Property(
     *                         property="review",
     *                         type="string",
     *                         example="This course is awesome"
     *                     ),
     *                     @OA\Property(
     *                         property="status",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         format="date-time",
     *                         example="2020-01-01 12:00:00"
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No reviews found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="No reviews found"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 items={},
     *                 example=null
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to retrieve reviews",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Failed to retrieve reviews"
     *             ),
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Something went wrong while retrieving reviews"
     *             )
     *         )
     *     )
     * )
     */
    public function reviews(Request $request, $slug)
    {
        try {
            // $reviews = CourseReview::with('course:id,title,slug,thumbnail', 'user:id,name')
            //     ->whereHas('course', function ($q) use ($slug) {
            //         $q->where('slug', $slug);
            //     })
            //     ->orderByDesc('id')
            //     ->get();

            $course = Course::where('slug', $slug)->firstOrFail();

            $reviews = CourseReview::with('user', 'course')->whereHas('course', function ($q) use ($slug) {
                $q->where('slug', $slug);
            })->where(function ($query) use ($request) {
                $query->where('status', 1)
                    ->orWhere(function ($subQuery) use ($request) {
                        $subQuery->where('user_id', $request->user_id)
                            ->where('status', 0);
                    });
            })->whereHas('course')
                ->whereHas('user')
                ->orderByRaw('status = 0 DESC')
                ->orderBy('id', 'desc')
                ->paginate(8, ['*'], 'page', $request->page ?? 1);

            if ($reviews->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada ulasan ditemukan',
                    'data' => [],
                ], 200);
            }

            return response()->json([
                'success' => true,
                'message' => 'Daftar ulasan yang pernah diberikan.',
                'data' => $reviews,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/courses/{slug}/reviews",
     *     summary="Add course review",
     *     description="Add course review",
     *     tags={"Courses"},
     *     @OA\Parameter(
     *         description="Course slug",
     *         in="path",
     *         name="slug",
     *         required=true,
     *         example="laravel-fundamentals"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="user_id",
     *                 type="integer",
     *                 example=1
     *             ),
     *             @OA\Property(
     *                 property="rating",
     *                 type="integer",
     *                 minimum=1,
     *                 maximum=5,
     *                 example=5
     *             ),
     *             @OA\Property(
     *                 property="review",
     *                 type="string",
     *                 example="This course is awesome!"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Review created successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Course not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function reviewsStore(Request $request, $slug)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string',
        ]);

        try {
            $course = Course::where('slug', $slug)->first();

            if (!$course) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pelatihan tidak ditemukan',
                ], 404);
            }

            $review = CourseReview::firstOrCreate([
                'course_id' => $course->id,
                'user_id' => $request->user_id,
                'rating' => $request->rating,
                'review' => $request->review,
                'status' => 1
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ulasan berhasil disimpan.',
                'data' => $review,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/courses/{slug}/questions",
     *     summary="Get course questions",
     *     description="Get course questions",
     *     tags={"Courses"},
     *     @OA\Parameter(
     *         description="Course slug",
     *         in="path",
     *         name="slug",
     *         required=true,
     *         example="laravel-fundamentals"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Questions retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="course",
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="title",
     *                         type="string",
     *                         example="Laravel Fundamentals"
     *                     ),
     *                     @OA\Property(
     *                         property="slug",
     *                         type="string",
     *                         example="laravel-fundamentals"
     *                     ),
     *                     @OA\Property(
     *                         property="thumbnail",
     *                         type="string",
     *                         format="binary",
     *                         example="thumbnail.jpg"
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="user",
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="John Doe"
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="lesson",
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="title",
     *                         type="string",
     *                         example="Laravel Installation"
     *                     ),
     *                     @OA\Property(
     *                         property="slug",
     *                         type="string",
     *                         example="laravel-installation"
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="replies",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(
     *                             property="id",
     *                             type="integer",
     *                             example=1
     *                         ),
     *                         @OA\Property(
     *                             property="user",
     *                             type="object",
     *                             @OA\Property(
     *                                 property="id",
     *                                 type="integer",
     *                                 example=1
     *                             ),
     *                             @OA\Property(
     *                                 property="name",
     *                                 type="string",
     *                                 example="John Doe"
     *                             )
     *                         ),
     *                         @OA\Property(
     *                             property="reply",
     *                             type="string",
     *                             example="This is a reply"
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Course not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function questions(Request $request, $slug)
    {
        try {
            $questions = LessonQuestion::with('course:id,title,slug,thumbnail', 'user:id,name', 'lesson', 'replies.user:id,name')
                ->whereHas('course', function ($q) use ($slug) {
                    $q->where('slug', $slug);
                })
                ->orderByDesc('id')
                ->get();

            if ($questions->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada pertanyaan ditemukan',
                    'data' => [],
                ], 200);
            }

            return response()->json([
                'success' => true,
                'message' => 'Daftar pertanyaan yang pernah diberikan.',
                'data' => $questions,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/lessons/{slug}/questions",
     *     summary="Get questions for a lesson",
     *     description="Get questions for a lesson",
     *     tags={"Lessons"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         description="Lesson slug",
     *         in="path",
     *         name="slug",
     *         required=true,
     *         example="laravel-fundamentals"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Questions retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Questions retrieved successfully"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="course",
     *                         type="object",
     *                         @OA\Property(
     *                             property="id",
     *                             type="integer",
     *                             example=1
     *                         ),
     *                         @OA\Property(
     *                             property="title",
     *                             type="string",
     *                             example="Laravel Fundamentals"
     *                         ),
     *                         @OA\Property(
     *                             property="slug",
     *                             type="string",
     *                             example="laravel-fundamentals"
     *                         ),
     *                         @OA\Property(
     *                             property="thumbnail",
     *                             type="string",
     *                             example="https://example.com/thumbnail.jpg"
     *                         )
     *                     ),
     *                     @OA\Property(
     *                         property="user",
     *                         type="object",
     *                         @OA\Property(
     *                             property="id",
     *                             type="integer",
     *                             example=1
     *                         ),
     *                         @OA\Property(
     *                             property="name",
     *                             type="string",
     *                             example="John Doe"
     *                         )
     *                     ),
     *                     @OA\Property(
     *                         property="lesson",
     *                         type="object",
     *                         @OA\Property(
     *                             property="id",
     *                             type="integer",
     *                             example=1
     *                         ),
     *                         @OA\Property(
     *                             property="title",
     *                             type="string",
     *                             example="Laravel Basics"
     *                         ),
     *                         @OA\Property(
     *                             property="slug",
     *                             type="string",
     *                             example="laravel-basics"
     *                         )
     *                     ),
     *                     @OA\Property(
     *                         property="question_title",
     *                         type="string",
     *                         example="This is a question title"
     *                     ),
     *                     @OA\Property(
     *                         property="question_description",
     *                         type="string",
     *                         example="This is a question description"
     *                     ),
     *                     @OA\Property(
     *                         property="replies",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(
     *                                 property="id",
     *                                 type="integer",
     *                                 example=1
     *                             ),
     *                             @OA\Property(
     *                                 property="reply",
     *                                 type="string",
     *                                 example="This is a reply"
     *                             )
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Lesson not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function lessonQuestions(Request $request, $slug)
    {
        try {
            $questions = LessonQuestion::with('course:id,title,slug,thumbnail', 'user:id,name', 'lesson', 'replies')
                ->whereHas('lesson', function ($q) use ($slug) {
                    $q->where('slug', $slug);
                })
                ->orderByDesc('id')
                ->get();

            if ($questions->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada pertanyaan ditemukan',
                    'data' => [],
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Daftar pertanyaan yang pernah diberikan.',
                'data' => $questions,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/lessons/questions-store",
     *     summary="Add new question",
     *     description="Add new question",
     *     tags={"Lessons"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="course_id",
     *                 type="integer",
     *                 example=1,
     *                 description="Course ID"
     *             ),
     *             @OA\Property(
     *                 property="user_id",
     *                 type="integer",
     *                 example=1,
     *                 description="User ID"
     *             ),
     *             @OA\Property(
     *                 property="lesson_id",
     *                 type="integer",
     *                 example=1,
     *                 description="Lesson ID"
     *             ),
     *             @OA\Property(
     *                 property="question_title",
     *                 type="string",
     *                 example="This is a question title",
     *                 description="Question title"
     *             ),
     *             @OA\Property(
     *                 property="question_description",
     *                 type="string",
     *                 example="This is a question description",
     *                 description="Question description"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Question created successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Course not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function questionsStore(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'user_id' => 'required|exists:users,id',
            'lesson_id' => 'required|exists:course_chapter_lessons,id',
            'question_title' => 'required|string',
            'question_description' => 'required|string',
        ]);

        try {
            $course = Course::where('id', $request->course_id)->first();

            if (!$course) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pelatihan tidak ditemukan',
                ], 404);
            }

            $lesson = CourseChapterLesson::where('course_id', $course->id)
                ->where('id', $request->lesson_id)
                ->first();

            if (!$lesson) {
                return response()->json([
                    'success' => false,
                    'message' => 'Materi pelatihan tidak ditemukan',
                ], 404);
            }

            $review = LessonQuestion::firstOrCreate([
                'course_id' => $request->course_id,
                'user_id' => $request->user_id,
                'lesson_id' => $request->lesson_id,
                'question_title' => $request->question_title,
                'question_description' => $request->question_description
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pertanyaan berhasil ditambahkan.',
                'data' => $review,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/lessons/answer-store",
     *     summary="Add new answer",
     *     description="Add new answer",
     *     tags={"Lessons"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="question_id",
     *                 type="integer",
     *                 example=1,
     *                 description="Question ID"
     *             ),
     *             @OA\Property(
     *                 property="user_id",
     *                 type="integer",
     *                 example=1,
     *                 description="User ID"
     *             ),
     *             @OA\Property(
     *                 property="reply",
     *                 type="string",
     *                 example="This is an answer",
     *                 description="Answer description"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Answer created successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Question not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function answerStore(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:lesson_questions,id',
            'user_id' => 'required|exists:users,id',
            'reply' => 'required|string',
        ]);

        try {
            $lesson = CourseChapterLesson::where('id', $request->question_id)->first();

            if (!$lesson) {
                return response()->json([
                    'success' => false,
                    'message' => 'Materi pelatihan tidak ditemukan',
                ], 404);
            }

            $answer = LessonReply::firstOrCreate([
                'question_id' => $request->question_id,
                'user_id' => $request->user_id,
                'reply' => $request->reply
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tanggapan berhasil ditambahkan.',
                'data' => $answer,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/courses/request-sign-certificate/{enrollment}",
     *     summary="Request sign certificate",
     *     description="Request sign certificate",
     *     tags={"Courses"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         description="Enrollment ID",
     *         in="path",
     *         name="enrollment",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function requestSignCertificate(Request $request, string $enrollment)
    {
        $enrollment = Enrollment::where('uuid', $enrollment)->first();

        if (!$enrollment) {
            return $this->errorResponse(__('Enrollment not found'), [], 404);
        }

        try {
            $course = $enrollment->course;

            if (null == $course) {
                return $this->errorResponse(__('Course not found'), [], 404);
            }

            $courseChapters = CourseChapter::where('course_id', $course->id)
                ->where('status', 'active')
                ->get();

            $courseLectureCount = CourseChapterItem::whereHas('chapter', function ($q) use ($course) {
                $q->where('course_id', $course->id);
            })->count();

            $courseLectureCompletedByUser = CourseProgress::where('user_id', $request->user()->id)
                ->where('course_id', $course->id)->where('watched', 1)->latest();

            $completed_date = formatDate($courseLectureCompletedByUser->first()?->created_at);

            $courseLectureCompletedByUser = CourseProgress::where('user_id', $request->user()->id)
                ->where('course_id', $course->id)->where('watched', 1)->count();

            $courseCompletedPercent = $courseLectureCount > 0 ? ($courseLectureCompletedByUser / $courseLectureCount) * 100 : 0;

            $certificate = CertificateBuilder::findOrFail($course->certificate_id);
            $certificateItems = $certificate->items;

            // $now = now();
            $cover1Base64 = null;
            if (filled($certificate->background)) {
                if (!Storage::disk('private')->exists($certificate->background)) {
                    return $this->errorResponse(__('Certificate background not found'), [], 404);
                }
                $cover1Base64 = base64_encode(file_get_contents(Storage::disk('private')->path($certificate->background)));
            }

            $qrCodePublicURL = route('public.certificate', ['uuid' => $enrollment->uuid]);

            $qrcodeData = QrCode::format('png')->size(200)
                ->merge('/public/backend/img/logobantul.png')
                ->generate($qrCodePublicURL);
            $qrcodeData = 'data:image/png;base64,' . base64_encode($qrcodeData);


            $page1Html = view('frontend.student-dashboard.certificate.index', [
                'certificateItems' => $certificateItems,
                'certificate' => $certificate,
                'cover1Base64' => $cover1Base64,
                'qrcodeData' => $qrcodeData
            ])->render();

            $page1Html = str_replace('[student_name]', $request->user()->name, $page1Html);
            $page1Html = str_replace('[platform_name]', Cache::get('setting')->app_name, $page1Html);
            $page1Html = str_replace('[course]', $course->title, $page1Html);
            $page1Html = str_replace('[date]', formatDate($completed_date), $page1Html);
            $page1Html = str_replace('[instructor_name]', $course->instructor->name, $page1Html);

            $signer1 = $course->signers()->where('step', 1)->first()->user;


            $page1Html = str_replace('[tanggal_sertifikat]', sprintf('Bantul, %s %s %s', now()->day, now()->monthName, now()->year), $page1Html);
            $page1Html = str_replace('[nama_jabatan]', $signer1->jabatan, $page1Html);
            $page1Html = str_replace('[nama_kepala_opd]',  $signer1->name, $page1Html);
            $page1Html = str_replace('[nama_golongan]', $signer1->golongan, $page1Html);
            $page1Html = str_replace('[nip]',  $signer1->nip, $page1Html);

            $now = now();
            $pdf1Data = Pdf::loadHTML($page1Html)
                ->setPaper('A4', 'landscape')->setWarnings(false)->output();
            Log::info('render pdf 1 took ' . now()->diffInSeconds($now, true) . ' seconds');

            //=========
            // page2
            //=========
            $cover2Base64 = null;
            if (filled($certificate->background2)) {
                if (!Storage::disk('private')->exists($certificate->background2)) {
                    return $this->errorResponse(__('Certificate background not found'), [], 404);
                }
                $cover2Base64 = base64_encode(file_get_contents(Storage::disk('private')->path($certificate->background2)));
            }


            $qrcodeData2 = QrCode::format('png')->size(200)
                ->merge('/public/backend/img/logobantul.png')
                ->generate($qrCodePublicURL);

            $qrcodeData2 = 'data:image/png;base64,' . base64_encode($qrcodeData2);


            $page2Html = view('frontend.student-dashboard.certificate.summary', [
                'course' => $course,
                'certificateItems' => $certificateItems,
                'certificate' => $certificate,
                'courseChapers' => $courseChapters,
                'cover2Base64' => $cover2Base64,
                'qrcodeData2' => $qrcodeData2
            ])->render();


            $signer2 = $course->signers()->where('step', 2)->first()->user;

            $page2Html = str_replace('[tanggal_sertifikat]', sprintf('Bantul, %s %s %s', now()->day, now()->monthName, now()->year), $page2Html);
            $page2Html = str_replace('[nama_jabatan]', $signer2->jabatan, $page2Html);
            $page2Html = str_replace('[nama_kepala_opd]',  $signer2->name, $page2Html);
            $page2Html = str_replace('[nama_golongan]', $signer2->golongan, $page2Html);
            $page2Html = str_replace('[nip]',  $signer2->nip, $page2Html);

            $pdf2Data = Pdf::loadHTML($page2Html)
                ->setPaper('A4', 'portrait')->setWarnings(false)->output();

            $now = now();
            $m = new Merger();
            $m->addRaw($pdf1Data);
            $m->addRaw($pdf2Data);
            $output = $m->merge();

            Log::info('merge pdf took ' . now()->diffInSeconds($now));

            // send to Bantara API endpoint
            $url = sprintf('%s/internal/v1/tte/documents', appConfig('bantara_url'));

            $signers = [];
            foreach ($course->signers()->orderBy('step', 'desc')->get() as $signer) {
                $signers[] =
                    [
                        'nik' => $signer->user->nik,
                        'action' => 'SIGN'
                    ];
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . appConfig('bantara_key'),
            ])
                ->attach(
                    'file',
                    $output,
                    'certificate.pdf',
                    ['Content-Type' => 'application/pdf']
                )
                ->post($url, [
                    'signers' => json_encode($signers),
                    'title' => sprintf("Sertifikat Pelatihan %s an %s", $course->title, $enrollment->user->name),
                    'description' => $enrollment->user->name,
                    'callback_url' => sprintf("%s", route('api.bantara-callback', $enrollment)),
                    'callback_key' => appConfig('bantara_callback_key'),
                ]);

            if ($response->failed()) {
                Log::error($response->body());
                return $this->errorResponse('Terjadi kesalahan dalam pengiriman sertifikat ke Bantara', [], 500);
            }

            $enrollment->certificate_status = 'requested';
            $enrollment->save();

            return $this->successResponse([], 'Sertifikat berhasil dikirim ke Bantara', 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/courses/{slug}/tos",
     *     summary="Get course TOS",
     *     description="Get course TOS",
     *     tags={"Courses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         description="Slug of course",
     *         in="path",
     *         name="slug",
     *         required=true,
     *         example="course-1",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="user_id",
     *                 type="integer",
     *                 example=1
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function courseTos($slug, Request $request)
    {
        try {
            $course = Course::where('slug', $slug)->firstOrFail();
            $enrollment = Enrollment::where('course_id', $course->id)->where('user_id', $request->user_id)->firstOrFail();
            $courseTos = CourseTos::first()->toArray();
            $courseTos['tos_status'] = $enrollment->tos_status;
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menampilkan syarat dan ketentuan',
                'data' => $courseTos
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/courses/{slug}/accept-tos",
     *     summary="Accept course TOS",
     *     description="Accept course TOS",
     *     tags={"Courses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         description="Slug of course",
     *         in="path",
     *         name="slug",
     *         required=true,
     *         example="course-1",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="user_id",
     *                 type="integer",
     *                 example=1
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="internal server error"
     *     )
     * )
     */
    public function acceptTos($slug, Request $request)
    {
        try {
            $course = Course::where('slug', $slug)->first();
            $enrollment = Enrollment::where('course_id', $course->id)->where('user_id', $request->user_id)->first();
            if ($enrollment && $enrollment->tos_status != 'accepted') {
                $enrollment->update(['tos_status' => 'accepted']);

                $currentProgress = CourseProgress::where('user_id', $request->user_id)
                    ->where('course_id', $course->id)
                    ->where('current', 1)
                    ->orderBy('id', 'desc')
                    ->first();

                if (!$currentProgress) {
                    $lessonId = @$course->chapters?->first()?->chapterItems()?->first()?->lesson->id;
                    if ($lessonId) {
                        $currentProgress = CourseProgress::create([
                            'user_id'    => $request->user_id,
                            'course_id'  => $course->id,
                            'chapter_id' => $course->chapters->first()->id,
                            'lesson_id'  => $lessonId,
                            'current'    => 1,
                        ]);
                    }
                }
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil menerima syarat dan ketentuan',
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
