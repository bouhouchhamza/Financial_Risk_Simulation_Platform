<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $fillable = [
        'startup_id',
        'transaction_id',
        'type',
        'severity',
        'message',
        'status',
        'data',
    ];
    protected  $casts = [

        'data' => 'array',
    ];
    public function startup()
    {
        return $this->belongsTo(Startup::class);
    }
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
