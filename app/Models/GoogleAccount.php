<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoogleAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'google_user_id',
        'email',
        'access_token',
        'refresh_token',
        'expires_at',
    ];

    /**
     * Encrypt tokens at rest
     */
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Encrypt access_token when setting
     */
    public function setAccessTokenAttribute($value)
    {
        $this->attributes['access_token'] = $value ? encrypt($value) : null;
    }

    /**
     * Decrypt access_token when getting
     */
    public function getAccessTokenAttribute($value)
    {
        try {
            return $value ? decrypt($value) : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Encrypt refresh_token when setting
     */
    public function setRefreshTokenAttribute($value)
    {
        $this->attributes['refresh_token'] = $value ? encrypt($value) : null;
    }

    /**
     * Decrypt refresh_token when getting
     */
    public function getRefreshTokenAttribute($value)
    {
        try {
            return $value ? decrypt($value) : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Check if access token is expired
     */
    public function isTokenExpired(): bool
    {
        if (!$this->expires_at) {
            return true;
        }

        // Consider expired 5 minutes before actual expiry
        return $this->expires_at->subMinutes(5)->isPast();
    }

    /**
     * Check if we have a valid refresh token
     */
    public function hasRefreshToken(): bool
    {
        return !empty($this->refresh_token);
    }

    /**
     * Relationship to User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
