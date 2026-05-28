@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto my-12">
    <!-- Header -->
    <div class="text-center mb-6">
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Welcome Back</h1>
        <p class="text-xs text-slate-500 mt-1">Sign in to XenProfessional to participate in discussions and upload media.</p>
    </div>

    <!-- Login Panel -->
    <div class="mui-card overflow-hidden bg-white border border-slate-200 shadow-lg">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">🔑 Sign In Details</span>
        </div>
        <form action="{{ route('login') }}" method="POST" class="p-6 space-y-5 bg-white">
            @csrf

            <!-- Email Field -->
            <div class="space-y-1.5">
                <label for="email" class="text-xs font-bold text-slate-705 uppercase tracking-wide">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-850 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all placeholder:text-slate-400 font-semibold" placeholder="you@example.com" required autofocus>
                @error('email')
                    <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Field -->
            <div class="space-y-1.5">
                <label for="password" class="text-xs font-bold text-slate-705 uppercase tracking-wide">Password</label>
                <input type="password" id="password" name="password" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-850 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all placeholder:text-slate-400 font-semibold" placeholder="••••••••" required>
                @error('password')
                    <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember me checkbox -->
            <div class="flex items-center">
                <input type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded bg-slate-50 border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                <label for="remember" class="text-xs text-slate-650 ml-2 font-bold cursor-pointer">Remember me next time</label>
            </div>

            <!-- Submit Action -->
            <div class="pt-2">
                <button type="submit" class="w-full xen-button text-xs font-bold text-white py-3 rounded-xl shadow-md cursor-pointer">
                    Sign In
                </button>
            </div>

            <div class="text-center pt-4 border-t border-slate-100 text-xs text-slate-500">
                Don't have an account? <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">Register here</a>
            </div>
        </form>
    </div>
</div>
@endsection
