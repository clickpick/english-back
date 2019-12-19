<?php

namespace App\Services;

use App\Events\Bot\GotMessagesAllowed;
use App\Events\Bot\GotMessagesDenied;
use App\Events\Bot\GotNewMessage;

class VkCallback {

    private $request;

    public const CONFIRMATION = 'confirmation';
    public const MESSAGE_NEW = 'message_new';
    public const MESSAGE_ALLOW = 'message_allow';
    public const MESSAGE_DENY = 'message_deny';

    public function __construct($request)
    {
        $this->request = $request;
    }

    private function getType() {
        return $this->request->type;
    }

    private function getObject() {
        return $this->request->object;
    }

    public function handle() {
        switch ($this->getType()) {
            case self::CONFIRMATION:
                return $this->confirmation();
                break;

            case self::MESSAGE_NEW:
                event(new GotNewMessage($this->getObject()));
                break;

            case self::MESSAGE_ALLOW:
                event(new GotMessagesAllowed($this->getObject()));
                break;
            case self::MESSAGE_DENY:
                event(new GotMessagesDenied($this->getObject()));
                break;

            default:
                abort(422, __('unknown type'));
        }

        return $this->successResponse();
    }

    private function confirmation() {
        return config('services.vk.group.confirmation');
    }

    private function successResponse(): string
    {
        return 'ok';
    }
}
