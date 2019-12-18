<?php

namespace App\Jobs;

use App\Lesson;

class SendLessonNotification extends Job
{
    public $deleteWhenMissingModels = true;

    /**
     * @var Lesson
     */
    protected $lesson;

    /**
     * Create a new job instance.
     *
     * @param Lesson $lesson
     */
    public function __construct(Lesson $lesson)
    {
        $this->lesson = $lesson;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->lesson->is_sent) {
            return;
        }

        $this->lesson->is_learned = true;
        $this->lesson->save()

        $user = $this->lesson->user;
        $phrase = $this->lesson->phrase;
        $user->sendPhraseMessage($phrase);

        $this->lesson->is_sent = true;
        $this->lesson->save();
    }
}
