<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigHistory extends Model
{
    use HasFactory;

    protected $table = 'config_history';

    protected $fillable = [
        'workstation_id',
        'change_description',
        'components_before',
        'components_after',
        'change_type',
        'user_id',
    ];

    protected $casts = [
        'components_before' => 'array',
        'components_after' => 'array',
    ];

    public function workstation()
    {
        return $this->belongsTo(Workstation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Типы изменений
    public static function getChangeTypes()
    {
        return [
            'assembly' => 'Сборка',
            'upgrade' => 'Апгрейд',
            'repair' => 'Ремонт',
            'replacement' => 'Замена',
            'decommission' => 'Списание',
        ];
    }
}
