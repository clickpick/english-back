<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{


    private $data = [
        'Начальный' => [
            'to Quit' => [
                [
                    'native' => 'to Quit',
                    'translation' => 'Оставлять, уходить, бросать, увольняться'
                ],
                [
                    'native' => 'You should quit smoking',
                    'translation' => 'Ты должен бросить курить'
                ],
                [
                    'native' => 'Quit laughing',
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
