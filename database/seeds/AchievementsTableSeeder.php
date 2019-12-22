<?php

use Illuminate\Database\Seeder;

class AchievementsTableSeeder extends Seeder
{

    private function data() {
        return [
            // is ready +
            [
                'name' => 'Свет, камера, мотор!',
                'description' => 'Начать обучение',
                'slug' => \App\Achievement::SLUG_START
            ],
            // +
            [
                'name' => 'Я календарь переверну',
                'description' => 'Уже месяц с нами',
                'slug' => \App\Achievement::SLUG_MONTH
            ],
            // +
            [
                'name' => 'Книжный червь',
                'description' => 'Выбрать в настройках - знающий',
                'slug' => \App\Achievement::SLUG_CLEVER
            ],
            // +
            [
                'name' => 'Во славу click\'у',
                'description' => 'Разрешить уведомления',
                'slug' => \App\Achievement::SLUG_NOTIFIED
            ],

            // +
            [
                'name' => 'Навесить ярлык',
                'description' => 'Написать боту',
                'slug' => \App\Achievement::SLUG_BOT
            ],

            //
            [
                'name' => 'Выпускник',
                'description' => 'Получить все награды',
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
