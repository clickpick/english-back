<?php

namespace App\Jobs;

use App\User;

class DisableNotificationForVkUser extends Job
{
    protected $vkUserId;

    /**
     * Create a new job instance.
     *
     * @param $vkUserId
     */
    public function __construct($vkUserId)
    {
        $this->vkUserId = $vkUserId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::getByVkId($this->vkUserId);

        $user->disableNotifications();
    }
}
