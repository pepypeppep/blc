<?php

namespace Modules\PendidikanLanjutan\database\seeders;

use Illuminate\Database\Seeder;
use Modules\PendidikanLanjutan\database\seeders\VacancyAttachmentSeeder;
use Modules\PendidikanLanjutan\database\seeders\VacancySeeder;
use Modules\PendidikanLanjutan\database\seeders\VacancyUserSeeder;

class PendidikanLanjutanDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            VacancySeeder::class,
            VacancyAttachmentSeeder::class,
            VacancyUserSeeder::class,
            VacancyUserAttachmentSeeder::class
        ]);
    }
}
