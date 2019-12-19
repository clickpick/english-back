<?php

namespace App\Events\Bot;


use App\Events\Event;
use App\Services\Bot\IncomeMessage;

class GotNewMessage extends Event
{

    public $incomeMessage;

    /**
     * Create a new event instance.
     *
     * @param $object
     */
    public function __construct($object)
    {
        $this->incomeMessage = new IncomeMessage($object);
    }
}
