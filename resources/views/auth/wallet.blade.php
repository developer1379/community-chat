@extends('layouts.app')

@section('content')
<div class="space-y-8 my-6 max-w-6xl mx-auto">
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
        <a href="{{ route('home') }}" class="hover:text-blue-600">Forums</a>
        <span>/</span>
        <span class="text-blue-600 font-semibold">My Wallet</span>
    </div>

    <!-- Header Panel -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-950 tracking-tight flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 border border-amber-200 shadow-sm">
                    <span class="material-symbols-outlined text-xl">account_balance_wallet</span>
                </span>
                My Coin Wallet
            </h1>
            <p class="text-xs text-slate-500 font-medium mt-1.5">
                Monitor your Gold Coins, view earning history, and rank up your community status.
            </p>
        </div>
    </div>

    <!-- Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Balance & Milestone Progress Panel -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Glassmorphic Metallic Gold Balance Card -->
            <div class="relative overflow-hidden rounded-3xl border border-amber-250 bg-gradient-to-tr from-amber-500 via-yellow-400 to-amber-600 p-8 text-white shadow-xl shadow-amber-500/10 flex flex-col justify-between h-56 group">
                <!-- Background decorative icons -->
                <span class="material-symbols-outlined absolute -right-6 -bottom-6 text-[180px] text-white/10 select-none pointer-events-none group-hover:scale-105 transition-transform duration-500">monetization_on</span>
                
                <div class="flex justify-between items-start z-10">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-amber-100 bg-white/15 px-3 py-1 rounded-full border border-white/20">XenCoins Gold</span>
                        <h2 class="text-5xl font-black mt-4 flex items-center gap-2">
                            <span>{{ number_format($user->coins) }}</span>
                            <span class="text-2xl font-extrabold text-amber-100">🪙</span>
                        </h2>
                    </div>
                    <span class="text-xs font-black uppercase tracking-widest text-white/90 bg-slate-950/20 px-3 py-1 rounded-xl">Active Balance</span>
                </div>

                <div class="flex justify-between items-end border-t border-white/10 pt-4 z-10">
                    <div>
                        <p class="text-[10px] uppercase font-bold text-amber-150">Current Wallet Tier</p>
                        <p class="text-sm font-black">{{ $currentTier }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] uppercase font-bold text-amber-150">Owner Account</p>
                        <p class="text-xs font-bold">{{ $user->name }}</p>
                    </div>
                </div>
            </div>

            <!-- Rank Milestone Progression Indicator -->
            <div class="mui-card p-6 rounded-2xl border border-slate-200 bg-white shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-800">Rank Milestone Progress</h3>
                        <p class="text-[10px] font-medium text-slate-450 mt-0.5">Reach target coin levels to earn higher status tiers automatically.</p>
                    </div>
                    <span class="text-xs font-extrabold text-blue-600 bg-blue-50 border border-blue-150 px-2.5 py-1 rounded-lg">{{ $percent }}% Complete</span>
                </div>

                <!-- Progress gauge bar -->
                <div class="space-y-2">
                    <div class="w-full h-3 rounded-full bg-slate-100 overflow-hidden border border-slate-150 shadow-inner">
                        <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                    </div>
                    <div class="flex justify-between items-center text-[10px] font-bold text-slate-500">
                        <span>{{ $currentTier }}</span>
                        <span class="text-slate-400">Next: <span class="text-slate-700 font-extrabold">{{ $nextTier }}</span> ({{ number_format($target) }} coins)</span>
                    </div>
                </div>
            </div>

            <!-- Transaction Audit History Log Table -->
            <div class="mui-card rounded-2xl overflow-hidden border border-slate-200 bg-white shadow-sm flex flex-col">
                <div class="px-5 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                    <h3 class="text-xs font-black uppercase text-slate-500 tracking-wider flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-sm text-blue-600">history</span> Transaction Audit Logs
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left divide-y divide-slate-100">
                        <thead>
                            <tr class="bg-slate-50/50 text-[10px] font-bold text-slate-500 uppercase tracking-wider divide-y">
                                <th class="px-5 py-3">Audit Details / Cause</th>
                                <th class="px-5 py-3">Earning Type</th>
                                <th class="px-5 py-3 text-right">Adjustment</th>
                                <th class="px-5 py-3 text-right">Logged Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-650">
                            @forelse($transactions as $tx)
                                <tr class="hover:bg-slate-50/30 transition-colors">
                                    <td class="px-5 py-4">
                                        <p class="font-bold text-slate-800 leading-tight">{{ $tx->description }}</p>
                                    </td>
                                    <td class="px-5 py-4">
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
                                                'thread_created' => 'bg-indigo-50 text-indigo-700 border-indigo-150',
                                                'reply_posted' => 'bg-blue-50 text-blue-700 border-blue-150',
                                                'reaction_received' => 'bg-emerald-50 text-emerald-700 border-emerald-150',
                                                'reaction_removed' => 'bg-rose-50 text-rose-700 border-rose-150',
                                                default => 'bg-slate-50 text-slate-700 border-slate-150'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[9px] font-bold border {{ $badgeClass }}">
                                            {{ $label }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        @if($tx->amount > 0)
                                            <span class="font-extrabold text-emerald-600 text-sm">+{{ $tx->amount }} 🪙</span>
                                        @else
                                            <span class="font-extrabold text-rose-600 text-sm">{{ $tx->amount }} 🪙</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-right text-slate-400 text-[10px]">
                                        {{ \Carbon\Carbon::parse($tx->created_at)->diffForHumans() }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-12 text-center text-slate-400">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center mx-auto mb-3 shadow-inner">
                                            <span class="material-symbols-outlined text-xl text-slate-350">receipt_long</span>
                                        </div>
                                        <p class="font-bold text-slate-800 text-xs">No transactions recorded yet</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5">Start posting in the forum discussions to earn coins!</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination footer link panel -->
                @if($transactions->hasPages())
                    <div class="px-5 py-4 bg-slate-50 border-t border-slate-200">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar Coin Earning Cheat Sheet Guide -->
        <div class="space-y-6">
            <div class="mui-card p-5 rounded-2xl border border-slate-200 bg-white shadow-sm space-y-4 text-left">
                <h3 class="text-sm font-extrabold tracking-wider text-slate-500 uppercase flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-blue-600 text-base">emoji_events</span> Coin Earning Rules
                </h3>
                <p class="text-[10px] font-medium text-slate-450 leading-relaxed">
                    Earn coins instantly as you interact. Accumulate balance to unlock community clout and upcoming premium rewards.
                </p>

                <!-- Rules list item rows -->
                <div class="space-y-3 pt-2">
                    <div class="flex items-center justify-between p-2.5 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-2">
                            <span class="text-xl">📚</span>
                            <div>
                                <p class="text-xs font-bold text-slate-800 leading-none">Start New Thread</p>
                                <span class="text-[9px] text-slate-400 mt-0.5 inline-block">Post creative topics</span>
                            </div>
                        </div>
                        <span class="text-xs font-black text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg border border-indigo-150">+15 🪙</span>
                    </div>

                    <div class="flex items-center justify-between p-2.5 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-2">
                            <span class="text-xl">💬</span>
                            <div>
                                <p class="text-xs font-bold text-slate-800 leading-none">Post a Reply</p>
                                <span class="text-[9px] text-slate-400 mt-0.5 inline-block">Helpful discussion responses</span>
                            </div>
                        </div>
                        <span class="text-xs font-black text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-150">+5 🪙</span>
                    </div>

                    <div class="flex items-center justify-between p-2.5 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-2">
                            <span class="text-xl">💖</span>
                            <div>
                                <p class="text-xs font-bold text-slate-800 leading-none">Receive Reactions</p>
                                <span class="text-[9px] text-slate-400 mt-0.5 inline-block">Earn when people like posts</span>
                            </div>
                        </div>
                        <span class="text-xs font-black text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-150">+2 🪙</span>
                    </div>
                </div>
            </div>

            <!-- Fun Wallet Milestones Badge Tiers -->
            <div class="mui-card p-5 rounded-2xl border border-slate-200 bg-white shadow-sm space-y-4 text-left">
                <h3 class="text-sm font-extrabold tracking-wider text-slate-500 uppercase flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-blue-600 text-base">stars</span> Rank Badge Milestones
                </h3>

                <div class="space-y-2.5 pt-1">
                    <div class="flex items-center justify-between text-xs p-1.5 border-b border-slate-100">
                        <span class="font-bold text-slate-700">Wandering Ninja 🍃</span>
                        <span class="font-semibold text-slate-400">Welcome Tier</span>
                    </div>
                    <div class="flex items-center justify-between text-xs p-1.5 border-b border-slate-100">
                        <span class="font-bold text-slate-700">Guild Adventurer 🛡️</span>
                        <span class="font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded border border-blue-150">100 Coins</span>
                    </div>
                    <div class="flex items-center justify-between text-xs p-1.5 border-b border-slate-100">
                        <span class="font-bold text-slate-700">Super Saiyan ⚡</span>
                        <span class="font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded border border-amber-150">500 Coins</span>
                    </div>
                    <div class="flex items-center justify-between text-xs p-1.5 border-b border-slate-100">
                        <span class="font-bold text-slate-700">Soul Reaper 💀</span>
                        <span class="font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded border border-purple-150">1,000 Coins</span>
                    </div>
                    <div class="flex items-center justify-between text-xs p-1.5">
                        <span class="font-bold text-slate-700">Pirate King 🏴‍☠️</span>
                        <span class="font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded border border-rose-150">5,000 Coins</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
