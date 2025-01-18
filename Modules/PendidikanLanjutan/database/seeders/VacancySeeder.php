<?php

namespace Modules\PendidikanLanjutan\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PendidikanLanjutan\app\Models\Vacancy;

class VacancySeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Magister Teknologi Informasi - Universitas Gajah Mada',
                'description' => 'Program magister untuk teknologi informasi di Universitas Gajah Mada.',
                'start_at' => now(),
                'end_at' => now()->addDays(30),
                'year' => 2025,
            ],
            [
                'name' => 'Magister Teknologi Informasi - Universitas Amikom Yogyakarta',
                'description' => 'Program magister teknologi informasi di Universitas Amikom Yogyakarta.',
                'start_at' => now(),
                'end_at' => now()->addDays(25),
                'year' => 2025,
            ],
            [
                'name' => 'Informatika - Universitas Amikom Yogyakarta',
                'description' => 'Program sarjana informatika di Universitas Amikom Yogyakarta.',
                'start_at' => now(),
                'end_at' => now()->addDays(20),
                'year' => 2025,
            ],
            [
                'name' => 'Manajemen Informatika - Politeknik Negeri Jakarta',
                'description' => 'Program diploma manajemen informatika di Politeknik Negeri Jakarta.',
                'start_at' => now(),
                'end_at' => now()->addDays(15),
                'year' => 2025,
            ],
        ];

        foreach ($data as $item) {
            Vacancy::create($item);
        }
    }
}
