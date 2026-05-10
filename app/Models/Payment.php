<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Payment extends Model
{
    protected $connection = 'mongodb';

    protected $fillable = [
        'user_id',
        'policy_id',
        'amount',
        'payment_method',
        'transaction_id',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function policy()
    {
        return $this->belongsTo(Policy::class);
    }
}  