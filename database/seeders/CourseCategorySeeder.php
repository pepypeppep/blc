<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Course\app\Models\CourseCategory;

class CourseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = CourseCategory::create([
            'slug' => 'IT DEPARTMENT',
        ]);

        CourseCategory::create([
            'slug' => 'SOFTWARE ENGINEERING',
            'parent_id' => $category->id
        ]);


        $category = CourseCategory::create([
            'slug' => 'MANAGEMENT DEPARTMENT',
        ]);

        CourseCategory::create([
            'slug' => 'MARKETING',
            'parent_id' => $category->id
        ]);
    }
}
