<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;


/**
 * App\Word
 *
 * @property-read Level $level
 * @method static Builder|Word newModelQuery()
 * @method static Builder|Word newQuery()
 * @method static Builder|Word query()
 * @mixin Eloquent
 * @property int $id
 * @property int $level_id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Word whereCreatedAt($value)
 * @method static Builder|Word whereId($value)
 * @method static Builder|Word whereLevelId($value)
 * @method static Builder|Word whereName($value)
 * @method static Builder|Word whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Phrase[] $phrases
 * @property-read int|null $phrases_count
 */
class Word extends Model
{
    protected $fillable = [
        'name'
    ];

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function phrases(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Phrase::class);
    }
}
