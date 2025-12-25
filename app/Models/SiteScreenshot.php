<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteScreenshot extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id',
        'url',
        'image_url',
        'image_path',
        'device_type',
        'width',
        'height',
        'status',
        'error_message',
        'captured_at',
        'load_time_ms',
    ];

    protected $casts = [
        'width' => 'integer',
        'height' => 'integer',
        'captured_at' => 'datetime',
    ];

    /**
     * Get the site this screenshot belongs to.
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Scope for pending screenshots.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for captured screenshots.
     */
    public function scopeCaptured($query)
    {
        return $query->where('status', 'captured');
    }

    /**
     * Scope for failed screenshots.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
    /**
     * Get the full source URL for the screenshot image.
     */
    public function getImageSrcAttribute()
    {
        if ($this->image_path) {
            $base = rtrim(env('SCREENSHOT_API_BASE'), '/');
            $token = env('SCREENSHOT_IMAGE_TOKEN');
            return "{$base}/image/{$this->image_path}?token={$token}";
        }

        return $this->image_url;
    }
}
