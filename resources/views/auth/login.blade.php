@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto my-12 px-4 sm:px-0">
    <!-- Brand Header -->
    <div class="text-center mb-8">
        <div class="w-16 h-16 mx-auto bg-gradient-to-tr from-blue-600 to-indigo-500 rounded-2xl flex items-center justify-center shadow-xl shadow-blue-500/20 mb-6 transform -rotate-3 hover:rotate-0 transition-transform duration-300">
            <span class="material-symbols-outlined text-white text-3xl font-light">forum</span>
        </div>
        <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Welcome Back</h1>
        <p class="text-sm font-medium text-slate-500">Sign in to XenProfessional to join the discussion.</p>
    </div>

    <!-- Login Panel -->
    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-xl overflow-hidden relative">
        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500"></div>
        
        <div class="p-8 sm:p-10 space-y-6">
            <!-- Social Login Button (Dummy) -->
            <button type="button" class="w-full flex items-center justify-center gap-3 px-4 py-3 bg-white border-2 border-slate-100 rounded-2xl text-sm font-bold text-slate-700 hover:bg-slate-50 hover:border-slate-200 hover:shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-slate-200">
                <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Continue with Google
            </button>

            <!-- Divider -->
            <div class="relative flex items-center py-2">
                <div class="flex-grow border-t border-slate-200"></div>
                <span class="flex-shrink-0 mx-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Or sign in with email</span>
                <div class="flex-grow border-t border-slate-200"></div>
            </div>

            <!-- Standard Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Email Field -->
                <div class="space-y-2">
                    <label for="email" class="text-[11px] font-black text-slate-700 uppercase tracking-widest ml-1">Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-400 text-[18px]">mail</span>
                        </span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full bg-slate-50/50 border border-slate-200 rounded-2xl pl-11 pr-4 py-3.5 text-slate-800 text-sm font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all placeholder:text-slate-400 placeholder:font-medium shadow-inner shadow-slate-100/50" placeholder="you@example.com" required autofocus>
                    </div>
                    @error('email')
                        <p class="text-xs text-rose-500 mt-1.5 ml-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between ml-1">
                        <label for="password" class="text-[11px] font-black text-slate-700 uppercase tracking-widest">Password</label>
                        <a href="#" class="text-[10px] font-bold text-blue-600 hover:text-blue-700 hover:underline">Forgot?</a>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-400 text-[18px]">lock</span>
                        </span>
                        <input type="password" id="password" name="password" class="w-full bg-slate-50/50 border border-slate-200 rounded-2xl pl-11 pr-4 py-3.5 text-slate-800 text-sm font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all placeholder:text-slate-400 placeholder:font-medium shadow-inner shadow-slate-100/50" placeholder="••••••••" required>
                    </div>
                    @error('password')
                        <p class="text-xs text-rose-500 mt-1.5 ml-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember me -->
                <div class="flex items-center ml-1">
                    <input type="checkbox" id="remember" name="remember" class="w-4.5 h-4.5 rounded-lg bg-slate-50 border-slate-300 text-blue-600 focus:ring-blue-500 focus:ring-offset-0 cursor-pointer transition-colors">
                    <label for="remember" class="text-[11px] text-slate-600 ml-2.5 font-bold cursor-pointer">Remember me next time</label>
                </div>

                <!-- Submit Action -->
                <div class="pt-4">
                    <button type="submit" class="w-full relative group overflow-hidden bg-slate-900 hover:bg-slate-800 text-sm font-bold text-white py-4 rounded-2xl shadow-lg shadow-slate-900/20 cursor-pointer transition-all">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            Sign In to Account
                            <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </span>
                        <div class="absolute inset-0 h-full w-full bg-gradient-to-r from-blue-600 to-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Footer Link -->
        <div class="bg-slate-50 border-t border-slate-100 p-6 text-center">
            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                Don't have an account? <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 hover:underline">Register here</a>
            </p>
        </div>
    </div>
</div>
@endsection
