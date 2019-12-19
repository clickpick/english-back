<?php

namespace App;

use App\Jobs\SendLessonNotification;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Queue;


/**
 * App\Lesson
 *
 * @property int $id
 * @property int $user_id
 * @property int $phrase_id
 * @property string $send_at
 * @property bool $is_sent
 * @property bool $is_registered
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Phrase $phrase
 * @property-read User $user
 * @method static Builder|Lesson newModelQuery()
 * @method static Builder|Lesson newQuery()
 * @method static Builder|Lesson query()
 * @method static Builder|Lesson whereCreatedAt($value)
 * @method static Builder|Lesson whereId($value)
 * @method static Builder|Lesson whereIsRegistered($value)
 * @method static Builder|Lesson whereIsSent($value)
 * @method static Builder|Lesson wherePhraseId($value)
 * @method static Builder|Lesson whereSendAt($value)
 * @method static Builder|Lesson whereUpdatedAt($value)
 * @method static Builder|Lesson whereUserId($value)
 * @mixin Eloquent
 * @property bool $is_learned
 * @method static Builder|Lesson whereIsLearned($value)
 */
class Lesson extends Model
{
    public const MINUTES_BEFORE_REGISTER = 5;

    protected $fillable = [
        'phrase_id',
        'send_at',
        'is_sent',
        'is_registered'
    ];

    protected $casts = [
        'send_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function phrase(): BelongsTo
    {
        return $this->belongsTo(Phrase::class);
    }

    public function registerNotification()
    {
        if ($this->is_registered || $this->is_sent) {
            return;
        }

        $this->is_registered = true;
        $this->save();

        Queue::later($this->send_at, new SendLessonNotification($this));
    }
}
