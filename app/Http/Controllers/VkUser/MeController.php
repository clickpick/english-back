<?php

/** @noinspection NullPointerExceptionInspection */

namespace App\Http\Controllers\VkUser;

use App\Group;
use App\Http\Controllers\Controller;
use App\Http\Resources\GroupResource;
use App\Http\Resources\UserResource;
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
}
