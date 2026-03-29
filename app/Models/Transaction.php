<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'startup_id',
        'type',
        'amount',
        'transaction_date',
        'description',
        'is_suspicious',
    ];
    protected  $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
        'is_suspicious' => 'boolean'
    ];


    public function startup()
    {
        return $this->belongsTo(Startup::class);
    }
    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }
}
