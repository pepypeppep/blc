<?php

namespace Modules\PendidikanLanjutan\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PendidikanLanjutan\Entities\VacancyDetail;

class VacancyDetailSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'vacancy_id' => 1,
                'name' => 'Detail Program Studi - Magister Teknologi Informasi',
                'category' => 'Magister',
                'type' => 'Persyaratan',
                'type_value' => 'Wajib',
                'description' => 'Memiliki gelar sarjana di bidang terkait.',
            ],
            [
                'vacancy_id' => 2,
                'name' => 'Detail Program Studi - Magister Teknologi Informasi',
                'category' => 'Magister',
                'type' => 'Persyaratan',
                'type_value' => 'Wajib',
                'description' => 'Memiliki sertifikat TOEFL minimal 500.',
            ],
            [
                'vacancy_id' => 3,
                'name' => 'Detail Program Studi - Informatika',
                'category' => 'Sarjana',
                'type' => 'Persyaratan',
                'type_value' => 'Wajib',
                'description' => 'Lulusan SMA/sederajat dengan nilai rata-rata minimal 80.',
            ],
            [
                'vacancy_id' => 4,
                'name' => 'Detail Program Studi - Manajemen Informatika',
                'category' => 'Diploma',
                'type' => 'Persyaratan',
                'type_value' => 'Wajib',
                'description' => 'Lulusan SMA/sederajat.',
            ],
        ];

        foreach ($data as $item) {
            VacancyDetail::create($item);
        }
    }
}
