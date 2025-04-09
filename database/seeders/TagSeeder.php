<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\InstructorRequest\app\Models\InstructorRequest;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 24; $i++) {
            DB::table('tags')->insert([
                'name' => 'Tag ' . fake()->word() . $i,
            ]);
        }
    }
}
