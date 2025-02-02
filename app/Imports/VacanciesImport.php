<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\PendidikanLanjutan\app\Models\Study;
use Modules\PendidikanLanjutan\app\Models\Vacancy;

class VacanciesImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Check if the row is empty
        if (empty(array_filter($row))) {
            return null;
        }

        // Check if required fields are present and not empty
        if (empty($row['jenjang']) || empty($row['pangkatgolongan']) || empty($row['status_kepegawaian']) || empty($row['jenis_biaya']) || empty($row['jumlah_formasi']) || empty($row['batas_usia']) || empty($row['tahun'])) {
            return null;
        }

        // Dump the row for debugging purposes
        dump($row);

        return new Vacancy([
            'study_id' => Study::firstOrCreate(['name' => $row['program_studi']])->id ?? 1,
            'education_level' => $row['jenjang'],
            'employment_grade' => $row['pangkatgolongan'],
            'employment_status' => $row['status_kepegawaian'],
            'cost_type' => $row['jenis_biaya'],
            'formation' => $row['jumlah_formasi'],
            'age_limit' => $row['batas_usia'],
            'year' => $row['tahun'],
            'description' => $row['catatan'] ?? null, // Assuming 'catatan' is optional
        ]);
    }
}
