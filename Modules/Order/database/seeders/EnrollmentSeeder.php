<?php

namespace Modules\Order\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Order\app\Models\Enrollment;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Enrollment::create([
            'user_id' => 1,
            'course_id' => 1,
            'has_access' => 1,
        ]);
    }
}