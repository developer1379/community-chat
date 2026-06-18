@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto sm:my-12 px-0 sm:px-4">
    <!-- Brand Header -->
    <div class="text-center mb-8 px-4 sm:px-0">
        <div class="w-16 h-16 mx-auto bg-gradient-to-tr from-blue-600 to-indigo-500 rounded-2xl flex items-center justify-center shadow-xl shadow-blue-500/20 mb-6 transform -rotate-3 hover:rotate-0 transition-transform duration-300">
            <span class="material-symbols-outlined text-white text-3xl font-light">mail</span>
        </div>
        <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight mb-2">Verify Email</h1>
        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">We have sent a 6-digit verification code to your email.</p>
    </div>

    <!-- OTP Panel -->
    <div class="bg-white dark:bg-slate-900 rounded-none sm:rounded-[2rem] border-y sm:border border-slate-200 dark:border-slate-800 shadow-xl overflow-hidden relative">
        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500"></div>
        
        <div class="p-8 sm:p-10 space-y-6">
            <!-- Success / Error Alerts -->
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-xs font-bold leading-normal">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/20 border border-rose-500/20 text-rose-600 dark:text-rose-400 text-xs font-bold leading-normal">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/20 border border-rose-500/20 text-rose-600 dark:text-rose-400 text-xs font-bold leading-normal">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Standard Form -->
            <form action="{{ route('verify.otp') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

                <!-- OTP Code Field -->
                <div class="space-y-2 text-left">
                    <label for="otp" class="text-[11px] font-black text-slate-700 dark:text-slate-350 uppercase tracking-widest ml-1">One-Time Password (OTP)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-400 text-[18px]">key</span>
                        </span>
                        <input type="text" id="otp" name="otp" class="w-full bg-slate-50/50 dark:bg-slate-950/20 border border-slate-200 dark:border-slate-800 rounded-2xl pl-11 pr-4 py-3.5 text-slate-850 dark:text-white text-lg font-black tracking-[12px] text-center focus:bg-white dark:focus:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all placeholder:text-slate-400 dark:placeholder:text-slate-655 placeholder:font-medium shadow-inner shadow-slate-100/50 dark:shadow-none" placeholder="000000" maxlength="6" pattern="\d{6}" required autofocus autocomplete="one-time-code">
                    </div>
                </div>

                <!-- Submit Action -->
                <div class="pt-2">
                    <button type="submit" class="w-full relative group overflow-hidden bg-slate-900 dark:bg-slate-850 hover:bg-slate-800 text-sm font-bold text-white py-4 rounded-2xl shadow-lg shadow-slate-900/20 cursor-pointer transition-all border-0">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            Verify & Proceed
                            <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </span>
                        <div class="absolute inset-0 h-full w-full bg-gradient-to-r from-blue-600 to-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </button>
                </div>
            </form>

            <form action="{{ route('resend.otp') }}" method="POST" class="pt-2 text-center">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Didn't receive the email? 
                    <button type="submit" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline bg-transparent border-0 cursor-pointer p-0">
                        Resend Verification OTP
                    </button>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection
