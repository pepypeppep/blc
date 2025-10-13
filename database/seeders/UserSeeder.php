<?php

namespace Database\Seeders;

use App\Models\Admin;
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
        DB::table('instansis')->insert([
            [
                'id' => 1,
                'name' => 'Dinas 1',
            ],
            [
                'id' => 2,
                'name' => 'Dinas 2',
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



        // User
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
            'name' => 'LMS Student Two',
            'email' => 'studenttwo@gmail.com',
            'username' => 'lms_student_two',
            'password' => bcrypt('1234'),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        User::create([
            'id' => 12,
            'name' => 'LMS Student Three',
            'email' => 'studentthree@gmail.com',
            'username' => 'lms_student_three',
            'password' => bcrypt('1234'),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        User::create([
            'id' => 9,
            'name' => 'Instruktur BKPSDM',
            'username' => 'instruktur_bkpsdm',
            'email' => 'instruktur_bkpsdm@gmail.com',
            'password' => bcrypt('1234'),
            'role' => 'instructor',
            'email_verified_at' => now(),
        ]);

        // Bobot
        User::create([
            'id' => 10,
            'name' => 'Bobot',
            'username' => 'bobot',
            'email' => 'bobot@gmail.com',
            'password' => bcrypt('1234'),
            'role' => 'student',
            'email_verified_at' => now(),
            'nik' => '3402171906690002',
        ]);

        // Isa 
        User::create([
            'id' => 11,
            'name' => 'Isa Budihartono',
            'username' => 'isa',
            'email' => 'isa@gmail.com',
            'password' => bcrypt('1234'),
            'role' => 'student',
            'email_verified_at' => now(),
            'nik' => '3471120505680001',
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
