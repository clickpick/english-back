<?php

namespace App\Services;

use App\Jobs\DisableNotificationForVkUser;
use App\Services\Bot\OutgoingMessage;
use CURLFile;
use Illuminate\Support\Collection;
use VK\Client\VKApiClient;
use VK\Exceptions\Api\VKApiMessagesDenySendException;
use VK\Exceptions\VKApiException;
use VK\Exceptions\VKClientException;

class VkClient
{
    protected $client;
    private $accessToken;

    public const GROUP_TOKEN = 'group';
    public const APP_TOKEN = 'app';

    public const API_VERSION = '5.103';
    public const LANG = 'ru';
    public const API_HOST = 'https://api.vk.com/method';

    public function __construct($tokenType = self::APP_TOKEN)
    {
        $this->client = new VKApiClient(self::API_VERSION, 'ru');
        $this->accessToken = config('services.vk.' . $tokenType . '.service_key');
    }

    public function getUsers($ids, array $fields)
    {

        $isFew = is_array($ids);

        $response = $this->client->users()->get($this->accessToken, [
            'user_ids' => $isFew ? $ids : [$ids],
            'fields' => $fields,
        ]);

        return $isFew ? $response : $response[0];
    }

    public function sendMessage(OutgoingMessage $outgoingMessage): void
    {
        $this->client->messages()->send($this->accessToken, $outgoingMessage->toVkRequest());
    }

    public function uploadAudioDocs($storagePath, $peerId)
    {
        try {
            $uploadUrl = $this->client->docs()->getMessagesUploadServer($this->accessToken, [
                'type' => 'audio_message',
                'peer_id' => $peerId
            ])['upload_url'];
        } catch (\Exception $e) {
            dd('here');
        }

        $curl = curl_init($uploadUrl);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, array('file' => new CURLfile($storagePath)));
        $json = curl_exec($curl);
        $error = curl_error($curl);
        if ($error) {
            throw new \RuntimeException("Failed {$uploadUrl} request");
        }
        curl_close($curl);
        $file = json_decode($json, true)['file'];

        $response = $this->client->docs()->save($this->accessToken, [
            'file' => $file,
            'title' => 'audio message'
        ]);

        return $response['audio_message'];
    }

    public function sendPushes(Collection $ids, $message, $fragment = '') {

        $ids->chunk(100)->each(function(Collection $chunkedIds) use ($message, $fragment) {

            try {
                $result = $this->client->notifications()->sendMessage($this->accessToken, [
                    'user_ids' => $chunkedIds->implode(','),
                    'message' => $message,
                    'fragment' => $fragment
                ]);
            } catch (\Exception $e) {
                return;
            }


            collect($result)->filter(function ($item) {
                return !$item['status'];
            })->filter(function( $item) {
                return $item['error']['code'] === 1;
            })->each(function($item) {
                dispatch(new DisableNotificationForVkUser($item['user_id']));
            });
        });
    }
}
