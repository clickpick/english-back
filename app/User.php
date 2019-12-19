<?php

namespace App;

use App\Events\UserCreated;
use App\Services\Bot\OutgoingMessage;
use App\Services\VkClient;
use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Laravel\Lumen\Auth\Authorizable;
use Spatie\Regex\Regex;


/**
 * App\User
 *
 * @property int $id
 * @property bool $notifications_are_enabled
 * @property bool $messages_are_enabled
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $avatar_200
 * @property string|null $bdate
 * @property int $sex
 * @property int|null $utc_offset
 * @property string|null $visited_at
 * @property bool $is_admin
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereAvatar200($value)
 * @method static Builder|User whereBdate($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereFirstName($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereIsAdmin($value)
 * @method static Builder|User whereLastName($value)
 * @method static Builder|User whereMessagesAreEnabled($value)
 * @method static Builder|User whereNotificationsAreEnabled($value)
 * @method static Builder|User whereSex($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereUtcOffset($value)
 * @method static Builder|User whereVisitedAt($value)
 * @method static User firstOrCreate(array $array)
 * @mixin Eloquent
 * @property int $start_at
 * @property int $end_at
 * @property int|null $level_id
 * @property bool $is_active
 * @method static Builder|User whereEndAt($value)
 * @method static Builder|User whereIsActive($value)
 * @method static Builder|User whereLevelId($value)
 * @method static Builder|User whereStartAt($value)
 * @property-read Collection|Word[] $learnedWords
 * @property-read int|null $learned_words_count
 * @property-read Collection|VkMessage[] $vkMessages
 * @property-read int|null $vk_messages_count
 * @property-read Collection|Lesson[] $lessons
 * @property-read int|null $lessons_count
 * @property bool $is_ready
 * @method static Builder|User whereIsReady($value)
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    public function getIncrementing(): bool
    {
        return false;
    }

    protected $fillable = [
        'id',
        'utc_offset',
        'notifications_are_enabled',
        'messages_are_enabled',
        'visited_at',
        'is_active',
        'start_at',
        'end_at',
        'level_id'
    ];

    protected $casts = [
        'id' => 'integer',
        'notifications_are_enabled' => 'boolean',
        'messages_are_enabled' => 'boolean',
        'start_at' => 'integer',
        'end_at' => 'integer',
        'is_active' => 'boolean'
    ];

    protected $dispatchesEvents = [
        'created' => UserCreated::class
    ];

    public function vkMessages()
    {
        return $this->hasMany(VkMessage::class);
    }

    public function learnedWords()
    {
        return $this->belongsToMany(Word::class, 'learned_words')->withTimestamps();
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function fillPersonalInfoFromVk($data = null)
    {
        $data = $data ?? (new VkClient())->getUsers($this->id, ['first_name', 'last_name', 'photo_200', 'timezone', 'sex', 'bdate', 'city']);

        $this->first_name = $data['first_name'] ?? null;
        $this->last_name = $data['last_name'] ?? null;
        $this->avatar_200 = $data['photo_200'] ?? null;
        $this->sex = $data['sex'] ?? 0;

        if (isset($data['bdate'])) {
            $reYear = Regex::match('/\d{1,2}.\d{1,2}.\d{4}/', $data['bdate']);
            $reDay = Regex::match('/\d{1,2}.\d{1,2}/', $data['bdate']);

            if ($reYear->hasMatch()) {
                $this->bdate = Carbon::parse($data['bdate']);
            } elseif ($reDay->hasMatch()) {

                $date = explode('.', $data['bdate']);

                $bdate = new Carbon();

                $bdate->setYear(1);
                $bdate->setMonth($date[1]);
                $bdate->setDay($date[0]);

                $this->bdate = $bdate;

            } else {
                $this->bdate = null;
            }
        }

        if (isset($data['timezone'])) {
            $this->utc_offset = $data['timezone'] * 60;
        }

        $this->save();
    }

    /**
     * @param $vkId
     * @return User
     */
    public static function getByVkId($vkId): ?self
    {
        if (!$vkId) {
            return null;
        }

        return self::firstOrCreate(['id' => $vkId]);
    }

    public function enableNotifications()
    {
        if ($this->notifications_are_enabled) {
            return;
        }

        $this->notifications_are_enabled = true;
        $this->save();
    }

    public function disableNotifications()
    {
        if (!$this->notifications_are_enabled) {
            return;
        }

        $this->notifications_are_enabled = false;
        $this->save();
    }

    public function enableMessages()
    {
        if ($this->messages_are_enabled) {
            return;
        }

        $this->messages_are_enabled = true;
        $this->save();
    }

    public function disableMessages()
    {
        if (!$this->messages_are_enabled) {
            return;
        }

        $this->messages_are_enabled = false;
        $this->save();
    }

    public function getLocalDate(\Carbon\Carbon $date)
    {
        if (!$this->utc_offset) {
            return $date;
        }

        return $date->utcOffset($this->utc_offset);
    }

    public function setUtcOffset($offset)
    {
        $this->utc_offset = $offset;
        $this->save();
    }

    public function sendVkMessage(OutgoingMessage $outgoingMessage)
    {
        if (!$this->messages_are_enabled) {
            return;
        }

        $outgoingMessage->setRecipient($this);
        $outgoingMessage->createModel();

        (new VkClient(VkClient::GROUP_TOKEN))->sendMessage($outgoingMessage);
    }

    public function sendPhraseMessage(Phrase $phrase)
    {
        $outgoingMessage = new OutgoingMessage("{$phrase->native}\n\n{$phrase->translation}");
        $outgoingMessage->setRecipient($this);
        $outgoingMessage->addAudio(Storage::path($phrase->audio_path));

        $this->sendVkMessage($outgoingMessage);
    }

    public function getNextLessonAt()
    {
        $lesson = $this->lessons()
            ->where('send_at', '>', Carbon::now())
            ->orderBy('send_at', 'asc')
            ->first();

        if ($lesson) {
            return $lesson->send_at;
        }

        if ($this->is_ready) {
            $minutes = $this->utc_offset ? ((-1) * $this->utc_offset + $this->start_at) : $this->start_at;

            return Carbon::tomorrow()->setMinutes($minutes);
        }

        return null;
    }
}
