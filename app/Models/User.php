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
     * Default attributes for new user documents in MongoDB
     */
    protected $attributes = [
        'is_admin' => false,
        'role' => 'user', 
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
            'is_admin' => 'boolean', 
        ];
    }

    public function policies()
    {
        return $this->hasMany(Policy::class, 'user_id', '_id');
    }

    public function claims()
    {
        return $this->hasMany(Claim::class, 'user_id', '_id');
    }

    public function policyApplications()
    {
        return $this->hasMany(Policy::class, 'user_id');
    }

    /**
     * Production-Safe Admin Role Check
     */
    public function isAdmin(): bool
    {
        // Fetch raw attribute safely bypassing MongoDB missing field exceptions
        $isAdminRaw = $this->getAttribute('is_admin');

        // 1. If explicitly true or a truthy string/integer, they are an admin
        if (filter_var($isAdminRaw ?? false, FILTER_VALIDATE_BOOLEAN)) {
            return true;
        }

        // 2. Backup check: match by role string if is_admin didn't evaluate cleanly
        $roleRaw = $this->getAttribute('role');
        if (isset($roleRaw) && strtolower($roleRaw) === 'admin') {
            return true;
        }

        return false;
    }
}