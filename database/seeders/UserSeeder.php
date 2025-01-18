<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserEducation;
use App\Models\UserExperience;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\InstructorRequest\app\Models\InstructorRequest;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // force truncate
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('users')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        \DB::table('unors')->insert([
            [
                'id' => 1,
                'name' => 'Unor Example 1',
            ],
            [
                'id' => 2,
                'name' => 'Unor Example 2',
            ]
        ]);

        User::create([
            'id' => 1000,
            'unor_id' => 1,
            'name' => 'Jhon Doe',
            'email' => 'student@gmail.com',
            'password' => bcrypt('1234'),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        User::create([
            'id' => 1001,
            'unor_id' => 1,
            'name' => 'Jason Thorne',
            'email' => 'instructor@gmail.com',
            'password' => bcrypt('1234'),
            'role' => 'instructor',
            'email_verified_at' => now(),
            // 'short_bio' => 'I am a web developer with a vast array of knowledge in many different front end and back end languages, responsive frameworks, databases, and best code practices',
            // 'bio' => 'I am a web developer with a vast array of knowledge in many different front end and back end languages, responsive frameworks, databases, and best code practices. My objective is simply to be the best web developer that I can be and to contribute to the technology industry all that I know and can do. I am dedicated to perfecting my craft by learning from more seasoned developers, remaining humble, and continuously making strides to learn all that I can about development.

            // developer with a vast array of knowledge in many different front end and back end languages, responsive frameworks, databases, and best code practices. My objective is simply to be the best web developer that I can be and to contribute to the technology industry all that I know and can do. I am dedicated to perfecting my craft by learning from more seasoned developers, remaining humble, and continuously making strides to learn all that I can about development.

            // a vast array of knowledge in many different front end and back end languages, responsive frameworks, databases, and best code practices. My objective is simply to be the best web developer that I can be and to contribute to the technology industry all that I know and can do. I am dedicated to perfecting my craft by learning from more seasoned developers, remaining humble, and continuously making strides to learn all that I can about development.
            // ',
            // 'job_title' => 'Developer',
            // 'facebook' => 'https://www.facebook.com/',
            // 'twitter' => 'https://twitter.com/',
            // 'linkedin' => 'https://www.linkedin.com/',
            // 'website' => 'https://www.websolutionus.com/',
            // 'github' => 'https://www.github.com/',
        ]);

        User::create([
            'id' => 1002,
            'unor_id' => 1,
            'name' => 'Mark Davenport',
            'email' => 'instructortwo@gmail.com',
            'password' => bcrypt('1234'),
            'role' => 'instructor',
            'email_verified_at' => now(),
            // 'short_bio' => 'I am a web developer with a vast array of knowledge in many different front end and back end languages, responsive frameworks, databases, and best code practices',
            // 'bio' => 'I am a web developer with a vast array of knowledge in many different front end and back end languages, responsive frameworks, databases, and best code practices. My objective is simply to be the best web developer that I can be and to contribute to the technology industry all that I know and can do. I am dedicated to perfecting my craft by learning from more seasoned developers, remaining humble, and continuously making strides to learn all that I can about development.

            // developer with a vast array of knowledge in many different front end and back end languages, responsive frameworks, databases, and best code practices. My objective is simply to be the best web developer that I can be and to contribute to the technology industry all that I know and can do. I am dedicated to perfecting my craft by learning from more seasoned developers, remaining humble, and continuously making strides to learn all that I can about development.

            // a vast array of knowledge in many different front end and back end languages, responsive frameworks, databases, and best code practices. My objective is simply to be the best web developer that I can be and to contribute to the technology industry all that I know and can do. I am dedicated to perfecting my craft by learning from more seasoned developers, remaining humble, and continuously making strides to learn all that I can about development.
            // ',
            // 'job_title' => 'Developer',
            // 'facebook' => 'https://www.facebook.com/',
            // 'twitter' => 'https://twitter.com/',
            // 'linkedin' => 'https://www.linkedin.com/',
            // 'website' => 'https://www.websolutionus.com/',
            // 'github' => 'https://www.github.com/',
        ]);

        User::create([
            'id' => 1003,
            'unor_id' => 1,
            'name' => 'Ethan Granger',
            'email' => 'instructortrhee@gmail.com',
            'password' => bcrypt('1234'),
            'role' => 'instructor',
            'email_verified_at' => now(),
            // 'short_bio' => 'I am a web developer with a vast array of knowledge in many different front end and back end languages, responsive frameworks, databases, and best code practices',
            // 'bio' => 'I am a web developer with a vast array of knowledge in many different front end and back end languages, responsive frameworks, databases, and best code practices. My objective is simply to be the best web developer that I can be and to contribute to the technology industry all that I know and can do. I am dedicated to perfecting my craft by learning from more seasoned developers, remaining humble, and continuously making strides to learn all that I can about development.

            // developer with a vast array of knowledge in many different front end and back end languages, responsive frameworks, databases, and best code practices. My objective is simply to be the best web developer that I can be and to contribute to the technology industry all that I know and can do. I am dedicated to perfecting my craft by learning from more seasoned developers, remaining humble, and continuously making strides to learn all that I can about development.

            // a vast array of knowledge in many different front end and back end languages, responsive frameworks, databases, and best code practices. My objective is simply to be the best web developer that I can be and to contribute to the technology industry all that I know and can do. I am dedicated to perfecting my craft by learning from more seasoned developers, remaining humble, and continuously making strides to learn all that I can about development.
            // ',
            // 'job_title' => 'Developer',
            // 'facebook' => 'https://www.facebook.com/',
            // 'twitter' => 'https://twitter.com/',
            // 'linkedin' => 'https://www.linkedin.com/',
            // 'website' => 'https://www.websolutionus.com/',
            // 'github' => 'https://www.github.com/',
        ]);

        User::create([
            'id' => 1004,
            'unor_id' => 1,
            'name' => 'Lucas Hale',
            'email' => 'instructorfour@gmail.com',
            'password' => bcrypt('1234'),
            'role' => 'instructor',
            'email_verified_at' => now(),
            // 'short_bio' => 'I am a web developer with a vast array of knowledge in many different front end and back end languages, responsive frameworks, databases, and best code practices',
            // 'bio' => 'I am a web developer with a vast array of knowledge in many different front end and back end languages, responsive frameworks, databases, and best code practices. My objective is simply to be the best web developer that I can be and to contribute to the technology industry all that I know and can do. I am dedicated to perfecting my craft by learning from more seasoned developers, remaining humble, and continuously making strides to learn all that I can about development.

            // developer with a vast array of knowledge in many different front end and back end languages, responsive frameworks, databases, and best code practices. My objective is simply to be the best web developer that I can be and to contribute to the technology industry all that I know and can do. I am dedicated to perfecting my craft by learning from more seasoned developers, remaining humble, and continuously making strides to learn all that I can about development.

            // a vast array of knowledge in many different front end and back end languages, responsive frameworks, databases, and best code practices. My objective is simply to be the best web developer that I can be and to contribute to the technology industry all that I know and can do. I am dedicated to perfecting my craft by learning from more seasoned developers, remaining humble, and continuously making strides to learn all that I can about development.
            // ',
            // 'job_title' => 'Instructor',
            // 'facebook' => 'https://www.facebook.com/',
            // 'twitter' => 'https://twitter.com/',
            // 'linkedin' => 'https://www.linkedin.com/',
            // 'website' => 'https://www.websolutionus.com/',
            // 'github' => 'https://www.github.com/',
        ]);

        User::create([
            'id' => 1005,
            'unor_id' => 1,
            'name' => 'Nathaniel Cross',
            'email' => 'instructorfive@gmail.com',
            'password' => bcrypt('1234'),
            'role' => 'instructor',
            'email_verified_at' => now(),
            // 'short_bio' => 'I am a web developer with a vast array of knowledge in many different front end and back end languages, responsive frameworks, databases, and best code practices',
            // 'bio' => 'I am a web developer with a vast array of knowledge in many different front end and back end languages, responsive frameworks, databases, and best code practices. My objective is simply to be the best web developer that I can be and to contribute to the technology industry all that I know and can do. I am dedicated to perfecting my craft by learning from more seasoned developers, remaining humble, and continuously making strides to learn all that I can about development.

            // developer with a vast array of knowledge in many different front end and back end languages, responsive frameworks, databases, and best code practices. My objective is simply to be the best web developer that I can be and to contribute to the technology industry all that I know and can do. I am dedicated to perfecting my craft by learning from more seasoned developers, remaining humble, and continuously making strides to learn all that I can about development.

            // a vast array of knowledge in many different front end and back end languages, responsive frameworks, databases, and best code practices. My objective is simply to be the best web developer that I can be and to contribute to the technology industry all that I know and can do. I am dedicated to perfecting my craft by learning from more seasoned developers, remaining humble, and continuously making strides to learn all that I can about development.
            // ',
            // 'job_title' => 'Developer',
            // 'facebook' => 'https://www.facebook.com/',
            // 'twitter' => 'https://twitter.com/',
            // 'linkedin' => 'https://www.linkedin.com/',
            // 'website' => 'https://www.websolutionus.com/',
            // 'github' => 'https://www.github.com/',
        ]);

        User::create([
            'id' => 1006,
            'unor_id' => 1,
            'name' => 'Adrian Pierce',
            'email' => 'instructorsix@gmail.com',
            'password' => bcrypt('1234'),
            'role' => 'instructor',
            'email_verified_at' => now(),
            // 'short_bio' => 'I am a web developer with a vast array of knowledge in many different front end and back end languages, responsive frameworks, databases, and best code practices',
            // 'bio' => 'I am a web developer with a vast array of knowledge in many different front end and back end languages, responsive frameworks, databases, and best code practices. My objective is simply to be the best web developer that I can be and to contribute to the technology industry all that I know and can do. I am dedicated to perfecting my craft by learning from more seasoned developers, remaining humble, and continuously making strides to learn all that I can about development.

            // developer with a vast array of knowledge in many different front end and back end languages, responsive frameworks, databases, and best code practices. My objective is simply to be the best web developer that I can be and to contribute to the technology industry all that I know and can do. I am dedicated to perfecting my craft by learning from more seasoned developers, remaining humble, and continuously making strides to learn all that I can about development.

            // a vast array of knowledge in many different front end and back end languages, responsive frameworks, databases, and best code practices. My objective is simply to be the best web developer that I can be and to contribute to the technology industry all that I know and can do. I am dedicated to perfecting my craft by learning from more seasoned developers, remaining humble, and continuously making strides to learn all that I can about development.
            // ',
            // 'job_title' => 'Developer',
            // 'facebook' => 'https://www.facebook.com/',
            // 'twitter' => 'https://twitter.com/',
            // 'linkedin' => 'https://www.linkedin.com/',
            // 'website' => 'https://www.websolutionus.com/',
            // 'github' => 'https://www.github.com/',
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
