<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Policy extends Model
{
    protected $connection = 'mongodb';

    protected $fillable = [
        'user_id',
        'plan_id',
        'policy_number',
        'start_date',
        'end_date',
        'premium_paid',
        'payment_status',
        'status',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
