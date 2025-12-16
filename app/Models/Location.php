<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'building',
        'floor',
        'room',
        'description',
        'responsible_person',
        'phone',
        'email',
    ];

    public function workstations()
    {
        return $this->hasMany(Workstation::class);
    }

    public function getFullAddressAttribute()
    {
        $parts = [];
        if ($this->building) $parts[] = "Корпус {$this->building}";
        if ($this->floor) $parts[] = "Этаж {$this->floor}";
        if ($this->room) $parts[] = "Кабинет {$this->room}";

        return implode(', ', $parts) ?: 'Адрес не указан';
    }

    public function getWorkstationsCountAttribute()
    {
        return $this->workstations()->count();
    }
}
