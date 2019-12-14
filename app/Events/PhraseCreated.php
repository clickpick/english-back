<?php

namespace App\Events;

use App\Phrase;

class PhraseCreated extends Event
{

    public $phrase;

    /**
     * Create a new event instance.
     *
     * @param Phrase $phrase
     */
    public function __construct(Phrase $phrase)
    {
        $this->phrase = $phrase;
    }
}
