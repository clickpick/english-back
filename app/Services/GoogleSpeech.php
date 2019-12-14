<?php

namespace App\Services;

use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\AudioEncoding;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
use Google\Cloud\TextToSpeech\V1\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GoogleSpeech
{
    private $client;

    public function __construct()
    {

        $this->client = new TextToSpeechClient([
            'credentials' => storage_path('app/config/antodo-7e61756b0a9a.json')
        ]);
    }

    public function recognize($text)
    {
        $input = new SynthesisInput();
        $input->setText($text);
        $voice = new VoiceSelectionParams();
        $voice->setLanguageCode('en-US');
        $audioConfig = new AudioConfig();
        $audioConfig->setAudioEncoding(AudioEncoding::MP3);

        $resp = $this->client->synthesizeSpeech($input, $voice, $audioConfig);

        $fileName = 'public/audios/' . Str::random() . '.mp3';

        Storage::put($fileName, $resp->getAudioContent());

        return $fileName;
    }
}
