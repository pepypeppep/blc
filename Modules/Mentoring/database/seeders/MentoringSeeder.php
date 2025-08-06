<?php

namespace Modules\Mentoring\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Article\app\Models\Article;
use Modules\Mentoring\app\Models\Mentoring;
use Modules\Order\app\Models\Enrollment;
use Faker\Factory as Faker;

class MentoringSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $statuses = [Mentoring::STATUS_REJECT, Mentoring::STATUS_PROCESS, Mentoring::STATUS_SUBMISSION, Mentoring::STATUS_DRAFT, Mentoring::STATUS_VERIFICATION];
        for ($i = 0; $i < 200; $i++) {
            $status = $statuses[array_rand($statuses)];
            Mentoring::create([
                'title' => 'Mentoring ' . $i,
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quasi.',
                'purpose' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quasi.',
                'total_session' => 1,
                'mentor_availability_letter' => 'mentoring/document/2025/06/mentoring-availability-letter.pdf',
                'status' => $status,
                'reason' => $status == Mentoring::STATUS_REJECT ? 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quasi.' : '',
                'mentor_id' => $users->random()->id,
                'mentee_id' => $users->random()->id,
            ]);
        }
    }
}
