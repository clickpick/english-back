<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    private $data = [
        'System' => [
            'about' => [
                [
                    'native' => 'Hi! It`s time to make notifications useful',
                    'translation' => 'Привет! Пора сделать пуши полезными'
                ],
                [
                    'native' => 'At first, allow to get messages\u00A0💬',
                    'translation' => 'Для начала разреши получение сообщений'
                ],
                [
                    'native' => 'Fine. Now set the words timer and your level in settings tab',
                    'translation' => 'Отлично. Пора установить таймер слов и свой уровень знаний на вкладке настроек'
                ],
                [
                    'native' => 'Awesome! We are ready to go\u00A0👍',
                    'translation' => 'Потрясающе! Мы готовы начать'
                ],
                [
                    'native' => 'Pull down to see when first word comes\u00A0👆',
                    'translation' => 'Потяни вниз, чтобы увидеть, когда придёт первое слово'
                ],
            ]
        ],
        'Начальный' => [
            'to Quit' => [
                [
                    'native' => 'to Quit\u00A0😱',
                    'translation' => 'Оставлять, уходить, бросать, увольняться'
                ],
                [
                    'native' => 'You should quit smoking\u00A0🤯',
                    'translation' => 'Ты должен бросить курить'
                ],
                [
                    'native' => 'Quit laughing\u00A0😩',
                    'translation' => 'Хорош смеяться'
                ]
            ]
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = new \Illuminate\Support\Collection($this->data);

        $data->each(function ($wordNames, $levelName) {
           $level = \App\Level::create([
               'name' => $levelName
           ]);

           $wordNames = new \Illuminate\Support\Collection($wordNames);

           $wordNames->each(function ($phraseData, $wordName) use ($level) {
                $word = $level->words()->create([
                    'name' => $wordName
                ]);

                $word->phrases()->createMany($phraseData);
           });
        });
    }
}
