<?php

namespace App\Services;

use App\Models\User;
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
                    'reason' => "{$user->name} sudah memiliki {$existingCount} sesi di {$monthName} dan mencoba menambahkan {$newCount} lagi. Maksimal 2 sesi per bulan diperbolehkan."
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
                    'reason' => "{$user->name} sudah memiliki {$existingCount} sesi di {$monthName} dan mencoba menambahkan {$newCount} lagi. Maksimal 2 sesi per bulan diperbolehkan."
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
            $query->where('mentee_id', $user->id)
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
                    'reason' => "{$user->name} sudah memiliki {$existingCount} sesi mentoring di {$monthName} dan mencoba menambahkan {$newCount} lagi. Maksimal 2 sesi per bulan diperbolehkan."
                ];
            }
        }

        return [
            'can_proceed' => true,
            'reason' => null
        ];
    }

    public function canAddCoachingSessionsForMultipleUsers($userIds, $requestSessions)
    {
        $results = [];
        $users = User::whereIn('id', $userIds)->get()->keyBy('id');

        // Get existing counts for all users at once
        $existingCounts = CoachingSession::whereHas('coaching', function ($query) {
            $query->where('status', Coaching::STATUS_CONSENSUS);
        })
            ->whereHas('coaching.coachees', function ($query) use ($userIds) {
                $query->whereIn('user_id', $userIds);
            })
            ->join('coachings', 'coaching_sessions.coaching_id', '=', 'coachings.id')
            ->join('coaching_users', 'coachings.id', '=', 'coaching_users.coaching_id')
            ->select(
                DB::raw("DATE_FORMAT(coaching_sessions.coaching_date, '%Y-%m') as month"),
                'coaching_users.user_id',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month', 'coaching_users.user_id')
            ->get()
            ->groupBy('user_id');

        // Count new sessions per month
        $newCounts = [];
        foreach ($requestSessions as $session) {
            $month = Carbon::parse($session)->format('Y-m');
            $newCounts[$month] = ($newCounts[$month] ?? 0) + 1;
        }

        // Check each user
        foreach ($users as $user) {
            $userExistingCounts = $existingCounts[$user->id] ?? collect();
            $userMonthlyCounts = [];

            foreach ($userExistingCounts as $record) {
                $userMonthlyCounts[$record->month] = $record->count;
            }

            foreach ($newCounts as $month => $newCount) {
                $existingCount = $userMonthlyCounts[$month] ?? 0;
                if (($existingCount + $newCount) > 2) {
                    $monthName = Carbon::createFromFormat('Y-m', $month)->format('F Y');
                    $results[$user->id] = [
                        'can_proceed' => false,
                        'reason' => "{$user->name} sudah memiliki {$existingCount} sesi di {$monthName} dan mencoba menambahkan {$newCount} lagi. Maksimal 2 sesi per bulan diperbolehkan."
                    ];
                    continue 2;
                }
            }

            $results[$user->id] = [
                'can_proceed' => true,
                'reason' => null
            ];
        }

        $errors = [];
        foreach ($results as $userId => $result) {
            if (!$result['can_proceed']) {
                $errors["user_$userId"] = $result['reason'];
            }
        }

        return [
            'is_valid' => empty($errors),
            'errors' => $errors,
            'detailed_results' => $results
        ];
    }

    public function canAddCoaching2SessionsForMultipleUsers($userIds, $requestSessions)
    {
        $results = [];
        $users = User::whereIn('id', $userIds)->get()->keyBy('id');

        // Get existing counts for all users at once
        $existingCounts = CoachingSession::whereHas('coaching', function ($query) {
            $query->where('status', Coaching::STATUS_PROCESS);
        })
            ->whereHas('coaching.joinedCoachees', function ($query) use ($userIds) {
                $query->whereIn('user_id', $userIds);
            })
            ->join('coachings', 'coaching_sessions.coaching_id', '=', 'coachings.id')
            ->join('coaching_users', 'coachings.id', '=', 'coaching_users.coaching_id')
            ->select(
                DB::raw("DATE_FORMAT(coaching_sessions.coaching_date, '%Y-%m') as month"),
                'coaching_users.user_id',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month', 'coaching_users.user_id')
            ->get()
            ->groupBy('user_id');

        // Count new sessions per month
        $newCounts = [];
        foreach ($requestSessions as $session) {
            $month = Carbon::parse($session)->format('Y-m');
            $newCounts[$month] = ($newCounts[$month] ?? 0) + 1;
        }

        // Check each user
        foreach ($users as $user) {
            $userExistingCounts = $existingCounts[$user->id] ?? collect();
            $userMonthlyCounts = [];

            foreach ($userExistingCounts as $record) {
                $userMonthlyCounts[$record->month] = $record->count;
            }

            foreach ($newCounts as $month => $newCount) {
                $existingCount = $userMonthlyCounts[$month] ?? 0;
                if (($existingCount + $newCount) > 2) {
                    $monthName = Carbon::createFromFormat('Y-m', $month)->format('F Y');
                    $results[$user->id] = [
                        'can_proceed' => false,
                        'reason' => "{$user->name} sudah memiliki {$existingCount} sesi di {$monthName} dan mencoba menambahkan {$newCount} lagi. Maksimal 2 sesi per bulan diperbolehkan."
                    ];
                    continue 2;
                }
            }

            $results[$user->id] = [
                'can_proceed' => true,
                'reason' => null
            ];
        }

        $errors = [];
        foreach ($results as $userId => $result) {
            if (!$result['can_proceed']) {
                $errors["user_$userId"] = $result['reason'];
            }
        }

        return [
            'is_valid' => empty($errors),
            'errors' => $errors,
            'detailed_results' => $results
        ];
    }

    public function canAddCoachingUpdateSessionsForMultipleUsers($userIds, $requestSessions)
    {
        $results = [];
        $users = User::whereIn('id', $userIds)->get()->keyBy('id');

        // Get existing counts for all users at once
        $existingCounts = CoachingSession::whereHas('coaching', function ($query) {
            $query->where('status', Coaching::STATUS_DRAFT);
        })
            ->whereHas('coaching.coachees', function ($query) use ($userIds) {
                $query->whereIn('user_id', $userIds);
            })
            ->join('coachings', 'coaching_sessions.coaching_id', '=', 'coachings.id')
            ->join('coaching_users', 'coachings.id', '=', 'coaching_users.coaching_id')
            ->select(
                DB::raw("DATE_FORMAT(coaching_sessions.coaching_date, '%Y-%m') as month"),
                'coaching_users.user_id',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month', 'coaching_users.user_id')
            ->get()
            ->groupBy('user_id');

        // Count new sessions per month
        $newCounts = [];
        foreach ($requestSessions as $session) {
            $month = Carbon::parse($session)->format('Y-m');
            $newCounts[$month] = ($newCounts[$month] ?? 0) + 1;
        }

        // Check each user
        foreach ($users as $user) {
            $userExistingCounts = $existingCounts[$user->id] ?? collect();
            $userMonthlyCounts = [];

            foreach ($userExistingCounts as $record) {
                $userMonthlyCounts[$record->month] = $record->count;
            }

            foreach ($newCounts as $month => $newCount) {
                $existingCount = $userMonthlyCounts[$month] ?? 0;
                if (($existingCount + $newCount) > 2) {
                    $monthName = Carbon::createFromFormat('Y-m', $month)->format('F Y');
                    $results[$user->id] = [
                        'can_proceed' => false,
                        'reason' => "{$user->name} sudah memiliki {$existingCount} sesi di {$monthName} dan mencoba menambahkan {$newCount} lagi. Maksimal 2 sesi per bulan diperbolehkan."
                    ];
                    continue 2;
                }
            }

            $results[$user->id] = [
                'can_proceed' => true,
                'reason' => null
            ];
        }

        $errors = [];
        foreach ($results as $userId => $result) {
            if (!$result['can_proceed']) {
                $errors["user_$userId"] = $result['reason'];
            }
        }

        return [
            'is_valid' => empty($errors),
            'errors' => $errors,
            'detailed_results' => $results
        ];
    }

    public function canAddMentoringSessionsForMultipleUsers($userIds, $requestSessions)
    {
        $results = [];

        // Get existing mentoring session counts for all users at once
        $existingCounts = MentoringSession::whereHas('mentoring', function ($query) use ($userIds) {
            $query->whereIn('mentee_id', $userIds)
                ->where('status', Mentoring::STATUS_PROCESS);
        })
            ->select(
                DB::raw("DATE_FORMAT(mentoring_date, '%Y-%m') as month"),
                'mentoring.mentee_id as user_id',
                DB::raw('COUNT(*) as count')
            )
            ->join('mentoring', 'mentoring_sessions.mentoring_id', '=', 'mentoring.id')
            ->groupBy('month', 'mentoring.mentee_id')
            ->get()
            ->groupBy('user_id');

        // Count new sessions per month
        $newCounts = [];
        foreach ($requestSessions as $session) {
            $month = Carbon::parse($session)->format('Y-m');
            $newCounts[$month] = ($newCounts[$month] ?? 0) + 1;
        }

        // Check each user
        foreach (User::whereIn('id', $userIds)->get() as $user) {
            $userExistingCounts = $existingCounts[$user->id] ?? collect();
            $userMonthlyCounts = [];

            foreach ($userExistingCounts as $record) {
                $userMonthlyCounts[$record->month] = $record->count;
            }

            foreach ($newCounts as $month => $newCount) {
                $existingCount = $userMonthlyCounts[$month] ?? 0;
                if (($existingCount + $newCount) > 2) {
                    $monthName = Carbon::createFromFormat('Y-m', $month)->format('F Y');
                    $results[$user->id] = [
                        'can_proceed' => false,
                        'reason' => "{$user->name} sudah memiliki {$existingCount} sesi mentoring di {$monthName} dan mencoba menambahkan {$newCount} lagi. Maksimal 2 sesi per bulan diperbolehkan."
                    ];
                    continue 2;
                }
            }

            $results[$user->id] = [
                'can_proceed' => true,
                'reason' => null
            ];
        }

        // Return format compatible with Laravel validation
        $errors = [];
        foreach ($results as $userId => $result) {
            if (!$result['can_proceed']) {
                $errors["user_$userId"] = $result['reason'];
            }
        }

        return [
            'is_valid' => empty($errors),
            'errors' => $errors,
            'detailed_results' => $results
        ];
    }
}
