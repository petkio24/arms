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
}
