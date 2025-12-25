<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiteAnalyticsController extends Controller
{
    public function show(Site $site)
    {
        if ($site->user_id !== Auth::id()) {
            abort(403);
        }

        return view('sites.analytics', [
            'site' => $site,
        ]);
    }
}
