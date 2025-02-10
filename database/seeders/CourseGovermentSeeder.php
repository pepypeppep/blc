<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseChapter;
use App\Models\CourseChapterItem;
use App\Models\CourseChapterLesson;
use App\Models\CourseSelectedLanguage;
use App\Models\CourseSelectedLevel;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizQuestionAnswer;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;


class CourseGovermentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // truncate tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('course_chapter_lessons')->truncate();
        DB::table('course_chapter_items')->truncate();
        DB::table('course_chapters')->truncate();
        DB::table('courses')->truncate();
        DB::table('course_selected_languages')->truncate();
        DB::table('course_selected_levels')->truncate();
        DB::table('quizzes')->truncate();
        DB::table('quiz_questions')->truncate();
        DB::table('quiz_question_answers')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        $coursesNames = array(
            "Administrasi Publik dan Kebijakan Pemerintah",
            "Manajemen Keuangan Daerah dan APBD",
            "Hukum Tata Negara dan Regulasi Pemerintahan",
            "Pelayanan Publik yang Efektif dan Inovatif",
            "Pengadaan Barang dan Jasa Pemerintah",
            "E-Government: Digitalisasi Administrasi Publik",
            "Manajemen Sumber Daya Manusia di Sektor Publik",
            "Pengelolaan Keuangan Negara dan Akuntabilitas",
            "Pembangunan Berkelanjutan dan Kebijakan Lingkungan",
            "Tata Kelola Pemerintahan yang Baik (Good Governance)",
            "Manajemen Risiko di Sektor Publik",
            "Pemanfaatan Big Data dalam Pengambilan Keputusan Pemerintah",
            "Transparansi dan Akuntabilitas dalam Administrasi Publik",
            "Strategi Komunikasi Pemerintah yang Efektif",
            "Pemilu dan Demokrasi di Indonesia",
            "Pembangunan Desa dan Pemberdayaan Masyarakat",
            "Cybersecurity untuk Institusi Pemerintah",
            "Penyusunan Rencana Strategis Nasional dan Daerah",
            "Smart City: Konsep dan Implementasi",
            "Transformasi Digital dalam Layanan Publik",
            "Pengelolaan Konflik Sosial dan Mediasi",
            "Pencegahan dan Pemberantasan Korupsi di Sektor Publik",
            "Kebijakan Kesehatan Publik dan Jaminan Sosial",
            "Manajemen Krisis dan Tanggap Darurat",
            "Perencanaan Infrastruktur dan Pembangunan Nasional",
            "Ekonomi Makro dan Kebijakan Fiskal Pemerintah",
            "Desentralisasi dan Otonomi Daerah",
            "Manajemen Sumber Daya Air dan Lingkungan Hidup",
            "Strategi Kebijakan Sosial dan Perlindungan Masyarakat",
            "Hubungan Internasional dan Diplomasi Indonesia"
        );

        $course_chapters = array(
            array(
                "title" => "Pengenalan Administrasi Publik dan Kebijakan Pemerintah",
                "instructor_id" => 1001,
                "course_id" => 1,
                "order" => 1,
                "status" => "active",
                "created_at" => "2024-06-04 04:35:50",
                "updated_at" => "2024-06-04 04:35:50",
            ),
            array(
                "title" => "Pengelolaan Keuangan Negara dan Akuntabilitas",
                "instructor_id" => 1001,
                "course_id" => 1,
                "order" => 3,
                "status" => "active",
                "created_at" => "2024-06-04 04:36:44",
                "updated_at" => "2024-06-04 04:37:56",
            ),
            array(
                "title" => "Pengelolaan Sumber Daya Manusia di Sektor Publik",
                "instructor_id" => 1001,
                "course_id" => 1,
                "order" => 4,
                "status" => "active",
                "created_at" => "2024-06-04 04:37:02",
                "updated_at" => "2024-06-04 04:37:56",
            ),
            array(
                "title" => "Pengelolaan Risiko di Sektor Publik",
                "instructor_id" => 1001,
                "course_id" => 1,
                "order" => 5,
                "status" => "active",
                "created_at" => "2024-06-04 04:38:14",
                "updated_at" => "2024-06-04 04:38:14",
            ),
            array(
                "title" => "Pengelolaan Konflik Sosial dan Mediasi",
                "instructor_id" => 1001,
                "course_id" => 1,
                "order" => 6,
                "status" => "active",
                "created_at" => "2024-06-04 04:38:25",
                "updated_at" => "2024-06-04 04:38:25",
            ),
            array(
                "title" => "Pencegahan dan Pemberantasan Korupsi di Sektor Publik",
                "instructor_id" => 1001,
                "course_id" => 1,
                "order" => 7,
                "status" => "active",
                "created_at" => "2024-06-04 04:38:45",
                "updated_at" => "2024-06-04 04:38:45",
            ),
            array(
                "title" => "Pengelolaan Kebijakan Kesehatan Publik dan Jaminan Sosial",
                "instructor_id" => 1001,
                "course_id" => 1,
                "order" => 8,
                "status" => "active",
                "created_at" => "2024-06-04 04:39:02",
                "updated_at" => "2024-06-04 04:39:02",
            ),
        );

        $course_chapter_items = array(
            array(
                "instructor_id" => 1001,
                "chapter_id" => 1,
                "type" => "lesson",
                "order" => 1,
                "created_at" => "2024-06-04 05:05:26",
                "updated_at" => "2024-06-04 05:05:26",
            ),
            array(
                "instructor_id" => 1001,
                "chapter_id" => 1,
                "type" => "lesson",
                "order" => 2,
                "created_at" => "2024-06-04 05:10:16",
                "updated_at" => "2024-06-04 05:10:16",
            ),
            array(
                "instructor_id" => 1001,
                "chapter_id" => 1,
                "type" => "lesson",
                "order" => 3,
                "created_at" => "2024-06-04 05:16:11",
                "updated_at" => "2024-06-04 05:16:11",
            ),
            array(
                "instructor_id" => 1001,
                "chapter_id" => 1,
                "type" => "quiz",
                "order" => 5,
                "created_at" => "2024-06-04 05:30:06",
                "updated_at" => "2024-06-04 07:02:07",
            ),
            array(
                "instructor_id" => 1001,
                "chapter_id" => 1,
                "type" => "lesson",
                "order" => 4,
                "created_at" => "2024-06-04 07:01:55",
                "updated_at" => "2024-06-04 07:02:07",
            ),
        );

        $course_chapter_lessons = array(
            array(
                "id" => 1,
                "title" => "Administrasi Publik dan Kebijakan Pemerintah",
                "slug" => NULL,
                "description" => "Materi ini membahas administrasi publik serta kebijakan pemerintah dalam pengelolaan sumber daya dan pengambilan keputusan.",
                "instructor_id" => 1001,
                "course_id" => 1,
                "chapter_id" => 1,
                "chapter_item_id" => 1,
                "file_path" => "https://www.youtube.com/watch?v=7cMOjf4C9KE",
                "storage" => "youtube",
                "duration" => "11",
                "file_type" => "video",
                "downloadable" => 1,
                "is_free" => 1,
                "status" => "active",
            ),
            array(
                "id" => 2,
                "title" => "Manajemen Keuangan Daerah dan APBD",
                "slug" => NULL,
                "description" => "Materi ini membahas pengelolaan keuangan daerah, penyusunan APBD, serta mekanisme pengawasan dan transparansi keuangan pemerintah daerah.",
                "instructor_id" => 1001,
                "course_id" => 2,
                "chapter_id" => 1,
                "chapter_item_id" => 2,
                "file_path" => "https://vimeo.com/273651219",
                "storage" => "vimeo",
                "duration" => "1",
                "file_type" => "video",
                "downloadable" => 1,
                "is_free" => 1,
                "status" => "active",
            ),
            array(
                "id" => 3,
                "title" => "Hukum Tata Negara dan Regulasi Pemerintahan",
                "slug" => NULL,
                "description" => "Materi ini membahas hukum tata negara, regulasi pemerintahan, serta aspek hukum yang mendukung tata kelola pemerintahan yang baik.",
                "instructor_id" => 1001,
                "course_id" => 3,
                "chapter_id" => 1,
                "chapter_item_id" => 3,
                "file_path" => "https://drive.google.com/file/d/1CmtT6i3-QZtz7Oq_lcJHBcQkCVMdb0cV/view",
                "storage" => "google_drive",
                "duration" => "2",
                "file_type" => "video",
                "downloadable" => 1,
                "is_free" => 0,
                "status" => "active",
            ),
            array(
                "id" => 4,
                "title" => "E-Government: Digitalisasi Administrasi Publik",
                "slug" => NULL,
                "description" => "Materi ini membahas konsep e-Government, digitalisasi administrasi publik, serta implementasi teknologi dalam pelayanan publik.",
                "instructor_id" => 1001,
                "course_id" => 6,
                "chapter_id" => 2,
                "chapter_item_id" => 5,
                "file_path" => "https://www.youtube.com/watch?v=dELcl7aB5k8",
                "storage" => "youtube",
                "duration" => "1",
                "file_type" => "video",
                "downloadable" => 1,
                "is_free" => 1,
                "status" => "active",
            ),
            array(
                "id" => 5,
                "title" => "Pencegahan dan Pemberantasan Korupsi di Sektor Publik",
                "slug" => NULL,
                "description" => "Materi ini membahas strategi pencegahan dan pemberantasan korupsi dalam administrasi publik serta mekanisme penegakan hukum.",
                "instructor_id" => 1001,
                "course_id" => 22,
                "chapter_id" => 3,
                "chapter_item_id" => 10,
                "file_path" => "https://www.youtube.com/watch?v=pVTGfHyLlDU",
                "storage" => "youtube",
                "duration" => "1",
                "file_type" => "video",
                "downloadable" => 1,
                "is_free" => 1,
                "status" => "active",
            ),
            array(
                "id" => 6,
                "title" => "Transparansi dan Akuntabilitas dalam Administrasi Publik",
                "slug" => NULL,
                "description" => "Materi ini membahas prinsip transparansi dan akuntabilitas dalam pengelolaan sektor publik untuk mendukung tata kelola yang baik.",
                "instructor_id" => 1001,
                "course_id" => 13,
                "chapter_id" => 1,
                "chapter_item_id" => 11,
                "file_path" => "/uploads/store/files/1001/certificate-22.pdf",
                "storage" => "upload",
                "duration" => "1",
                "file_type" => "pdf",
                "downloadable" => 1,
                "is_free" => 1,
                "status" => "active",
            ),
        );


        $quizzes = array(
            array(
                "id" => 1,
                "chapter_item_id" => 4,
                "instructor_id" => 1001,
                "chapter_id" => 1,
                "course_id" => 1,
                "title" => "QUIZ: This is a demo quiz test",
                "time" => "10",
                "attempt" => "10",
                "pass_mark" => "50",
                "total_mark" => "100",
                "status" => "active",
                "created_at" => "2024-06-04 05:30:06",
                "updated_at" => "2024-06-04 05:30:32",
            ),
        );

        $quiz_questions = array(
            array(
                "id" => 1,
                "quiz_id" => 1,
                "title" => "Siapa Presiden Indonesia saat ini?",
                "type" => "multiple",
                "grade" => 10,
                "created_at" => "2024-06-04 05:31:31",
                "updated_at" => "2024-06-04 05:31:31",
            ),
            array(
                "id" => 2,
                "quiz_id" => 1,
                "title" => "Apa nama ibu kota Indonesia?",
                "type" => "multiple",
                "grade" => 10,
                "created_at" => "2024-06-04 05:31:31",
                "updated_at" => "2024-06-04 05:31:31",
            ),
            array(
                "id" => 3,
                "quiz_id" => 1,
                "title" => "Apa fungsi DPR dalam pemerintahan Indonesia?",
                "type" => "multiple",
                "grade" => 10,
                "created_at" => "2024-06-04 05:31:31",
                "updated_at" => "2024-06-04 05:31:31",
            ),
            array(
                "id" => 4,
                "quiz_id" => 1,
                "title" => "Siapa yang berhak mengangkat dan memberhentikan menteri di Indonesia?",
                "type" => "multiple",
                "grade" => 10,
                "created_at" => "2024-06-04 05:31:31",
                "updated_at" => "2024-06-04 05:31:31",
            ),
            array(
                "id" => 5,
                "quiz_id" => 1,
                "title" => "Apa itu UUD 1945?",
                "type" => "multiple",
                "grade" => 10,
                "created_at" => "2024-06-04 05:31:31",
                "updated_at" => "2024-06-04 05:31:31",
            ),
            array(
                "id" => 6,
                "quiz_id" => 1,
                "title" => "Apa yang dimaksud dengan otonomi daerah?",
                "type" => "multiple",
                "grade" => 10,
                "created_at" => "2024-06-04 05:31:31",
                "updated_at" => "2024-06-04 05:31:31",
            ),
            array(
                "id" => 7,
                "quiz_id" => 1,
                "title" => "Siapa yang memiliki kewenangan untuk membuat undang-undang di Indonesia?",
                "type" => "multiple",
                "grade" => 10,
                "created_at" => "2024-06-04 05:31:31",
                "updated_at" => "2024-06-04 05:31:31",
            ),
            array(
                "id" => 8,
                "quiz_id" => 1,
                "title" => "Apa tugas utama Mahkamah Agung di Indonesia?",
                "type" => "multiple",
                "grade" => 10,
                "created_at" => "2024-06-04 05:31:31",
                "updated_at" => "2024-06-04 05:31:31",
            ),
            array(
                "id" => 9,
                "quiz_id" => 1,
                "title" => "Siapa yang berhak mengajukan RUU di Indonesia?",
                "type" => "multiple",
                "grade" => 10,
                "created_at" => "2024-06-04 05:31:31",
                "updated_at" => "2024-06-04 05:31:31",
            ),
            array(
                "id" => 10,
                "quiz_id" => 1,
                "title" => "Apa yang dimaksud dengan desentralisasi pemerintahan?",
                "type" => "multiple",
                "grade" => 10,
                "created_at" => "2024-06-04 05:31:31",
                "updated_at" => "2024-06-04 05:31:31",
            ),
        );



        $quiz_question_answers = array(
            // Jawaban untuk pertanyaan 1
            array(
                "id" => 1,
                "question_id" => 1,
                "title" => "Joko Widodo",
                "correct" => 1,
                "created_at" => "2024-06-04 05:31:31",
                "updated_at" => "2024-06-04 05:31:31",
            ),
            array(
                "id" => 2,
                "question_id" => 1,
                "title" => "Prabowo Subianto",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 3,
                "question_id" => 1,
                "title" => "Megawati Soekarnoputri",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 4,
                "question_id" => 1,
                "title" => "Susilo Bambang Yudhoyono",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 5,
                "question_id" => 1,
                "title" => "Bacharuddin Jusuf Habibie",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            // Jawaban untuk pertanyaan 2
            array(
                "id" => 6,
                "question_id" => 2,
                "title" => "Jakarta",
                "correct" => 1,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 7,
                "question_id" => 2,
                "title" => "Surabaya",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 8,
                "question_id" => 2,
                "title" => "Bandung",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 9,
                "question_id" => 2,
                "title" => "Medan",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 10,
                "question_id" => 2,
                "title" => "Yogyakarta",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            // Jawaban untuk pertanyaan 3
            array(
                "id" => 11,
                "question_id" => 3,
                "title" => "Membuat undang-undang",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 12,
                "question_id" => 3,
                "title" => "Menguji konstitusionalitas undang-undang",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 13,
                "question_id" => 3,
                "title" => "Menetapkan anggaran negara",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 14,
                "question_id" => 3,
                "title" => "Mewakili rakyat dalam pemerintahan",
                "correct" => 1,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 15,
                "question_id" => 3,
                "title" => "Melaksanakan keputusan Mahkamah Konstitusi",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            // Jawaban untuk pertanyaan 4
            array(
                "id" => 16,
                "question_id" => 4,
                "title" => "Presiden",
                "correct" => 1,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 17,
                "question_id" => 4,
                "title" => "DPR",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 18,
                "question_id" => 4,
                "title" => "MPR",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 19,
                "question_id" => 4,
                "title" => "Dewan Perwakilan Daerah",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 20,
                "question_id" => 4,
                "title" => "Komisi Yudisial",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            // Jawaban untuk pertanyaan 5
            array(
                "id" => 21,
                "question_id" => 5,
                "title" => "Undang-Undang Dasar Negara Republik Indonesia Tahun 1945",
                "correct" => 1,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 22,
                "question_id" => 5,
                "title" => "Deklarasi Kemerdekaan Indonesia",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 23,
                "question_id" => 5,
                "title" => "Piagam Jakarta",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 24,
                "question_id" => 5,
                "title" => "Baliho Kemerdekaan Indonesia",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 25,
                "question_id" => 5,
                "title" => "Undang-Undang Pertanahan Nasional",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            // Jawaban untuk pertanyaan 6
            array(
                "id" => 26,
                "question_id" => 6,
                "title" => "Pemberian hak otonomi kepada daerah untuk mengatur dan mengurus urusan pemerintahan sendiri",
                "correct" => 1,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 27,
                "question_id" => 6,
                "title" => "Pemerintah pusat yang memiliki wewenang penuh dalam segala hal",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 28,
                "question_id" => 6,
                "title" => "Pemisahan kekuasaan antara pusat dan daerah tanpa batasan",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 29,
                "question_id" => 6,
                "title" => "Pemberian hak veto kepada daerah",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 30,
                "question_id" => 6,
                "title" => "Penyatuan kebijakan antara pusat dan daerah",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            // Jawaban untuk pertanyaan 7
            array(
                "id" => 31,
                "question_id" => 7,
                "title" => "DPR dan Presiden",
                "correct" => 1,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 32,
                "question_id" => 7,
                "title" => "Mahkamah Agung",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 33,
                "question_id" => 7,
                "title" => "MPR",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 34,
                "question_id" => 7,
                "title" => "Komisi Yudisial",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 35,
                "question_id" => 7,
                "title" => "Dewan Perwakilan Daerah (DPD)",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            // Jawaban untuk pertanyaan 8
            array(
                "id" => 36,
                "question_id" => 8,
                "title" => "Menegakkan hukum dan keadilan di Indonesia",
                "correct" => 1,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 37,
                "question_id" => 8,
                "title" => "Menerima laporan pengaduan masyarakat",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 38,
                "question_id" => 8,
                "title" => "Melakukan pemilihan umum presiden",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 39,
                "question_id" => 8,
                "title" => "Menjadi penghubung antara presiden dan masyarakat",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 40,
                "question_id" => 8,
                "title" => "Mengawasi jalannya pemerintahan di daerah",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            // Jawaban untuk pertanyaan 9
            array(
                "id" => 41,
                "question_id" => 9,
                "title" => "Presiden dan DPR",
                "correct" => 1,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 42,
                "question_id" => 9,
                "title" => "MPR dan Mahkamah Konstitusi",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 43,
                "question_id" => 9,
                "title" => "DPR dan Mahkamah Agung",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 44,
                "question_id" => 9,
                "title" => "Komisi Yudisial dan DPD",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 45,
                "question_id" => 9,
                "title" => "Mahkamah Konstitusi dan Presiden",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            // Jawaban untuk pertanyaan 10
            array(
                "id" => 46,
                "question_id" => 10,
                "title" => "Penyerahan sebagian kewenangan pemerintahan pusat kepada daerah",
                "correct" => 1,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 47,
                "question_id" => 10,
                "title" => "Pemberian wewenang penuh kepada daerah tanpa kendali pusat",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 48,
                "question_id" => 10,
                "title" => "Pemberian dana pusat ke daerah tanpa batasan",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 49,
                "question_id" => 10,
                "title" => "Pemisahan total pemerintahan antara pusat dan daerah",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
            array(
                "id" => 50,
                "question_id" => 10,
                "title" => "Pemerintahan pusat tetap mengendalikan seluruh urusan pemerintahan daerah",
                "correct" => 0,
                "created_at" => "2024-06-04 05:31:32",
                "updated_at" => "2024-06-04 05:31:32",
            ),
        );

        $instructorList = array(1001, 1002, 1003, 1004, 1005, 1006);
        foreach ($coursesNames as $courseName) {
            // create course
            $course = new Course();
            $course->instructor_id = $instructorList[array_rand($instructorList, 1)];
            $course->category_id = rand(1, 5);
            $course->type = "course";
            $course->title = $courseName;
            $course->slug = Str::slug($courseName);
            $course->seo_description = $courseName;
            $course->duration = "3000";
            $course->timezone = NULL;
            $course->thumbnail = $this->getRandomFilename();
            $course->demo_video_storage = "youtube";
            $course->demo_video_source = "https://www.youtube.com/watch?v=MHhIzIgFgJo";
            $course->description = "Kursus untuk ASN Pemerintah Kabupaten Bantul";
            $course->capacity = NULL;
            $course->price = rand(50, 200);
            $course->discount = null;
            $course->certificate = 1;
            $course->downloadable = 0;
            $course->partner_instructor = 0;
            $course->qna = 1;
            $course->message_for_reviewer = NULL;
            $course->status = "active";
            $course->is_approved = "approved";
            $course->save();
            // create course level
            CourseSelectedLevel::create([
                'course_id' => $course->id,
                'level_id' => rand(1, 3)
            ]);
            // create course language
            CourseSelectedLanguage::create([
                'course_id' => $course->id,
                'language_id' => rand(1, 5)
            ]);

            foreach ($course_chapters as $chapterIndex => $chapter) {
                $courseChapter = new CourseChapter();
                $courseChapter->title = $chapter['title'];
                $courseChapter->instructor_id = $course->instructor_id;
                $courseChapter->course_id = $course->id;
                $courseChapter->order = $chapter['order'];
                $courseChapter->status = "active";
                $courseChapter->save();

                foreach ($course_chapter_items as $index => $chapterItem) {
                    $courseChapterItem = new CourseChapterItem();
                    $courseChapterItem->instructor_id = $course->instructor_id;
                    $courseChapterItem->chapter_id = $courseChapter->id;
                    $courseChapterItem->type = $chapterItem['type'];
                    $courseChapterItem->order = $chapterItem['order'];
                    $courseChapterItem->save();


                    if ($chapterIndex == 0) {

                        if ($chapterItem['type'] == "lesson") {

                            $courseLesson = new CourseChapterLesson();
                            $courseLesson->title = $course_chapter_lessons[$index]['title'];
                            $courseLesson->slug = $course_chapter_lessons[$index]['slug'];
                            $courseLesson->description = $course_chapter_lessons[$index]['description'];
                            $courseLesson->instructor_id = $course->instructor_id;
                            $courseLesson->course_id = $course->id;
                            $courseLesson->chapter_id = $courseChapter->id;
                            $courseLesson->chapter_item_id = $courseChapterItem->id;
                            $courseLesson->file_path = $course_chapter_lessons[$index]['file_path'];
                            $courseLesson->storage = $course_chapter_lessons[$index]['storage'];
                            $courseLesson->volume = Null;
                            $courseLesson->duration = $course_chapter_lessons[$index]['duration'];
                            $courseLesson->file_type = $course_chapter_lessons[$index]['file_type'];
                            $courseLesson->downloadable = $course_chapter_lessons[$index]['downloadable'];
                            // $courseLesson->order = $course_chapter_lessons[$index]['order'];
                            $courseLesson->order = $chapterIndex + 1;
                            $courseLesson->is_free = $course_chapter_lessons[$index]['is_free'];
                            $courseLesson->status = $course_chapter_lessons[$index]['status'];

                            $courseLesson->save();
                        }

                        if ($chapterItem['type'] == "quiz") {

                            foreach ($quizzes as $quiz) {
                                $courseQuiz = new Quiz();
                                $courseQuiz->instructor_id = $course->instructor_id;
                                $courseQuiz->chapter_item_id = $courseChapterItem->id;
                                $courseQuiz->instructor_id = $course->instructor_id;
                                $courseQuiz->chapter_id = $courseChapter->id;
                                $courseQuiz->course_id = $course->id;
                                $courseQuiz->title = $quiz['title'];
                                $courseQuiz->time = $quiz['time'];
                                $courseQuiz->attempt = $quiz['attempt'];
                                $courseQuiz->pass_mark = $quiz['pass_mark'];
                                $courseQuiz->total_mark = $quiz['total_mark'];
                                $courseQuiz->status = $quiz['status'];
                                $courseQuiz->save();

                                foreach ($quiz_questions as $question) {
                                    $courseQuizQuestion = new QuizQuestion();
                                    $courseQuizQuestion->quiz_id = $courseQuiz->id;
                                    $courseQuizQuestion->title = $question['title'];
                                    $courseQuizQuestion->type = $question['type'];
                                    $courseQuizQuestion->grade = $question['grade'];
                                    $courseQuizQuestion->save();
                                    foreach ($quiz_question_answers as $answer) {
                                        $courseQuizQuestionAnswer = new QuizQuestionAnswer();
                                        $courseQuizQuestionAnswer->title = $answer['title'];
                                        $courseQuizQuestionAnswer->question_id = $courseQuizQuestion->id;
                                        $courseQuizQuestionAnswer->correct = $answer['correct'];
                                        $courseQuizQuestionAnswer->save();
                                    }
                                }
                            }
                        }
                    } else {
                        if ($chapterItem['type'] == "lesson") {

                            $courseLesson = new CourseChapterLesson();
                            $courseLesson->title = fake()->sentence();
                            $courseLesson->slug = fake()->slug();
                            $courseLesson->description = $course_chapter_lessons[$index]['description'];
                            $courseLesson->instructor_id = $course->instructor_id;
                            $courseLesson->course_id = $course->id;
                            $courseLesson->chapter_id = $courseChapter->id;
                            $courseLesson->chapter_item_id = $courseChapterItem->id;
                            $courseLesson->file_path = $course_chapter_lessons[$index]['file_path'];
                            $courseLesson->storage = $course_chapter_lessons[$index]['storage'];
                            $courseLesson->volume = Null;
                            $courseLesson->duration = $course_chapter_lessons[$index]['duration'];
                            $courseLesson->file_type = $course_chapter_lessons[$index]['file_type'];
                            $courseLesson->downloadable = $course_chapter_lessons[$index]['downloadable'];
                            // $courseLesson->order = $course_chapter_lessons[$index]['order'];
                            $courseLesson->order = $chapterIndex + 1;
                            $courseLesson->is_free = $course_chapter_lessons[$index]['is_free'];
                            $courseLesson->status = $course_chapter_lessons[$index]['status'];

                            $courseLesson->save();
                        }

                        if ($chapterItem['type'] == "quiz") {

                            foreach ($quizzes as $quiz) {
                                $courseQuiz = new Quiz();
                                $courseQuiz->instructor_id = $course->instructor_id;
                                $courseQuiz->chapter_item_id = $courseChapterItem->id;
                                $courseQuiz->instructor_id = $course->instructor_id;
                                $courseQuiz->chapter_id = $courseChapter->id;
                                $courseQuiz->course_id = $course->id;
                                $courseQuiz->title = fake()->sentence(5);
                                $courseQuiz->time = $quiz['time'];
                                $courseQuiz->attempt = $quiz['attempt'];
                                $courseQuiz->pass_mark = $quiz['pass_mark'];
                                $courseQuiz->total_mark = $quiz['total_mark'];
                                $courseQuiz->status = $quiz['status'];
                                $courseQuiz->save();

                                foreach ($quiz_questions as $question) {
                                    $courseQuizQuestion = new QuizQuestion();
                                    $courseQuizQuestion->quiz_id = $courseQuiz->id;
                                    $courseQuizQuestion->title = $question['title'];
                                    $courseQuizQuestion->type = $question['type'];
                                    $courseQuizQuestion->grade = $question['grade'];
                                    $courseQuizQuestion->save();
                                    foreach ($quiz_question_answers as $answer) {
                                        $courseQuizQuestionAnswer = new QuizQuestionAnswer();
                                        $courseQuizQuestionAnswer->title = $answer['title'];
                                        $courseQuizQuestionAnswer->question_id = $courseQuizQuestion->id;
                                        $courseQuizQuestionAnswer->correct = $answer['correct'];
                                        $courseQuizQuestionAnswer->save();
                                    }
                                }
                            }
                        }
                    }
                }
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