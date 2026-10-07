<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsManager
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isManager()) {
            abort(403, 'Toegang geweigerd: Alleen managers hebben toegang tot deze beheersomgeving.');
        }

        return $next($request);
    }
}
