<?php

namespace Modules\Pengetahuan\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Pengetahuan\database\seeders\VacancyAttachmentSeeder;
use Modules\Pengetahuan\database\seeders\VacancyMasterReportFileSeeder;

class PengetahuanDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            VacancySeeder::class,
            VacancyAttachmentSeeder::class,
            VacancyMasterReportFileSeeder::class
            // VacancyUserSeeder::class,
            // VacancyUserAttachmentSeeder::class
        ]);
    }
}
