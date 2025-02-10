<?php

namespace Modules\Course\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Course\app\Models\CourseLevel;
use Illuminate\Support\Str;
use Modules\Course\app\Models\CourseLevelTranslation;
use PHPUnit\Framework\Constraint\Count;

class CourseLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //make level for course
        $levels = [
            [
                'slug' => Str::slug('Beginner'),
                'status' => 1,
            ],
            [
                'slug' => Str::slug('Intermediate'),
                'status' => 1,
            ],
            [
                'slug' => Str::slug('Advanced'),
                'status' => 1,
            ],
        ];

        foreach ($levels as $level) {
            CourseLevel::updateOrCreate(['slug' => $level['slug']], $level);
        }

        //make level translation for course
        $levels = [
            [
                'name' => 'Beginner',
                'course_level_id' => CourseLevel::where('slug', 'beginner')->first()->id,
                'lang_code'  => 'id',
            ],
            [
                'name' => 'Intermediate',
                'course_level_id' => CourseLevel::where('slug', 'intermediate')->first()->id,
                'lang_code'  => 'id',
            ],
            [
                'name' => 'Advanced',
                'course_level_id' => CourseLevel::where('slug', 'advanced')->first()->id,
                'lang_code'  => 'id',
            ],
        ];

        foreach ($levels as $level) {
            CourseLevelTranslation::updateOrCreate(['course_level_id' => $level['course_level_id']], $level);
        }
    }
}