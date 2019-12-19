<?php

namespace App\Providers;

use App\Events\Bot\GotMessagesAllowed;
use App\Events\Bot\GotMessagesDenied;
use App\Events\Bot\GotNewMessage;
use App\Events\PhraseCreated;
use App\Events\UserCreated;
use App\Listeners\Bot\ParseIncomeMessage;
use App\Listeners\Bot\SetMessagesAllowed;
use App\Listeners\Bot\SetMessagesDenied;
use App\Listeners\CreateAudioForPhrase;
use App\Listeners\Bot\EnableMessages;
use App\Listeners\FillPersonalDataFromVk;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UserCreated::class => [
            FillPersonalDataFromVk::class
        ],

        PhraseCreated::class => [
            CreateAudioForPhrase::class
        ],


        GotNewMessage::class => [
            EnableMessages::class,
            ParseIncomeMessage::class
        ],
        GotMessagesAllowed::class => [
            SetMessagesAllowed::class
        ],
        GotMessagesDenied::class => [
            SetMessagesDenied::class
        ],
    ];
}
