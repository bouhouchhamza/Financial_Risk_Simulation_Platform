<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'startup_id',
        'simulation_id',
        'summary',
        'risk_level',
        'recommendations',
    ];
    public function  startup()
    {
        return $this->belongsTo(Startup::class);
    }
    public function simulation()
    {
        return $this->belongsTo(Simulation::class);
    }
}
