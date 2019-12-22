<?php

use Illuminate\Database\Seeder;

class PhrasesTableSeeder extends Seeder
{

    private function data()
    {

        $space = \App\Phrase::SPACE;

        $beginner = \Illuminate\Support\Facades\Storage::get('seed/phrases/beginner.json');
        $beginner = str_replace('{$space}', $space, $beginner);
        $beginner = json_decode($beginner, true);

        $beginnerTxt = $this->parseTxt('beginner.txt');

        $beginner = array_merge($beginner, $beginnerTxt);

        $normal = $this->parseTxt('normal.txt');
        $advanced = $this->parseTxt('advanced.txt');

        return [
            'System' => [
                'about' => [
                    [
                        'native' => 'Hi! It\'s time to make notifications useful',
                        'translation' => 'Привет! Пора сделать пуши полезными'
                    ],
                    [
                        'native' => "At first, allow to get messages{$space}💬",
                        'translation' => 'Для начала разреши получение сообщений'
                    ],
                    [
                        'native' => 'Fine. Now set the words timer and your level in settings tab',
                        'translation' => 'Отлично. Пора установить таймер слов и свой уровень знаний на вкладке настроек'
                    ],
                    [
                        'native' => "Awesome! We are ready to go{$space}👍",
                        'translation' => 'Потрясающе! Мы готовы начать'
                    ],
                    [
                        'native' => "Pull down to see when first word comes{$space}👆",
                        'translation' => 'Потяни вниз, чтобы увидеть, когда придёт первое слово'
                    ],
                ]
            ],
            'Начальный' => $beginner,
            'Средний' => $normal,
            'Продвинутый' => $advanced
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = new \Illuminate\Support\Collection($this->data());

        $data->each(function ($wordNames, $levelName) {
            $level = \App\Level::firstOrCreate([
                'name' => $levelName
            ]);

            $wordNames = new \Illuminate\Support\Collection($wordNames);

            $wordNames->each(function ($phraseData, $wordName) use ($level) {
                $word = $level->words()->firstOrCreate([
                    'name' => $wordName
                ]);

                $word->phrases()->createMany($phraseData);
            });
        });
    }


    private function parseTxt($filename) {
        $content = \Illuminate\Support\Facades\Storage::get("seed/phrases/{$filename}");

        $exploded = explode("\n", $content);

        $words = [];
        $phrases = [];

        foreach ($exploded as $phrase) {
            if (empty($phrase)) {
                $words[] = $phrases;
                unset($phrases);
                $phrases = [];
                continue;
            }

            $phrases[] = $phrase;
        }

        $result = [];

        foreach ($words as $phrases) {
            $result[$phrases[0]] = [];

            for ($i = 0, $iMax = count($phrases); $i < $iMax; $i+=2) {
                $result[$phrases[0]][] = [
                    'native' => $phrases[$i],
                    'translation' => $phrases[$i + 1]
                ];
            }
        }

        return $result;
    }
}
