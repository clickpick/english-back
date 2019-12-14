<?php

namespace App\Services;

use Illuminate\Support\Collection;
use VK\Client\VKApiClient;
use VK\Client\VKApiRequest;

class VkClient
{
    protected $client;
    private $accessToken;

    public const API_VERSION = '5.103';
    public const LANG = 'ru';
    public const API_HOST = 'https://api.vk.com/method';

    public function __construct($accessToken = null)
    {
        $this->client = new VKApiClient(self::API_VERSION, self::LANG);

        $this->accessToken = $accessToken ?? config('services.vk.app.service_key');
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
}
