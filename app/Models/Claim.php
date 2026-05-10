<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Claim extends Model
{
    protected $connection = 'mongodb';

    protected $fillable = [
        'policy_id',
        'user_id',
        'claim_reason',
        'documents',
        'claim_amount',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'documents' => 'array',
        'submitted_at' => 'datetime',
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