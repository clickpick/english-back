<?php

namespace App\Listeners\Bot;

use App\Events\Bot\GotNewMessage;

class EnableMessages
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
     * @param GotNewMessage $event
     * @return void
     */
    public function handle(GotNewMessage $event)
    {
        $incomeMessage = $event->incomeMessage;

        $user = $incomeMessage->getUser();
        $user->enableMessages();
    }
}
