<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\QnaController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Frontend\ArticleController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\CheckOutController;
use App\Http\Controllers\Frontend\FavoriteController;
use App\Http\Controllers\Frontend\HomePageController;
use App\Http\Controllers\Frontend\LearningController;
use App\Http\Controllers\Frontend\AboutPageController;
use App\Http\Controllers\Frontend\CoursePageController;
use App\Http\Controllers\Global\CloudStorageController;
use App\Http\Controllers\Frontend\NotificationController;
use App\Http\Controllers\Frontend\CourseContentController;
use App\Http\Controllers\Frontend\StudentReviewController;
use App\Http\Controllers\Frontend\FollowUpActionController;
use App\Http\Controllers\Frontend\BecomeInstructorController;
use App\Http\Controllers\Frontend\InstructorCourseController;
use App\Http\Controllers\Frontend\InstructorPayoutController;
use App\Http\Controllers\Frontend\StudentDashboardController;
use App\Http\Controllers\Frontend\StudentPengetahuanController;
use App\Http\Controllers\Frontend\TinymceImageUploadController;
use App\Http\Controllers\Frontend\InstructorDashboardController;
use App\Http\Controllers\Frontend\InstructorLessonQnaController;
use App\Http\Controllers\Frontend\StudentFollowUpActionController;
use App\Http\Controllers\Frontend\StudentProfileSettingController;
use App\Http\Controllers\Frontend\InstructorAnnouncementController;
use App\Http\Controllers\Frontend\InstructorLiveCredentialController;
use App\Http\Controllers\Frontend\InstructorProfileSettingController;
use App\Http\Controllers\Frontend\StudentPendidikanLanjutanController;

