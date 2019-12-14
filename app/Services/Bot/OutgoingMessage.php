<?php

namespace App\Services\Bot;

use App\Services\VkClient;
use App\User;

class OutgoingMessage
{
    private $message;

    /**
     * @var User|null
     */
    private $recipient;
    private $randomId;
    private $attachments = [];

    private $model;

    /**
     * @var VkKeyboard|null
     */
    private $vkKeyboard = null;

    public function __construct($message = null)
    {
        $this->message = $message;
    }

    public function setRecipient(User $user): OutgoingMessage
    {
        $this->recipient = $user;
        return $this;
    }

    public function setKeyboard(VkKeyboard $vkKeyboard): OutgoingMessage
    {
        $this->vkKeyboard = $vkKeyboard;
        return $this;
    }

    public function getRecipient(): ?User
    {
        return $this->recipient;
    }

    private function setRandomId($randomId): OutgoingMessage
    {
        $this->randomId = $randomId;
        return $this;
    }

    public function toVkRequest(): array
    {
        $request = [
            'random_id' => $this->randomId,
            'peer_id' => $this->recipient->id,
            'message' => $this->message
        ];

        if ($this->vkKeyboard) {
            $request['keyboard'] = json_encode($this->vkKeyboard->toArray(), JSON_UNESCAPED_UNICODE);
        }

        if (!empty($this->attachments)) {
            $request['attachment'] = $this->attachments;
//            $request['attachment'] = implode(',', $this->attachments);
        }

        return $request;
    }

    public function createModel(): void
    {
        $this->model = $this->recipient->vkMessages()->create([
            'message' => $this->message,
            'params' => []
        ]);

        $this->setRandomId($this->model->id);
    }

    public function addAttachment(string $attachment): OutgoingMessage
    {
        $this->attachments[] = $attachment;
        return $this;
    }

    public function addAudio($storagePath): OutgoingMessage
    {
        $response = (new VkClient(VkClient::GROUP_TOKEN))->uploadAudioDocs($storagePath, $this->getRecipient()->id);
        $this->addAttachment("doc{$response['owner_id']}_{$response['id']}");
        return $this;
    }
}
