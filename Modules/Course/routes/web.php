<?php

use App\Http\Controllers\Frontend\InstructorCourseController;
use Faker\Guesser\Name;
use Illuminate\Support\Facades\Route;
use Modules\Course\app\Http\Controllers\CourseCategoryController;
use Modules\Course\app\Http\Controllers\CourseContentController;
use Modules\Course\app\Http\Controllers\CourseController;
use Modules\Course\app\Http\Controllers\CourseDeleteRequestController;
use Modules\Course\app\Http\Controllers\CourseLanguageController;
use Modules\Course\app\Http\Controllers\CourseLevelController;
use Modules\Course\app\Http\Controllers\CourseReportController;
use Modules\Course\app\Http\Controllers\CourseReviewController;
use Modules\Course\app\Http\Controllers\CourseSubCategoryController;
use Modules\Course\app\Http\Controllers\CourseTosController;
use Modules\Course\app\Http\Controllers\CourseVerificationController;
use Modules\Course\app\Http\Controllers\ReviewController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['auth:admin', 'translation'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::resource('course', CourseController::class)->names('course');

    Route::group(['middleware' => 'role.admin:Super Admin|Admin BKPSDM'], function () {
        /** Course category routes */
        Route::put('course-category/status-update/{id}', [CourseCategoryController::class, 'statusUpdate'])->name('course-category.status-update');
        Route::resource('course-category', CourseCategoryController::class)->names('course-category');
        /** Course sub category routes */
        Route::get('course-sub-category/{parent_id}', [CourseSubCategoryController::class, 'index'])->name('course-sub-category.index');
        Route::get('course-sub-category/{parent_id}/create', [CourseSubCategoryController::class, 'create'])->name('course-sub-category.create');
        Route::post('course-sub-category/{parent_id}/store', [CourseSubCategoryController::class, 'store'])->name('course-sub-category.store');
        Route::get('course-sub-category/{parent_id}/{sub_category_id}/edit', [CourseSubCategoryController::class, 'edit'])->name('course-sub-category.edit');
        Route::put('course-sub-category/{parent_id}/{sub_category_id}/update', [CourseSubCategoryController::class, 'update'])->name('course-sub-category.update');
        Route::delete('course-sub-category/{parent_id}/{sub_category_id}', [CourseSubCategoryController::class, 'destroy'])->name('course-sub-category.destroy');
        Route::put('course-sub-category/status-update/{id}', [CourseSubCategoryController::class, 'statusUpdate'])->name('course-sub-category.status-update');

        /** Course verification routes */
        Route::get('/course-verification/{id}', [CourseVerificationController::class, 'index'])->name('course-verification.index');
        Route::post('/course/update-enrollment-status', [CourseVerificationController::class, 'updateEnrollmentStatus'])->name('course.updateEnrollmentStatus');
        Route::get('/course-verification/{id}/rejected', [CourseVerificationController::class, 'rejectedList'])->name('course-verification.rejectedList');

        Route::get('/course-tos', [CourseTosController::class, 'create'])->name('course-tos.create');
        Route::post('/course-tos', [CourseTosController::class, 'store'])->name('course-tos.store');

        Route::get('/course-report', [CourseReportController::class, 'index'])->name('course-report.index');
        Route::put('/course-report/{id}', [CourseReportController::class, 'update'])->name('course-report.update');
    });


    Route::group(['middleware' => 'role.admin:Super Admin'], function () {
        /** Course Language Routes */
        Route::put('course-language/status-update/{id}', [CourseLanguageController::class, 'statusUpdate'])->name('course-language.status-update');
        Route::resource('course-language', CourseLanguageController::class)->names('course-language');
    });

    Route::group(['middleware' => 'role.admin:Super Admin|Admin BKPSDM'], function () {
        /** Course Level Routes */
        Route::put('course-level/status-update/{id}', [CourseLevelController::class, 'statusUpdate'])->name('course-level.status-update');
        Route::resource('course-level', CourseLevelController::class)->names('course-level');
    });


    /** Course Routes */
    Route::get('courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::get('courses/create/{id}/step/{step?}', [CourseController::class, 'edit'])->name('courses.edit');
    Route::get('courses/{id}/show', [CourseController::class, 'show'])->name('courses.show');
    Route::get('courses/{id}/edit', [CourseController::class, 'editView'])->name('courses.edit-view');

    Route::get('courses/get-instructors', [CourseController::class, 'getInstructors'])->name('courses.get-instructors');
    Route::get('courses/get-signers', [CourseController::class, 'getSigners'])->name('courses.get-signers');
    Route::post('courses/create', [CourseController::class, 'store'])->name('courses.store');
    Route::post('courses/update', [CourseController::class, 'update'])->name('courses.update');
    Route::get('courses/duplicate/{id}', [CourseController::class, 'duplicate'])->name('courses.duplicate');
    Route::delete('courses/delete/{id}', [CourseController::class, 'destroy'])->name('courses.destroy');
    Route::post('courses/status-update/{id}', [CourseController::class, 'statusUpdate'])->name('courses.status-update');
    Route::post('courses/publish-status-update/{id}', [CourseController::class, 'publishStatusUpdate'])->name('courses.publish-status-update');

    /** Students */
    Route::get('courses/get-students', [CourseController::class, 'getStudents'])->name('courses.get-students');
    Route::get('courses/get-instansi', [CourseController::class, 'getIntansi'])->name('courses.get-instansi');
    Route::get('courses/get-unor', [CourseController::class, 'getUnor'])->name('courses.get-unor');


    /** Course content routes */
    Route::post('course-chapter/{course_id?}/store', [CourseContentController::class, 'chapterStore'])->name('course-chapter.store');
    Route::get('course-chapter/sorting/{course_id}', [CourseContentController::class, 'chapterSorting'])->name('course-chapter.sorting.index');
    Route::get('course-chapter/edit/{chapter_id}', [CourseContentController::class, 'chapterEdit'])->name('course-chapter.edit');
    Route::put('course-chapter/update/{chapter_id}', [CourseContentController::class, 'chapterUpdate'])->name('course-chapter.update');
    Route::delete('course-chapter/delete/{chapter_id}', [CourseContentController::class, 'chapterDestroy'])->name('course-chapter.destroy');

    Route::post('course-chapter/sorting/{course_id}', [CourseContentController::class, 'chapterSortingStore'])->name('course-chapter.sorting.store');
    Route::get('course-chapter/lesson/create', [CourseContentController::class, 'lessonCreate'])->name('course-chapter.lesson.create');
    Route::post('course-chapter/lesson/create', [CourseContentController::class, 'lessonStore'])->name('course-chapter.lesson.store');
    Route::get('course-chapter/lesson/edit', [CourseContentController::class, 'lessonEdit'])->name('course-chapter.lesson.edit');

    Route::post('course-chapter/lesson/update', [CourseContentController::class, 'lessonUpdate'])->name('course-chapter.lesson.update');
    Route::delete('course-chapter/lesson/{chapter_item_id}/destroy', [CourseContentController::class, 'chapterLessonDestroy'])->name('course-chapter.lesson.destroy');
    Route::post('course-chapter/lesson/sorting/{chapter_id}', [CourseContentController::class, 'sortLessons'])->name('course-chapter.lesson.sorting');

    Route::get('course-chapter/quiz-question/create/{quiz_id}', [CourseContentController::class, 'createQuizQuestion'])->name('course-chapter.quiz-question.create');
    Route::get('course-chapter/quiz-question/import/{quiz_id}', [CourseContentController::class, 'importQuizQuestion'])->name('course-chapter.quiz-question.import-index');
    Route::post('course-chapter/quiz-question/create/{quiz_id}', [CourseContentController::class, 'storeQuizQuestion'])->name('course-chapter.quiz-question.store');
    Route::post('course-chapter/quiz-question/import/{quiz_id}', [CourseContentController::class, 'importFileQuestion'])->name('course-chapter.quiz-question.import-store');
    Route::get('course-chapter/quiz-question/edit/{question_id}', [CourseContentController::class, 'editQuizQuestion'])->name('course-chapter quiz-question.edit');
    Route::put('course-chapter/quiz-question/update/{question_id}', [CourseContentController::class, 'updateQuizQuestion'])->name('course-chapter.quiz-question.update');
    Route::delete('course-chapter/quiz-question/delete/{question_id}', [CourseContentController::class, 'destroyQuizQuestion'])->name('course-chapter.quiz-question.destroy');


    Route::put('course-chapter/rtl/update/{follow_up_action_id}', [CourseContentController::class, 'updateFollowUpAction'])->name('course-chapter.rtl.update');


    /** review controller */
    Route::resource('course-review', CourseReviewController::class);

    Route::group(['middleware' => 'role.admin:Super Admin|Admin BKPSDM'], function () {
        /** course delete request */
        Route::resource('course-delete-request', CourseDeleteRequestController::class);
    });
});
