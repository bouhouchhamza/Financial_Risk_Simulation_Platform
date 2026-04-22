<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'title',
        'type',
        'startup_id',
        'simulation_id',
        'summary',
        'risk_level',
        'recommendations',
        'file_path',
        'generated_at',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
    ];

    public function startup()
    {
        return $this->belongsTo(Startup::class);
    }

    public function simulation()
    {
        return $this->belongsTo(Simulation::class);
    }
}
