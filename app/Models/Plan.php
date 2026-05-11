<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Plan extends Model
{
    protected $connection = 'mongodb';

    protected $fillable = [
        'name',
        'description',
        'coverage_amount',
        'premium_amount',
        'duration_years',
        'features',
    ];

    protected $casts = [
        'features' => 'array',
    ];

    public function policies()
    {
        return $this->hasMany(Policy::class);
    }
}
