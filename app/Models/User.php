<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Claim;
use App\Models\Policy;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use MongoDB\Laravel\Auth\User as Authenticatable;

/**
 * @property string $name
 * @property string $email
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * 🔥 FIX: Default attributes for new user documents in MongoDB
     * This forces every new record to have is_admin = false automatically.
     */
    protected $attributes = [
        'is_admin' => false,
        'role' => 'user', // Good practice to default the role string too!
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'role',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean', // Force MongoDB to always cast it to a real boolean
        ];
    }

    public function policies()
    {
        return $this->hasMany(Policy::class);
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    public function policyApplications()
    {
        return $this->hasMany(Policy::class, 'user_id');
    }

    public function isAdmin(): bool
{
    // 1. If explicitly true or a truthy string/integer, they are an admin
    if (filter_var($this->is_admin ?? false, FILTER_VALIDATE_BOOLEAN)) {
        return true;
    }

    // 2. Backup check: match by role string if is_admin didn't evaluate cleanly
    if (isset($this->role) && strtolower($this->role) === 'admin') {
        return true;
    }

    return false;
}
}