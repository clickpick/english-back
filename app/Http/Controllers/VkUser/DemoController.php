<?php

/** @noinspection NullPointerExceptionInspection */

namespace App\Http\Controllers\VkUser;

use App\Group;
use App\Http\Controllers\Controller;
use App\Http\Resources\GroupResource;
use App\Http\Resources\PhraseResource;
use App\Http\Resources\UserResource;
use App\Phrase;
use App\Word;
use Illuminate\Support\Facades\Auth;

class DemoController extends Controller
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

    public function demo()
    {
        $word = Word::first();
        $phrases = $word->phrases;

        return PhraseResource::collection($phrases);
    }
}
