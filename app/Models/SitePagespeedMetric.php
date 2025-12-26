<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SitePagespeedMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id',
        'strategy',
        'performance_score',
        'seo_score',
        'accessibility_score',
        'best_practices_score',
        'lcp',
        'fcp',
        'cls',
        'tbt',
        'speed_index',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}