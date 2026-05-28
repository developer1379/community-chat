@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto my-12">
    <!-- Brand Header -->
    <div class="text-center mb-6">
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Create Account</h1>
        <p class="text-xs text-slate-500 mt-1">Join the XenProfessional community and begin contributing today.</p>
    </div>

    <!-- Registration Panel -->
    <div class="mui-card overflow-hidden bg-white border border-slate-200 shadow-lg">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">📝 Registration Details</span>
        </div>
        <form action="{{ route('register') }}" method="POST" class="p-6 space-y-5 bg-white">
            @csrf

            <!-- Username Field -->
            <div class="space-y-1.5">
                <label for="name" class="text-xs font-bold text-slate-705 uppercase tracking-wide">Username</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-850 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all placeholder:text-slate-400 font-semibold" placeholder="Choose a display username" required autofocus>
                @error('name')
                    <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Field -->
            <div class="space-y-1.5">
                <label for="email" class="text-xs font-bold text-slate-705 uppercase tracking-wide">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-850 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all placeholder:text-slate-400 font-semibold" placeholder="you@example.com" required>
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

            <!-- Password Confirmation Field -->
            <div class="space-y-1.5">
                <label for="password_confirmation" class="text-xs font-bold text-slate-705 uppercase tracking-wide">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-850 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all placeholder:text-slate-400 font-semibold" placeholder="••••••••" required>
            </div>

            <!-- Submit Action -->
            <div class="pt-2">
                <button type="submit" class="w-full xen-button text-xs font-bold text-white py-3 rounded-xl shadow-md cursor-pointer">
                    Create My Account
                </button>
            </div>

            <div class="text-center pt-4 border-t border-slate-100 text-xs text-slate-500">
                Already registered? <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Sign In here</a>
            </div>
        </form>
    </div>
</div>
@endsection
