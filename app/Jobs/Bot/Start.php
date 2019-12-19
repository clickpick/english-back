<?php

namespace App\Jobs\Bot;


use App\Services\Bot\OutgoingMessage;
use App\Services\Bot\VkAppButton;
use App\Services\Bot\VkKeyboard;

class Start extends VkBotJob
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $message = 'Зайди в приложение, чтобы начать учить английский!';

        $outgoingMessage = new OutgoingMessage($message);

        $openAppBtn = new VkAppButton('Открыть приложение');

        $keyboard = new VkKeyboard();
        $keyboard->addButton($openAppBtn);

        $outgoingMessage->setKeyboard($keyboard);


        $this->user->sendVkMessage($outgoingMessage);
    }
}
