<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteGoogleProperty extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id',
        'ga4_property_id',
        'ga4_property_name',
        'ga4_connected',
        'gsc_domain',        // Domain property only (sc-domain:example.com)
        'gsc_connected',
    ];

    protected $casts = [
        'ga4_connected' => 'boolean',
        'gsc_connected' => 'boolean',
    ];

    /**
     * Get the site this property belongs to.
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Check if GA4 is connected.
     */
    public function isGa4Connected(): bool
    {
        return $this->ga4_connected && !empty($this->ga4_property_id);
    }

    /**
     * Check if GSC is connected.
     */
    public function isGscConnected(): bool
    {
        return $this->gsc_connected && !empty($this->gsc_domain);
    }

    /**
     * Check if both GA4 and GSC are connected.
     */
    public function isFullyConnected(): bool
    {
        return $this->isGa4Connected() && $this->isGscConnected();
    }

    /**
     * Get the GSC domain property URL (sc-domain:example.com format).
     */
    public function getGscPropertyUrl(): ?string
    {
        return $this->gsc_domain;
    }

    /**
     * Get the clean domain from GSC property.
     */
    public function getGscDomainClean(): ?string
    {
        if (!$this->gsc_domain) {
            return null;
        }

        // Remove sc-domain: prefix
        return str_replace('sc-domain:', '', $this->gsc_domain);
    }
}
