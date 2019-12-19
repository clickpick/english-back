<?php

namespace App\Listeners\Bot;

use App\Events\Bot\GotMessagesAllowed;
use App\User;

class SetMessagesAllowed
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
     * @param GotMessagesAllowed $event
     * @return void
     */
    public function handle(GotMessagesAllowed $event)
    {
        $vkUserId = $event->object['user_id'];
        $user = User::getByVkId($vkUserId);
        $user->enableMessages();
    }
}
