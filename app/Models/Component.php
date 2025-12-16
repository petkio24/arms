<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'category_id',
        'model',
        'serial_number',
        'inventory_number',
        'manufacturer',
        'specifications',
        'purchase_date',
        'status',
    ];
    protected $casts = [
        'purchase_date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function workstations()
    {
        return $this->belongsToMany(Workstation::class, 'workstation_components')
            ->withPivot('installed_at', 'removed_at', 'notes')
            ->withTimestamps();
    }

    // Отношение для текущей рабочей станции (где компонент установлен сейчас)
    public function currentWorkstation()
    {
        return $this->belongsToMany(Workstation::class, 'workstation_components')
            ->wherePivot('removed_at', null)
            ->withPivot('installed_at', 'removed_at', 'notes')
            ->withTimestamps()
            ->latest('pivot_installed_at');
    }

    // Accessor для получения текущей рабочей станции
    public function getCurrentWorkstationAttribute()
    {
        return $this->currentWorkstation()->first();
    }

    public function getTypeTextAttribute()
    {
        $types = self::getTypes();
        return $types[$this->type] ?? $this->type;
    }

    public function getStatusTextAttribute()
    {
        $statuses = self::getStatuses();
        return $statuses[$this->status] ?? $this->status;
    }

    // Типы комплектующих
    public static function getTypes()
    {
        return [
            'processor' => 'Процессор',
            'motherboard' => 'Материнская плата',
            'ram' => 'Оперативная память',
            'storage' => 'Накопитель',
            'gpu' => 'Видеокарта',
            'psu' => 'Блок питания',
            'case' => 'Корпус',
            'cooler' => 'Охлаждение',
            'monitor' => 'Монитор',
            'keyboard' => 'Клавиатура',
            'mouse' => 'Мышь',
            'network' => 'Сетевое оборудование',
            'other' => 'Прочее',
        ];
    }

    // Статусы
    public static function getStatuses()
    {
        return [
            'in_stock' => 'На складе',
            'installed' => 'Установлен',
            'defective' => 'Неисправен',
            'decommissioned' => 'Списан',
        ];
    }
}
