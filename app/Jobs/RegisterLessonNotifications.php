<?php

namespace App\Jobs;

use App\Lesson;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class RegisterLessonNotifications extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Lesson::where('is_sent', false)
            ->whereBetween('send_at', [Carbon::now(), Carbon::now()->addMinutes(Lesson::MINUTES_BEFORE_REGISTER)])
            ->chunk(200, function (Collection $lessons) {
                $lessons->each(function (Lesson $lesson) {
                    $lesson->registerNotification();
                });
            });
    }
}
