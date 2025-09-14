<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Frontend\app\Models\ContactSection;
use Modules\Frontend\app\Models\SectionTranslation;
use Modules\SiteAppearance\app\Models\SectionSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SectionTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Hero Section
        SectionTranslation::create([
            'section_id' => 1,
            'lang_code' => 'id',
            'content' => [
                'title' => 'Learning Management System Kabupaten Bantul',
                'sub_title' => 'Giat belajar untuk menjadi lebih pintar',
                'total_student' => '6905',
                'total_instructor' => '100',
                'video_button_text' => 'Pelatihan',
                'action_button_text' => 'Pelatihan',
            ],
        ]);

        //About Section
        SectionTranslation::create([
            'section_id' => 2,
            'lang_code' => 'id',
            'content' => [
                'short_title' => 'LMS Bantul',
                'title' => 'Tentang [Kami]',
                'description' => '<p>Potong bebek angsa Masak di kuali Nona minta dansa, dansa empat kali Sorong ke kiri Sorong ke kanan Lalalala Sorong ke kiri Sorong ke kanan Lalalala&nbsp;</p>',
                'button_text' => 'Halo',
            ],
        ]);

        //FAQ Section
        SectionTranslation::create([
            'section_id' => 5,
            'lang_code' => 'id',
            'content' => [
                'short_title' => 'FAQs',
                'title' => 'Mulailah [Belajar] dari {Sekarang}',
                'description' => 'Potong bebek angsa Masak di kuali Nona minta dansa, dansa empat kali Sorong ke kiri Sorong ke kanan Lalalala Sorong ke kiri Sorong ke kanan Lalalala',
            ],
        ]);

        //Features Section
        SectionTranslation::create([
            'section_id' => 6,
            'lang_code' => 'id',
            'content' => [
                'title_one' => 'Belajar dengan Pakar',
                'sub_title_one' => 'Tingkatkan pembelajaran Anda. Bimbingan terpercaya, hasil nyata',
                'title_two' => 'Pelajari Semuanya',
                'sub_title_two' => 'Kuasai Keterampilan Apa Pun. Keluarkan Potensi Anda dan berubahlah menjadi yang terbaik',
                'title_three' => 'Dapatkan Sertifikat',
                'sub_title_three' => 'Mulai pembelajaran sekarang dan Segera Dapatkan Sertifikasi Hari Ini',
                'title_four' => 'Informasi Pelatihan',
                'sub_title_four' => 'Dapatkan informasi terkait Pelatihan terbaru secara cepat',
            ],
        ]);

        //Contact Section
        ContactSection::updateOrCreate(
            ['id' => 1],
            [
                'address'   => 'Jl. RW Monginsidi 1 Bantul Daerah Istimewa Yogyakarta 55711',
                'phone_one' => '0274-367509',
                'email_one' => 'bkpsdm@bantulkab.go.id',
                'map'       => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4404.927614523036!2d110.32533732500605!3d-7.886218792136385!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7aff0036c0342f%3A0x33038655a04641a1!2sBadan%20kepegawaian%20dan%20Pengembangan%20Sumber%20Daya%20Manusia%20Kabupaten%20Bantul!5e1!3m2!1sid!2sid!4v1757845174565!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade',
            ]
        );

        //Enable all section
        SectionSetting::updateOrCreate(
            ['id' => 1],
            [
                'hero_section' => 1,
                'top_category_section' => 1,
                'brands_section' => 1,
                'about_section' => 1,
                'featured_course_section' => 1,
                'news_letter_section' => 1,
                'featured_instructor_section' => 1,
                'counter_section' => 1,
                'faq_section' => 1,
                'our_features_section' => 1,
                'testimonial_section' => 1,
                'banner_section' => 1,
                'latest_blog_section' => 1,
                'blog_page' => 1,
                'about_page' => 1,
                'contact_page' => 1,
            ]
        );
    }
}
