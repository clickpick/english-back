<?php

namespace App\Providers;

use App\Events\PhraseCreated;
use App\Events\UserCreated;
use App\Listeners\CreateAudioForPhrase;
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
        ]
    ];
}
