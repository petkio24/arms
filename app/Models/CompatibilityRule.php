<?php
// app/Models/CompatibilityRule.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompatibilityRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'component_type',
        'compatible_with',
        'rule_type',
        'description',
        'constraints',
    ];

    protected $casts = [
        'constraints' => 'array',
    ];

    public static function getRuleTypes()
    {
        return [
            'required' => 'Обязательный компонент',
            'optional' => 'Опциональный',
            'incompatible' => 'Несовместимо',
        ];
    }
}
