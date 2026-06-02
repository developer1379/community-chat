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

            <!-- Rank Milestone Progression Journey Roadmap (Vertical Zig-Zag TryHackMe Style!) -->
            <div class="mui-card p-6 rounded-2xl border border-slate-200 bg-white shadow-sm space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-800 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-blue-600 text-base animate-spin-slow">map</span> Otaku Learning Path Roadmap
                        </h3>
                        <p class="text-[10px] font-medium text-slate-450 mt-0.5">Explore your journey, complete all 20 stages, and conquer the leaderboard!</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-black text-slate-500">Current Level:</span>
                        <span class="text-xs font-black text-blue-600 bg-blue-50 border border-blue-150 px-2.5 py-1 rounded-lg">Lvl {{ $currentMilestone->level }}</span>
                    </div>
                </div>

                <!-- Vertical Timeline Roadmap Journey Map (TryHackMe zig-zag style, super responsive) -->
                <div class="relative py-8 select-none">
                    <!-- Central Connected Track Line (centered on desktop, left-aligned on mobile) -->
                    <div class="absolute left-6 md:left-1/2 top-4 bottom-4 w-1 bg-slate-100 border border-slate-200/50 rounded-full transform md:-translate-x-1/2"></div>
                    
                    <!-- Dynamic Progress Line -->
                    @php
                        $totalMilestones = count($milestones);
                        $activeLevelsCount = $milestones->filter(fn($ms) => $user->coins >= $ms->coins_required)->count();
                        $totalPercent = ($activeLevelsCount / $totalMilestones) * 100;
                    @endphp
                    <div class="absolute left-6 md:left-1/2 top-4 w-1 bg-gradient-to-b from-emerald-500 via-blue-500 to-purple-600 rounded-full shadow-sm transition-all duration-1000 ease-out transform md:-translate-x-1/2" style="height: calc({{ $totalPercent }}% - 2rem)"></div>

                    <!-- Nodes Grid Wrapper (Vertical stacking) -->
                    <div class="space-y-8 relative">
                        @foreach($milestones as $index => $ms)
                            @php
                                $unlocked = $user->coins >= $ms->coins_required;
                                $isCurrent = $currentMilestone->level === $ms->level;
                                $isLeft = $index % 2 === 0;
                                
                                // Color definitions
                                $glowStyle = $unlocked ? "box-shadow: 0 8px 16px -4px {$ms->color}50; border-color: {$ms->color}; background: {$ms->color}08;" : "";
                                $gradientSphere = $unlocked 
                                    ? "background: {$ms->color}; shadow: 0 4px 6px {$ms->color}40;"
                                    : "background: linear-gradient(135deg, #f3f4f6, #e5e7eb);";
                            @endphp
                            <!-- Zig-Zag Row (Alternates left/right on md, remains left-aligned on mobile) -->
                            <div class="flex items-center w-full relative {{ $isLeft ? 'md:flex-row' : 'md:flex-row-reverse' }} flex-row pl-3 md:pl-0">
                                
                                <!-- Left side spacer on desktop / Right side content card -->
                                <div class="w-full md:w-1/2 flex {{ $isLeft ? 'justify-end md:pr-12' : 'justify-start md:pl-12' }} justify-start pl-8 md:pl-0">
                                    <!-- Journey Card (Glassmorphic Material UI Card) -->
                                    <div class="mui-card p-4 rounded-2xl border border-slate-200 bg-white w-full max-w-sm hover:shadow-md hover:border-slate-300 transition-all duration-300 transform hover:-translate-y-0.5 group/card relative overflow-hidden">
                                        <!-- Colorful glow bar on the edge -->
                                        <div class="absolute top-0 bottom-0 left-0 w-1 bg-slate-300 group-hover/card:scale-y-105 transition-transform" style="background-color: {{ $unlocked ? $ms->color : '#e2e8f0' }}"></div>
                                        
                                        <div class="flex items-center gap-3">
                                            <!-- Mini material sphere icon inside card -->
                                            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-base font-black text-white shadow-md transform group-hover/card:rotate-6 transition-transform" style="{{ $gradientSphere }}">
                                                {{ $ms->icon }}
                                            </div>
                                            <div class="min-w-0 leading-tight">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="text-[9px] font-black uppercase tracking-wider text-slate-400">Level {{ $ms->level }}</span>
                                                    @if($isCurrent)
                                                        <span class="text-[8px] font-black uppercase tracking-wider text-blue-600 bg-blue-50 px-1.5 py-0.2 rounded border border-blue-150 animate-pulse">Active Focus</span>
                                                    @endif
                                                </div>
                                                <h4 class="text-xs font-black text-slate-800 truncate mt-0.5">{{ $ms->name }}</h4>
                                                @if($unlocked)
                                                    <span class="text-[8px] font-extrabold uppercase tracking-wider text-emerald-600 mt-1 inline-block bg-emerald-50/50 px-2 py-0.5 rounded border border-emerald-100">Unlocked ✓</span>
                                                @else
                                                    <span class="text-[8px] font-extrabold uppercase tracking-wider text-slate-400 mt-1 inline-block bg-slate-50 px-2 py-0.5 rounded border border-slate-200">{{ number_format($ms->coins_required) }} Coins</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Center Connector Circle Node -->
                                <div class="absolute left-6 md:left-1/2 transform -translate-x-1/2 w-6 h-6 rounded-full border-2 bg-white flex items-center justify-center z-15 shadow-sm transition-all duration-300" style="{{ $unlocked ? "border-color: {$ms->color}; box-shadow: 0 0 12px {$ms->color}60;" : 'border-color: #cbd5e1;' }}">
                                    <div class="w-2.5 h-2.5 rounded-full transition-all duration-300" style="{{ $unlocked ? "background-color: {$ms->color}; animate-pulse" : 'background-color: #94a3b8;' }}"></div>
                                </div>

                                <!-- Right side spacer on desktop -->
                                <div class="w-full md:w-1/2 hidden md:block"></div>
                            </div>
                        @endforeach
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
