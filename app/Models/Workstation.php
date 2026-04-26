<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workstation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'inventory_number',
        'location_id',
        'status',
        'initial_config',
        'notes',
    ];

    protected $casts = [
        'initial_config' => 'array',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function components()
    {
        return $this->belongsToMany(Component::class, 'workstation_components')
            ->withPivot('installed_at', 'removed_at', 'notes')
            ->withTimestamps();
    }

    public function currentComponents()
    {
        return $this->belongsToMany(Component::class, 'workstation_components')
            ->wherePivot('removed_at', null)
            ->withPivot('installed_at', 'removed_at', 'notes')
            ->withTimestamps();
    }

    public function configHistory()
    {
        return $this->hasMany(ConfigHistory::class);
    }

    // Статусы рабочих станций
    public static function getStatuses()
    {
        return [
            'active' => 'Активна',
            'maintenance' => 'На обслуживании',
            'decommissioned' => 'Списана',
        ];
    }

    public function getStatusTextAttribute()
    {
        $statuses = [
            'active' => 'Активна',
            'maintenance' => 'На обслуживании',
            'decommissioned' => 'Списана',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    // Получить текущую конфигурацию в виде массива
    public function getCurrentConfigAttribute()
    {
        return $this->currentComponents()
            ->get()
            ->groupBy('type')
            ->map(function ($components) {
                return $components->map(function ($component) {
                    return [
                        'id' => $component->id,
                        'name' => $component->name,
                        'model' => $component->model,
                        'serial_number' => $component->serial_number,
                        'inventory_number' => $component->inventory_number,
                        'installed_at' => $component->pivot->installed_at,
                    ];
                });
            })
            ->toArray();
    }

    // Accessor для получения количества текущих компонентов
    public function getCurrentComponentsCountAttribute()
    {
        return $this->currentComponents()->count();
    }

    public function getLocationInfoAttribute()
    {
        if (!$this->location) {
            return 'Не указано';
        }

        return $this->location->name . ' (' . $this->location->room . ')';
    }

    public function checkCompatibility($newComponentId = null)
    {
        $currentComponents = $this->currentComponents()->get();
        $errors = [];
        $warnings = [];

        if ($newComponentId) {
            $newComponent = Component::find($newComponentId);
            if ($newComponent) {
                $currentComponents = $currentComponents->push($newComponent);
            }
        }

        // 1. Проверка основных компонентов
        $hasMotherboard = false;
        $hasProcessor = false;
        $hasRam = false;
        $hasStorage = false;
        $hasPowerSupply = false;

        foreach ($currentComponents as $component) {
            switch ($component->type) {
                case 'motherboard':
                    $hasMotherboard = true;
                    break;
                case 'processor':
                    $hasProcessor = true;
                    break;
                case 'ram':
                    $hasRam = true;
                    break;
                case 'storage':
                    $hasStorage = true;
                    break;
                case 'psu':
                    $hasPowerSupply = true;
                    break;
            }
        }

        // Минимальные требования
        if (!$hasMotherboard) {
            $errors[] = 'Отсутствует материнская плата';
        }
        if (!$hasProcessor) {
            $errors[] = 'Отсутствует процессор';
        }
        if (!$hasRam) {
            $errors[] = 'Отсутствует оперативная память';
        }

        // 2. Проверка совместимости компонентов между собой
        $componentsByType = $currentComponents->groupBy('type');

        // Проверка CPU и материнской платы
        if ($hasProcessor && $hasMotherboard) {
            $cpu = $componentsByType['processor']->first();
            $motherboard = $componentsByType['motherboard']->first();

            if ($cpu->socket && $motherboard->socket && $cpu->socket !== $motherboard->socket) {
                $errors[] = "Несовместимость: сокет процессора ({$cpu->socket}) не подходит к сокету материнской платы ({$motherboard->socket})";
            } else {
                $warnings[] = "Рекомендуется проверить совместимость сокетов процессора и материнской платы";
            }
        }

        // Проверка RAM и материнской платы
        if ($hasRam && $hasMotherboard) {
            $ram = $componentsByType['ram']->first();
            $motherboard = $componentsByType['motherboard']->first();

            if ($ram->ram_type && $motherboard->ram_type && $ram->ram_type !== $motherboard->ram_type) {
                $errors[] = "Несовместимость: тип RAM ({$ram->ram_type}) не поддерживается материнской платой ({$motherboard->ram_type})";
            }
        }

        // Проверка блока питания
        if ($hasPowerSupply) {
            $psu = $componentsByType['psu']->first();

            // Рассчитываем примерное потребление
            $totalPower = 0;
            foreach ($currentComponents as $component) {
                if ($component->power_watt) {
                    $totalPower += $component->power_watt;
                }
            }

            if ($totalPower > 0 && $psu->power_watt && $psu->power_watt < $totalPower) {
                $errors[] = "Недостаточная мощность блока питания: {$psu->power_watt}W, требуется примерно {$totalPower}W";
            } elseif ($psu->power_watt && $totalPower > 0) {
                $warnings[] = "Рекомендуемый запас мощности БП: текущий {$psu->power_watt}W, потребление ~{$totalPower}W";
            }
        }

        // 3. Проверка форм-фактора
        if ($hasMotherboard) {
            $motherboard = $componentsByType['motherboard']->first();
            $case = $componentsByType['case']->first();

            if ($case && $motherboard->form_factor && $case->form_factor) {
                // Проверка совместимости форм-фактора
                $compatible = [
                    'ATX' => ['ATX', 'E-ATX'],
                    'Micro-ATX' => ['ATX', 'Micro-ATX'],
                    'Mini-ITX' => ['ATX', 'Micro-ATX', 'Mini-ITX'],
                ];

                if (!in_array($motherboard->form_factor, $compatible[$case->form_factor] ?? [])) {
                    $errors[] = "Форм-фактор материнской платы ({$motherboard->form_factor}) не подходит для корпуса ({$case->form_factor})";
                }
            }
        }

        // 4. Проверка количества компонентов
        $maxRamSlots = 4; // Можно получать из характеристик материнской платы
        if ($componentsByType['ram'] && $componentsByType['ram']->count() > $maxRamSlots) {
            $warnings[] = "Количество планок RAM ({$componentsByType['ram']->count()}) превышает типичное количество слотов";
        }

        return [
            'compatible' => count($errors) === 0,
            'errors' => $errors,
            'warnings' => $warnings,
        ];
    }


    public function checkComponentCompatibility(Component $newComponent)
    {
        $errors = [];
        $currentComponents = $this->currentComponents()->get();

        // Группируем текущие компоненты по типу
        $componentsByType = [];
        foreach ($currentComponents as $comp) {
            $componentsByType[$comp->type] = $comp;
        }

        // 1. Проверка процессора и материнской платы
        if ($newComponent->type == 'processor') {
            if (isset($componentsByType['motherboard'])) {
                $motherboard = $componentsByType['motherboard'];
                if ($newComponent->socket && $motherboard->socket) {
                    if ($newComponent->socket !== $motherboard->socket) {
                        $errors[] = "Процессор (сокет {$newComponent->socket}) не совместим с материнской платой (сокет {$motherboard->socket})";
                    }
                }
            }
        }

        // 2. Проверка материнской платы и процессора
        if ($newComponent->type == 'motherboard') {
            if (isset($componentsByType['processor'])) {
                $processor = $componentsByType['processor'];
                if ($newComponent->socket && $processor->socket) {
                    if ($newComponent->socket !== $processor->socket) {
                        $errors[] = "Материнская плата (сокет {$newComponent->socket}) не совместима с процессором (сокет {$processor->socket})";
                    }
                }
            }
        }

        // 3. Проверка оперативной памяти
        if ($newComponent->type == 'ram') {
            if (isset($componentsByType['motherboard'])) {
                $motherboard = $componentsByType['motherboard'];
                if ($newComponent->ram_type && $motherboard->ram_type) {
                    if ($newComponent->ram_type !== $motherboard->ram_type) {
                        $errors[] = "Оперативная память (тип {$newComponent->ram_type}) не совместима с материнской платой (тип {$motherboard->ram_type})";
                    }
                }
            }
        }

        // 4. Проверка блока питания
        if ($newComponent->type == 'psu') {
            // Рассчитываем примерное потребление всех компонентов
            $totalPower = $newComponent->power ?? 0;
            foreach ($currentComponents as $comp) {
                if ($comp->power) {
                    $totalPower += $comp->power;
                }
            }

            if ($newComponent->power && $totalPower > $newComponent->power) {
                $errors[] = "Мощность блока питания ({$newComponent->power}W) недостаточна (требуется ~{$totalPower}W)";
            }
        }

        // 5. Проверка форм-фактора
        if ($newComponent->type == 'case') {
            if (isset($componentsByType['motherboard'])) {
                $motherboard = $componentsByType['motherboard'];
                if ($newComponent->form_factor && $motherboard->form_factor) {
                    $compatibleFormFactors = [
                        'ATX' => ['ATX', 'Micro-ATX'],
                        'Micro-ATX' => ['ATX', 'Micro-ATX', 'Mini-ITX'],
                        'Mini-ITX' => ['ATX', 'Micro-ATX', 'Mini-ITX'],
                    ];

                    if (!in_array($motherboard->form_factor, $compatibleFormFactors[$newComponent->form_factor] ?? [])) {
                        $errors[] = "Корпус (форм-фактор {$newComponent->form_factor}) не совместим с материнской платой ({$motherboard->form_factor})";
                    }
                }
            }
        }

        return [
            'compatible' => count($errors) === 0,
            'errors' => $errors
        ];
    }
}
