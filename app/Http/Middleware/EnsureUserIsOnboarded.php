<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsOnboarded
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !Auth::user()->is_onboarded) {
            // Check if requesting setup routes or logout or username checking
            if (!$request->routeIs('register.setup-profile') && 
                !$request->routeIs('register.save-setup-profile') && 
                !$request->routeIs('register.check-username') && 
                !$request->routeIs('logout')) {
                return redirect()->route('register.setup-profile');
            }
        }

        return $next($request);
    }
}
