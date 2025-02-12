<?php

namespace Database\Seeders;

use Illuminate\Http\Request;
use Illuminate\Database\Seeder;
use Modules\Course\app\Models\CourseCategory;
use Modules\Course\app\Models\CourseCategoryTranslation;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Traits\GenerateTranslationTrait;

class CourseCategorySeeder extends Seeder
{
    use GenerateTranslationTrait;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $request = new Request([
            'name' => 'IT DEPARTMENT',
            'slug' => 'it-department',
        ]);

        $category = CourseCategory::create([
            'slug' => $request->slug,
            'icon' => 'uploads/custom-images/it-department.png',
            'status' => 1
        ]);

        CourseCategoryTranslation::create([
            'name' => $request->name,
            'course_category_id' => $category->id,
            'lang_code' => 'id'
        ]);

        // $this->generateTranslations(
        //     TranslationModels::CourseCategory,
        //     $category,
        //     'course_category_id',
        //     $request,
        // );

        $request = new Request([
            'name' => 'SOFTWARE ENGINEERING',
            'slug' => 'software-engineering',
        ]);

        $category = CourseCategory::create([
            'slug' => $request->slug,
            'parent_id' => $category->id,
            'icon' => 'uploads/custom-images/it-department.png',
            'status' => 1
        ]);

        CourseCategoryTranslation::create([
            'name' => $request->name,
            'course_category_id' => $category->id,
            'lang_code' => 'id'
        ]);
        // $this->generateTranslations(
        //     TranslationModels::CourseCategory,
        //     $category,
        //     'course_category_id',
        //     $request,
        // );

        $request = new Request([
            'name' => 'MANAGEMENT DEPARTMENT',
            'slug' => 'management-department',
        ]);

        $category =  CourseCategory::create([
            'slug' => $request->slug,
            'icon' => 'uploads/custom-images/it-department.png',
            'status' => 1
        ]);

        CourseCategoryTranslation::create([
            'name' => $request->name,
            'course_category_id' => $category->id,
            'lang_code' => 'id'
        ]);

        // $this->generateTranslations(
        //     TranslationModels::CourseCategory,
        //     $category,
        //     'course_category_id',
        //     $request,
        // );


        $request = new Request([
            'name' => 'MARKETING',
            'slug' => 'marketing',
        ]);

        $category =  CourseCategory::create([
            'slug' => $request->slug,
            'parent_id' => $category->id,
            'icon' => 'uploads/custom-images/it-department.png',
            'status' => 1
        ]);

        CourseCategoryTranslation::create([
            'name' => $request->name,
            'course_category_id' => $category->id,
            'lang_code' => 'id'
        ]);

        // $this->generateTranslations(
        //     TranslationModels::CourseCategory,
        //     $category,
        //     'course_category_id',
        //     $request,
        // );
    }
}
