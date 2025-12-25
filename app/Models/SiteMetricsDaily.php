<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteMetricsDaily extends Model
{
    use HasFactory;

    protected $table = 'site_metrics_daily';

    protected $fillable = [
        'site_id',
        'date',
        'ga_users',
        'ga_sessions',
        'ga_pageviews',
        'ga_engaged_sessions',
        'ga_engagement_rate',
        'ga_event_count',
        'gsc_clicks',
        'gsc_impressions',
        'gsc_ctr',
        'gsc_position',
    ];

    protected $casts = [
        'date' => 'date',
        'ga_engagement_rate' => 'float',
        'gsc_ctr' => 'float',
        'gsc_position' => 'float',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}
