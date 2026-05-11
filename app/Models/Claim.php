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
        'incident_date',
        'incident_location',
        'witnesses',
        'medical_reports',
        'police_report',
        'damage_photos',
        'other_documents',
        'claim_amount',
        'status',
        'submitted_at',
        'approved_at',
        'processed_by',
        'notes',
    ];

    protected $casts = [
        'incident_date' => 'datetime',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'medical_reports' => 'array',
        'damage_photos' => 'array',
        'other_documents' => 'array',
        'witnesses' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function policy()
    {
        return $this->belongsTo(Policy::class);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'amber',
            'under_review' => 'blue',
            'approved' => 'emerald',
            'rejected' => 'rose',
            'paid' => 'green',
            default => 'slate'
        };
    }

    public function getStatusIconAttribute()
    {
        return match($this->status) {
            'pending' => '⏳',
            'under_review' => '🔍',
            'approved' => '✅',
            'rejected' => '❌',
            'paid' => '💰',
            default => '📋'
        };
    }
}