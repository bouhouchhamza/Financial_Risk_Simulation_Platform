<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FraudRule extends Model
{
    protected $fillable = [
        'name',
        'code',
        'threshold_value',
        'score_weight',
        'decision_if_matched',
        'is_active',
        'description',
    ];
    protected $casts = [
        'is_active' => 'boolean',
    ];
}
