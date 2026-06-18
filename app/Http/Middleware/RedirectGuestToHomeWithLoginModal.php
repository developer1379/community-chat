<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectGuestToHomeWithLoginModal
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is authenticated, let them proceed
        if (Auth::check()) {
            return $next($request);
        }

        $currentRouteName = $request->route() ? $request->route()->getName() : null;

        // Allow POST requests to login & register
        if ($request->isMethod('post') && ($currentRouteName === 'login' || $currentRouteName === 'register')) {
            return $next($request);
        }

        // Allowed guest routes list
        $allowedRoutes = [
            'home',
            'register.check-username',
            'auth.google.redirect',
            'auth.google.callback',
            'media.proxy.attachment',
            'media.proxy.avatar',
            'sitemap',
        ];

        if ($currentRouteName && in_array($currentRouteName, $allowedRoutes)) {
            return $next($request);
        }

        // Fallback for root path or google auth paths
        if ($request->is('/') || $request->is('auth/google/*')) {
            return $next($request);
        }

        // For any other page, redirect to home page and trigger login modal
        return redirect()->route('home')->with('show_login_modal_redirect', true);
    }
}
