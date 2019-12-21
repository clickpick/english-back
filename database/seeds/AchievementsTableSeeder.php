<?php

use Illuminate\Database\Seeder;

class AchievementsTableSeeder extends Seeder
{

    private function data() {
        return [
            // is ready +
            [
                'name' => 'Свет камера мотор',
                'description' => 'Начать обучение',
                'slug' => \App\Achievement::SLUG_START
            ],
            // +
            [
                'name' => 'Месячный',
                'description' => 'Месяц с нами',
                'slug' => \App\Achievement::SLUG_MONTH
            ],
            // +
            [
                'name' => 'Уверен в себе',
                'description' => 'Выбрать в настройках - знающий',
                'slug' => \App\Achievement::SLUG_CLEVER
            ],
            // +
            [
                'name' => 'В курсе всего',
                'description' => 'Разрешить уведомления',
                'slug' => \App\Achievement::SLUG_NOTIFIED
            ],

            // +
            [
                'name' => 'Я не робот',
                'description' => 'Написать боту',
                'slug' => \App\Achievement::SLUG_BOT
            ],

            //
            [
                'name' => 'Game over',
                'description' => 'Получить все прочие ачивки',
                'slug' => \App\Achievement::SLUG_COMPLETED
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
