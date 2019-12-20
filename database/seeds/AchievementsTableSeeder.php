<?php

use Illuminate\Database\Seeder;

class AchievementsTableSeeder extends Seeder
{

    private function data() {
        return [
            [
                'name' => 'Решающий выбор',
                'description' => 'Начать изучение',
                'slug' => 'start'
            ],
            [
                'name' => 'На пути к совершенству',
                'description' => 'Прослушать запись дважды',
                'slug' => 'twice'
            ],
            [
                'name' => 'Главное - количество',
                'description' => 'Уже месяц с нами',
                'slug' => 'month'
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
