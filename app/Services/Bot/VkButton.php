<?php

namespace App\Services\Bot;

use Illuminate\Support\Str;

class VkButton {

    protected const PRIMARY = 'primary';
    protected const SECONDARY = 'secondary';
    protected const NEGATIVE = 'negative';
    protected const POSITIVE = 'positive';

    protected $type;
    protected $payload;

    protected $color = self::PRIMARY;

    public function toArray(): array
    {
        $attributes = get_object_vars($this);
        unset($attributes['color']);

        foreach ($attributes as $key => $value) {
            $newKey = Str::snake($key);
            unset($attributes[$key]);
            $attributes[$newKey] = $value;
        }

        $result = [
            'action' => $attributes
        ];

        if ($this instanceof VkTextButton) {
            $result['color'] = $this->color;
        }


        return $result;
    }

    public function setPrimaryColor() {
        $this->color = self::PRIMARY;
    }

    public function setSecondaryColor() {
        $this->color = self::SECONDARY;
    }

    public function setNegativeColor() {
        $this->color = self::NEGATIVE;
    }

    public function setPositiveColor() {
        $this->color = self::POSITIVE;
    }
}
