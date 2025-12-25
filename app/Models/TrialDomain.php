<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrialDomain extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain',
        'user_id',
        'site_id',
        'trial_started_at',
        'trial_ends_at',
        'is_expired',
    ];

    protected $casts = [
        'trial_started_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'is_expired' => 'boolean',
    ];

    /**
     * Get the user who used this trial.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the site associated with this trial.
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Check if a domain has already been used for a trial.
     */
    public static function hasTrialBeenUsed(string $domain): bool
    {
        return self::where('domain', strtolower($domain))->exists();
    }

    /**
     * Create a new trial for a domain.
     */
    public static function createTrial(Site $site): self
    {
        return self::create([
            'domain' => $site->domain,
            'user_id' => $site->user_id,
            'site_id' => $site->id,
            'trial_started_at' => now(),
            'trial_ends_at' => now()->addDays(7),
            'is_expired' => false,
        ]);
    }

    /**
     * Check if the trial is still active.
     */
    public function isActive(): bool
    {
        return !$this->is_expired && now()->lt($this->trial_ends_at);
    }

    /**
     * Get remaining trial days.
     */
    public function remainingDays(): int
    {
        if ($this->is_expired || now()->gte($this->trial_ends_at)) {
            return 0;
        }

        return now()->diffInDays($this->trial_ends_at);
    }
}
