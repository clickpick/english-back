<?php

namespace App\Guards;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class VkGuard
{

    public const CACHE_PREFIX = 'auth_';

    public function __invoke(Request $request)
    {
        $vkParams = $request->header('vk-params');

        $cachedVkId = $this->fromCache($vkParams);
        if ($cachedVkId) {
            return $this->resolveUserById($cachedVkId);
        }

        $params = $this->validate($vkParams);
        $this->checkSign($params);

        Cache::put(self::CACHE_PREFIX . $vkParams, $params['vk_user_id'], Carbon::now()->addMinutes(15));

        return $this->getUser($params);
    }

    public function fromCache($vkParams)
    {
        if (!Cache::has(self::CACHE_PREFIX . $vkParams)) {
            return null;
        }

        return Cache::get(self::CACHE_PREFIX . $vkParams, null);
    }

    private function validate($params)
    {
        if (!$params) {
            abort(403, 'required Vk-Params header');
        }

        $params = json_decode(base64_decode($params), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            abort(403, 'invalid json');
        }

        Validator::make($params, [
            'vk_user_id' => 'required|integer',
            'utc_offset' => 'required|integer',
            'vk_are_notifications_enabled' => 'required|boolean',
            'sign' => 'required|string'
        ])->validate();

        return $params;
    }

    private function getSecret()
    {
        return config('services.vk.app.secret');
    }

    private function checkSign($params): void
    {
        if (app()->environment() !== 'production') {
            return;
        }

        $usefulParams = $this->collectUsefulParams($params);

        /* Формируем строку вида "param_name1=value&param_name2=value"*/
        $sign_params_query = $usefulParams->map(static function ($value, $key) {
            return "{$key}=$value";
        })->join('&');

        /* Получаем хеш-код от строки, используя защищеный ключ приложения. Генерация на основе метода HMAC. */
        $sign = rtrim(strtr(base64_encode(hash_hmac(
            'sha256', $sign_params_query, $this->getSecret(), true
        )), '+/', '-_'), '=');

        if (!($sign === $params['sign'])) {
            abort(403, 'Bad sign');
        }
    }

    private function collectUsefulParams($params): Collection
    {
        return collect($params)->map(static function ($param) {
            return $param ?? '';
        })->filter(static function ($param, $key) {
            return Str::startsWith($key, 'vk_');
        })->sortKeys();
    }

    private function resolveUserById($vkId): User
    {
        return User::updateOrCreate([
            'id' => $vkId,
        ], [
            'visited_at' => Carbon::now()
        ]);
    }


    private function getUser($params): User
    {
        return User::updateOrCreate([
            'id' => $params['vk_user_id'],
        ], [
            'utc_offset' => $params['utc_offset'],
            'notifications_are_enabled' => $params['vk_are_notifications_enabled'],
            'visited_at' => Carbon::now()
        ]);
    }
}
