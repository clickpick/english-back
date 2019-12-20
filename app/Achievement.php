<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Achievement
 *
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Achievement newModelQuery()
 * @method static Builder|Achievement newQuery()
 * @method static Builder|Achievement query()
 * @method static Builder|Achievement whereCreatedAt($value)
 * @method static Builder|Achievement whereDescription($value)
 * @method static Builder|Achievement whereId($value)
 * @method static Builder|Achievement whereName($value)
 * @method static Builder|Achievement whereSlug($value)
 * @method static Builder|Achievement whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Achievement extends Model
{
    protected $fillable = [
        'name',
        'description',
        'slug'
    ];
}
