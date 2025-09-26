<?php

namespace Modules\CertificateRecognition\database\seeders;

use Illuminate\Database\Seeder;

class CertificateRecognitionDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            CompetencyDevelopmentSeeder::class,
            // CertificateRecognitionSeeder::class,
            // CertificateRecognitionEnrollmentSeeder::class,
        ]);
    }
}
