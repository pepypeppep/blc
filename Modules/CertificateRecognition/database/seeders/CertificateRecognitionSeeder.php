<?php

namespace Modules\CertificateRecognition\database\seeders;

use Illuminate\Database\Seeder;
use Modules\CertificateRecognition\app\Models\CertificateRecognition;
use App\Models\Instansi;
use Modules\CertificateBuilder\app\Models\CertificateBuilder;

class CertificateRecognitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $certificate_recognitions = array();
        
        // Statuses
        $statuses = [CertificateRecognition::STATUS_IS_DRAFT, CertificateRecognition::STATUS_VERIFICATION, CertificateRecognition::STATUS_REJECTED, CertificateRecognition::STATUS_PUBLISHED];

        // Approval States
        $approval_states = [CertificateRecognition::IS_APPROVED_PENDING, CertificateRecognition::IS_APPROVED_APPROVED, CertificateRecognition::IS_APPROVED_REJECTED];

        // Certificate Statuses
        $certificate_statuses = [CertificateRecognition::CERTIFICATE_STATUS_PENDING, CertificateRecognition::CERTIFICATE_STATUS_PROCESS, CertificateRecognition::CERTIFICATE_STATUS_FINISH];

        // Instansis
        $instansis = Instansi::all();

        // Cerificates
        $certificates = CertificateBuilder::all();

        $rejection_notes = [
            'Insufficient documentation provided',
            'Course requirements not met',
            'Incomplete submission',
            'Invalid certificate format',
            'Missing required signatures',
            'Expired supporting documents',
            'Incorrect course information',
            'Incomplete participant details',
            'Invalid institution credentials',
            'Missing verification documents'
        ];

        for ($i = 1; $i <= 100; $i++) {
            // First determine if it's approved or rejected
            $is_approved = rand(0, 1) ? CertificateRecognition::IS_APPROVED_APPROVED : CertificateRecognition::IS_APPROVED_REJECTED;
            
            // If rejected, set status to rejected and add notes
            if ($is_approved === CertificateRecognition::IS_APPROVED_REJECTED) {
                $status = CertificateRecognition::STATUS_REJECTED;
                $notes = $rejection_notes[array_rand($rejection_notes)];
                $certificate_status = CertificateRecognition::CERTIFICATE_STATUS_PENDING;
            } else {
                // If approved, randomly choose between verification and published
                $status = rand(0, 1) ? CertificateRecognition::STATUS_VERIFICATION : CertificateRecognition::STATUS_PUBLISHED;
                $notes = null;
                // If published, certificate should be in process or finish
                $certificate_status = $status === CertificateRecognition::STATUS_PUBLISHED 
                    ? (rand(0, 1) ? CertificateRecognition::CERTIFICATE_STATUS_PROCESS : CertificateRecognition::CERTIFICATE_STATUS_FINISH)
                    : CertificateRecognition::CERTIFICATE_STATUS_PENDING;
            }
            
            $start_date = date('Y-m-d H:i:s', strtotime("-" . rand(1, 365) . " days"));
            $end_date = date('Y-m-d H:i:s', strtotime($start_date . " +" . rand(1, 30) . " days"));
            
            $certificate_recognitions[] = array(
                "id" => $i,
                "instansi_id" => $instansis->random()->id,
                "certificate_id" => $certificates->random()->id,
                "name" => "Certificate of Completion #" . $i,
                "goal" => "To recognize the successful completion of course #" . $i,
                "competency" => "To demonstrate the knowledge and skills of course #" . $i,
                "indicator_of_success" => "To achieve the learning outcomes of course #" . $i,
                "activity_plan" => "To complete course #" . $i,
                "start_at" => $start_date,
                "end_at" => $end_date,
                "jp" => rand(1, 10),
                "status" => $status,
                "is_approved" => $is_approved,
                "certificate_status" => $certificate_status,
                "notes" => $notes,
            );
        }
        
        \DB::table('certificate_recognitions')->insert($certificate_recognitions);
    }
}
