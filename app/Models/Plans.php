<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'title',
        'category',
        'description',
        'benefits',
        'premium',
        'coverage_amount',
        'duration',
        'status',
    ];

    protected $casts = [
        'benefits' => 'array',
    ];

    public function policies()
    {
        return $this->hasMany(Policy::class);
    }
}