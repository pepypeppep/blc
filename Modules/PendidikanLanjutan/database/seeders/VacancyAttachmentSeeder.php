<?php

namespace Modules\PendidikanLanjutan\database\seeders;

use Illuminate\Database\Seeder;
use Modules\PendidikanLanjutan\app\Models\Vacancy;
use Modules\PendidikanLanjutan\app\Models\VacancyAttachment;
use Modules\PendidikanLanjutan\app\Models\VacancyMasterAttachment;

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
                'name' => $att,
                'category' => 'syarat'
            ]);
        }

        $attachments = [
            'Perjanjian Kinerja',
            'SK',
            'Petikan',
        ];

        foreach ($attachments as $key => $att) {
            VacancyMasterAttachment::create([
                'name' => $att,
                'category' => 'lampiran'
            ]);
        }

        $attachments = [
            'Ijazah',
            'Transkrip Nilai',
            'Surat Pengantar dari Perangkat Daerah',
        ];

        foreach ($attachments as $key => $att) {
            VacancyMasterAttachment::create([
                'name' => $att,
                'category' => 'aktivasi'
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
            VacancyAttachment::create([
                'vacancy_id' => $vacancy->id,
                'name' => 'Ijazah',
                'type' => 'pdf',
                'max_size' => 10000,
                'category' => 'aktivasi'
            ]);
            VacancyAttachment::create([
                'vacancy_id' => $vacancy->id,
                'name' => 'Transkrip Nilai',
                'type' => 'pdf',
                'max_size' => 10000,
                'category' => 'aktivasi'
            ]);
            VacancyAttachment::create([
                'vacancy_id' => $vacancy->id,
                'name' => 'Surat Pengantar dari Perangkat Daerah',
                'type' => 'pdf',
                'max_size' => 10000,
                'category' => 'aktivasi'
            ]);
        }
    }
}
