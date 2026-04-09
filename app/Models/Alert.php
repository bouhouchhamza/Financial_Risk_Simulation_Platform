<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $fillable = [
        'startup_id',
        'transaction_id',
        'type',
        'rule_code',
        'severity',
        'message',
        'status',
        'data',
        'review_status',
        'review_note',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [

        'data' => 'array',
        'reviewed_at' => 'datetime',
    ];

    public function startup()
    {
        return $this->belongsTo(Startup::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
