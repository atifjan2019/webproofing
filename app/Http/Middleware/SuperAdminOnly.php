<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminOnly
{
    /**
     * Handle an incoming request.
     * Only allow access if the user is a super admin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->is_super_admin) {
            abort(403, 'Access denied. Super admin privileges required.');
        }

        return $next($request);
    }
}
