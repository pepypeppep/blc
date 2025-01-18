<?php

namespace Modules\PendidikanLanjutan\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PendidikanLanjutan\Entities\VacancyDetailUserAttachment;

class VacancyDetailUserAttachmentSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'vacancy_detail_id' => 1,
                'vacancy_user_id' => 1,
                'file' => 'attachment1.pdf',
            ],
            [
                'vacancy_detail_id' => 2,
                'vacancy_user_id' => 2,
                'file' => 'attachment2.pdf',
            ],
            [
                'vacancy_detail_id' => 3,
                'vacancy_user_id' => 3,
                'file' => 'attachment3.pdf',
            ],
            [
                'vacancy_detail_id' => 4,
                'vacancy_user_id' => 4,
                'file' => 'attachment4.pdf',
            ],
        ];

        foreach ($data as $item) {
            VacancyDetailUserAttachment::create($item);
        }
    }
}
