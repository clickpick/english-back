<?php

namespace App\Listeners;

use App\Events\UserCreated;

class FillPersonalDataFromVk
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
     * @param UserCreated $userCreated
     * @return void
     */
    public function handle(UserCreated $userCreated): void
    {
        $user = $userCreated->user;
        $user->fillPersonalInfoFromVk();
    }
}
