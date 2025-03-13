<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserEducation;
use App\Models\UserExperience;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\InstructorRequest\app\Models\InstructorRequest;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // force truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('instansis')->truncate();
        DB::table('unors')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('instansis')->insert([
            [
                'id' => 1,
                'name' => 'Badan Kepegawaian dan Pelatihan Bantul',
            ],
            [
                'id' => 2,
                'name' => 'Dinas Komunikasi dan Informatika Kabupaten Bantul',
            ]
        ]);

        DB::table('unors')->insert([
            [
                'id' => 1,
                'name' => 'Unor Example 1',
            ],
        ]);

        DB::table('unors')->insert([
            [
                'id' => 2,
                'name' => 'Unor Example 2',
                'parent_id' => 1
            ],
        ]);

        // 20000
        DB::table('unors')->insert([
            [
                'id' => 20000,
                'name' => 'Perda 20000',
            ],
        ]);

        User::create([
            'id' => 1,
            'instansi_id' => 1,
            'username' => 'lms_student',
            'name' => 'LMS Student',
            'email' => 'student@gmail.com',
            'password' => bcrypt('1234'),
            'role' => 'student',
            'email_verified_at' => now(),
            'golongan' => 'III/a',

        ]);

        User::create([
            'id' => 2,
            'name' => 'LMS Instructor',
            'username' => 'lms_instructor',
            'email' => 'instructor@gmail.com',
            'password' => bcrypt('1234'),
            'role' => 'instructor',
            'email_verified_at' => now(),
        ]);

        User::create([
            'id' => 3,
            'name' => 'Mark Davenport',
            'email' => 'instructortwo@gmail.com',
            'username' => 'lms_instructor_two',
            'password' => bcrypt('1234'),
            'role' => 'instructor',
            'email_verified_at' => now(),
        ]);

        User::create([
            'id' => 4,
            'name' => 'Ethan Granger',
            'email' => 'instructortrhee@gmail.com',
            'username' => 'lms_instructor_three',
            'password' => bcrypt('1234'),
            'role' => 'instructor',
            'email_verified_at' => now(),
        ]);

        User::create([
            'id' => 5,
            'name' => 'Lucas Hale',
            'email' => 'instructorfour@gmail.com',
            'username' => 'lms_instructor_four',
            'password' => bcrypt('1234'),
            'role' => 'instructor',
            'email_verified_at' => now(),
        ]);

        User::create([
            'id' => 6,
            'name' => 'Nathaniel Cross',
            'email' => 'instructorfive@gmail.com',
            'username' => 'lms_instructor_five',
            'password' => bcrypt('1234'),
            'role' => 'instructor',
            'email_verified_at' => now(),
        ]);

        User::create([
            'id' => 7,
            'name' => 'Adrian Pierce',
            'email' => 'instructorsix@gmail.com',
            'username' => 'lms_instructor_six',
            'password' => bcrypt('1234'),
            'role' => 'instructor',
            'email_verified_at' => now(),
        ]);

        User::create([
            'id' => 8,
            'name' => 'Laire Kaira Nayadita',
            'email' => 'studenttwo@gmail.com',
            'username' => 'lms_student_two',
            'password' => bcrypt('1234'),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        foreach (User::where('role', 'instructor')->get() as $key => $instructor) {
            InstructorRequest::updateOrCreate([
                'user_id' => $instructor->id
            ], [
                'status' => 'approved'
            ]);
        }
    }
}
