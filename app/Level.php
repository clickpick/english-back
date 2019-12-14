<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;


/**
 * App\Level
 *
 * @property-read Collection|Word[] $words
 * @property-read int|null $words_count
 * @method static Builder|Level newModelQuery()
 * @method static Builder|Level newQuery()
 * @method static Builder|Level query()
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Level whereCreatedAt($value)
 * @method static Builder|Level whereId($value)
 * @method static Builder|Level whereName($value)
 * @method static Builder|Level whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Level extends Model
{
    protected $fillable = [
        'name'
    ];

    public function words(): HasMany
    {
        return $this->hasMany(Word::class);
    }
}
