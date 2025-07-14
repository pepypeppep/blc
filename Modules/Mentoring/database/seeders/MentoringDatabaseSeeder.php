<?php

namespace Modules\Mentoring\database\seeders;

use Illuminate\Database\Seeder;

class MentoringDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $this->call([
             MentoringSeeder::class,
             MentoringSessionSeeder::class,
         ]);
    }
}
