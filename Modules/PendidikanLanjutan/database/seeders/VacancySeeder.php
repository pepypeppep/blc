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
                'formation' => 3,
                'year' => 2025,
                'open_at' => now(),
                'close_at' => now()->addDays(30),
            ],
            [
                'study_id' => 2,
                'instansi_id' => 19,
                'education_level' => 'strata_2',
                'employment_grade' => 'juru_ia',
                'formation' => 2,
                'year' => 2025,
                'open_at' => now(),
                'close_at' => now()->addDays(30),
            ],
            [
                'study_id' => 3,
                'education_level' => 'strata_3',
                'employment_grade' => 'juru_ic',
                'formation' => 1,
                'year' => 2025,
                'open_at' => now(),
                'close_at' => now()->addDays(30),
            ]
        ];

        $details = [
            [
                'employment_status' => 'Tidak diberhentikan dari Jabatan',
                'cost_type' => 'APBD',
                'age_limit' => 50
            ],
            [
                'employment_status' => 'Diberhentikan dari Jabatan',
                'cost_type' => 'APBD',
                'age_limit' => 40
            ],
            [
                'employment_status' => 'Tidak diberhentikan dari Jabatan',
                'cost_type' => 'Non APBD',
                'age_limit' => 30
            ],
            [
                'employment_status' => 'Diberhentikan dari Jabatan',
                'cost_type' => 'Non APBD',
                'age_limit' => 40
            ],
            [
                'employment_status' => 'Tidak diberhentikan dari Jabatan',
                'cost_type' => 'Mandiri',
                'age_limit' => 50
            ],
            [
                'employment_status' => 'Diberhentikan dari Jabatan',
                'cost_type' => 'Mandiri',
                'age_limit' => 50
            ]
        ];

        foreach ($data as $item) {
            $vacancy = Vacancy::create($item);

            $randomIndex = rand(1, count($details));

            for ($i = 1; $i <= $randomIndex; $i++) {
                $vacancy->details()->create($details[array_rand($details)]);
            }
        }

        VacancySchedule::create([
            'start_at' => now(),
            'end_at' => now()->addDays(30),
            'year' => now()->year,
            'description' => 'Test',
        ]);
    }
}
