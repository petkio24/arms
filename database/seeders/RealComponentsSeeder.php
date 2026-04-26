<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Component;
use App\Models\Location;
use App\Models\Workstation;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RealComponentsSeeder extends Seeder
{
    public function run()
    {
        // Создаем пользователей
        User::updateOrCreate(
            ['email' => 'admin@arm.local'],
            [
                'name' => 'Администратор',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@arm.local'],
            [
                'name' => 'Иван Петров',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        // Создаем помещения
        $locations = [
            ['name' => 'Отдел разработки', 'building' => 'А', 'floor' => '3', 'room' => '301', 'responsible_person' => 'Иванов И.И.'],
            ['name' => 'Отдел тестирования', 'building' => 'А', 'floor' => '3', 'room' => '302', 'responsible_person' => 'Петров П.П.'],
            ['name' => 'Бухгалтерия', 'building' => 'А', 'floor' => '2', 'room' => '205', 'responsible_person' => 'Сидорова М.И.'],
            ['name' => 'Серверная', 'building' => 'Б', 'floor' => '1', 'room' => '101', 'responsible_person' => 'Козлов С.В.'],
            ['name' => 'Приемная комиссия', 'building' => 'А', 'floor' => '1', 'room' => '108', 'responsible_person' => 'Васильева Е.Н.'],
            ['name' => 'Склад', 'building' => 'Б', 'floor' => '1', 'room' => 'S-01', 'responsible_person' => 'Михайлов А.А.'],
        ];

        foreach ($locations as $loc) {
            Location::updateOrCreate(
                ['name' => $loc['name']],
                $loc
            );
        }

        // Получаем ID помещений
        $devLocation = Location::where('name', 'Отдел разработки')->first();
        $testLocation = Location::where('name', 'Отдел тестирования')->first();
        $accountingLocation = Location::where('name', 'Бухгалтерия')->first();
        $serverLocation = Location::where('name', 'Серверная')->first();
        $stockLocation = Location::where('name', 'Склад')->first();

        // ============ ПРОЦЕССОРЫ ============
        $processors = [
            // Intel LGA1700
            ['name' => 'Intel Core i9-14900K', 'type' => 'processor', 'model' => 'BX8071514900K', 'manufacturer' => 'Intel', 'socket' => 'LGA1700', 'power' => 125],
            ['name' => 'Intel Core i7-14700K', 'type' => 'processor', 'model' => 'BX8071514700K', 'manufacturer' => 'Intel', 'socket' => 'LGA1700', 'power' => 125],
            ['name' => 'Intel Core i5-14600K', 'type' => 'processor', 'model' => 'BX8071514600K', 'manufacturer' => 'Intel', 'socket' => 'LGA1700', 'power' => 125],
            ['name' => 'Intel Core i5-14400', 'type' => 'processor', 'model' => 'BX8071514400', 'manufacturer' => 'Intel', 'socket' => 'LGA1700', 'power' => 65],
            ['name' => 'Intel Core i3-14100', 'type' => 'processor', 'model' => 'BX8071514100', 'manufacturer' => 'Intel', 'socket' => 'LGA1700', 'power' => 60],

            // Intel LGA1200
            ['name' => 'Intel Core i7-11700K', 'type' => 'processor', 'model' => 'BX8070811700K', 'manufacturer' => 'Intel', 'socket' => 'LGA1200', 'power' => 125],
            ['name' => 'Intel Core i5-11400', 'type' => 'processor', 'model' => 'BX8070811400', 'manufacturer' => 'Intel', 'socket' => 'LGA1200', 'power' => 65],

            // AMD AM5
            ['name' => 'AMD Ryzen 9 7950X', 'type' => 'processor', 'model' => '100-100000514WOF', 'manufacturer' => 'AMD', 'socket' => 'AM5', 'power' => 170],
            ['name' => 'AMD Ryzen 7 7800X3D', 'type' => 'processor', 'model' => '100-100000910WOF', 'manufacturer' => 'AMD', 'socket' => 'AM5', 'power' => 120],
            ['name' => 'AMD Ryzen 5 7600X', 'type' => 'processor', 'model' => '100-100000593WOF', 'manufacturer' => 'AMD', 'socket' => 'AM5', 'power' => 105],

            // AMD AM4
            ['name' => 'AMD Ryzen 7 5800X', 'type' => 'processor', 'model' => '100-100000063WOF', 'manufacturer' => 'AMD', 'socket' => 'AM4', 'power' => 105],
            ['name' => 'AMD Ryzen 5 5600X', 'type' => 'processor', 'model' => '100-100000065BOX', 'manufacturer' => 'AMD', 'socket' => 'AM4', 'power' => 65],
            ['name' => 'AMD Ryzen 5 5500', 'type' => 'processor', 'model' => '100-100000457BOX', 'manufacturer' => 'AMD', 'socket' => 'AM4', 'power' => 65],
        ];

        // ============ МАТЕРИНСКИЕ ПЛАТЫ ============
        $motherboards = [
            // LGA1700
            ['name' => 'ASUS ROG MAXIMUS Z790 HERO', 'type' => 'motherboard', 'model' => '90MB1CV0-M0EAY0', 'manufacturer' => 'ASUS', 'socket' => 'LGA1700', 'ram_type' => 'DDR5', 'form_factor' => 'ATX'],
            ['name' => 'MSI MPG Z790 CARBON WIFI', 'type' => 'motherboard', 'model' => 'MSI-Z790-CARBON', 'manufacturer' => 'MSI', 'socket' => 'LGA1700', 'ram_type' => 'DDR5', 'form_factor' => 'ATX'],
            ['name' => 'Gigabyte B760 AORUS ELITE AX', 'type' => 'motherboard', 'model' => 'GA-B760-AORUS', 'manufacturer' => 'Gigabyte', 'socket' => 'LGA1700', 'ram_type' => 'DDR5', 'form_factor' => 'ATX'],
            ['name' => 'ASUS PRIME B760M-A', 'type' => 'motherboard', 'model' => '90MB1CS0-M0EAY0', 'manufacturer' => 'ASUS', 'socket' => 'LGA1700', 'ram_type' => 'DDR5', 'form_factor' => 'Micro-ATX'],
            ['name' => 'MSI PRO B660M-A', 'type' => 'motherboard', 'model' => 'MSI-B660M-A', 'manufacturer' => 'MSI', 'socket' => 'LGA1700', 'ram_type' => 'DDR4', 'form_factor' => 'Micro-ATX'],

            // LGA1200
            ['name' => 'ASUS PRIME Z590-A', 'type' => 'motherboard', 'model' => '90MB15W0-M0EAY0', 'manufacturer' => 'ASUS', 'socket' => 'LGA1200', 'ram_type' => 'DDR4', 'form_factor' => 'ATX'],
            ['name' => 'Gigabyte B560M DS3H', 'type' => 'motherboard', 'model' => 'GA-B560M-DS3H', 'manufacturer' => 'Gigabyte', 'socket' => 'LGA1200', 'ram_type' => 'DDR4', 'form_factor' => 'Micro-ATX'],

            // AM5
            ['name' => 'ASUS ROG CROSSHAIR X670E HERO', 'type' => 'motherboard', 'model' => '90MB1BK0-M0EAY0', 'manufacturer' => 'ASUS', 'socket' => 'AM5', 'ram_type' => 'DDR5', 'form_factor' => 'ATX'],
            ['name' => 'MSI MPG B650 CARBON WIFI', 'type' => 'motherboard', 'model' => 'MSI-B650-CARBON', 'manufacturer' => 'MSI', 'socket' => 'AM5', 'ram_type' => 'DDR5', 'form_factor' => 'ATX'],
            ['name' => 'Gigabyte B650 AORUS ELITE AX', 'type' => 'motherboard', 'model' => 'GA-B650-AORUS', 'manufacturer' => 'Gigabyte', 'socket' => 'AM5', 'ram_type' => 'DDR5', 'form_factor' => 'ATX'],

            // AM4
            ['name' => 'ASUS ROG STRIX B550-F', 'type' => 'motherboard', 'model' => '90MB14S0-M0EAY0', 'manufacturer' => 'ASUS', 'socket' => 'AM4', 'ram_type' => 'DDR4', 'form_factor' => 'ATX'],
            ['name' => 'MSI B550 TOMAHAWK', 'type' => 'motherboard', 'model' => 'MSI-B550-TOMAHAWK', 'manufacturer' => 'MSI', 'socket' => 'AM4', 'ram_type' => 'DDR4', 'form_factor' => 'ATX'],
            ['name' => 'Gigabyte B450 AORUS M', 'type' => 'motherboard', 'model' => 'GA-B450-AORUS', 'manufacturer' => 'Gigabyte', 'socket' => 'AM4', 'ram_type' => 'DDR4', 'form_factor' => 'Micro-ATX'],
        ];

        // ============ ОПЕРАТИВНАЯ ПАМЯТЬ ============
        $rams = [
            // DDR5
            ['name' => 'Corsair Vengeance 32GB DDR5 6000MHz', 'type' => 'ram', 'model' => 'CMK32GX5M2B6000C30', 'manufacturer' => 'Corsair', 'ram_type' => 'DDR5', 'power' => 6],
            ['name' => 'Kingston Fury 32GB DDR5 5600MHz', 'type' => 'ram', 'model' => 'KF556C40BBK2-32', 'manufacturer' => 'Kingston', 'ram_type' => 'DDR5', 'power' => 6],
            ['name' => 'G.Skill Trident Z5 RGB 32GB DDR5 6400MHz', 'type' => 'ram', 'model' => 'F5-6400J3239G16GX2-TZ5RK', 'manufacturer' => 'G.Skill', 'ram_type' => 'DDR5', 'power' => 6],
            ['name' => 'TeamGroup T-Force Delta 16GB DDR5 5600MHz', 'type' => 'ram', 'model' => 'FFRD516G5600HC40', 'manufacturer' => 'TeamGroup', 'ram_type' => 'DDR5', 'power' => 5],

            // DDR4
            ['name' => 'Kingston Fury 32GB DDR4 3200MHz', 'type' => 'ram', 'model' => 'KF432C16BBK2-32', 'manufacturer' => 'Kingston', 'ram_type' => 'DDR4', 'power' => 5],
            ['name' => 'Corsair Vengeance LPX 16GB DDR4 3200MHz', 'type' => 'ram', 'model' => 'CMK16GX4M1E3200C16', 'manufacturer' => 'Corsair', 'ram_type' => 'DDR4', 'power' => 5],
            ['name' => 'G.Skill Aegis 16GB DDR4 3200MHz', 'type' => 'ram', 'model' => 'F4-3200C16S-16GIS', 'manufacturer' => 'G.Skill', 'ram_type' => 'DDR4', 'power' => 5],
            ['name' => 'Samsung 8GB DDR4 2666MHz', 'type' => 'ram', 'model' => 'M378A1K43CB2-CTD', 'manufacturer' => 'Samsung', 'ram_type' => 'DDR4', 'power' => 4],
        ];

        // ============ НАКОПИТЕЛИ ============
        $storages = [
            // NVMe SSD
            ['name' => 'Samsung 990 PRO 1TB NVMe', 'type' => 'storage', 'model' => 'MZ-V9P1T0BW', 'manufacturer' => 'Samsung', 'power' => 9],
            ['name' => 'Samsung 980 PRO 1TB NVMe', 'type' => 'storage', 'model' => 'MZ-V8P1T0BW', 'manufacturer' => 'Samsung', 'power' => 9],
            ['name' => 'WD Black SN850X 1TB NVMe', 'type' => 'storage', 'model' => 'WDS100T2X0E', 'manufacturer' => 'Western Digital', 'power' => 9],
            ['name' => 'Kingston KC3000 1TB NVMe', 'type' => 'storage', 'model' => 'SKC3000S/1024G', 'manufacturer' => 'Kingston', 'power' => 8],
            ['name' => 'Crucial P5 Plus 1TB NVMe', 'type' => 'storage', 'model' => 'CT1000P5PSSD8', 'manufacturer' => 'Crucial', 'power' => 8],

            // SATA SSD
            ['name' => 'Samsung 870 EVO 1TB SATA', 'type' => 'storage', 'model' => 'MZ-77E1T0BW', 'manufacturer' => 'Samsung', 'power' => 7],
            ['name' => 'Crucial MX500 1TB SATA', 'type' => 'storage', 'model' => 'CT1000MX500SSD1', 'manufacturer' => 'Crucial', 'power' => 7],

            // HDD
            ['name' => 'Western Digital 2TB Blue', 'type' => 'storage', 'model' => 'WD20EZBX', 'manufacturer' => 'Western Digital', 'power' => 12],
            ['name' => 'Seagate 1TB Barracuda', 'type' => 'storage', 'model' => 'ST1000DM010', 'manufacturer' => 'Seagate', 'power' => 11],
        ];

        // ============ БЛОКИ ПИТАНИЯ ============
        $psus = [
            ['name' => 'Corsair RM1000e 1000W', 'type' => 'psu', 'model' => 'CP-9020260-EU', 'manufacturer' => 'Corsair', 'power' => 1000, 'form_factor' => 'ATX'],
            ['name' => 'Corsair RM850e 850W', 'type' => 'psu', 'model' => 'CP-9020260-EU', 'manufacturer' => 'Corsair', 'power' => 850, 'form_factor' => 'ATX'],
            ['name' => 'Corsair RM750e 750W', 'type' => 'psu', 'model' => 'CP-9020260-EU', 'manufacturer' => 'Corsair', 'power' => 750, 'form_factor' => 'ATX'],
            ['name' => 'be quiet! Straight Power 12 850W', 'type' => 'psu', 'model' => 'BN342', 'manufacturer' => 'be quiet!', 'power' => 850, 'form_factor' => 'ATX'],
            ['name' => 'be quiet! System Power 10 650W', 'type' => 'psu', 'model' => 'BN330', 'manufacturer' => 'be quiet!', 'power' => 650, 'form_factor' => 'ATX'],
            ['name' => 'Cooler Master MWE 650W', 'type' => 'psu', 'model' => 'MPE-6501-ACAAB', 'manufacturer' => 'Cooler Master', 'power' => 650, 'form_factor' => 'ATX'],
            ['name' => 'DeepCool PK550D 550W', 'type' => 'psu', 'model' => 'PK550D', 'manufacturer' => 'DeepCool', 'power' => 550, 'form_factor' => 'ATX'],
        ];

        // ============ ВИДЕОКАРТЫ ============
        $gpus = [
            ['name' => 'NVIDIA RTX 4090 24GB', 'type' => 'gpu', 'model' => 'RTX4090-24GB', 'manufacturer' => 'NVIDIA', 'power' => 450],
            ['name' => 'NVIDIA RTX 4080 16GB', 'type' => 'gpu', 'model' => 'RTX4080-16GB', 'manufacturer' => 'NVIDIA', 'power' => 320],
            ['name' => 'NVIDIA RTX 4070 Ti 12GB', 'type' => 'gpu', 'model' => 'RTX4070TI-12GB', 'manufacturer' => 'NVIDIA', 'power' => 285],
            ['name' => 'NVIDIA RTX 4070 12GB', 'type' => 'gpu', 'model' => 'RTX4070-12GB', 'manufacturer' => 'NVIDIA', 'power' => 200],
            ['name' => 'NVIDIA RTX 4060 Ti 8GB', 'type' => 'gpu', 'model' => 'RTX4060TI-8GB', 'manufacturer' => 'NVIDIA', 'power' => 160],
            ['name' => 'AMD Radeon RX 7900 XTX', 'type' => 'gpu', 'model' => 'RX7900XTX', 'manufacturer' => 'AMD', 'power' => 355],
            ['name' => 'AMD Radeon RX 7800 XT', 'type' => 'gpu', 'model' => 'RX7800XT', 'manufacturer' => 'AMD', 'power' => 263],
            ['name' => 'AMD Radeon RX 7700 XT', 'type' => 'gpu', 'model' => 'RX7700XT', 'manufacturer' => 'AMD', 'power' => 245],
        ];

        // ============ КОРПУСА ============
        $cases = [
            ['name' => 'Fractal Design Define 7', 'type' => 'case', 'model' => 'FD-C-DEF7A-01', 'manufacturer' => 'Fractal Design', 'form_factor' => 'ATX'],
            ['name' => 'Corsair 4000D Airflow', 'type' => 'case', 'model' => 'CC-9011200-WW', 'manufacturer' => 'Corsair', 'form_factor' => 'ATX'],
            ['name' => 'NZXT H5 Flow', 'type' => 'case', 'model' => 'CC-H51FB-01', 'manufacturer' => 'NZXT', 'form_factor' => 'ATX'],
            ['name' => 'be quiet! Pure Base 500DX', 'type' => 'case', 'model' => 'BGW42', 'manufacturer' => 'be quiet!', 'form_factor' => 'ATX'],
            ['name' => 'DeepCool MATREXX 40', 'type' => 'case', 'model' => 'DP-ATX-MATREXX40', 'manufacturer' => 'DeepCool', 'form_factor' => 'Micro-ATX'],
        ];

        // ============ КУЛЕРЫ / ОХЛАЖДЕНИЕ ============
        $coolers = [
            ['name' => 'Noctua NH-D15', 'type' => 'cooler', 'model' => 'NH-D15', 'manufacturer' => 'Noctua', 'power' => 5],
            ['name' => 'DeepCool AK620', 'type' => 'cooler', 'model' => 'AK620', 'manufacturer' => 'DeepCool', 'power' => 5],
            ['name' => 'Cooler Master Hyper 212', 'type' => 'cooler', 'model' => 'RR-212S-20PK-R1', 'manufacturer' => 'Cooler Master', 'power' => 4],
            ['name' => 'Corsair iCUE H150i RGB', 'type' => 'cooler', 'model' => 'CW-9060060-WW', 'manufacturer' => 'Corsair', 'power' => 6],
            ['name' => 'Arctic Liquid Freezer II 240', 'type' => 'cooler', 'model' => 'ACFRE00055A', 'manufacturer' => 'Arctic', 'power' => 5],
        ];

        $counter = 1;

        // Функция для добавления компонентов
        $addComponents = function($components, &$counter) {
            foreach ($components as $comp) {
                Component::updateOrCreate(
                    ['inventory_number' => $comp['type'] . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT)],
                    array_merge($comp, [
                        'serial_number' => strtoupper($comp['type']) . '-' . strtoupper(uniqid()),
                        'specifications' => $comp['specifications'] ?? '',
                        'purchase_date' => '2024-' . rand(1, 12) . '-' . rand(1, 28),
                        'status' => 'in_stock',
                        'inventory_number' => $comp['type'] . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT),
                    ])
                );
                $counter++;
            }
        };

        // Добавляем все компоненты
        $addComponents($processors, $counter);
        $addComponents($motherboards, $counter);
        $addComponents($rams, $counter);
        $addComponents($storages, $counter);
        $addComponents($psus, $counter);
        $addComponents($gpus, $counter);
        $addComponents($cases, $counter);
        $addComponents($coolers, $counter);

        // Создаем несколько готовых рабочих станций
        $workstations = [
            [
                'name' => 'Разработка - Максимальная',
                'inventory_number' => 'WS-DEV-001',
                'location_id' => $devLocation->id,
                'status' => 'active',
                'notes' => 'Для разработчиков высоконагруженных проектов',
                'components' => [
                    'processor' => 'Intel Core i9-14900K',
                    'motherboard' => 'ASUS ROG MAXIMUS Z790 HERO',
                    'ram' => 'Corsair Vengeance 32GB DDR5 6000MHz',
                    'storage' => 'Samsung 990 PRO 1TB NVMe',
                    'psu' => 'Corsair RM1000e 1000W',
                    'gpu' => 'NVIDIA RTX 4090 24GB',
                    'case' => 'Fractal Design Define 7',
                    'cooler' => 'Corsair iCUE H150i RGB',
                ]
            ],
            [
                'name' => 'Разработка - Средняя',
                'inventory_number' => 'WS-DEV-002',
                'location_id' => $devLocation->id,
                'status' => 'active',
                'notes' => 'Для разработчиков средней нагрузки',
                'components' => [
                    'processor' => 'Intel Core i7-14700K',
                    'motherboard' => 'MSI MPG Z790 CARBON WIFI',
                    'ram' => 'Kingston Fury 32GB DDR5 5600MHz',
                    'storage' => 'WD Black SN850X 1TB NVMe',
                    'psu' => 'Corsair RM850e 850W',
                    'gpu' => 'NVIDIA RTX 4070 Ti 12GB',
                    'case' => 'Corsair 4000D Airflow',
                    'cooler' => 'DeepCool AK620',
                ]
            ],
            [
                'name' => 'Тестирование',
                'inventory_number' => 'WS-TEST-001',
                'location_id' => $testLocation->id,
                'status' => 'active',
                'notes' => 'Для тестировщиков',
                'components' => [
                    'processor' => 'AMD Ryzen 7 7800X3D',
                    'motherboard' => 'ASUS ROG STRIX B650-F',
                    'ram' => 'G.Skill Trident Z5 RGB 32GB DDR5 6400MHz',
                    'storage' => 'Kingston KC3000 1TB NVMe',
                    'psu' => 'be quiet! Straight Power 12 850W',
                    'gpu' => 'NVIDIA RTX 4070 12GB',
                    'case' => 'NZXT H5 Flow',
                ]
            ],
            [
                'name' => 'Бухгалтерия',
                'inventory_number' => 'WS-ACC-001',
                'location_id' => $accountingLocation->id,
                'status' => 'active',
                'notes' => 'Офисная станция для бухгалтеров',
                'components' => [
                    'processor' => 'Intel Core i5-14400',
                    'motherboard' => 'MSI PRO B660M-A',
                    'ram' => 'Corsair Vengeance LPX 16GB DDR4 3200MHz',
                    'storage' => 'Samsung 870 EVO 1TB SATA',
                    'psu' => 'be quiet! System Power 10 650W',
                    'case' => 'DeepCool MATREXX 40',
                    'cooler' => 'Cooler Master Hyper 212',
                ]
            ],
            [
                'name' => 'Сервер',
                'inventory_number' => 'SVR-001',
                'location_id' => $serverLocation->id,
                'status' => 'active',
                'notes' => 'Сервер для хранения данных',
                'components' => [
                    'processor' => 'AMD Ryzen 9 7950X',
                    'motherboard' => 'ASUS ROG CROSSHAIR X670E HERO',
                    'ram' => 'Corsair Vengeance 32GB DDR5 6000MHz',
                    'storage' => 'Samsung 990 PRO 1TB NVMe',
                    'storage2' => 'Western Digital 2TB Blue',
                    'psu' => 'Corsair RM1000e 1000W',
                    'case' => 'Fractal Design Define 7',
                ]
            ],
        ];

        foreach ($workstations as $wsData) {
            $workstation = Workstation::updateOrCreate(
                ['inventory_number' => $wsData['inventory_number']],
                [
                    'name' => $wsData['name'],
                    'location_id' => $wsData['location_id'],
                    'status' => $wsData['status'],
                    'notes' => $wsData['notes'],
                ]
            );

            // Устанавливаем компоненты
            foreach ($wsData['components'] as $type => $componentName) {
                $component = Component::where('name', $componentName)->first();
                if ($component && $component->status == 'in_stock') {
                    $workstation->components()->attach($component->id, [
                        'installed_at' => '2024-' . rand(1, 12) . '-' . rand(1, 28),
                    ]);
                    $component->status = 'installed';
                    $component->save();
                }
            }
        }

        $this->command->info('✅ База данных успешно заполнена!');
        $this->command->info('📝 Логин: admin@arm.local');
        $this->command->info('🔑 Пароль: password');
        $this->command->info('📊 Добавлено компонентов: ' . ($counter - 1));
        $this->command->info('🖥️ Добавлено рабочих станций: ' . count($workstations));
    }
}
