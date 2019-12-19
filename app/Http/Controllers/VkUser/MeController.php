<?php

/** @noinspection NullPointerExceptionInspection */

namespace App\Http\Controllers\VkUser;

use App\Group;
use App\Http\Controllers\Controller;
use App\Http\Resources\GroupResource;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function user(): UserResource
    {
        return new UserResource(Auth::user());
    }

    public function setStartAt(Request $request): UserResource
    {
        $this->validate($request, [
            'start_at' => 'required|integer|min:420|max:720'
        ]);

        /**
         * @var User $user
         */
        $user = Auth::user();

        $user->start_at = $request->input('start_at');
        $user->save();

        return new UserResource($user);
    }

    public function setEndAt(Request $request): UserResource
    {
        $this->validate($request, [
            'end_at' => 'required|integer|min:1140|max:1440'
        ]);

        /**
         * @var User $user
         */
        $user = Auth::user();

        $user->end_at = $request->input('end_at');
        $user->save();

        return new UserResource($user);
    }

    public function start(): UserResource
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        $user->is_active = true;
        $user->save();

        return new UserResource($user);
    }

    public function stop(): UserResource
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        $user->is_active = false;
        $user->save();

        return new UserResource($user);
    }

    public function updateSettings(Request $request): UserResource
    {

        $this->validate($request, [
            'start_at' => 'integer|min:420|max:720',
            'end_at' => 'integer|min:1140|max:1440',
            'is_active' => 'boolean',
            'is_ready' => 'boolean',
            'notifications_are_enabled' => 'boolean',
            'level_id' => 'integer|exists:levels,id',
        ]);

        /**
         * @var User $user
         */
        $user = Auth::user();

        $user->update($request->only(['start_at', 'end_at', 'is_active', 'level_id', 'notifications_are_enabled', 'is_ready']));

        return new UserResource($user);
    }
}
