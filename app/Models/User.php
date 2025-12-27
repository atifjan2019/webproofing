<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'stripe_customer_id',
        'is_super_admin',
        'has_free_access',
        'is_suspended',
        'suspension_reason',
        'service_speed_test',
        'service_screenshots',
        'service_google',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_super_admin' => 'boolean',
        'has_free_access' => 'boolean',
        'is_suspended' => 'boolean',
        'service_speed_test' => 'boolean',
        'service_screenshots' => 'boolean',
        'service_google' => 'boolean',
    ];

    /**
     * Get all sites owned by this user.
     */
    public function sites(): HasMany
    {
        return $this->hasMany(Site::class);
    }

    /**
     * Get all trial domains used by this user.
     */
    public function trialDomains(): HasMany
    {
        return $this->hasMany(TrialDomain::class);
    }

    /**
     * Get the Google account for this user.
     */
    public function googleAccount(): HasOne
    {
        return $this->hasOne(GoogleAccount::class);
    }

    /**
     * Check if user has connected their Google account.
     */
    public function hasGoogleAccount(): bool
    {
        return $this->googleAccount !== null;
    }

    /**
     * Get the user's subscription.
     */
    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class);
    }

    /**
     * Check if user has an active subscription.
     */
    public function hasActiveSubscription(): bool
    {
        return $this->subscription && $this->subscription->isActive();
    }

    /**
     * Check if user is on trial.
     */
    public function onTrial(): bool
    {
        return $this->subscription && $this->subscription->onTrial();
    }
}
