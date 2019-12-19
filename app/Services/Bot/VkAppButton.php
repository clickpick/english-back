<?php

namespace App\Services\Bot;

class VkAppButton extends VkButton
{

    protected $label;
    protected $appId;
    protected $hash;
    protected $ownerId;

    public function __construct($label, $appId = null, $hash = '', $payload = [], $ownerId = null)
    {
        $this->type = 'open_app';
        $this->payload = $payload;
        $this->label = $label;
        $this->appId = $appId ?: (int)config('services.vk.app.id');
        $this->hash = $hash;
        $this->ownerId = $ownerId;
    }
}
