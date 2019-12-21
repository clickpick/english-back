<?php

use Illuminate\Database\Seeder;

class AchievementsTableSeeder extends Seeder
{

    private function data() {
        return [
            // is ready
            [
                'name' => 'Свет камера мотор',
                'description' => 'Начать обучение',
                'slug' => 'start'
            ],
            //
            [
                'name' => 'Месячный',
                'description' => 'Месяц с нами',
                'slug' => 'month'
            ],

            [
                'name' => 'Уверен в себе',
                'description' => 'Выбрать в настройках - знающий',
                'slug' => 'clever'
            ],

            [
                'name' => 'В курсе всего',
                'description' => 'Разрешить уведомления',
                'slug' => 'notified'
            ],
            [
                'name' => 'Я не робот',
                'description' => 'Написать боту',
                'slug' => 'bot'
            ],
            [
                'name' => 'Game over',
                'description' => 'Получить все прочие ачивки',
                'slug' => 'completed'
            ],
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->data() as $item) {
            \App\Achievement::create($item);
        }
    }
}
