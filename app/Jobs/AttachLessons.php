<?php

namespace App\Jobs;

use App\User;
use Illuminate\Database\Eloquent\Collection;

class AttachLessons extends Job
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
        User::where('is_active', true)->chunk(200, function (Collection $users) {
            $users->each(function (User $user) {
                dispatch(new RegisterLessonsForUser($user));
            });
        });
    }
}
