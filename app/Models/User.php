<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'otp_verified',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        // if you are on Laravel 10+ you may keep 'password' => 'hashed' and remove the mutator below.
        // keeping the mutator makes this model backward-compatible.
        'otp_verified' => 'boolean',
    ];

    /**
     * Mutator: safely hash a password only when it's plain text.
     * If the incoming value already appears hashed, we leave it as-is.
     */
    public function setPasswordAttribute($value): void
    {
        if ($value === null || $value === '') {
            $this->attributes['password'] = $value;
            return;
        }

        // Hash::info returns ['algo'=>0,...] for non-hashed strings.
        // If algo === 0, treat as plain text and hash it.
        try {
            $info = Hash::info($value);
            $isHashed = isset($info['algo']) && $info['algo'] !== 0;
        } catch (\Throwable $e) {
            // defensive: assume plain text if Hash::info fails
            $isHashed = false;
        }

        $this->attributes['password'] = $isHashed ? $value : Hash::make($value);
    }

    /* Relationships */
    public function doctor()
    {
        return $this->hasOne(\App\Models\Doctor::class, 'user_id');
    }
}
