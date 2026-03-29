<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimulationResult extends Model
{
    protected $fillable = [
        'simulation_id',
        'month_number',
        'revenue',
        'expenses',
        'cashflow',
        'is_critical',
    ];
    protected $casts = [
        'revenue' => 'decimal:2',
        'expenses' => 'decimal:2',
        'cashflow' => 'decimal:2',
        'is_critical' => 'boolean',
    ];

    public function simulation()
    {
        return $this->belongsTo(Simulation::class);
    }
}
