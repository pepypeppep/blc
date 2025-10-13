<?php

namespace Modules\Coaching\app\Policies;

use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Coaching\app\Models\Coaching;

class CoachingPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function sendToBantara(Admin $user, Coaching $coaching)
    {
        return $coaching->status === Coaching::STATUS_VERIFICATION;
    }

    // choose certificate
    public function chooseCertificate(Admin $user, Coaching $coaching)
    {
        return $coaching->status === Coaching::STATUS_VERIFICATION;
    }
}
