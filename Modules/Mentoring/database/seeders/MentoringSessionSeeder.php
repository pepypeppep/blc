<?php

namespace Modules\Mentoring\Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Modules\Article\app\Models\Article;
use Modules\Mentoring\app\Models\Mentoring;
use Modules\Mentoring\app\Models\MentoringSession;
use Modules\Order\app\Models\Enrollment;
use Faker\Factory as Faker;

class MentoringSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mentorings = Mentoring::all();
        foreach ($mentorings as $key => $mentoring) {
            for ($j = 0; $j < rand(1, 7); $j++) {
                MentoringSession::create([
                    'mentoring_id' => $mentoring->id,
                    'mentoring_date' => Carbon::now()->addDays($j + 1),
                    'status' => 'pending',
                ]);
            }
        }
    }
}
