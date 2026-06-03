@extends('layouts.admin')

@section('content')
<div class="space-y-8 max-w-4xl mx-auto text-left">
    <!-- Premium Settings Header Banner -->
    <div class="relative rounded-3xl overflow-hidden shadow-lg border border-slate-200 bg-gradient-to-r from-slate-800 via-slate-900 to-indigo-950 p-6 sm:p-10 text-white">
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 space-y-3">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-red-650/40 text-red-200 border border-red-500/30 uppercase tracking-widest leading-none">
                <span class="material-symbols-outlined text-xs">manage_accounts</span> System Admin
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight font-sans">
                Admin Account Settings
            </h1>
            <p class="text-sm sm:text-lg text-slate-350 max-w-2xl font-medium leading-relaxed">
                Update your administrative login credentials to maintain secure platform operations.
            </p>
        </div>
    </div>

    <!-- Settings Update Form -->
    <div class="border border-slate-200 bg-white dark:bg-slate-900 dark:border-slate-800 rounded-3xl p-6 sm:p-10 shadow-xl space-y-6">
        @if ($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-100 text-rose-800 dark:bg-rose-950/20 dark:border-rose-900/50 dark:text-rose-450 rounded-2xl text-xs font-semibold space-y-1">
                <p class="font-bold">Please correct the following errors:</p>
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Email Address -->
            <div class="space-y-2">
                <label for="email" class="block text-xs font-extrabold uppercase tracking-widest text-slate-505 dark:text-slate-400">
                    Administrator Email Address <span class="text-rose-500">*</span>
                </label>
                <input type="email" name="email" id="email" required value="{{ old('email', $user->email) }}" 
                    class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all font-semibold">
            </div>

            <!-- Password Fields Group -->
            <div class="border-t border-slate-150 dark:border-slate-800/60 pt-6 space-y-6">
                <div class="space-y-1">
                    <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">Change Access Password</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-450 font-semibold">Leave the new password fields blank if you only wish to change your email address.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- New Password -->
                    <div class="space-y-2">
                        <label for="new_password" class="block text-xs font-extrabold uppercase tracking-widest text-slate-505 dark:text-slate-400">
                            New Password
                        </label>
                        <input type="password" name="new_password" id="new_password" 
                            placeholder="Min 8 characters"
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all font-semibold">
                    </div>

                    <!-- Confirm New Password -->
                    <div class="space-y-2">
                        <label for="new_password_confirmation" class="block text-xs font-extrabold uppercase tracking-widest text-slate-505 dark:text-slate-400">
                            Confirm New Password
                        </label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" 
                            placeholder="Repeat new password"
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all font-semibold">
                    </div>
                </div>
            </div>

            <!-- Current Password Verification -->
            <div class="border-t border-slate-150 dark:border-slate-800/60 pt-6 space-y-2">
                <label for="current_password" class="block text-xs font-extrabold uppercase tracking-widest text-slate-505 dark:text-slate-400">
                    Confirm Current Password <span class="text-rose-500">*</span>
                </label>
                <input type="password" name="current_password" id="current_password" required 
                    placeholder="Enter current password to authorize changes"
                    class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all font-semibold">
            </div>

            <!-- Submission Actions -->
            <div class="pt-4 flex flex-col sm:flex-row items-center gap-3">
                <button type="submit" class="w-full sm:w-auto px-6 py-3.5 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-sm shadow-lg shadow-rose-500/25 transition-all duration-200 cursor-pointer flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-base">save</span> Save Account Changes
                </button>
                <a href="{{ route('admin.bugs.index') }}" class="w-full sm:w-auto px-6 py-3.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 font-extrabold text-sm transition-all duration-200 text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