Route::group(['middleware' => 'maintenance.mode'], function () {

    /**
     * ============================================================================
     * Global Routes
     * ============================================================================
     */

    Route::get('set-language', [DashboardController::class, 'setLanguage'])->name('set-language');
    Route::get('set-currency', [HomePageController::class, 'setCurrency'])->name('set-currency');

    Route::get('/', [HomePageController::class, 'index'])->name('home');

    Route::get('countries', [HomePageController::class, 'countries'])->name('countries');
    Route::get('states/{country_id}', [HomePageController::class, 'states'])->name('states');
    Route::get('cities/{state_id}', [HomePageController::class, 'cities'])->name('cities');

    /** become a instructor */
    Route::get('become-instructor', [BecomeInstructorController::class, 'index'])->name('become-instructor')->middleware('auth');
    Route::post('become-instructor', [BecomeInstructorController::class, 'store'])->name('become-instructor.create')->middleware('auth');

    Route::get('courses', [CoursePageController::class, 'index'])->name('courses');
    Route::get('fetch-courses', [CoursePageController::class, 'fetchCourses'])->name('fetch-courses');
    Route::get('course/{slug}', [CoursePageController::class, 'show'])->name('course.show');

    /** cart routes */
    Route::get('cart', [CartController::class, 'index'])->name('cart');
    Route::post('add-to-cart/{id}', [CartController::class, 'addToCart'])->name('add-to-cart');
    Route::get('remove-cart-item/{rowId}', [CartController::class, 'removeCartItem'])->name('remove-cart-item');
    Route::post('apply-coupon', [CartController::class, 'applyCoupon'])->name('apply-coupon');
    Route::get('remove-coupon', [CartController::class, 'removeCoupon'])->name('remove-coupon');

    /** Blog Routes */
    Route::get('blog', [BlogController::class, 'index'])->name('blogs');
    Route::get('blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
    Route::post('blog/submit-comment', [BlogController::class, 'submitComment'])->name('blog.submit-comment');
    Route::get('all-instructors', [HomePageController::class, 'allInstructors'])->name('all-instructors');
    Route::get('instructor-details/{id}/{slug?}', [HomePageController::class, 'instructorDetails'])->name('instructor-details');
    Route::post('quick-connect/{id}', [HomePageController::class, 'quickConnect'])->name('quick-connect');

    /** Article Routes */
    Route::get('article', [ArticleController::class, 'index'])->name('article');
    Route::get('article/{slug}', [ArticleController::class, 'show'])->name('article.show');
    Route::post('article/submit-comment', [ArticleController::class, 'submitComment'])->name('article.submit-comment');

    /** About page routes */
    Route::get('about-us', [AboutPageController::class, 'index'])->name('about-us');
    /** Contact page routes */
    Route::get('contact', [ContactController::class, 'index'])->name('contact.index');
    Route::post('contact/send-mail', [ContactController::class, 'sendMail'])->name('contact.send-mail');

    /** Custom pages */
    Route::get('page/{slug}', [HomePageController::class, 'customPage'])->name('custom-page');

    /** other routes */
    Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['auth:admin'], 'as' => 'admin.'], function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });
    Route::group(['prefix' => 'frontend-filemanager', 'middleware' => ['web']], function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });

    Route::get('change-theme/{name}', [HomePageController::class, 'changeTheme'])->name('change-theme');

    Route::get('/notifications', [NotificationController::class, 'list'])->name('notification.list');
    Route::get('/notifications/counter', [NotificationController::class, 'getNotificationCount'])->name('notification.counter');
    Route::get('/notifications/read', [NotificationController::class, 'read'])->name('notification.read');

    Route::get('/send-fcm-notification', [NotificationController::class, 'sendNotification'])->name('firebase.send.notification');
    Route::get('/student/pengetahuan/view/{id}', [StudentPengetahuanController::class, 'view'])->name('student.pengetahuan.view.file');

    /**
     * ============================================================================
     * Student Dashboard Routes
     * ============================================================================
     */

    Route::group(['middleware' => ['auth', 'verified'], 'prefix' => 'student', 'as' => 'student.'], function () {
        Route::get('dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
        Route::get('notifications', [NotificationController::class, 'list'])->name('notifications');
        // Profile setting routes
        // Route::get('setting', [StudentProfileSettingController::class, 'index'])->name('setting.index');
        // Route::put('setting/profile', [StudentProfileSettingController::class, 'updateProfile'])->name('setting.profile.update');
        // Route::put('setting/bio', [StudentProfileSettingController::class, 'updateBio'])->name('setting.bio.update');
        // Route::put('setting/password', [StudentProfileSettingController::class, 'updatePassword'])->name('setting.password.update');
        // Route::get('setting/experience-modal', [StudentProfileSettingController::class, 'showExperienceModal'])->name('setting.experience-modal');
        // Route::get('setting/edit-experience-modal/{id}', [StudentProfileSettingController::class, 'editExperienceModal'])->name('setting.edit-experience-modal');

        // Route::post('setting/experience', [StudentProfileSettingController::class, 'storeExperience'])->name('setting.experience.store');
        // Route::put('setting/experience/{id}', [StudentProfileSettingController::class, 'updateExperience'])->name('setting.experience.update');
        // Route::delete('setting/experience/{id}', [StudentProfileSettingController::class, 'destroyExperience'])->name('setting.experience.destroy');

        // Route::get('setting/add-education-modal', [StudentProfileSettingController::class, 'addEducationModal'])->name('setting.add-education-modal');
        // Route::post('setting/education', [StudentProfileSettingController::class, 'storeEducation'])->name('setting.education.store');
        // Route::get('setting/edit-education-modal/{id}', [StudentProfileSettingController::class, 'editEducationModal'])->name('setting.edit-education-modal');
        // Route::put('setting/education/{id}', [StudentProfileSettingController::class, 'updateEducation'])->name('setting.education.update');
        // Route::delete('setting/education/{id}', [StudentProfileSettingController::class, 'destroyEducation'])->name('setting.education.destroy');

        // Route::put('setting/address', [StudentProfileSettingController::class, 'updateAddress'])->name('setting.address.update');
        // Route::put('setting/socials', [StudentProfileSettingController::class, 'updateSocials'])->name('setting.socials.update');

        /** Order Routes */
        // Route::get('orders', [StudentOrderController::class, 'index'])->name('orders.index');
        // Route::get('order-details/{id}', [StudentOrderController::class, 'show'])->name('order.show');
        // Route::get('order/invoice/{id}', [StudentOrderController::class, 'printInvoice'])->name('order.print-invoice');

        Route::group(['prefix' => 'pengetahuan', 'as' => 'pengetahuan.'], function () {
            Route::get('/', [StudentPengetahuanController::class, 'index'])->name('index');
            Route::get('show/{slug}', [StudentPengetahuanController::class, 'show'])->name('show');
            Route::get('create', [StudentPengetahuanController::class, 'create'])->name('create');
            Route::post('store', [StudentPengetahuanController::class, 'store'])->name('store');
            Route::get('edit/{slug}', [StudentPengetahuanController::class, 'edit'])->name('edit');
            Route::put('update/{slug}', [StudentPengetahuanController::class, 'update'])->name('update');
            Route::put('/{slug}/ajukan', [StudentPengetahuanController::class, 'ajukanPengetahuan'])->name('ajukan');
            Route::delete('destroy/{slug}', [StudentPengetahuanController::class, 'destroy'])->name('destroy');
            // Route::get('view/{id}', [StudentPengetahuanController::class, 'view'])->name('view.file');
        });


        Route::get('reviews', [StudentReviewController::class, 'index'])->name('reviews.index');
        Route::get('reviews/{id}', [StudentReviewController::class, 'show'])->name('reviews.show');
        Route::delete('reviews/{id}', [StudentReviewController::class, 'destroy'])->name('reviews.destroy');
        Route::get('enrolled-courses', [StudentDashboardController::class, 'enrolledCourses'])->name('enrolled-courses');
        Route::get('quiz-attempts', [StudentDashboardController::class, 'quizAttempts'])->name('quiz-attempts');

        Route::get('continuing-education', [StudentPendidikanLanjutanController::class, 'index'])->name('continuing-education');
        Route::get('continuing-education/{id}', [StudentPendidikanLanjutanController::class, 'continuingEducationDetail'])->name('continuing-education.show');
        Route::post('continuing-education/{id}/register', [StudentPendidikanLanjutanController::class, 'register'])->name('continuing-education.register');
        Route::put('continuing-education/{id}/ajukan-kembali', [StudentPendidikanLanjutanController::class, 'ajukanKembali'])->name('continuing-education.ajukanKembali');
        Route::post('continuing-education-attachment/{id}', [StudentPendidikanLanjutanController::class, 'uploadRequirementFile'])->name('continuing-education.attachment');
        Route::get('continuing-education-attachment/{id}', [StudentPendidikanLanjutanController::class, 'viewRequirementFile'])->name('continuing-education.attachment.view');
        Route::get('continuing-education-registration', [StudentPendidikanLanjutanController::class, 'registered'])->name('continuing-education.registration');
        Route::get('continuing-education-registration/{id}', [StudentPendidikanLanjutanController::class, 'registeredDetail'])->name('continuing-education.registration.show');
        Route::post('continuing-education-registration/{id}/report', [StudentPendidikanLanjutanController::class, 'vacancyReportSubmit'])->name('continuing-education.registration.report');
        Route::put('continuing-education-registration/{id}/report/{report_id}', [StudentPendidikanLanjutanController::class, 'vacancyReportUpdate'])->name('continuing-education.registration.report.update');
        Route::delete('continuing-education-registration/{id}/report/{report_id}', [StudentPendidikanLanjutanController::class, 'vacancyReportDelete'])->name('continuing-education.registration.report.delete');
        Route::get('continuing-education-registration/{id}/report/{report_id}', [StudentPendidikanLanjutanController::class, 'vacancyReportView'])->name('continuing-education.registration.report.view');
        Route::post('continuing-education-registration/{vacancy_attachment_id}/update-requirement-activation', [StudentPendidikanLanjutanController::class, 'uploadRequirementActivation'])->name('continuing-education.registration.update-requirement-activation');
        Route::delete('continuing-education-registration/{vacancy_attachment_id}/delete-requirement-activation/{user_activation_id}', [StudentPendidikanLanjutanController::class, 'deleteRequirementActivation'])->name('continuing-education.registration.delete-requirement-activation');
        Route::get('continuing-education-registration/{vacancy_attachment_id}/view-requirement-activation/{user_activation_id}', [StudentPendidikanLanjutanController::class, 'viewRequirementActivation'])->name('continuing-education.registration.view-requirement-activation');


        /** follow up action routes */

        Route::get('follow-up-action/files/{filename}', function ($filename) {

            $path = storage_path('app/private/rtl/' . $filename);

            if (!file_exists($path)) {
                abort(404);
            }

            return response()->file($path);
        })->name('follow-up-action.files');

        Route::resource('follow-up-action', StudentFollowUpActionController::class);
        // Route::get('follow-up-action/create', [StudentFollowUpActionController::class, 'create'])->name('follow-up-action.create');

        /** learning routes */
        Route::get('learning/{slug}', [LearningController::class, 'index'])->name('learning.index');
        Route::post('learning/get-file-info', [LearningController::class, 'getFileInfo'])->name('get-file-info');
        Route::get('learning/get-file-direct/{id}', [LearningController::class, 'getDirectFile'])->name('get-file-direct');
        Route::post('learning/make-lesson-complete', [LearningController::class, 'makeLessonComplete'])->name('make-lesson-complete');
        Route::get('learning/resource-download/{id}', [LearningController::class, 'downloadResource'])->name('download-resource');

        Route::get('learning/follow-up-action/{id}', [LearningController::class, 'rtlIndex'])->name('rtl.index');
        Route::post('learning/follow-up-action/{id}', [LearningController::class, 'rtlStore'])->name('rtl.store');

        Route::get('learning/quiz/{id}', [LearningController::class, 'quizIndex'])->name('quiz.index');
        Route::post('learning/quiz/{id}', [LearningController::class, 'quizStore'])->name('quiz.store');
        Route::get('learning/quiz-result/{id}/{result_id}', [LearningController::class, 'quizResult'])->name('quiz.result');
        Route::get('learning/{slug}/{lesson_id}', [LearningController::class, 'liveSession'])->name('learning.live');



        /** qna routes */
        Route::post('create-question', [QnaController::class, 'create'])->name('qna.create');
        Route::get('fetch-lesson-questions', [QnaController::class, 'fetchLessonQuestions'])->name('fetch-lesson-questions');
        Route::post('create-reply', [QnaController::class, 'createReply'])->name('create-reply');
        Route::get('fetch-replies', [QnaController::class, 'fetchReply'])->name('fetch-replies');

        Route::delete('delete-question/{id}', [QnaController::class, 'destroyQuestion'])->name('destroy-question');
        Route::delete('delete-reply/{id}', [QnaController::class, 'destroyReply'])->name('destroy-reply');

        /** course review Routes */
        Route::post('add-review', [LearningController::class, 'addReview'])->name('add-review');
        Route::get('fetch-reviews/{course_id}', [LearningController::class, 'fetchReviews'])->name('fetch-reviews');

        /** download certificate route */
        Route::get('download-certificate/{id}', [StudentDashboardController::class, 'downloadCertificate'])->name('download-certificate');
        Route::view('wishlist', 'frontend.wishlist.index')->name('wishlist');

        /** general service **/
        Route::get('courses/get-students', [LearningController::class, 'getStudents'])->name('courses.get-students');
        Route::get('courses/register-course/{slug}', [CoursePageController::class, 'registerCourse'])->name('courses.register-course');
    });

    /**
     * ============================================================================
     * Instructor Dashboard Routes
     * ============================================================================
     */

    Route::group(['middleware' => ['auth', 'verified', 'approved.instructor', 'role:instructor'], 'prefix' => 'instructor', 'as' => 'instructor.'], function () {
        Route::get('dashboard', [InstructorDashboardController::class, 'index'])->name('dashboard');
        Route::get('notifications', [NotificationController::class, 'list'])->name('notifications');
        // Profile setting routes
        // Route::get('setting', [InstructorProfileSettingController::class, 'index'])->name('setting.index');
        // Route::put('setting/profile', [InstructorProfileSettingController::class, 'updateProfile'])->name('setting.profile.update');
        // Route::put('setting/bio', [InstructorProfileSettingController::class, 'updateBio'])->name('setting.bio.update');
        // Route::put('setting/password', [InstructorProfileSettingController::class, 'updatePassword'])->name('setting.password.update');
        // Route::get('setting/experience-modal', [InstructorProfileSettingController::class, 'showExperienceModal'])->name('setting.experience-modal');
        // Route::get('setting/edit-experience-modal/{id}', [InstructorProfileSettingController::class, 'editExperienceModal'])->name('setting.edit-experience-modal');

        // Route::post('setting/experience', [InstructorProfileSettingController::class, 'storeExperience'])->name('setting.experience.store');
        // Route::put('setting/experience/{id}', [InstructorProfileSettingController::class, 'updateExperience'])->name('setting.experience.update');
        // Route::delete('setting/experience/{id}', [InstructorProfileSettingController::class, 'destroyExperience'])->name('setting.experience.destroy');

        // Route::get('setting/add-education-modal', [InstructorProfileSettingController::class, 'addEducationModal'])->name('setting.add-education-modal');
        // Route::post('setting/education', [InstructorProfileSettingController::class, 'storeEducation'])->name('setting.education.store');
        // Route::get('setting/edit-education-modal/{id}', [InstructorProfileSettingController::class, 'editEducationModal'])->name('setting.edit-education-modal');
        // Route::put('setting/education/{id}', [InstructorProfileSettingController::class, 'updateEducation'])->name('setting.education.update');
        // Route::delete('setting/education/{id}', [InstructorProfileSettingController::class, 'destroyEducation'])->name('setting.education.destroy');

        // Route::put('setting/payout', [InstructorProfileSettingController::class, 'updatePayout'])->name('setting.payout.update');

        // Route::put('setting/address', [InstructorProfileSettingController::class, 'updateAddress'])->name('setting.address.update');
        // Route::put('setting/socials', [InstructorProfileSettingController::class, 'updateSocials'])->name('setting.socials.update');
        Route::put('course-chapter/rtl/update/{follow_up_action_id}', [CourseContentController::class, 'updateFollowUpAction'])->name('course-chapter.rtl.update');

        /** Course Routes */
        Route::get('courses', [InstructorCourseController::class, 'index'])->name('courses.index');
        // Route::get('courses/create', [InstructorCourseController::class, 'create'])->name('courses.create');
        // Route::get('courses/create/{id}/step/{step?}', [InstructorCourseController::class, 'edit'])->name('courses.edit');
        // Route::get('courses/{id}/edit', [InstructorCourseController::class, 'editView'])->name('courses.edit-view');

        // Route statis terlebih dahulu
        Route::get('courses/get-filters/{category_id}', [InstructorCourseController::class, 'getFiltersByCategory'])->name('courses.get-filters');
        Route::get('courses/get-instructors', [InstructorCourseController::class, 'getInstructors'])->name('courses.get-instructors');
        Route::get('courses/response-rtl/{id}', [InstructorCourseController::class, 'getResponeRtl'])->name('courses.response-rtl');

        // Route yang lebih spesifik (2 parameter)
        Route::get('courses/{course_id}/detail-rtl/{rtl_id}', [InstructorCourseController::class, 'detailRtl'])->name('courses.detail-rtl');

        // Route yang memiliki 1 parameter, urutkan sesuai fungsi
        Route::get('courses/{id}/list-rtl', [InstructorCourseController::class, 'getRtl'])->name('courses.list-rtl');
        Route::get('courses/{id}/detail', [InstructorCourseController::class, 'detail'])->name('courses.detail');

        // Route file RTL (bisa di akhir karena tidak konflik dengan yang lain)
        Route::get('courses/rtl-file/{filename}', function ($filename) {
            $path = storage_path('app/private/rtl/' . $filename);

            if (!file_exists($path)) {
                return redirect('/frontend/img/no_file.svg'); // ini mengarah ke URL publik
            }

            return response()->file($path);
        })->name('courses.rtl-file');

        //feedbackResponseRtl
        Route::post('courses/feedback-response-rtl', [InstructorCourseController::class, 'feedbackResponseRtl'])->name('courses.feedback-response-rtl');



        // Route::post('courses/create', [InstructorCourseController::class, 'store'])->name('courses.store');
        // Route::post('courses/update', [InstructorCourseController::class, 'update'])->name('courses.update');

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
        Route::post('course-chapter/quiz-question/create/{quiz_id}', [CourseContentController::class, 'storeQuizQuestion'])->name('course-chapter.quiz-question.store');
        Route::get('course-chapter/quiz-question/edit/{question_id}', [CourseContentController::class, 'editQuizQuestion'])->name('course-chapter quiz-question.edit');
        Route::put('course-chapter/quiz-question/update/{question_id}', [CourseContentController::class, 'updateQuizQuestion'])->name('course-chapter.quiz-question.update');
        Route::delete('course-chapter/quiz-question/delete/{question_id}', [CourseContentController::class, 'destroyQuizQuestion'])->name('course-chapter.quiz-question.destroy');
        Route::get('course-delete-request/{course_id}', [InstructorCourseController::class, 'showDeleteRequest'])->name('course.delete-request.show');
        Route::post('course-delete-request', [InstructorCourseController::class, 'sendDeleteRequest'])->name('course.send-delete-request');

        /** payout routes */
        Route::get('payout', [InstructorPayoutController::class, 'index'])->name('payout.index');
        Route::get('payout/create', [InstructorPayoutController::class, 'create'])->name('payout.create');
        Route::post('payout/create', [InstructorPayoutController::class, 'store'])->name('payout.store');
        Route::delete('payout/delete/{id}', [InstructorPayoutController::class, 'destroy'])->name('payout.destroy');

        /** announcement routes */
        Route::resource('announcements', InstructorAnnouncementController::class);

        /** my sales routes */
        Route::get('my-sells', [InstructorDashboardController::class, 'mySells'])->name('my-sells.index');
        /** lessons qna routes */
        Route::get('lesson-question', [InstructorLessonQnaController::class, 'index'])->name('lesson-questions.index');
        Route::post('lesson-question/{id}', [InstructorLessonQnaController::class, 'createReply'])->name('lesson-question.reply');
        Route::delete('lesson-question/destroy/{id}', [InstructorLessonQnaController::class, 'destroyQuestion'])->name('lesson-question.destroy');
        Route::delete('lesson-question/reply/destroy/{id}', [InstructorLessonQnaController::class, 'destroyReply'])->name('lesson-reply.destroy');
        Route::put('lesson-question/seen-update/{id}', [InstructorLessonQnaController::class, 'markAsReadUnread'])->name('lesson-question.seen-update');

        Route::post('cloud/store', [CloudStorageController::class, 'store'])->name('cloud.store');
    });
    /** wishlist routes */
    Route::group(['middleware' => ['auth', 'verified']], function () {
        Route::controller(FavoriteController::class)->group(function () {
            Route::get('wishlist/{course:slug}', 'update')->name('wishlist.update');
            Route::delete('wishlist/{course:slug}', 'destroy')->name('wishlist.remove');
        });
        /** secure-video route */
        Route::get('secure-video/{hash}', App\Http\Controllers\SecureLinkPreviewController::class)->name('secure.video')->middleware('signed');
    });

    Route::group(['middleware' => ['auth', 'verified']], function () {
        Route::get('checkout', [CheckOutController::class, 'index'])->name('checkout.index');
        Route::post('tinymce-upload-image', [TinymceImageUploadController::class, 'upload']);
        Route::delete('tinymce-delete-image', [TinymceImageUploadController::class, 'destroy']);
    });
});

//maintenance mode route
Route::get('/maintenance-mode', function () {
    $setting = Illuminate\Support\Facades\Cache::get('setting', null);
    if (!$setting?->maintenance_mode) {
        return redirect()->route('home');
    }

    return view('global.maintenance');
})->name('maintenance.mode');

require __DIR__ . '/auth.php';

require __DIR__ . '/admin.php';
