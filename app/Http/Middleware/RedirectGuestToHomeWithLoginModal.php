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

        // Allowed guest routes list (by route name)
        $allowedRoutes = [
            'home',
            'login',
            'register',
            'register.check-username',
            'auth.google.redirect',
            'auth.google.callback',
            'media.proxy.attachment',
            'media.proxy.avatar',
            'sitemap',
            'verify.otp',
            'resend.otp',
            'rules',
            'search',
            'categories.show',
            'threads.show',
            'members.index',
            'rankings.index',
            'search.history.clear',
            'search.history.delete',
        ];

        if ($currentRouteName && in_array($currentRouteName, $allowedRoutes)) {
            return $next($request);
        }

        // Path-based whitelist fallback for POST requests or routes without names
        $allowedPaths = [
            '/',
            'login',
            'register',
            'register/check-username',
            'verify-otp',
            'resend-otp',
            'auth/google/*',
            'media/proxy/*',
            'sitemap',
            'rules',
            'search',
            'search/history/*',
            'categories/*',
            'threads/*',
            'members',
            'rankings',
        ];

        foreach ($allowedPaths as $path) {
            if ($request->is($path)) {
                return $next($request);
            }
        }

        // For any other page, redirect to home page and trigger login modal
        return redirect()->route('home')->with('show_login_modal_redirect', true);
    }
}
