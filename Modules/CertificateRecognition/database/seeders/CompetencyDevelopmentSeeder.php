<?php

namespace Modules\CertificateRecognition\database\seeders;

use Illuminate\Database\Seeder;
use Modules\CertificateRecognition\app\Models\CompetencyDevelopment;

class CompetencyDevelopmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);

        CompetencyDevelopment::create([
            'name' => 'Pelatihan Kepemimpinan Struktural',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        CompetencyDevelopment::create([
            'name' => 'Pelatihan Teknis',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        CompetencyDevelopment::create([
            'name' => 'Pelatihan Sosial Kultural',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        CompetencyDevelopment::create([
            'name' => 'Pelatihan Dasar CPNS',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        CompetencyDevelopment::create([
            'name' => 'Orientasi PPPK',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        CompetencyDevelopment::create([
            'name' => 'Pengembangan Kompetensi Lainnya',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
