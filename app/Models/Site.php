<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'raw_url',
        'domain',
        'name',
        'status',
        'ga4_property_id',
        'ga4_property_name',
        'gsc_site_url',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Get the user that owns the site.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the trial domain record for this site.
     */
    public function trialDomain(): HasOne
    {
        return $this->hasOne(TrialDomain::class);
    }

    /**
     * Get all screenshots for this site.
     */
    public function screenshots(): HasMany
    {
        return $this->hasMany(SiteScreenshot::class);
    }

    /**
     * Get the Google properties for this site.
     */
    public function googleProperty(): HasOne
    {
        return $this->hasOne(SiteGoogleProperty::class);
    }

    /**
     * Get daily metrics for this site.
     */
    public function metricsDaily(): HasMany
    {
        return $this->hasMany(SiteMetricsDaily::class)->orderBy('date', 'desc');
    }

    /**
     * Check if GA4 is configured for this site.
     */
    public function hasGa4(): bool
    {
        return !empty($this->ga4_property_id);
    }

    /**
     * Check if GSC is configured for this site.
     */
    public function hasGsc(): bool
    {
        return !empty($this->gsc_site_url);
    }

    /**
     * Normalize a URL to extract the domain.
     */
    public static function normalizeDomain(string $url): string
    {
        $url = trim($url);

        // Add protocol if missing
        if (!preg_match('/^https?:\/\//i', $url)) {
            $url = 'https://' . $url;
        }

        $parsed = parse_url($url);
        $host = $parsed['host'] ?? $url;

        // Remove www prefix
        $host = preg_replace('/^www\./i', '', $host);

        return strtolower($host);
    }

    /**
     * Check if this site is on trial.
     */
    public function isOnTrial(): bool
    {
        return $this->trialDomain && !$this->trialDomain->is_expired && now()->lt($this->trialDomain->trial_ends_at);
    }
    /**
     * Get pagespeed metrics for this site.
     */
    public function pagespeedMetrics(): HasMany
    {
        return $this->hasMany(SitePagespeedMetric::class);
    }

    protected static function booted()
    {
        static::created(function ($site) {
            // Auto-run PageSpeed test on creation
            \App\Jobs\RunPageSpeedAnalysis::dispatch($site, 'mobile');
            \App\Jobs\RunPageSpeedAnalysis::dispatch($site, 'desktop');
        });
    }
}
