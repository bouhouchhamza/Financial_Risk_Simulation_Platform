<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Startup extends Model
{
    protected $fillable = [
        'name',
        'activity_type',
        'initial_budget',
        'monthly_revenue',
        'monthly_expenses',
        'employees_count',
        'user_id'
    ];

    protected  $casts = [
        'initial_budget' => 'decimal:2',
        'monthly_revenue' => 'decimal:2',
        'monthly_expenses' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    public function simulations()
    {
        return $this->hasMany(Simulation::class);
    }
    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
