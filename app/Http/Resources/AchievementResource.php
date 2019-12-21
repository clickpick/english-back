<?php

namespace App\Http\Resources;

use App\Achievement;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

/**
 * Class AchievementResource
 * @package App\Http\Resources
 * @mixin Achievement
 */
class AchievementResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => $this->description,
            'is_achieved' => $this->is_achieved ?? new MissingValue()
        ];
    }
}
