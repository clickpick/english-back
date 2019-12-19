<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VkCallbackAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (!((int)$request->group_id === (int)config('services.vk.group.id') && $request->secret === config('services.vk.group.secret'))) {
            abort(403, 'bad sign');
        }

        return $next($request);
    }
}
