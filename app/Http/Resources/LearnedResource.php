<?php

namespace App\Http\Resources;

use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * @package App\Http\Resources
 * @mixin User
 */
class LearnedResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'day_num' => $this->resource['day_num'],
            'phrases' => PhraseResource::collection($this->resource['phrases'])
        ];
    }
}
