<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingFeedback extends Model
{
    use HasFactory;

    protected $table = 'pricing_feedbacks';

    protected $fillable = ['user_id', 'price_opinion', 'suggestion', 'ip_address'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
