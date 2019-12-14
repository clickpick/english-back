<?php

namespace App\Services\Bot;

class VkLocationButton extends VkButton {

    public function __construct($payload = [])
    {
        $this->type = 'location';
        $this->payload = $payload;
    }
}
