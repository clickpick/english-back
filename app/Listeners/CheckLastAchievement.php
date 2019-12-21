<?php

namespace App\Listeners;

use App\Achievement;
use App\Events\AchievementGot;
use App\Events\UserSaved;

class CheckLastAchievement
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
     * @param AchievementGot $achievementGot
     * @return void
     */
    public function handle(AchievementGot $achievementGot): void
    {
        $user = $achievementGot->user;

        $totalAchievementCount = Achievement::count();
        if ($user->achievements_count === $totalAchievementCount - 1) {

            $a = Achievement::whereSlug(Achievement::SLUG_COMPLETED)->first();

            if ($a) {
                $user->completeAchievement($a);
            }
        }
    }
}
