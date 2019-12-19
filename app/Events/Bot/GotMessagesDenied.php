<?php

namespace App\Events\Bot;

use App\Events\Event;

class GotMessagesDenied extends Event
{
    public $object;

    /**
     * Create a new event instance.
     *
     * @param $object
     */
    public function __construct($object)
    {
        $this->object = $object;
    }
}
