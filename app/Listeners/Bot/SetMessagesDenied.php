<?php

namespace App\Listeners\Bot;

use App\Events\Bot\GotMessagesDenied;
use App\User;

class SetMessagesDenied
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
     * @param GotMessagesDenied $event
     * @return void
     */
    public function handle(GotMessagesDenied $event)
    {
        $vkUserId = $event->object['user_id'];
        $user = User::getByVkId($vkUserId);
        $user->disableMessages();
    }
}
