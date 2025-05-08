<?php

namespace Modules\PendidikanLanjutan\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PendidikanLanjutan\app\Models\Study;
use Modules\PendidikanLanjutan\app\Models\Vacancy;
use Modules\PendidikanLanjutan\app\Models\VacancySchedule;

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
                'instansi_id' => 19,
                'education_level' => 'strata_1',
                'employment_grade' => 'juru_ia',
                'employment_status' => 'tidak_diberhentikan_dari_jabatan',
                'cost_type' => 'non_apbd',
                'formation' => 3,
                'age_limit' => 50,
                'year' => 2025,
                'open_at' => now(),
                'close_at' => now()->addDays(30),
            ],
            [
                'study_id' => 2,
                'instansi_id' => 19,
                'education_level' => 'strata_2',
                'employment_grade' => 'juru_ia',
                'employment_status' => 'tidak_diberhentikan_dari_jabatan',
                'cost_type' => 'apbd',
                'formation' => 2,
                'age_limit' => 40,
                'year' => 2025,
                'open_at' => now(),
                'close_at' => now()->addDays(30),
            ],
            [
                'study_id' => 3,
                'education_level' => 'strata_3',
                'employment_grade' => 'juru_ic',
                'employment_status' => 'tidak_diberhentikan_dari_jabatan',
                'cost_type' => 'mandiri',
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

        VacancySchedule::create([
            'start_at' => now(),
            'end_at' => now()->addDays(30),
            'year' => now()->year,
            'description' => 'Test',
        ]);
    }
}
