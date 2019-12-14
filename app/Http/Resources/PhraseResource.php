<?php

namespace App\Http\Resources;

use App\Phrase;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * @package App\Http\Resources
 * @mixin Phrase
 */
class PhraseResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'native' => $this->native,
            'translation' => $this->translation,
            'audio_link' => $this->getAudioLink()
        ];
    }
}
