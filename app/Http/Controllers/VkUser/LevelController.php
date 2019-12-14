<?php

/** @noinspection NullPointerExceptionInspection */

namespace App\Http\Controllers\VkUser;

use App\Group;
use App\Http\Controllers\Controller;
use App\Http\Resources\GroupResource;
use App\Http\Resources\LevelResource;
use App\Http\Resources\PhraseResource;
use App\Http\Resources\UserResource;
use App\Level;
use App\Phrase;
use App\Word;
use Illuminate\Support\Facades\Auth;

class LevelController extends Controller
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

    public function index()
    {
        $levels = Level::all();

        return LevelResource::collection($levels);
    }
}
