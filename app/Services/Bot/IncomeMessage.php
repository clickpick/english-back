<?php

namespace App\Services\Bot;

use App\User;

class IncomeMessage
{
    private $rawObject;
    private $message;
    private $clientInfo;

    private $user = null;

    public function __construct($object)
    {
        $this->rawObject = $object;
        $this->message = $object['message'];
        $this->clientInfo = $object['client_info'];
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getClientInfo()
    {
        return $this->clientInfo;
    }

    public function getVkUserId()
    {
        return $this->getMessage()['from_id'];
    }

    public function getText()
    {
        return $this->getMessage()['text'];
    }

    public function getAttachments()
    {
        return $this->getMessage()['attachments'];
    }

    public function getPayload()
    {
        return json_decode($this->getMessage()['payload'], true);
    }

//    public function hasCommand() {
//        return isset($this->getMessage()['payload']) && isset($this->getPayload()['command']);
//    }
//
//    public function getCommand() {
//        if (!$this->hasCommand()) {
//            return null;
//        }
//
//        return new VkCommand($this->getPayload()['command']['type'], $this->getPayload()['command']['params']);
//    }


    public function getUser(): User
    {
        if (!$this->user) {
            $this->user = User::getByVkId($this->getVkUserId());
        }

        return $this->user;
    }

    private function getButtonActions()
    {
        return $this->getClientInfo()['button_actions'];
    }

    public function supportTextButton(): bool
    {
        if (!$this->supportAnyKeyboard()) {
            return false;
        }

        return in_array('text', $this->getButtonActions(), true);
    }

    public function supportVkPayButton(): bool
    {
        if (!$this->supportAnyKeyboard()) {
            return false;
        }

        return in_array('vkpay', $this->getButtonActions(), true);
    }

    public function supportOpenAppButton(): bool
    {
        if (!$this->supportAnyKeyboard()) {
            return false;
        }

        return in_array('open_app', $this->getButtonActions(), true);
    }

    public function supportLocationButton(): bool
    {
        if (!$this->supportAnyKeyboard()) {
            return false;
        }

        return in_array('location', $this->getButtonActions(), true);
    }

    public function supportAnyKeyboard(): bool
    {
        return $this->supportKeyboard() || $this->supportInlineKeyboard();
    }

    public function supportKeyboard()
    {
        return $this->getClientInfo()['keyboard'] ?? false;
    }

    public function supportInlineKeyboard()
    {
        return $this->getClientInfo()['inline_keyboard'] ?? false;
    }

    public function hasGeo(): bool
    {
        return isset($this->getMessage()['geo']);
    }

    public function getLocation()
    {
        return $this->getMessage()['geo']['coordinates'];
    }
}
