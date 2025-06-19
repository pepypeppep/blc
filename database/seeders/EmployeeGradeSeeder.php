<?php

namespace Database\Seeders;

use App\Models\EmployeeGrade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grades = [
            "Juru (Ia)",
            "Juru Muda Tingkat I (Ib)",
            "Juru (Ic)",
            "Juru Tingkat I (Id)",
            "Pengatur (IIa)",
            "Pengatur Muda Tingkat I (IIb)",
            "Pengatur (IIc)",
            "Pengatur Tingkat I (IId)",
            "Penata (IIIa)",
            "Penata Muda Tingkat I (IIIb)",
            "Penata (IIIc)",
            "Penata Tingkat I (IIId)",
            "Pembina (IVa)",
            "Pembina Tingkat I (IVb)",
            "Pembina Utama Muda (IVc)",
            "Pembina Utama Madya (IVd)",
            "Pembina Utama (IVe)"
        ];

        foreach ($grades as $key => $grade) {
            EmployeeGrade::create([
                'name' => $grade,
                'order' => $key + 1
            ]);
        }
    }
}
