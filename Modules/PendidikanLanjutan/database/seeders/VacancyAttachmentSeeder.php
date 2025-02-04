<?php

namespace Modules\PendidikanLanjutan\database\seeders;

use Illuminate\Database\Seeder;
use Modules\PendidikanLanjutan\app\Models\Vacancy;
use Modules\PendidikanLanjutan\app\Models\VacancyAttachment;

class VacancyAttachmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vacancies = Vacancy::all();

        foreach ($vacancies as $key => $vacancy) {
            VacancyAttachment::create([
                'vacancy_id' => $vacancy->id,
                'name' => 'Surat Pernyataan Kesanggupan Biaya Mandiri (Mandiri) atau SK Penerima Beasiswa (APBD/Non APBD)',
                'type' => 'pdf',
                'max_size' => 10000,
                'category' => 'syarat'
            ]);
            VacancyAttachment::create([
                'vacancy_id' => $vacancy->id,
                'name' => 'Scan Ijazah Terakhir',
                'type' => 'pdf',
                'max_size' => 10000,
                'category' => 'syarat'
            ]);
            VacancyAttachment::create([
                'vacancy_id' => $vacancy->id,
                'name' => 'Letter of Acceptance',
                'type' => 'pdf',
                'max_size' => 10000,
                'category' => 'syarat'
            ]);
            VacancyAttachment::create([
                'vacancy_id' => $vacancy->id,
                'name' => 'Surat Usulan dari Perangkat Daerah',
                'type' => 'pdf',
                'max_size' => 10000,
                'category' => 'syarat'
            ]);
            VacancyAttachment::create([
                'vacancy_id' => $vacancy->id,
                'name' => 'SK Akreditasi Program Studi',
                'type' => 'pdf',
                'max_size' => 10000,
                'category' => 'syarat'
            ]);
            VacancyAttachment::create([
                'vacancy_id' => $vacancy->id,
                'name' => 'Jadwal Perkuliahan',
                'type' => 'pdf',
                'max_size' => 10000,
                'category' => 'syarat'
            ]);
            VacancyAttachment::create([
                'vacancy_id' => $vacancy->id,
                'name' => 'Surat Keterangan Sehat',
                'type' => 'pdf',
                'max_size' => 10000,
                'category' => 'syarat'
            ]);


            VacancyAttachment::create([
                'vacancy_id' => $vacancy->id,
                'name' => 'Perjanjian Kinerja',
                'type' => 'pdf',
                'max_size' => 10000,
                'category' => 'lampiran'
            ]);
            VacancyAttachment::create([
                'vacancy_id' => $vacancy->id,
                'name' => 'SK',
                'type' => 'pdf',
                'max_size' => 10000,
                'category' => 'lampiran'
            ]);
            VacancyAttachment::create([
                'vacancy_id' => $vacancy->id,
                'name' => 'Petikan',
                'type' => 'pdf',
                'max_size' => 10000,
                'category' => 'lampiran'
            ]);
        }
    }
}
