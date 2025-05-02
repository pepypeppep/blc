<?php

namespace Modules\InstructorEvaluation\database\seeders;

use Illuminate\Database\Seeder;
use Modules\InstructorEvaluation\app\Models\InstructorEvaluation;

class InstructorEvaluationDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $instructorEvaluation = new InstructorEvaluation();
        $instructorEvaluation->student_id = 1;
        $instructorEvaluation->instructor_id = 2;
        $instructorEvaluation->course_id = 1;
        $instructorEvaluation->rating = 5;
        $instructorEvaluation->feedback = 'Good';
        $instructorEvaluation->save();
    }
}
