<?php

namespace Modules\CertificateRecognition\database\seeders;

use Illuminate\Database\Seeder;
use Modules\CertificateRecognition\app\Models\CertificateRecognition;
use App\Models\User;

class CertificateRecognitionEnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $certificate_recognitions = CertificateRecognition::all();
        $users = User::all();

        $certificate_recognitions_enrollments = array();
        
        for ($i = 1; $i <= 100; $i++) {
            $certificate_recognitions_enrollments[] = array(
                "id" => $i,
                "certificate_recognition_id" => $certificate_recognitions->random()->id,
                "user_id" => $users->random()->id,
                "created_at" => now(),
                "updated_at" => now(),
            );
        }
        
        \DB::table('certificate_recognition_enrollments')->insert($certificate_recognitions_enrollments);
    }
}
