<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\Course;
use Illuminate\Support\Str;
use App\Models\QuizQuestion;
use App\Models\CourseChapter;
use Illuminate\Database\Seeder;
use App\Models\CourseChapterItem;
use App\Models\QuizQuestionAnswer;
use App\Models\CourseChapterLesson;
use App\Models\CourseSelectedLevel;
use App\Models\FollowUpAction;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Modules\Order\app\Models\Enrollment;

class CourseSingleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create course
        $course = new Course();
        $course->instructor_id = 2;
        $course->instansi_id = 1;
        $course->certificate_id = 1;
        $course->category_id = 2;
        $course->type = "course";
        $course->title = "Pelatihan Uji Coba";
        $course->slug = Str::slug("Pelatihan Uji Coba");
        $course->seo_description = "Pelatihan Uji Coba";
        $course->duration = "3000";
        $course->timezone = NULL;
        $course->thumbnail = $this->getRandomFilename();
        $course->demo_video_storage = "youtube";
        $course->demo_video_source = "https://www.youtube.com/watch?v=MHhIzIgFgJo";
        $course->description = "<p>Description Pelatihan Uji Coba</p>";
        $course->background = "<p>Background Pelatihan Uji Coba</p>";
        $course->output = "<p>Output Pelatihan Uji Coba</p>";
        $course->outcome = "<p>Outcome Pelatihan Uji Coba</p>";
        $course->capacity = NULL;
        $course->start_date = "2025-01-01 00:00:00";
        $course->end_date = "2025-12-31 00:00:00";
        $course->jp = rand(1, 72);
        $course->discount = null;
        $course->certificate = 1;
        $course->downloadable = 0;
        $course->partner_instructor = 0;
        $course->qna = 1;
        $course->message_for_reviewer = NULL;
        $course->status = "active";
        $course->is_approved = "approved";
        $course->access = "public";
        $course->save();

        // create course level
        CourseSelectedLevel::create([
            'course_id' => $course->id,
            'level_id' => rand(1, 3)
        ]);

        Enrollment::create([
            'user_id' => 1,
            'course_id' => $course->id,
            'has_access' => 1,
        ]);

        $courseChapter = new CourseChapter();
        $courseChapter->title = "Pelatihan Pemrograman";
        $courseChapter->instructor_id = 2;
        $courseChapter->course_id = $course->id;
        $courseChapter->order = 1;
        $courseChapter->status = "active";
        $courseChapter->save();

        $course_chapter_items = array(
            array(
                "instructor_id" => 1,
                "chapter_id" => 1,
                "type" => "lesson",
                "order" => 1,
                "created_at" => "2024-06-04 05:16:11",
                "updated_at" => "2024-06-04 05:16:11",
            ),
            array(
                "instructor_id" => 1,
                "chapter_id" => 1,
                "type" => "lesson",
                "order" => 2,
                "created_at" => "2024-06-04 05:16:11",
                "updated_at" => "2024-06-04 05:16:11",
            ),
            array(
                "instructor_id" => 1,
                "chapter_id" => 1,
                "type" => "document",
                "order" => 3,
                "created_at" => "2024-06-04 05:16:11",
                "updated_at" => "2024-06-04 05:16:11",
            ),
            array(
                "instructor_id" => 1,
                "chapter_id" => 1,
                "type" => "quiz",
                "order" => 4,
                "created_at" => "2024-06-04 05:30:06",
                "updated_at" => "2024-06-04 07:02:07",
            ),
            array(
                "instructor_id" => 1,
                "chapter_id" => 1,
                "type" => "rtl",
                "order" => 5,
                "created_at" => "2024-06-04 05:30:06",
                "updated_at" => "2024-06-04 07:02:07",
            ),
        );

        foreach ($course_chapter_items as $index => $chapterItem) {
            $courseChapterItem = new CourseChapterItem();
            $courseChapterItem->instructor_id = 2;
            $courseChapterItem->chapter_id = $courseChapter->id;
            $courseChapterItem->type = $chapterItem['type'];
            $courseChapterItem->order = $chapterItem['order'];
            $courseChapterItem->save();

            if ($chapterItem['type'] == "lesson" && $chapterItem['order'] == 1) {
                $courseLesson = new CourseChapterLesson();
                $courseLesson->title = "Coding Dasar Youtube";
                $courseLesson->slug = Str::slug("Coding Dasar Youtube");
                $courseLesson->description = "Coding Dasar Youtube";
                $courseLesson->instructor_id = 2;
                $courseLesson->course_id = $course->id;
                $courseLesson->chapter_id = $courseChapter->id;
                $courseLesson->chapter_item_id = $courseChapterItem->id;
                $courseLesson->file_path = "https://www.youtube.com/watch?v=7cMOjf4C9KE";
                $courseLesson->storage = "youtube";
                $courseLesson->volume = Null;
                $courseLesson->duration = "11";
                $courseLesson->file_type = "video";
                $courseLesson->downloadable = 1;
                $courseLesson->order = NULL;
                $courseLesson->is_free = 1;
                $courseLesson->status = "active";
                $courseLesson->save();
            }

            if ($chapterItem['type'] == "lesson" && $chapterItem['order'] == 2) {
                $courseLesson = new CourseChapterLesson();
                $courseLesson->title = "Coding Dasar GDrive";
                $courseLesson->slug = Str::slug("Coding Dasar GDrive");
                $courseLesson->description = "Coding Dasar GDrive";
                $courseLesson->instructor_id = 2;
                $courseLesson->course_id = $course->id;
                $courseLesson->chapter_id = $courseChapter->id;
                $courseLesson->chapter_item_id = $courseChapterItem->id;
                $courseLesson->file_path = "https://drive.google.com/file/d/1CmtT6i3-QZtz7Oq_lcJHBcQkCVMdb0cV/view";
                $courseLesson->storage = "google_drive";
                $courseLesson->volume = NULL;
                $courseLesson->duration = "11";
                $courseLesson->file_type = "video";
                $courseLesson->downloadable = 1;
                $courseLesson->order = NULL;
                $courseLesson->is_free = 1;
                $courseLesson->status = "active";
                $courseLesson->save();
            }

            if ($chapterItem['type'] == "document") {
                $courseLesson = new CourseChapterLesson();
                $courseLesson->title = "Coding Materi PDF";
                $courseLesson->slug = Str::slug("Coding Materi PDF");
                $courseLesson->description = "Coding Materi PDF";
                $courseLesson->instructor_id = 2;
                $courseLesson->course_id = $course->id;
                $courseLesson->chapter_id = $courseChapter->id;
                $courseLesson->chapter_item_id = $courseChapterItem->id;
                $courseLesson->file_path = "https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf";
                $courseLesson->storage = "upload";
                $courseLesson->volume = NULL;
                $courseLesson->duration = NULL;
                $courseLesson->file_type = "pdf";
                $courseLesson->downloadable = 1;
                $courseLesson->order = NULL;
                $courseLesson->is_free = 1;
                $courseLesson->status = "active";
                $courseLesson->save();
            }

            if ($chapterItem['type'] == "quiz") {
                $courseQuiz = new Quiz();
                $courseQuiz->instructor_id = $course->instructor_id;
                $courseQuiz->chapter_item_id = $courseChapterItem->id;
                $courseQuiz->instructor_id = $course->instructor_id;
                $courseQuiz->chapter_id = $courseChapter->id;
                $courseQuiz->course_id = $course->id;
                $courseQuiz->title = "Kuis #1";
                $courseQuiz->time = "10";
                $courseQuiz->attempt = "10";
                $courseQuiz->pass_mark = "50";
                $courseQuiz->total_mark = "100";
                $courseQuiz->status = "active";
                $courseQuiz->save();

                $courseQuizQuestion = new QuizQuestion();
                $courseQuizQuestion->quiz_id = $courseQuiz->id;
                $courseQuizQuestion->title = "Mana yang merupakan bahasa pemrograman?";
                $courseQuizQuestion->type = "multiple";
                $courseQuizQuestion->grade = 80;
                $courseQuizQuestion->save();

                $courseQuizQuestionAnswer = new QuizQuestionAnswer();
                $courseQuizQuestionAnswer->title = "Javanese";
                $courseQuizQuestionAnswer->question_id = $courseQuizQuestion->id;
                $courseQuizQuestionAnswer->correct = 0;
                $courseQuizQuestionAnswer->save();

                $courseQuizQuestionAnswer = new QuizQuestionAnswer();
                $courseQuizQuestionAnswer->title = "Javascript";
                $courseQuizQuestionAnswer->question_id = $courseQuizQuestion->id;
                $courseQuizQuestionAnswer->correct = 1;
                $courseQuizQuestionAnswer->save();
            }

            if ($chapterItem['type'] == "rtl") {
                $rtl = new FollowUpAction();
                $rtl->title = "Rencana Tindak Lanjut 1";
                $rtl->description = "Rencana Tindak Lanjut 1";
                $rtl->instructor_id = 2;
                $rtl->course_id = $course->id;
                $rtl->chapter_id = $courseChapter->id;
                $rtl->chapter_item_id = $courseChapterItem->id;
                $rtl->start_date = "2025-01-01 00:00:00";
                $rtl->due_date = "2025-12-31 00:00:00";
                $rtl->save();
            }
        }
    }

    public function getRandomFilename()
    {
        $files = File::files(public_path('/uploads/store/files/1001/my course images/'));  // Get all files from the path

        if (empty($files)) {
            return null; // Return null if no files found
        }

        // Randomly select a file index
        $randomIndex = shuffle($files);

        $fileInfo = pathinfo($files[$randomIndex]);  // Get info of selected file
        return "/uploads/store/files/1001/my course images/" . $fileInfo['filename'] . '.' . $fileInfo['extension']; // Build filename
    }
}
