<?php

namespace App\Jobs\Bot;


use App\Services\Bot\OutgoingMessage;
use App\Services\Bot\VkAppButton;
use App\Services\Bot\VkKeyboard;
use Carbon\Carbon;

class SendNextLesson extends VkBotJob
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->incomeMessage->getUser();
        $nextLessonAt = $user->getNextLessonAt();

        $appButton = new VkAppButton('Открыть приложение');
        $keyboard = new VkKeyboard();
        $keyboard->addButton($appButton);

        if (!$nextLessonAt) {
            $om = new OutgoingMessage('Зайти в приложение, чтобы начать уроки!');
            $om->setKeyboard($keyboard);

            $this->user->sendVkMessage($om);
            return;
        }

        if ($nextLessonAt->isSameDay(Carbon::now())) {
            $date = $this->user->getLocalDate($nextLessonAt)->locale('ru')->diffForHumans();
            $message = "Новое слово будет {$date}";
        }elseif ($nextLessonAt->isTomorrow()) {
            $date = $this->user->getLocalDate($nextLessonAt)->format('H:i');
            $message = "Новое слово будет завтра в {$date}";
        }else {
            $date = $this->user->getLocalDate($nextLessonAt)->format('j F H:i');
            $message = "Новое слово будет {$date}";
        }



        $om = new OutgoingMessage($message);
        $om->setKeyboard($keyboard);

        $this->user->sendVkMessage($om);
    }
}
