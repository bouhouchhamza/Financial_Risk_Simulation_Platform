<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FraudRule extends Model
{
    protected $fillable = [
        'name',
        'threshold_value',
        'is_active',
        'description',
    ];
    protected $casts = [
        'is_active' => 'boolean',
    ];
}
