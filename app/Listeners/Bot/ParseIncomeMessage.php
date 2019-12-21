<?php

namespace App\Listeners\Bot;

use App\Achievement;
use App\Events\Bot\GotNewMessage;
use App\Jobs\Bot\SendNextLesson;
use App\Jobs\Bot\Start;
use App\Services\Bot\IncomeMessage;

class ParseIncomeMessage
{

    /**
     * @var IncomeMessage
     */
    private $incomeMessage;

//    private $actionRoutes = [];
//    private $commandRoutes = [];

    /**
     * Create the event listener.
     *
     */
//    public function __construct()
//    {
//        $this->actionRoutes = $this->mapActions();
//        $this->commandRoutes = $this->mapCommands();
//    }
//
//    private function mapActions()
//    {
//        return require base_path('routes/bot/actions.php');
//    }
//
//    private function mapCommands()
//    {
//        return require base_path('routes/bot/commands.php');
//    }

    /**
     * Handle the event.
     *
     * @param GotNewMessage $event
     * @return void
     */
    public function handle(GotNewMessage $event): void
    {
        $this->incomeMessage = $event->incomeMessage;

        $user = $this->incomeMessage->getUser();

        $a = Achievement::whereSlug(Achievement::SLUG_BOT)->first();
        if ($a) {
            $user->completeAchievement($a);
        }

        if ($user->is_ready) {
            $this->dispatch(SendNextLesson::class);
            return;
        }

        $this->fallbackAnswer();
    }

    private function fallbackAnswer(): void
    {
        $this->dispatch(new Start($this->incomeMessage));
    }


    private function dispatch($jobName): void
    {
        dispatch(new $jobName($this->incomeMessage));
    }
}
