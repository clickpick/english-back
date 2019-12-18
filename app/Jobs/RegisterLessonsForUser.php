<?php

namespace App\Jobs;

use App\Phrase;
use App\User;
use App\Word;
use Illuminate\Support\Carbon;

class RegisterLessonsForUser extends Job
{

    protected $user;

    /**
     * Create a new job instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $learnedWordIds = $this->user->learnedWords()->get(['id'])->pluck('id');

        $wordToLearn = Word::whereNotIn('id', $learnedWordIds)->inRandomOrder()->first();

        if (!$wordToLearn) {
            return;
        }

        $phrases = $wordToLearn->phrases;

        $phrasesCount = $phrases->count();
        $available = $this->user->end_at - $this->user->start_at;

        $period = round($available / ($phrasesCount - 1));

        $i = $this->user->start_at;

        $phrases->each(function (Phrase $phrase) use (&$i, $period) {

            $minutes = $this->user->utc_offset ? ((-1) * $this->user->utc_offset + $i) : $i;
            $date = Carbon::today()->setMinutes($minutes);

            $this->user->lessons()->create([
                'phrase_id' => $phrase->id,
                'send_at' => $date
            ]);

            $i += $period;
        });

        $this->user->learnedWords()->attach($wordToLearn->id);
    }
}
