<?php

namespace Modules\PendidikanLanjutan\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PendidikanLanjutan\database\seeders\VacancySeeder;
use Modules\PendidikanLanjutan\database\seeders\VacancyDetailSeeder;
use Modules\PendidikanLanjutan\database\seeders\VacancyDetailUserAttachmentSeeder;
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
            VacancyDetailSeeder::class,
            VacancyUserSeeder::class,
            VacancyDetailUserAttachmentSeeder::class,
        ]);
    }
}
