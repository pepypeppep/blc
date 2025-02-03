<?php

namespace Modules\PendidikanLanjutan\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PendidikanLanjutan\app\Models\Study;
use Modules\PendidikanLanjutan\app\Models\Vacancy;

class VacancySeeder extends Seeder
{
    public function run()
    {
        $studies = [
            'Magister Teknologi Informasi - Universitas Gajah Mada',
            'Magister Teknologi Informasi - Universitas Amikom Yogyakarta',
            'Informatika - Universitas Amikom Yogyakarta',
            'Sarjana Teknologi Informasi - Universitas Amikom Yogyakarta',
            'Manajemen Informatika - Politeknik Negeri Jakarta',
        ];

        foreach ($studies as $key => $std) {
            Study::create([
                'name' => $std
            ]);
        }

        $data = [
            [
                'study_id' => 1,
                'education_level' => 'Strata I',
                'employment_grade' => 'IIIb',
                'employment_status' => 'tidak_diberhentikan_dari_jabatan',
                'cost_type' => 'Non APBD',
                'formation' => 3,
                'age_limit' => 50,
                'year' => 2025,
                'open_at' => now(),
                'close_at' => now()->addDays(30),
            ],
            [
                'study_id' => 2,
                'education_level' => 'Strata II',
                'employment_grade' => 'IIIb',
                'employment_status' => 'tidak_diberhentikan_dari_jabatan',
                'cost_type' => 'APBD',
                'formation' => 2,
                'age_limit' => 40,
                'year' => 2025,
                'open_at' => now(),
                'close_at' => now()->addDays(30),
            ],
            [
                'study_id' => 3,
                'education_level' => 'Strata III',
                'employment_grade' => 'IIIb',
                'employment_status' => 'tidak_diberhentikan_dari_jabatan',
                'cost_type' => 'Mandiri',
                'formation' => 1,
                'age_limit' => 45,
                'year' => 2025,
                'open_at' => now(),
                'close_at' => now()->addDays(30),
            ]
        ];

        foreach ($data as $item) {
            Vacancy::create($item);
        }
    }
}
