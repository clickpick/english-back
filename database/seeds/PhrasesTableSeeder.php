<?php

use Illuminate\Database\Seeder;

class PhrasesTableSeeder extends Seeder
{

    private function data()
    {

        $space = \App\Phrase::SPACE;

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
            'Начальный' => [
                'to Quit' => [
                    [
                        'native' => "to Quit{$space}😱",
                        'translation' => 'Оставлять, уходить, бросать, увольняться'
                    ],
                    [
                        'native' => "You should quit smoking{$space}🤯",
                        'translation' => 'Ты должен бросить курить'
                    ],
                    [
                        'native' => "Quit laughing{$space}😩",
                        'translation' => 'Хорош смеяться'
                    ]
                ]
            ],
            'Средний' => [
                'to Quit' => [
                    [
                        'native' => "to Quit{$space}😱",
                        'translation' => 'Оставлять, уходить, бросать, увольняться'
                    ],
                    [
                        'native' => "You should quit smoking{$space}🤯",
                        'translation' => 'Ты должен бросить курить'
                    ],
                    [
                        'native' => "Quit laughing{$space}😩",
                        'translation' => 'Хорош смеяться'
                    ]
                ]
            ],
            'Продвинутый' => [
                'to Quit' => [
                    [
                        'native' => "to Quit{$space}😱",
                        'translation' => 'Оставлять, уходить, бросать, увольняться'
                    ],
                    [
                        'native' => "You should quit smoking{$space}🤯",
                        'translation' => 'Ты должен бросить курить'
                    ],
                    [
                        'native' => "Quit laughing{$space}😩",
                        'translation' => 'Хорош смеяться'
                    ]
                ]
            ]
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
