<?php

namespace App\Jobs;

use App\Achievement;
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

        if ($this->user->created_at->diffInDays(Carbon::now(), true) >= 30) {
            $a = Achievement::whereSlug(Achievement::SLUG_MONTH)->first();

            if ($a) {
                $this->user->completeAchievement($a);
            }
        }


        $learnedWordIds = $this->user->learnedWords()->get(['id'])->pluck('id');


        $levelId = $this->user->level_id ?: 2;

        $wordToLearn = Word::whereNotIn('id', $learnedWordIds)
            ->where('level_id', $levelId)
            ->inRandomOrder()
            ->first();

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
