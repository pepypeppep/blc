<?php

namespace Modules\GlobalSetting\database\seeders;

use Illuminate\Database\Seeder;
use Modules\GlobalSetting\app\Models\SeoSetting;

class SeoInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $item1 = new SeoSetting();
        $item1->page_name = 'home_page';
        $item1->seo_title = 'Beranda || LMS Kabupaten Bantul';
        $item1->seo_description = 'Beranda || LMS Kabupaten Bantul';
        $item1->save();

        $item2 = new SeoSetting();
        $item2->page_name = 'about_page';
        $item2->seo_title = 'Tentang || LMS Kabupaten Bantul';
        $item2->seo_description = 'Tentang || LMS Kabupaten Bantul';
        $item2->save();

        $item2 = new SeoSetting();
        $item2->page_name = 'course_page';
        $item2->seo_title = 'Pelatihan || LMS Kabupaten Bantul';
        $item2->seo_description = 'Pelatihan || LMS Kabupaten Bantul';
        $item2->save();

        $item2 = new SeoSetting();
        $item2->page_name = 'blog_page';
        $item2->seo_title = 'Blog || LMS Kabupaten Bantul';
        $item2->seo_description = 'Blog || LMS Kabupaten Bantul';
        $item2->save();

        $item2 = new SeoSetting();
        $item2->page_name = 'article_page';
        $item2->seo_title = 'Artikel || LMS Kabupaten Bantul';
        $item2->seo_description = 'Artikel || LMS Kabupaten Bantul';
        $item2->save();

        $item2 = new SeoSetting();
        $item2->page_name = 'contact_page';
        $item2->seo_title = 'Kontak || LMS Kabupaten Bantul';
        $item2->seo_description = 'Kontak || LMS Kabupaten Bantul';
        $item2->save();
    }
}
