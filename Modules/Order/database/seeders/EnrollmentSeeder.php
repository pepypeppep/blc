<?php

namespace Modules\Order\database\seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Order\app\Models\Enrollment;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'student')->get();
        $courses = Course::get();

        for ($i = 1; $i <= 23; $i++) {
            Enrollment::create([
                'user_id' => $users->random()->id,
                'course_id' => $courses->random()->id,
            ]);
        }
    }
}
