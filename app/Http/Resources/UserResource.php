<?php

namespace App\Http\Resources;

use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * @package App\Http\Resources
 * @mixin User
 */
class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'avatar_200' => $this->avatar_200,
            'utc_offset' => $this->utc_offset,
            'messages_are_enabled' => $this->messages_are_enabled,
            'notifications_are_enabled' => $this->notifications_are_enabled,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'is_active' => $this->is_active,
            'is_ready' => $this->is_ready,
            'level_id' => $this->level_id
        ];
    }
}
