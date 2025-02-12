<?php

namespace Modules\Course\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Course\app\Models\CourseCategory;
use Illuminate\Support\Str;
use Modules\Course\app\Models\CourseCategoryTranslation;
use Modules\Course\app\Models\CourseLanguage;
use Modules\Language\app\Enums\TranslationModels;

class CourseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $categories = [
            [
                'slug' => Str::slug('Administrasi Publik'),
                'icon' => 'images/icons/admin_publik.png',
                'status' => 1,
                'order' => 1,
                'parent_id' => null,
                'show_at_trending' => 1,
            ],
            [
                'slug' => Str::slug('Keuangan Negara'),
                'icon' => 'images/icons/keuangan_negara.png',
                'status' => 1,
                'order' => 2,
                'parent_id' => null,
                'show_at_trending' => 1,

            ],
            [
                'slug' => Str::slug('Hukum dan Regulasi'),
                'icon' => 'images/icons/hukum_regulasi.png',
                'status' => 1,
                'order' => 3,
                'parent_id' => null,
                'show_at_trending' => 1,

            ],
            [
                'slug' => Str::slug('Teknologi Informasi Pemerintahan'),
                'icon' => 'images/icons/ti_pemerintahan.png',
                'status' => 1,
                'order' => 4,
                'parent_id' => null,
                'show_at_trending' => 1,

            ],
            [
                'slug' => Str::slug('Kesehatan Masyarakat'),
                'icon' => 'images/icons/kesehatan_masyarakat.png',
                'status' => 1,
                'order' => 5,
                'parent_id' => null,
                'show_at_trending' => 1,

            ],
        ];

        // Create parent categories
        foreach ($categories as $category) {
            CourseCategory::updateOrCreate([
                'slug' => $category['slug']
            ], $category);
        }

        // Create child categories
        $categories = [
            [
                'slug' => Str::slug('Manajemen Publik'),
                'icon' => 'images/icons/manajemen_publik.png',
                'status' => 1,
                'order' => 6,
                'parent_id' => CourseCategory::where('slug', 'administrasi-publik')->first()->id,
                'show_at_trending' => 1,

            ],
            [
                'slug' => Str::slug('Anggaran Negara'),
                'icon' => 'images/icons/anggaran_negara.png',
                'status' => 1,
                'order' => 7,
                'parent_id' => CourseCategory::where('slug', 'keuangan-negara')->first()->id,
                'show_at_trending' => 1,

            ],
            [
                'slug' => Str::slug('Peraturan dan Kebijakan'),
                'icon' => 'images/icons/peraturan_kebijakan.png',
                'status' => 1,
                'order' => 8,
                'parent_id' => CourseCategory::where('slug', 'hukum-dan-regulasi')->first()->id,
                'show_at_trending' => 1,

            ],
            [
                'slug' => Str::slug('E-Government'),
                'icon' => 'images/icons/e_government.png',
                'status' => 1,
                'order' => 9,
                'parent_id' => CourseCategory::where('slug', 'teknologi-informasi-pemerintahan')->first()->id,
                'show_at_trending' => 1,

            ],
            [
                'slug' => Str::slug('Kesehatan Lingkungan'),
                'icon' => 'images/icons/kesehatan_lingkungan.png',
                'status' => 1,
                'order' => 10,
                'parent_id' => CourseCategory::where('slug', 'kesehatan-masyarakat')->first()->id,
                'show_at_trending' => 1,

            ],
        ];

        foreach ($categories as $category) {
            CourseCategory::updateOrCreate([
                'slug' => $category['slug']
            ], $category);
        }

        //create or upate category translations
        $categories = CourseCategory::all();

        foreach ($categories as $category) {
            CourseCategoryTranslation::updateOrCreate(['course_category_id' => $category['id']], [
                'name' => Str::title(str_replace('-', ' ', $category['slug'])),
                'lang_code' => 'id',
            ]);
        }
    }
}