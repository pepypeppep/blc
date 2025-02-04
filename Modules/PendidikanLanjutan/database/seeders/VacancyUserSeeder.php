<?php

namespace Modules\PendidikanLanjutan\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PendidikanLanjutan\app\Models\VacancyUser;

class VacancyUserSeeder extends Seeder
{
    public function run()
    {
        $data = [];
        for ($i = 1000; $i <= 1006; $i++) {
            $data[] = [
                'vacancy_id' => rand(1, 4),
                'user_id' => $i
            ];
        }

        foreach ($data as $item) {
            VacancyUser::create($item);
        }
    }
}
