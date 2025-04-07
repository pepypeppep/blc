<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Jobs\UpdateUserBadgeJob;

class UserBadgeUpdated
{
    use Dispatchable, SerializesModels;

    public $userIds;

    public function __construct($userIds)
    {
        $this->userIds = $userIds;
        
        UpdateUserBadgeJob::dispatch($this->userIds);
    }
}
