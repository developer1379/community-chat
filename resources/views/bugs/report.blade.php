@extends('layouts.app')

@section('content')
<div class="space-y-8 max-w-4xl mx-auto text-left">
    <!-- Premium Bug Reporting Header Banner -->
    <div class="relative rounded-3xl overflow-hidden shadow-lg border border-slate-200 bg-gradient-to-r from-rose-600 via-pink-650 to-indigo-700 p-6 sm:p-10 text-white">
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-rose-500/25 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 space-y-3">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-white/20 text-rose-100 border border-white/25 uppercase tracking-widest leading-none">
                <span class="material-symbols-outlined text-xs animate-pulse">bug_report</span> Submit Bug Report
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight font-sans">
                Report a Bug & Improve the Guild
            </h1>
            <p class="text-sm sm:text-lg text-rose-100/95 max-w-2xl font-medium leading-relaxed">
                Found a glitch, an error, or something behaving unexpectedly? Help us squash it! Provide the details below so our developers can investigate.
            </p>
        </div>
    </div>

    <!-- Bug Report Form Container -->
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

        <form action="{{ route('bugs.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Bug Title -->
            <div class="space-y-2">
                <label for="title" class="block text-xs font-extrabold uppercase tracking-widest text-slate-505 dark:text-slate-400">
                    Bug Summary / Title <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="title" id="title" required value="{{ old('title') }}" 
                    placeholder="Briefly describe the bug (e.g. 'Avatar uploading fails in settings page')" 
                    class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all font-semibold placeholder:font-normal placeholder:text-slate-400 dark:placeholder:text-slate-600">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Severity Selector -->
                <div class="space-y-2">
                    <label for="severity" class="block text-xs font-extrabold uppercase tracking-widest text-slate-505 dark:text-slate-400">
                        Severity Level <span class="text-rose-500">*</span>
                    </label>
                    <select name="severity" id="severity" required 
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all font-bold">
                        <option value="low" {{ old('severity') == 'low' ? 'selected' : '' }}>Low (Minor layout issues, typos)</option>
                        <option value="medium" {{ old('severity', 'medium') == 'medium' ? 'selected' : '' }}>Medium (Functionality glitches, but workarounds exist)</option>
                        <option value="high" {{ old('severity') == 'high' ? 'selected' : '' }}>High (Feature broken, preventing main usage)</option>
                        <option value="critical" {{ old('severity') == 'critical' ? 'selected' : '' }}>Critical (Crash, data loss, security issue)</option>
                    </select>
                </div>

                <!-- Autocompleted User Info -->
                <div class="space-y-2">
                    <label class="block text-xs font-extrabold uppercase tracking-widest text-slate-505 dark:text-slate-400">
                        Reporter
                    </label>
                    <div class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-100/50 dark:bg-slate-950/30 text-slate-500 dark:text-slate-550 text-sm font-bold flex items-center gap-2">
                        <img src="{{ auth()->user()->avatar_url }}" class="w-5 h-5 rounded-full object-cover border border-slate-300 dark:border-slate-800" alt="avatar">
                        <span>{{ auth()->user()->name }} ({{ auth()->user()->email }})</span>
                    </div>
                </div>
            </div>

            <!-- Detailed Description -->
            <div class="space-y-2">
                <label for="description" class="block text-xs font-extrabold uppercase tracking-widest text-slate-505 dark:text-slate-400">
                    What happens / Description <span class="text-rose-500">*</span>
                </label>
                <textarea name="description" id="description" required rows="4" 
                    placeholder="Provide a clear, detailed description of the bug. Mention what page or feature it is on, and what the expected behavior should be." 
                    class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all font-medium placeholder:font-normal placeholder:text-slate-400 dark:placeholder:text-slate-600">{{ old('description') }}</textarea>
            </div>

            <!-- Steps to Reproduce -->
            <div class="space-y-2">
                <label for="steps" class="block text-xs font-extrabold uppercase tracking-widest text-slate-505 dark:text-slate-400">
                    Steps to Reproduce (Optional)
                </label>
                <textarea name="steps" id="steps" rows="3" 
                    placeholder="1. Go to settings page&#10;2. Click on change avatar button&#10;3. Select a file larger than 2MB&#10;4. See the error alert" 
                    class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all font-medium placeholder:font-normal placeholder:text-slate-400 dark:placeholder:text-slate-600">{{ old('steps') }}</textarea>
            </div>

            <!-- Submission Actions -->
            <div class="pt-4 flex flex-col sm:flex-row items-center gap-3">
                <button type="submit" class="w-full sm:w-auto px-6 py-3.5 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-sm shadow-lg shadow-rose-500/25 transition-all duration-200 cursor-pointer flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-base">send</span> Submit Report
                </button>
                <a href="{{ route('home') }}" class="w-full sm:w-auto px-6 py-3.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 font-extrabold text-sm transition-all duration-200 text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
