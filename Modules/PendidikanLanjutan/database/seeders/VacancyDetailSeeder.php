<?php

namespace Modules\PendidikanLanjutan\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PendidikanLanjutan\app\Models\VacancyDetail;
use Illuminate\Support\Arr;

class VacancyDetailSeeder extends Seeder
{
    public function run()
    {
        $golonganPNS = ['III/a', 'III/b', 'II/a', 'II/b', 'IV/a', 'IV/b'];

        $data = [
            [
                'vacancy_id' => 1,
                'name' => 'Detail Program Studi - Magister Teknologi Informasi',
                'category' => 'Magister',
                'type' => 'Golongan',
                'value_type' => Arr::random($golonganPNS),
                'description' => 'Memiliki gelar sarjana di bidang terkait.',
            ],
            [
                'vacancy_id' => 2,
                'name' => 'Detail Program Studi - Magister Teknologi Informasi',
                'category' => 'Magister',
                'type' => 'Golongan',
                'value_type' => Arr::random($golonganPNS),
                'description' => 'Memiliki sertifikat TOEFL minimal 500.',
            ],
            [
                'vacancy_id' => 3,
                'name' => 'Detail Program Studi - Informatika',
                'category' => 'Sarjana',
                'type' => 'Golongan',
                'value_type' => Arr::random($golonganPNS),
                'description' => 'Lulusan SMA/sederajat dengan nilai rata-rata minimal 80.',
            ],
            [
                'vacancy_id' => 4,
                'name' => 'Detail Program Studi - Manajemen Informatika',
                'category' => 'Diploma',
                'type' => 'Golongan',
                'value_type' => Arr::random($golonganPNS),
                'description' => 'Lulusan SMA/sederajat.',
            ],
        ];

        foreach ($data as $item) {
            VacancyDetail::create($item);
        }
    }
}
