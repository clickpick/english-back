<?php

namespace App\Listeners;

use App\Events\ExampleEvent;
use App\Events\PhraseCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateAudioForPhrase
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
     * @param PhraseCreated $phraseCreated
     * @return void
     */
    public function handle(PhraseCreated $phraseCreated)
    {
        $phrase = $phraseCreated->phrase;

        $phrase->createAudio();
    }
}
