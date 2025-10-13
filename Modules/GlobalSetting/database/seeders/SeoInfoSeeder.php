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
        $item1->seo_title = 'Beranda || Bantul Corpu';
        $item1->seo_description = 'Beranda || Bantul Corpu';
        $item1->save();

        $item2 = new SeoSetting();
        $item2->page_name = 'about_page';
        $item2->seo_title = 'Tentang || Bantul Corpu';
        $item2->seo_description = 'Tentang || Bantul Corpu';
        $item2->save();

        $item2 = new SeoSetting();
        $item2->page_name = 'course_page';
        $item2->seo_title = 'Pelatihan || Bantul Corpu';
        $item2->seo_description = 'Pelatihan || Bantul Corpu';
        $item2->save();

        $item2 = new SeoSetting();
        $item2->page_name = 'blog_page';
        $item2->seo_title = 'Blog || Bantul Corpu';
        $item2->seo_description = 'Blog || Bantul Corpu';
        $item2->save();

        $item2 = new SeoSetting();
        $item2->page_name = 'article_page';
        $item2->seo_title = 'Artikel || Bantul Corpu';
        $item2->seo_description = 'Artikel || Bantul Corpu';
        $item2->save();

        $item2 = new SeoSetting();
        $item2->page_name = 'contact_page';
        $item2->seo_title = 'Kontak || Bantul Corpu';
        $item2->seo_description = 'Kontak || Bantul Corpu';
        $item2->save();
    }
}
