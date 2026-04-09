<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Simulation extends Model
{
    protected $fillable = [
        'startup_id',
        'duration',
        'total_revenue',
        'total_expenses',
        'final_cashflow',
        'risk_level',
    ];
    protected  $casts = [
        'total_revenue' => 'decimal:2',
        'total_expenses' => 'decimal:2',
        'final_cashflow' => 'decimal:2'
    ];


    public function startup()
    {
        return $this->belongsTo(Startup::class);
    }
    public function simulationResults()
    {
        return $this->hasMany(SimulationResult::class);
    }
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
