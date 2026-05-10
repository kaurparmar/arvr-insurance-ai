<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Policy extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'policies';

    protected $fillable = [
        'user_id',
        'plan_id',

        // policy details
        'policy_number',
        'start_date',
        'end_date',

        // financial info
        'premium_amount',
        'coverage_amount',

        // policy status
        'status',

        // payment info
        'payment_status',

        // optional
        'documents',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'documents' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}

// Recommended status values:

// active
// expired
// cancelled
// pending

// Recommended payment status:

// paid
// unpaid
// failed