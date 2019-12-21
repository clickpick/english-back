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

        $normal = \Illuminate\Support\Facades\Storage::get('seed/phrases/normal.json');
        $normal = str_replace('{$space}', $space, $normal);
        $normal = json_decode($normal, true);

        $advanced = \Illuminate\Support\Facades\Storage::get('seed/phrases/advanced.json');
        $advanced = str_replace('{$space}', $space, $advanced);
        $advanced = json_decode($advanced, true);

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
            $level = \App\Level::createOrFirst([
                'name' => $levelName
            ]);

            $wordNames = new \Illuminate\Support\Collection($wordNames);

            $wordNames->each(function ($phraseData, $wordName) use ($level) {
                $word = $level->words()->createOrFirst([
                    'name' => $wordName
                ]);

                $word->phrases()->createMany($phraseData);
            });
        });
    }
}
