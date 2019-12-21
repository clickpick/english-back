<?php

namespace App\Events;

use App\Achievement;
use App\User;

class AchievementGot extends Event
{

    public $user;
    public $achievement;

    /**
     * Create a new event instance.
     *
     * @param Achievement $achievement
     * @param User $user
     */
    public function __construct(Achievement $achievement, User $user)
    {
        $this->user = $user;
        $this->achievement = $achievement;
    }
}
