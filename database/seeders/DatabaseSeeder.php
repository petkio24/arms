<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Создаем помещения
        $locations = [
            [
                'name' => 'Отдел разработки',
                'building' => 'A',
                'floor' => '3',
                'room' => '301',
                'responsible_person' => 'Иванов И.И.',
                'phone' => '+7 (123) 456-78-90',
                'email' => 'dev@company.local',
            ],
            [
                'name' => 'Отдел тестирования',
                'building' => 'A',
                'floor' => '3',
                'room' => '302',
                'responsible_person' => 'Петров П.П.',
                'phone' => '+7 (123) 456-78-91',
            ],
            [
                'name' => 'Серверная',
                'building' => 'B',
                'floor' => '1',
                'room' => '101',
                'description' => 'Помещение для серверного оборудования',
                'responsible_person' => 'Сидоров С.С.',
            ],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }

    }
}
