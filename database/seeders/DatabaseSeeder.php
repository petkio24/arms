<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Component;
use App\Models\Workstation;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Создаем администратора
        User::create([
            'name' => 'Администратор',
            'email' => 'admin@arm.local',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Создаем обычного пользователя
        User::create([
            'name' => 'Пользователь',
            'email' => 'user@arm.local',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        // Типы комплектующих
        $componentTypes = Component::getTypes();

        // Создаем образцы комплектующих
        $components = [
            [
                'name' => 'Intel Core i5-12400',
                'type' => 'processor',
                'model' => 'BX8071512400',
                'serial_number' => 'SN123456789',
                'inventory_number' => 'INV-CPU-001',
                'manufacturer' => 'Intel',
                'specifications' => '6 ядер, 12 потоков, 2.5-4.4 ГГц',
                'purchase_date' => '2023-01-15',
                'status' => 'in_stock',
            ],
            [
                'name' => 'AMD Ryzen 5 5600X',
                'type' => 'processor',
                'model' => '100-100000065BOX',
                'serial_number' => 'SN987654321',
                'inventory_number' => 'INV-CPU-002',
                'manufacturer' => 'AMD',
                'specifications' => '6 ядер, 12 потоков, 3.7-4.6 ГГц',
                'purchase_date' => '2023-02-20',
                'status' => 'installed',
            ],
            [
                'name' => 'ASUS PRIME B660M-A',
                'type' => 'motherboard',
                'model' => '90MB18E0-M0EAY5',
                'serial_number' => 'SNMB123456',
                'inventory_number' => 'INV-MB-001',
                'manufacturer' => 'ASUS',
                'specifications' => 'LGA1700, DDR4, mATX',
                'purchase_date' => '2023-01-20',
                'status' => 'in_stock',
            ],
            [
                'name' => 'Kingston Fury 16GB DDR4',
                'type' => 'ram',
                'model' => 'KF426C16BB/16',
                'serial_number' => 'SNRAM111111',
                'inventory_number' => 'INV-RAM-001',
                'manufacturer' => 'Kingston',
                'specifications' => '16GB DDR4 3200MHz',
                'purchase_date' => '2023-01-25',
                'status' => 'installed',
            ],
            [
                'name' => 'Samsung 970 EVO Plus 500GB',
                'type' => 'storage',
                'model' => 'MZ-V7S500BW',
                'serial_number' => 'SNSSD222222',
                'inventory_number' => 'INV-SSD-001',
                'manufacturer' => 'Samsung',
                'specifications' => 'NVMe M.2 500GB',
                'purchase_date' => '2023-02-10',
                'status' => 'installed',
            ],
        ];

        foreach ($components as $component) {
            Component::create($component);
        }

        // Создаем рабочие станции
        $workstations = [
            [
                'name' => 'Рабочая станция 1',
                'inventory_number' => 'WS-001',
                'location' => 'Кабинет 101',
                'status' => 'active',
                'notes' => 'Основная рабочая станция для отдела разработки',
            ],
            [
                'name' => 'Рабочая станция 2',
                'inventory_number' => 'WS-002',
                'location' => 'Кабинет 102',
                'status' => 'maintenance',
                'notes' => 'Находится на обслуживании',
            ],
            [
                'name' => 'Рабочая станция 3',
                'inventory_number' => 'WS-003',
                'location' => 'Кабинет 103',
                'status' => 'active',
                'notes' => 'Резервная станция',
            ],
        ];

        foreach ($workstations as $workstation) {
            Workstation::create($workstation);
        }

        // Привязываем комплектующие к рабочим станциям
        $ws1 = Workstation::where('inventory_number', 'WS-001')->first();
        $cpu = Component::where('inventory_number', 'INV-CPU-002')->first();
        $ram = Component::where('inventory_number', 'INV-RAM-001')->first();
        $ssd = Component::where('inventory_number', 'INV-SSD-001')->first();

        if ($ws1 && $cpu) {
            $ws1->components()->attach($cpu->id, [
                'installed_at' => '2023-03-01',
                'notes' => 'Первоначальная установка'
            ]);
            $cpu->status = 'installed';
            $cpu->save();
        }

        if ($ws1 && $ram) {
            $ws1->components()->attach($ram->id, [
                'installed_at' => '2023-03-01',
                'notes' => 'Первоначальная установка'
            ]);
            $ram->status = 'installed';
            $ram->save();
        }

        if ($ws1 && $ssd) {
            $ws1->components()->attach($ssd->id, [
                'installed_at' => '2023-03-01',
                'notes' => 'Первоначальная установка'
            ]);
            $ssd->status = 'installed';
            $ssd->save();
        }
    }
}
