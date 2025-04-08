<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Course;
use App\Models\CourseReview;
use Modules\Badges\app\Models\Badge;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateUserBadgeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userIds;

    /**
     * Create a new job instance.
     */
    public function __construct($userIds)
    {
        $this->userIds = $userIds;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        foreach ($this->userIds as $userId) {
            $user = User::find($userId);

            if (!$user) {
                continue;
            }

            $this->checkAndUpdateBadges($user);
        }
    }

    protected function checkAndUpdateBadges(User $user)
    {
        $badges = Badge::where('status', 1)->get();

        foreach ($badges as $badge) {
            $parts = explode('_', $badge->key);
            array_splice($parts, -2);
            $badgeCategory = implode('_', $parts);

            $categoryCount = match ($badgeCategory) {
                'course_rating' => CourseReview::where('user_id', $user->id)->count(), //badge untuk jumlah pelatihan direview
                'course_enroll' => $user->enrollments()->count(), //badge untuk jumlah pelatihan diikuti
                'course_count' => Course::where('instructor_id', $user->id)->count(), //badge untuk jumlah pelatihan dibuat
                default => 0,
            };

            if ($categoryCount >= $badge->condition_from && $categoryCount <= $badge->condition_to) {
                $existingBadge = $user->badges()
                    ->wherePivot('category', $badgeCategory)
                    ->first();

                if ($existingBadge) {
                    if ($existingBadge->id !== $badge->id) {
                        $user->badges()->detach($existingBadge->id);
                        $user->badges()->attach($badge->id, ['category' => $badgeCategory]);
                    }
                } else {
                    $user->badges()->attach($badge->id, ['category' => $badgeCategory]);
                }
            } else {
                $maxBadge = Badge::where('key', 'like', "$badgeCategory%")
                    ->orderByDesc('condition_to')
                    ->first();

                if ($maxBadge && $categoryCount >= $maxBadge->condition_from) {
                    $existingBadge = $user->badges()
                        ->wherePivot('category', $badgeCategory)
                        ->first();
            
                    if (!$existingBadge || $existingBadge->id !== $maxBadge->id) {
                        $user->badges()->detach($existingBadge?->id);
                        $user->badges()->attach($maxBadge->id, ['category' => $badgeCategory]);
                    }
                } else {
                    $user->badges()->wherePivot('category', $badgeCategory)->detach($badge->id);
                }
            }
        }
    }
}
