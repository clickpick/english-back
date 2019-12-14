<?php

namespace App;

use App\Events\PhraseCreated;
use App\Services\GoogleSpeech;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;


/**
 * App\Phrase
 *
 * @property int $id
 * @property int $word_id
 * @property string $native
 * @property string $translation
 * @property string|null $audio_path
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Word $word
 * @method static Builder|Phrase newModelQuery()
 * @method static Builder|Phrase newQuery()
 * @method static Builder|Phrase query()
 * @method static Builder|Phrase whereAudioPath($value)
 * @method static Builder|Phrase whereCreatedAt($value)
 * @method static Builder|Phrase whereId($value)
 * @method static Builder|Phrase whereNative($value)
 * @method static Builder|Phrase whereTranslation($value)
 * @method static Builder|Phrase whereUpdatedAt($value)
 * @method static Builder|Phrase whereWordId($value)
 * @mixin Eloquent
 */
class Phrase extends Model
{
    protected $fillable = [
        'native',
        'translation'
    ];

    protected $dispatchesEvents = [
        'created' => PhraseCreated::class
    ];

    public function word(): BelongsTo
    {
        return $this->belongsTo(Word::class);
    }

    public function createAudio(): void
    {
        $fileName = (new GoogleSpeech())->recognize($this->removeEmoji($this->native));
        $this->audio_path = $fileName;
        $this->save();
    }

    public function getAudioLink() {
        if (!$this->audio_path) {
            return null;
        }

        return url(Storage::url($this->audio_path));
    }

    private function removeEmoji($string) {

        // Match Emoticons
        $regex_emoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $clear_string = preg_replace($regex_emoticons, '', $string);

        // Match Miscellaneous Symbols and Pictographs
        $regex_symbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $clear_string = preg_replace($regex_symbols, '', $clear_string);

        // Match Transport And Map Symbols
        $regex_transport = '/[\x{1F680}-\x{1F6FF}]/u';
        $clear_string = preg_replace($regex_transport, '', $clear_string);

        // Match Miscellaneous Symbols
        $regex_misc = '/[\x{2600}-\x{26FF}]/u';
        $clear_string = preg_replace($regex_misc, '', $clear_string);

        // Match Dingbats
        $regex_dingbats = '/[\x{2700}-\x{27BF}]/u';
        $clear_string = preg_replace($regex_dingbats, '', $clear_string);

        return $clear_string;
    }
}
