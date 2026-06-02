@extends('layouts.app')

@section('content')
<div class="space-y-8 my-6 max-w-6xl mx-auto px-4 sm:px-6">
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
                <span class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 border border-amber-200 shadow-sm relative overflow-hidden group">
                    <span class="absolute inset-0 bg-gradient-to-tr from-amber-400 to-yellow-300 opacity-20 group-hover:scale-110 transition-transform"></span>
                    <span class="material-symbols-outlined text-xl z-10 animate-bounce">account_balance_wallet</span>
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
            <div class="relative overflow-hidden rounded-3xl border border-amber-300 bg-gradient-to-tr from-amber-600 via-yellow-400 to-amber-700 p-8 text-white shadow-xl shadow-amber-500/20 flex flex-col justify-between h-60 group transition-all duration-300 hover:shadow-amber-500/30 hover:-translate-y-0.5">
                <!-- 3D shimmering light effects -->
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000 ease-out"></div>
                <span class="material-symbols-outlined absolute -right-6 -bottom-6 text-[200px] text-white/10 select-none pointer-events-none group-hover:rotate-12 transition-transform duration-500">monetization_on</span>
                
                <div class="flex justify-between items-start z-10">
                    <div>
                        <span class="text-[9px] font-black uppercase tracking-widest text-amber-900 bg-white/35 px-3.5 py-1.5 rounded-full border border-white/40 shadow-sm">XenCoins Gold</span>
                        <h2 class="text-5xl font-black mt-5 flex items-center gap-2 drop-shadow-md">
                            <span>{{ number_format($user->coins) }}</span>
                            <span class="text-2xl font-extrabold text-amber-100 animate-pulse">🪙</span>
                        </h2>
                    </div>
                    <span class="text-xs font-black uppercase tracking-widest text-white bg-slate-950/20 px-3.5 py-1.5 rounded-xl backdrop-blur-sm border border-white/10">Active Balance</span>
                </div>

                <div class="flex justify-between items-end border-t border-white/20 pt-4 z-10">
                    <div>
                        <p class="text-[9px] uppercase font-bold text-amber-900">Current Wallet Tier</p>
                        <p class="text-sm font-black drop-shadow-sm">{{ $currentTier }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[9px] uppercase font-bold text-amber-900">Owner Account</p>
                        <p class="text-xs font-bold">{{ $user->name }}</p>
                    </div>
                </div>
            </div>

            <!-- Rank Milestone Progression Journey Roadmap -->
            <div class="mui-card p-6 rounded-2xl border border-slate-200 bg-white shadow-sm space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-800 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-blue-600 text-base">map</span> Otaku Rank Journey Roadmap
                        </h3>
                        <p class="text-[10px] font-medium text-slate-450 mt-0.5">Explore your roadmap progression, unlock tiers, and build community clout.</p>
                    </div>
                    <span class="text-xs font-extrabold text-indigo-600 bg-indigo-50 border border-indigo-150 px-2.5 py-1 rounded-lg w-fit">{{ number_format($user->coins) }} Coins Active</span>
                </div>

                <!-- Horizontal Timeline Roadmap Journey Map -->
                <div class="relative py-4 overflow-x-auto hide-scrollbar">
                    <!-- Connector Track Line Background -->
                    <div class="absolute top-1/2 left-4 right-4 h-1.5 bg-slate-100 border border-slate-200/60 -translate-y-6 rounded-full"></div>
                    <!-- Active filled track line -->
                    @php
                        // Dynamically calculate filled line width based on active progress
                        $lineWidth = match(true) {
                            $user->coins >= 5000 => 100,
                            $user->coins >= 1000 => 75 + (($user->coins - 1000) / 4000 * 25),
                            $user->coins >= 500 => 50 + (($user->coins - 500) / 500 * 25),
                            $user->coins >= 100 => 25 + (($user->coins - 100) / 400 * 25),
                            default => ($user->coins / 100 * 25)
                        };
                    @endphp
                    <div class="absolute top-1/2 left-4 h-1.5 bg-gradient-to-r from-blue-500 via-indigo-500 to-amber-500 -translate-y-6 rounded-full shadow-sm transition-all duration-1000 ease-out" style="width: calc({{ $lineWidth }}% - 2rem)"></div>

                    <!-- Nodes Grid Wrapper -->
                    <div class="relative flex justify-between items-start min-w-[620px] px-2">
                        <!-- Node 1 -->
                        <div class="flex flex-col items-center text-center space-y-2.5 w-28 group">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-black bg-gradient-to-br from-emerald-400 to-teal-500 text-white shadow-lg shadow-emerald-200 ring-4 ring-emerald-50 z-10 transition-transform group-hover:scale-110">
                                🍃
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-800 leading-tight">Wandering Ninja</p>
                                <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100 mt-1 inline-block">Active</span>
                            </div>
                        </div>

                        <!-- Node 2 -->
                        @php $unlocked2 = $user->coins >= 100; @endphp
                        <div class="flex flex-col items-center text-center space-y-2.5 w-28 group">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-black z-10 transition-all duration-300 group-hover:scale-110 {{ $unlocked2 ? 'bg-gradient-to-br from-blue-400 to-indigo-500 text-white shadow-lg shadow-blue-200 ring-4 ring-blue-50' : 'bg-slate-100 text-slate-400 border border-slate-200 ring-4 ring-transparent' }}">
                                🛡️
                            </div>
                            <div>
                                <p class="text-[10px] font-black leading-tight {{ $unlocked2 ? 'text-slate-800' : 'text-slate-400' }}">Guild Adventurer</p>
                                @if($unlocked2)
                                    <span class="text-[9px] font-bold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-100 mt-1 inline-block">Unlocked</span>
                                @else
                                    <span class="text-[9px] font-bold text-slate-400 bg-slate-50 px-1.5 py-0.5 rounded border border-slate-200 mt-1 inline-block">100 Coins</span>
                                @endif
                            </div>
                        </div>

                        <!-- Node 3 -->
                        @php $unlocked3 = $user->coins >= 500; @endphp
                        <div class="flex flex-col items-center text-center space-y-2.5 w-28 group">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-black z-10 transition-all duration-300 group-hover:scale-110 {{ $unlocked3 ? 'bg-gradient-to-br from-amber-400 to-amber-500 text-white shadow-lg shadow-amber-200 ring-4 ring-amber-50' : 'bg-slate-100 text-slate-400 border border-slate-200 ring-4 ring-transparent' }}">
                                ⚡
                            </div>
                            <div>
                                <p class="text-[10px] font-black leading-tight {{ $unlocked3 ? 'text-slate-800' : 'text-slate-400' }}">Super Saiyan</p>
                                @if($unlocked3)
                                    <span class="text-[9px] font-bold text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded border border-amber-100 mt-1 inline-block">Unlocked</span>
                                @else
                                    <span class="text-[9px] font-bold text-slate-400 bg-slate-50 px-1.5 py-0.5 rounded border border-slate-200 mt-1 inline-block">500 Coins</span>
                                @endif
                            </div>
                        </div>

                        <!-- Node 4 -->
                        @php $unlocked4 = $user->coins >= 1000; @endphp
                        <div class="flex flex-col items-center text-center space-y-2.5 w-28 group">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-black z-10 transition-all duration-300 group-hover:scale-110 {{ $unlocked4 ? 'bg-gradient-to-br from-purple-400 to-violet-500 text-white shadow-lg shadow-purple-200 ring-4 ring-purple-50' : 'bg-slate-100 text-slate-400 border border-slate-200 ring-4 ring-transparent' }}">
                                💀
                            </div>
                            <div>
                                <p class="text-[10px] font-black leading-tight {{ $unlocked4 ? 'text-slate-800' : 'text-slate-400' }}">Soul Reaper</p>
                                @if($unlocked4)
                                    <span class="text-[9px] font-bold text-purple-600 bg-purple-50 px-1.5 py-0.5 rounded border border-purple-100 mt-1 inline-block">Unlocked</span>
                                @else
                                    <span class="text-[9px] font-bold text-slate-400 bg-slate-50 px-1.5 py-0.5 rounded border border-slate-200 mt-1 inline-block">1k Coins</span>
                                @endif
                            </div>
                        </div>

                        <!-- Node 5 -->
                        @php $unlocked5 = $user->coins >= 5000; @endphp
                        <div class="flex flex-col items-center text-center space-y-2.5 w-28 group">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-black z-10 transition-all duration-300 group-hover:scale-110 {{ $unlocked5 ? 'bg-gradient-to-br from-rose-400 to-pink-500 text-white shadow-lg shadow-rose-200 ring-4 ring-rose-50' : 'bg-slate-100 text-slate-400 border border-slate-200 ring-4 ring-transparent' }}">
                                🏴‍☠️
                            </div>
                            <div>
                                <p class="text-[10px] font-black leading-tight {{ $unlocked5 ? 'text-slate-800' : 'text-slate-400' }}">Pirate King</p>
                                @if($unlocked5)
                                    <span class="text-[9px] font-bold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-100 mt-1 inline-block">Legendary</span>
                                @else
                                    <span class="text-[9px] font-bold text-slate-400 bg-slate-50 px-1.5 py-0.5 rounded border border-slate-200 mt-1 inline-block">5k Coins</span>
                                @endif
                            </div>
                        </div>
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

        <!-- Sidebar Dynamic Earning Rules & Milestone Tiers -->
        <div class="space-y-6">
            <!-- Dynamic Earning Rules Card (Loading from DB!) -->
            <div class="mui-card p-5 rounded-2xl border border-slate-200 bg-white shadow-sm space-y-4 text-left">
                <h3 class="text-sm font-extrabold tracking-wider text-slate-500 uppercase flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-blue-600 text-base animate-pulse">emoji_events</span> Coin Earning Rules
                </h3>
                <p class="text-[10px] font-medium text-slate-450 leading-relaxed">
                    Earn coins instantly as you interact. Earning details and settings are fetched dynamically from the database.
                </p>

                <!-- Dynamic Rules list item rows -->
                <div class="space-y-3 pt-2">
                    @forelse($rules as $rule)
                        @php
                            // Match a colorful, custom 3D styled gradient & theme per rule dynamically
                            $gradient = match($rule->id) {
                                'thread_created' => 'from-indigo-400 to-purple-500 shadow-indigo-100',
                                'reply_posted' => 'from-blue-400 to-sky-500 shadow-blue-100',
                                'reaction_received' => 'from-emerald-400 to-teal-500 shadow-emerald-100',
                                default => 'from-amber-400 to-orange-500 shadow-amber-100'
                            };
                            $textCol = match($rule->id) {
                                'thread_created' => 'text-indigo-600 bg-indigo-50 border-indigo-150',
                                'reply_posted' => 'text-blue-600 bg-blue-50 border-blue-150',
                                'reaction_received' => 'text-emerald-600 bg-emerald-50 border-emerald-150',
                                default => 'text-amber-600 bg-amber-50 border-amber-150'
                            };
                        @endphp
                        <div class="flex items-center justify-between p-3 rounded-2xl border border-slate-100 bg-slate-50/40 hover:bg-slate-50 transition-all duration-200 hover:shadow-sm">
                            <div class="flex items-center gap-3 min-w-0">
                                <!-- 3D-effect Material Colorful Icon Sphere -->
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-lg bg-gradient-to-br {{ $gradient }} text-white shadow-md transform hover:rotate-12 transition-transform select-none flex-shrink-0">
                                    {{ $rule->icon }}
                                </div>
                                <div class="min-w-0 leading-tight">
                                    <p class="text-xs font-extrabold text-slate-800 truncate">{{ $rule->label }}</p>
                                    <span class="text-[9px] text-slate-450 mt-0.5 inline-block leading-none truncate max-w-[130px] sm:max-w-[160px]">{{ $rule->description }}</span>
                                </div>
                            </div>
                            <span class="text-xs font-black px-2.5 py-1.5 rounded-xl border {{ $textCol }} flex-shrink-0">+{{ $rule->amount }} 🪙</span>
                        </div>
                    @empty
                        <p class="text-[10px] text-slate-400 text-center py-4">No custom rules configured inside database.</p>
                    @endforelse
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
