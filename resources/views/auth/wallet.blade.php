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
                <span class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-950/20 flex items-center justify-center text-amber-500 border border-amber-200 dark:border-amber-900/30 shadow-sm relative overflow-hidden group">
                    <span class="material-symbols-outlined text-lg z-10 animate-pulse">account_balance_wallet</span>
                </span>
                My Coin Wallet
            </h1>
            <p class="text-xs text-slate-550 dark:text-slate-400 font-medium mt-1">
                Monitor your Gold Coins, view transaction audit logs, and track your level progress.
            </p>
        </div>
    </div>

    <!-- Grid Layout (Compact 3 Columns) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Balance & Timeline Progress Panel -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Compact Balance Card -->
            <div class="relative overflow-hidden rounded-2xl border border-amber-300 dark:border-amber-800 bg-gradient-to-tr from-amber-600 via-yellow-500 to-amber-700 p-6 text-white shadow-lg flex flex-col justify-between h-44 group transition-all duration-200">
                <!-- 3D shimmering light effects -->
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000 ease-out"></div>
                <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-[140px] text-white/10 select-none pointer-events-none group-hover:rotate-6 transition-transform duration-350">monetization_on</span>
                
                <div class="flex justify-between items-start z-10">
                    <div>
                        <span class="text-[9px] font-black uppercase tracking-widest text-amber-955 bg-white/20 px-3 py-1 rounded-full border border-white/20 shadow-sm">XenCoins Gold</span>
                        <h2 class="text-4xl font-black mt-3 flex items-center gap-1.5 drop-shadow-sm">
                            <span>{{ number_format($user->coins) }}</span>
                            <span class="text-xl font-extrabold text-amber-100 animate-bounce">🪙</span>
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
                    <h3 class="text-xs font-black uppercase text-slate-500 tracking-wider flex items-center gap-1.5">
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

        <!-- Sidebar Winding Candy Crush Level Map (Right Column) -->
        <div class="space-y-6">
            <!-- Animated Candy Crush Stepping Stones Roadmap Card -->
            <div class="border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 rounded-2xl p-5 shadow-sm space-y-4 text-left relative overflow-hidden">
                <!-- Glowing theme background circle -->
                <div class="absolute -right-8 -top-8 w-24 h-24 rounded-full blur-xl pointer-events-none opacity-5 bg-blue-500"></div>

                <div class="border-b border-slate-100 dark:border-slate-800 pb-3 flex items-center justify-between">
                    <div>
                        <h3 class="text-xs font-black uppercase text-slate-500 tracking-wider flex items-center gap-1">
                            <span class="material-symbols-outlined text-blue-600 text-base">map</span> Journey Map
                        </h3>
                        <p class="text-[10px] font-medium text-slate-400 mt-0.5">Coins track Lvl milestones</p>
                    </div>
                    <span class="text-[10px] font-black text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/20 border border-blue-150 dark:border-blue-900/30 px-2 py-0.5 rounded-lg">Lvl {{ $currentMilestone->level }}</span>
                </div>

                <!-- Candy Crush Winding Path Container -->
                <div class="relative bg-slate-50/50 dark:bg-slate-950/20 rounded-2xl p-4 min-h-[460px] border border-slate-100 dark:border-slate-850 overflow-hidden flex flex-col justify-start select-none">
                    <!-- SVG Winding Dashed Connecting Track (Snake path simulator) -->
                    <svg class="absolute inset-0 w-full h-full pointer-events-none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Custom snaking connector path generated dynamically matching our wavy items -->
                        <path d="M 40,30 Q 150,70 190,110 T 40,190 T 190,270 T 40,350 T 190,430" fill="none" stroke="#e2e8f0" stroke-width="4" stroke-linecap="round"/>
                        <path d="M 40,30 Q 150,70 190,110 T 40,190 T 190,270 T 40,350 T 190,430" fill="none" stroke="#fbbf24" stroke-width="4" stroke-linecap="round" stroke-dasharray="8, 6" class="animated-track-path"/>
                        <style>
                            .animated-track-path {
                                animation: trackDash 1.5s linear infinite;
                            }
                            @keyframes trackDash {
                                to { stroke-dashoffset: -14; }
                            }
                        </style>
                    </svg>

                    <!-- Nodes mapped in snaking margins -->
                    <div class="space-y-4 relative z-10 flex flex-col">
                        @php
                            // Margin offsets matching the Q/T snake anchor path (alternates left and right)
                            $offsets = [
                                10, 25, 45, 60, 50, 30, 15, 10, 25, 45, 
                                60, 50, 30, 15, 10, 25, 45, 60, 50, 30
                            ];
                        @endphp
                        @foreach($milestones as $index => $ms)
                            @php
                                $unlocked = $user->coins >= $ms->coins_required;
                                $isCurrent = $currentMilestone->level === $ms->level;
                                $offset = $offsets[$index] ?? 20;
                                
                                // Dynamic material icon per milestone tier
                                $mIcon = 'star_rate';
                                if ($ms->level >= 20) { $mIcon = 'emoji_events'; }
                                elseif ($ms->level >= 16) { $mIcon = 'diamond'; }
                                elseif ($ms->level >= 12) { $mIcon = 'workspace_premium'; }
                                elseif ($ms->level >= 8) { $mIcon = 'military_tech'; }
                                elseif ($ms->level >= 4) { $mIcon = 'shield'; }
                            @endphp
                            
                            <!-- Stepping stone node (Horizontal shift simulating S-Curve) -->
                            <div class="flex items-center gap-3 relative group" style="margin-left: {{ $offset }}%;">
                                <!-- Pulsing ring for current location -->
                                @if($isCurrent)
                                    <span class="absolute -left-1.5 -top-1.5 w-10 h-10 rounded-full border border-amber-500 bg-amber-500/20 animate-ping pointer-events-none"></span>
                                @endif
                                
                                <!-- Stepping circle button (Candy node style) -->
                                <button type="button" class="w-7 h-7 rounded-full flex items-center justify-center border-2 shadow-sm transition-all duration-300 transform group-hover:scale-110 active:scale-95 cursor-help"
                                        style="
                                            border-color: {{ $unlocked ? $ms->color : '#cbd5e1' }};
                                            background: {{ $unlocked ? 'linear-gradient(135deg, #ffffff, ' . $ms->color . '25)' : '#f1f5f9' }};
                                            color: {{ $unlocked ? $ms->color : '#94a3b8' }};
                                        "
                                        title="{{ $ms->name }} (Lvl {{ $ms->level }}) - {{ $unlocked ? 'Unlocked ✓' : number_format($ms->coins_required) . ' Coins Required' }}">
                                    @if($unlocked)
                                        <span class="material-symbols-outlined text-[15px] font-bold">{{ $mIcon }}</span>
                                    @else
                                        <span class="material-symbols-outlined text-[13px] font-bold">lock</span>
                                    @endif
                                </button>
                                
                                <!-- Popover/Hover info panel -->
                                <div class="opacity-0 group-hover:opacity-100 pointer-events-none transition-all duration-200 absolute left-9 top-1/2 -translate-y-1/2 bg-slate-900 text-white p-2.5 rounded-xl text-[10px] w-40 z-30 shadow-md">
                                    <div class="font-extrabold flex justify-between">
                                        <span>{{ $ms->name }}</span>
                                        <span style="color: {{ $ms->color }}">Lvl {{ $ms->level }}</span>
                                    </div>
                                    <p class="text-slate-400 mt-1 font-medium">{{ $ms->badge }}</p>
                                    <p class="text-slate-500 mt-0.5">{{ number_format($ms->coins_required) }} Coins required</p>
                                </div>
                                
                                <span class="text-[9px] font-black tracking-wide truncate max-w-[70px] uppercase text-slate-400 dark:text-slate-500 {{ $isCurrent ? 'font-bold' : '' }}" style="color: {{ $unlocked ? $ms->color : '' }}">
                                    {{ $ms->name }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Compact Earning Rules Card -->
            <div class="border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-4 rounded-2xl shadow-sm space-y-3.5 text-left">
                <h3 class="text-xs font-black uppercase text-slate-500 tracking-wider flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-blue-600 text-base animate-pulse">emoji_events</span> Coin Rules
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
        </div>
    </div>
</div>
@endsection
