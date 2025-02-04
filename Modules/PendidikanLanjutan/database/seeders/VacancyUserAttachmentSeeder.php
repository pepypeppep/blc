<?php

namespace Modules\PendidikanLanjutan\database\seeders;

use Illuminate\Database\Seeder;
use Modules\PendidikanLanjutan\app\Models\Vacancy;
use Modules\PendidikanLanjutan\app\Models\VacancyAttachment;
use Modules\PendidikanLanjutan\app\Models\VacancyUser;
use Modules\PendidikanLanjutan\app\Models\VacancyUserAttachment;

class VacancyUserAttachmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vacancies = VacancyUser::all();

        foreach ($vacancies as $vacancy) {
            $vacancyAttachments = VacancyAttachment::where('vacancy_id', $vacancy->id)->get();

            foreach ($vacancyAttachments as $key => $attachment) {
                VacancyUserAttachment::create([
                    'vacancy_user_id' => $vacancy->user_id,
                    'vacancy_attachment_id' => $attachment->id,
                    'file' => "https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf",
                    'category' => $attachment->category
                ]);
            }
        }
    }
}
