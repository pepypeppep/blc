<?php

namespace Modules\PendidikanLanjutan\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PendidikanLanjutan\app\Models\VacancyMasterReportFiles;

class VacancyMasterReportFileSeeder extends Seeder
{
    public function run()
    {
        $reports = [
            'Semester 1',
            'Semester 2',
            'Semester 3',
            'Semester 4',
            'Semester 5',            
            'Semester 6',            
            'Semester 7',            
            'Semester 8',
            'Laporan Selesai Studi'
        ];

        foreach ($reports as $key => $report) {
            VacancyMasterReportFiles::firstOrCreate([
                'name' => $report
            ]);
        }
    }
}
