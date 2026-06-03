@extends('layouts.admin')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto text-left">
    <!-- Premium Admin Header Banner -->
    <div class="relative rounded-3xl overflow-hidden shadow-lg border border-slate-200 bg-gradient-to-r from-slate-800 via-slate-900 to-indigo-950 p-6 sm:p-10 text-white">
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 space-y-3">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-red-650/40 text-red-200 border border-red-500/30 uppercase tracking-widest leading-none">
                <span class="material-symbols-outlined text-xs animate-pulse">security</span> Administrative Control
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight font-sans">
                Bug Reports Command Center
            </h1>
            <p class="text-sm sm:text-lg text-slate-350 max-w-2xl font-medium leading-relaxed">
                Review, classify, and track system bugs reported by community members. Keep the boards stable.
            </p>
        </div>
    </div>

    <!-- Bug Reports List -->
    <div class="border border-slate-200 bg-white dark:bg-slate-900 dark:border-slate-800 rounded-3xl p-6 shadow-xl space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-5">
            <h2 class="text-lg font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-indigo-650">bug_report</span> All Reports
                <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 text-xs font-bold">{{ $bugs->count() }}</span>
            </h2>
        </div>

        @if($bugs->isEmpty())
            <div class="text-center py-12 text-slate-400 dark:text-slate-500 space-y-3">
                <span class="material-symbols-outlined text-5xl">verified</span>
                <p class="text-sm font-bold">Excellent! No bugs are currently active.</p>
            </div>
        @else
            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach($bugs as $bug)
                    <div class="py-6 first:pt-0 last:pb-0 space-y-4">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <!-- Left: Title and User Info -->
                            <div class="space-y-1.5 min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-base font-extrabold text-slate-900 dark:text-white truncate">{{ $bug->title }}</h3>
                                    
                                    <!-- Severity Badge -->
                                    @if($bug->severity == 'critical')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-900/50 uppercase tracking-wider animate-pulse">Critical</span>
                                    @elseif($bug->severity == 'high')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-orange-50 text-orange-700 border border-orange-200 dark:bg-orange-950/20 dark:text-orange-400 dark:border-orange-900/50 uppercase tracking-wider">High</span>
                                    @elseif($bug->severity == 'medium')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-green-50 text-green-700 border border-green-200 dark:bg-green-950/20 dark:text-green-400 dark:border-green-900/50 uppercase tracking-wider">Medium</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/20 dark:text-blue-400 dark:border-blue-900/50 uppercase tracking-wider">Low</span>
                                    @endif

                                    <!-- Status Badge -->
                                    @if($bug->status == 'resolved')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/20 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900/50 uppercase tracking-wider">Resolved</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-amber-50 text-amber-800 dark:bg-amber-950/20 dark:text-amber-400 border border-amber-200 dark:border-amber-900/50 uppercase tracking-wider animate-pulse">Open</span>
                                    @endif
                                </div>

                                <div class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-450 font-semibold">
                                    <span>Reported by</span>
                                    <a href="{{ route('profile.show', $bug->user->name) }}" class="flex items-center gap-1 text-indigo-650 hover:underline font-extrabold">
                                        <img src="{{ $bug->user->avatar_url }}" class="w-4 h-4 rounded-full object-cover border border-slate-300 dark:border-slate-800" alt="avatar">
                                        {{ $bug->user->name }}
                                    </a>
                                    <span>• {{ $bug->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            <!-- Right: Resolve Button -->
                            @if($bug->status == 'open')
                                <form action="{{ route('admin.bugs.resolve', $bug->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-md shadow-emerald-500/10 transition-all cursor-pointer flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">check_circle</span> Resolve Bug
                                    </button>
                                </form>
                            @endif
                        </div>

                        <!-- Description & Steps -->
                        <div class="p-4 rounded-2xl bg-slate-50/50 border border-slate-100 dark:bg-slate-950/50 dark:border-slate-850 space-y-3">
                            <div class="space-y-1">
                                <h4 class="text-xs font-bold text-slate-400 dark:text-slate-550 uppercase tracking-wider">Bug Description</h4>
                                <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed font-semibold whitespace-pre-wrap">{{ $bug->description }}</p>
                            </div>

                            @if($bug->steps)
                                <div class="space-y-1 border-t border-slate-100 dark:border-slate-850/60 pt-3">
                                    <h4 class="text-xs font-bold text-slate-400 dark:text-slate-550 uppercase tracking-wider">Steps to Reproduce</h4>
                                    <p class="text-sm text-slate-700 dark:text-slate-350 leading-relaxed font-mono whitespace-pre-wrap">{{ $bug->steps }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
