<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Coaching\app\Models\Coaching;
use Modules\Mentoring\app\Models\Mentoring;
use Modules\Coaching\app\Models\CoachingSession;
use Modules\Mentoring\app\Models\MentoringSession;

class CoachingMentoringSessionChecker
{
    public function canAddCoachingSessions($user, $requestSessions)
    {
        // Get existing session counts per month
        $existingCounts = CoachingSession::whereHas('coaching', function ($query) use ($user) {
            $query->where('status', Coaching::STATUS_CONSENSUS);
        })
            ->whereHas('coaching.coachees', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->select(DB::raw("DATE_FORMAT(coaching_date, '%Y-%m') as month"), DB::raw('COUNT(*) as count'))
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Count new sessions per month
        $newCounts = [];
        foreach ($requestSessions as $session) {
            $month = Carbon::parse($session)->format('Y-m');
            $newCounts[$month] = ($newCounts[$month] ?? 0) + 1;
        }

        // Check if any month would exceed 2 sessions
        foreach ($newCounts as $month => $newCount) {
            $existingCount = $existingCounts[$month] ?? 0;
            if (($existingCount + $newCount) > 2) {
                $monthName = Carbon::createFromFormat('Y-m', $month)->format('F Y');
                return [
                    'can_proceed' => false,
                    'reason' => "Anda sudah memiliki {$existingCount} sesi di {$monthName} dan mencoba menambahkan {$newCount} lagi. Maksimal 2 sesi per bulan diperbolehkan."
                ];
            }
        }

        return [
            'can_proceed' => true,
            'reason' => null
        ];
    }
    public function canAddCoaching2Sessions($user, $requestSessions)
    {
        // Get existing session counts per month
        $existingCounts = CoachingSession::whereHas('coaching', function ($query) use ($user) {
            $query->where('status', Coaching::STATUS_PROCESS);
        })
            ->whereHas('coaching.joinedCoachees', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->select(DB::raw("DATE_FORMAT(coaching_date, '%Y-%m') as month"), DB::raw('COUNT(*) as count'))
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Count new sessions per month
        $newCounts = [];
        foreach ($requestSessions as $session) {
            $month = Carbon::parse($session)->format('Y-m');
            $newCounts[$month] = ($newCounts[$month] ?? 0) + 1;
        }

        // Check if any month would exceed 2 sessions
        foreach ($newCounts as $month => $newCount) {
            $existingCount = $existingCounts[$month] ?? 0;
            if (($existingCount + $newCount) > 2) {
                $monthName = Carbon::createFromFormat('Y-m', $month)->format('F Y');
                return [
                    'can_proceed' => false,
                    'reason' => "Anda sudah memiliki {$existingCount} sesi di {$monthName} dan mencoba menambahkan {$newCount} lagi. Maksimal 2 sesi per bulan diperbolehkan."
                ];
            }
        }

        return [
            'can_proceed' => true,
            'reason' => null
        ];
    }

    public function canAddMentoringSessions($user, $requestSessions)
    {
        // Get existing mentoring session counts per month
        $existingCounts = MentoringSession::whereHas('mentoring', function ($query) use ($user) {
            $query->where('mentee', $user->id)
                ->where('status', Mentoring::STATUS_PROCESS);
        })
            ->select(DB::raw("DATE_FORMAT(mentoring_date, '%Y-%m') as month"), DB::raw('COUNT(*) as count'))
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Count new sessions per month
        $newCounts = [];
        foreach ($requestSessions as $session) {
            $month = Carbon::parse($session)->format('Y-m');
            $newCounts[$month] = ($newCounts[$month] ?? 0) + 1;
        }

        // Check if any month would exceed 2 sessions
        foreach ($newCounts as $month => $newCount) {
            $existingCount = $existingCounts[$month] ?? 0;
            if (($existingCount + $newCount) > 2) {
                $monthName = Carbon::createFromFormat('Y-m', $month)->format('F Y');
                return [
                    'can_proceed' => false,
                    'reason' => "Anda sudah memiliki {$existingCount} sesi mentoring di {$monthName} dan mencoba menambahkan {$newCount} lagi. Maksimal 2 sesi per bulan diperbolehkan."
                ];
            }
        }

        return [
            'can_proceed' => true,
            'reason' => null
        ];
    }
}
