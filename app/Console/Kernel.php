<?php

namespace App\Console;

use App\Commands\StorageLinkCommand;
use App\Jobs\AttachLessons;
use App\Jobs\RegisterLessonNotifications;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        StorageLinkCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new AttachLessons())->dailyAt(0);
        $schedule->job(new RegisterLessonNotifications())->everyFiveMinutes();
    }
}
