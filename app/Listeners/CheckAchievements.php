<?php

namespace App\Listeners;

use App\Achievement;
use App\Events\UserSaved;

class CheckAchievements
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param UserSaved $userCreated
     * @return void
     */
    public function handle(UserSaved $userCreated): void
    {
        $user = $userCreated->user;

        if ($user->is_ready) {
            $a = Achievement::whereSlug(Achievement::SLUG_START)->first();

            if ($a) {
                $user->completeAchievement($a);
            }
        }

        if ($user->notifications_are_enabled) {
            $a = Achievement::whereSlug(Achievement::SLUG_NOTIFIED)->first();

            if ($a) {
                $user->completeAchievement($a);
            }
        }

        if ($user->level_id === 4) {
            $a = Achievement::whereSlug(Achievement::SLUG_CLEVER)->first();

            if ($a) {
                $user->completeAchievement($a);
            }
        }
    }
}
