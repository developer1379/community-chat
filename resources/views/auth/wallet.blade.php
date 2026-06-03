@extends('layouts.app')

@section('content')
<div class="space-y-6 my-6 max-w-6xl mx-auto px-4 sm:px-6">
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1 text-left">
        <a href="{{ route('home') }}" class="hover:text-blue-600">Forums</a>
        <span>/</span>
        <span class="text-blue-600 font-semibold">My Wallet</span>
    </div>

    <!-- Header Panel (Compact) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-left">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-950 dark:text-white tracking-tight flex items-center gap-2.5">
                <span class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-950/20 flex items-center justify-center text-amber-500 border border-amber-200 dark:border-amber-900/30 shadow-sm relative overflow-hidden">
                    <span class="material-symbols-outlined text-lg animate-pulse">account_balance_wallet</span>
                </span>
                My Coin Wallet
            </h1>
            <p class="text-xs text-slate-550 dark:text-slate-400 font-medium mt-1">
                Monitor your Gold Coins, view transaction audit logs, and track your level progress.
            </p>
        </div>
    </div>

    <!-- Full-Width Roadmap Progress Preview Bar (Tap to open full-screen Candy Crush Modal) -->
    <div onclick="openRoadmapModal()" class="w-full border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 rounded-3xl p-5 shadow-sm hover:shadow-md hover:border-blue-300 dark:hover:border-blue-800 transition-all cursor-pointer text-left relative overflow-hidden group">
        <div class="absolute right-0 top-0 bottom-0 w-48 opacity-10 bg-gradient-to-l from-blue-500 to-transparent pointer-events-none"></div>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">
            <div class="flex items-center gap-4">
                @php
                    $tier = $user->computed_anime_tier;
                    $level = $tier['level'] ?? 1;
                @endphp
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-white font-black shadow-md animate-bounce" style="background-color: {{ $tier['color'] }}">
                    <span class="material-symbols-outlined text-2xl">map</span>
                </div>
                <div>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-blue-50 dark:bg-blue-950/20 text-blue-700 dark:text-blue-400 border border-blue-100/30">
                        Active Level Progress
                    </span>
                    <h3 class="text-lg font-black text-slate-800 dark:text-white mt-1">
                        {{ $tier['name'] }} <span class="text-xs font-bold text-slate-400 dark:text-slate-500">• Level {{ $level }}</span>
                    </h3>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <span class="text-xs font-extrabold text-blue-600 dark:text-blue-400 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                    View Full Interactive Journey Map
                    <span class="material-symbols-outlined text-sm font-black">arrow_forward</span>
                </span>
            </div>
        </div>
    </div>

    <!-- Grid Layout (Compact 3 Columns) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Balance & Logs Panel -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Compact Balance Card -->
            <div class="relative overflow-hidden rounded-2xl border border-amber-300 dark:border-amber-800 bg-gradient-to-tr from-amber-600 via-yellow-500 to-amber-700 p-6 text-white shadow-lg flex flex-col justify-between h-44 group transition-all duration-200">
                <!-- Shimmering light effects -->
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000 ease-out"></div>
                <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-[140px] text-white/10 select-none pointer-events-none group-hover:rotate-6 transition-transform duration-350">monetization_on</span>
                
                <div class="flex justify-between items-start z-10">
                    <div>
                        <span class="text-[9px] font-black uppercase tracking-widest text-amber-955 bg-white/20 px-3 py-1 rounded-full border border-white/20 shadow-sm">XenCoins Gold</span>
                        <h2 class="text-4xl font-black mt-3 flex items-center gap-1.5 drop-shadow-sm">
                            <span>{{ number_format($user->coins) }}</span>
                            <span class="text-xl font-extrabold text-amber-100 animate-pulse">🪙</span>
                        </h2>
                    </div>
                    <span class="text-[9px] font-black uppercase tracking-widest text-white bg-slate-950/20 px-3 py-1 rounded-lg backdrop-blur-sm border border-white/10">Active Balance</span>
                </div>

                <div class="flex justify-between items-end border-t border-white/10 pt-3 z-10 text-left">
                    <div>
                        <p class="text-[9px] uppercase font-bold text-amber-900/80">Current Wallet Tier</p>
                        <p class="text-xs font-black drop-shadow-sm">{{ $currentTier }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[9px] uppercase font-bold text-amber-900/80">Owner Account</p>
                        <p class="text-xs font-bold">{{ $user->name }}</p>
                    </div>
                </div>
            </div>

            <!-- Transaction Audit History Log Table -->
            <div class="border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 rounded-2xl overflow-hidden shadow-sm flex flex-col">
                <div class="px-4 py-3 bg-slate-50 dark:bg-slate-950 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <h3 class="text-xs font-black uppercase text-slate-550 tracking-wider flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-sm text-blue-600">history</span> Transaction Audit Logs
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left divide-y divide-slate-100 dark:divide-slate-850">
                        <thead>
                            <tr class="bg-slate-50/50 dark:bg-slate-950/40 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                <th class="px-4 py-2.5">Audit Details</th>
                                <th class="px-4 py-2.5">Earning Type</th>
                                <th class="px-4 py-2.5 text-right">Adjustment</th>
                                <th class="px-4 py-2.5 text-right">Logged</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 font-medium text-slate-600 dark:text-slate-350">
                            @forelse($transactions as $tx)
                                <tr class="hover:bg-slate-50/30 dark:hover:bg-slate-800/10 transition-colors">
                                    <td class="px-4 py-3 text-left">
                                        <p class="font-bold text-slate-800 dark:text-slate-200 leading-tight">{{ $tx->description }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-left">
                                        @php
                                            $label = match($tx->type) {
                                                'thread_created' => '📚 Thread Created',
                                                'reply_posted' => '💬 Reply Posted',
                                                'reaction_received' => '💖 Like Received',
                                                'reaction_removed' => '💔 Like Removed',
                                                'welcome_gift' => '🎁 Welcome Gift',
                                                default => '🪙 System Adjustment'
                                            };
                                            $badgeClass = match($tx->type) {
                                                'thread_created' => 'bg-indigo-50 dark:bg-indigo-950/20 text-indigo-700 dark:text-indigo-400 border-indigo-150 dark:border-indigo-900/30',
                                                'reply_posted' => 'bg-blue-50 dark:bg-blue-950/20 text-blue-700 dark:text-blue-400 border-blue-150 dark:border-blue-900/30',
                                                'reaction_received' => 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border-emerald-150 dark:border-emerald-900/30',
                                                'reaction_removed' => 'bg-rose-50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-400 border-rose-150 dark:border-rose-900/30',
                                                default => 'bg-slate-50 dark:bg-slate-950/20 text-slate-700 dark:text-slate-400 border-slate-150 dark:border-slate-800'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[9px] font-bold border {{ $badgeClass }}">
                                            {{ $label }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        @if($tx->amount > 0)
                                            <span class="font-extrabold text-emerald-600 dark:text-emerald-400 text-xs">+{{ $tx->amount }} 🪙</span>
                                        @else
                                            <span class="font-extrabold text-rose-600 dark:text-rose-450 text-xs">{{ $tx->amount }} 🪙</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right text-slate-400 text-[10px]">
                                        {{ \Carbon\Carbon::parse($tx->created_at)->diffForHumans() }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-10 text-center text-slate-400">
                                        <p class="font-bold text-slate-800 dark:text-slate-200 text-xs">No transactions recorded yet</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($transactions->hasPages())
                    <div class="px-4 py-3 bg-slate-50 dark:bg-slate-950 border-t border-slate-200 dark:border-slate-800">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar Panel -->
        <div class="space-y-6">
            <!-- Compact Earning Rules Card -->
            <div class="border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-sm space-y-4 text-left">
                <h3 class="text-xs font-black uppercase text-slate-500 tracking-wider flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-blue-600 text-base">emoji_events</span> Coin Rules
                </h3>
                
                <div class="space-y-2">
                    @foreach($rules as $rule)
                        <div class="flex items-center justify-between p-2.5 rounded-xl border border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-950/20 text-xs">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                    {{ $rule->icon }}
                                </span>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-800 dark:text-slate-200 truncate">{{ $rule->label }}</p>
                                </div>
                            </div>
                            <span class="font-extrabold text-blue-700 dark:text-blue-400 flex-shrink-0">+{{ $rule->amount }} 🪙</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Simplified Rank Milestones Overview -->
            <div class="border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-sm space-y-4 text-left">
                <h3 class="text-xs font-black uppercase text-slate-550 tracking-wider flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-blue-600 text-base">stars</span> Core Tiers Overview
                </h3>

                <div class="space-y-2 text-xs text-slate-600 dark:text-slate-400">
                    <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50 dark:bg-slate-950/20 border border-slate-100 dark:border-slate-800">
                        <span class="font-bold text-slate-800 dark:text-slate-250">Wandering Ninja</span>
                        <span class="font-bold text-slate-400">Lvl 1 - 3</span>
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50 dark:bg-slate-950/20 border border-slate-100 dark:border-slate-800">
                        <span class="font-bold text-slate-800 dark:text-slate-250">Guild Adventurer</span>
                        <span class="font-bold text-blue-600 dark:text-blue-400">Lvl 4 - 7</span>
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50 dark:bg-slate-950/20 border border-slate-100 dark:border-slate-800">
                        <span class="font-bold text-slate-800 dark:text-slate-250">Super Saiyan</span>
                        <span class="font-bold text-amber-600 dark:text-amber-400">Lvl 8 - 11</span>
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50 dark:bg-slate-950/20 border border-slate-100 dark:border-slate-800">
                        <span class="font-bold text-slate-800 dark:text-slate-250">Soul Reaper</span>
                        <span class="font-bold text-purple-600 dark:text-purple-400">Lvl 12 - 15</span>
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50 dark:bg-slate-950/20 border border-slate-100 dark:border-slate-800">
                        <span class="font-bold text-slate-800 dark:text-slate-250">Pirate King</span>
                        <span class="font-bold text-rose-600 dark:text-rose-455">Lvl 16 - 20</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
