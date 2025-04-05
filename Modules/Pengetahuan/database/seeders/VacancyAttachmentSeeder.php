<?php

namespace Modules\Pengetahuan\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Pengetahuan\app\Models\Vacancy;
use Modules\Pengetahuan\app\Models\VacancyAttachment;
use Modules\Pengetahuan\app\Models\VacancyMasterAttachment;

class VacancyAttachmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vacancies = Vacancy::all();

        $attachments = [
            'Surat Pernyataan Kesanggupan Biaya Mandiri (Mandiri) atau SK Penerima Beasiswa (APBD/Non APBD)',
            'Scan Ijazah Terakhir',
            'Letter of Acceptance',
            'Surat Usulan dari Perangkat Daerah',
            'SK Akreditasi Program Studi',
            'Jadwal Perkuliahan',
            'Surat Keterangan Sehat'
        ];

        foreach ($attachments as $key => $att) {
            VacancyMasterAttachment::create([
                'name' => $att
            ]);
        }

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
