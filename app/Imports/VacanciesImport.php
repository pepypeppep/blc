<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\PendidikanLanjutan\app\Models\Study;
use Modules\PendidikanLanjutan\app\Models\Vacancy;
use Modules\PendidikanLanjutan\app\Models\VacancyMasterAttachment;
use Modules\PendidikanLanjutan\app\Models\VacancyAttachment;
use App\Enums\EmploymentGrade;
use App\Models\EmployeeGrade;
use App\Models\Instansi;
use Modules\PendidikanLanjutan\app\Models\VacancyDetail;

class VacanciesImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public int $imported = 0;
    public int $skipped = 0;

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

        $employeeGrade = EmployeeGrade::where('name', $row['pangkatgolongan'])->first();
        if (!$employeeGrade) {
            throw new \Exception("Invalid pangkat/golongan: {$row['pangkatgolongan']}");
        }

        // Dump the row for debugging purposes
        // dump($row);

        $study = Study::firstOrCreate([
            'name' => $row['program_studi'],
            'university' => $row['universitas'],
            'grade' => $row['jenjang'],
        ]);
        $studyId = $study->id ?? 1;
        $instansi = Instansi::where('name', $row['instansi'])->first();
        $employeeGrade = EmployeeGrade::where('name', $row['pangkatgolongan'])->first();

        $existing = Vacancy::where('study_id', $studyId)
            ->where('instansi_id', $instansi->id)
            ->where('education_level', $row['jenjang'])
            ->where('employee_grade_id', $employeeGrade->id)
            ->where('year', $row['tahun'])
            ->whereHas('details', function ($query) use ($row) {
                $query->where('employment_status', $row['status_kepegawaian'])
                    ->where('cost_type', $row['jenis_biaya'])
                    ->where('age_limit', $row['batas_usia']);
            })
            ->first();

        if ($existing) {
            // data duplikat
            $this->skipped++;
            return null;
        }

        $this->imported++;

        $vacancy = Vacancy::firstOrCreate([
            'study_id' => $studyId,
            'instansi_id' => $instansi->id,
            'education_level' => $row['jenjang'],
            'employee_grade_id' => $employeeGrade->id,
            'formation' => $row['jumlah_formasi'],
            'year' => $row['tahun'],
        ], [
            'description' => $row['catatan'] ?? null, // Assuming 'catatan' is optional
        ]);

        $vacancy->details()->create([
            'employment_status' => $row['status_kepegawaian'],
            'cost_type' => $row['jenis_biaya'],
            'age_limit' => $row['batas_usia'],
        ]);

        $masterAttachments = VacancyMasterAttachment::get();
        foreach ($masterAttachments as $attachment) {
            VacancyAttachment::create([
                'vacancy_id' => $vacancy->id,
                'name' => $attachment->name,
                'type' => 'pdf',
                'max_size' => 10000,
                'category' => 'syarat',
                'is_required' => 1
            ]);
        }

        return $vacancy;
    }
}
