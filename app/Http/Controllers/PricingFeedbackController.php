<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PricingFeedback;

class PricingFeedbackController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'price_opinion' => 'nullable|string|in:too_expensive,fair,good_deal',
            'suggestion' => 'nullable|string|max:1000',
        ]);

        if (empty($validated['price_opinion']) && empty($validated['suggestion'])) {
            return back()->with('error', 'Please provide either a vote or a suggestion.');
        }

        PricingFeedback::create([
            'user_id' => auth()->id(),
            'price_opinion' => $validated['price_opinion'] ?? null,
            'suggestion' => $validated['suggestion'] ?? null,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Thank you for your feedback! We appreciate your input.');
    }
}
