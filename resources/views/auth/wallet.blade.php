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

    <!-- Grid Layout (Compact 3 Columns) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Balance & Timeline Progress Panel -->
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

        <!-- Sidebar Journey Map (Right Column) -->
        <div class="space-y-6">
            <!-- Animated Candy Crush Stepping Stones Roadmap Card -->
            <div class="border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 rounded-2xl p-5 shadow-sm space-y-3.5 text-left relative overflow-hidden flex flex-col">
                <!-- Glowing background -->
                <div class="absolute -right-8 -top-8 w-24 h-24 rounded-full blur-xl pointer-events-none opacity-5 bg-blue-500"></div>

                <div class="border-b border-slate-100 dark:border-slate-800 pb-3 flex items-center justify-between flex-shrink-0">
                    <div>
                        <h3 class="text-xs font-black uppercase text-slate-550 tracking-wider flex items-center gap-1">
                            <span class="material-symbols-outlined text-blue-600 text-base">map</span> Journey Map
                        </h3>
                        <p class="text-[9px] font-medium text-slate-400 mt-0.5">Climb milestones to top!</p>
                    </div>
                    <span class="text-[9px] font-black text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/20 border border-blue-150 dark:border-blue-900/30 px-2 py-0.5 rounded-lg">Lvl {{ $currentMilestone->level }}</span>
                </div>

                <!-- Winding roadmap viewport with auto centering -->
                <div class="relative bg-slate-50 dark:bg-slate-950/40 rounded-xl p-2 border border-slate-100 dark:border-slate-850 overflow-y-auto max-h-[500px] custom-scrollbar flex flex-col items-center justify-start select-none" id="roadmap-scroll-container">
                    
                    @php
                        // Mathematical S-Curve Coordinates
                        $coords = [
                            1  => ['x' => 200, 'y' => 1280],
                            2  => ['x' => 280, 'y' => 1215],
                            3  => ['x' => 320, 'y' => 1150],
                            4  => ['x' => 280, 'y' => 1085],
                            5  => ['x' => 200, 'y' => 1020],
                            6  => ['x' => 120, 'y' => 955],
                            7  => ['x' => 80,  'y' => 890],
                            8  => ['x' => 120, 'y' => 825],
                            9  => ['x' => 200, 'y' => 760],
                            10 => ['x' => 280, 'y' => 695],
                            11 => ['x' => 320, 'y' => 630],
                            12 => ['x' => 280, 'y' => 565],
                            13 => ['x' => 200, 'y' => 500],
                            14 => ['x' => 120, 'y' => 435],
                            15 => ['x' => 80,  'y' => 370],
                            16 => ['x' => 120, 'y' => 305],
                            17 => ['x' => 200, 'y' => 240],
                            18 => ['x' => 280, 'y' => 175],
                            19 => ['x' => 320, 'y' => 110],
                            20 => ['x' => 200, 'y' => 45],
                        ];
                    @endphp

                    <!-- Responsive Viewport SVG Map -->
                    <svg viewBox="0 0 400 1350" width="100%" class="h-auto w-full relative z-10">
                        <defs>
                            <linearGradient id="unlockedGrad" x1="0" y1="0" x2="1" y2="1">
                                <stop offset="0%" stop-color="#ffffff" />
                                <stop offset="100%" stop-color="#fbbf24" />
                            </linearGradient>
                            <linearGradient id="activeTrackGrad" x1="0" y1="1" x2="0" y2="0">
                                <stop offset="0%" stop-color="#10B981" />
                                <stop offset="50%" stop-color="#3B82F6" />
                                <stop offset="100%" stop-color="#EF4444" />
                            </linearGradient>
                        </defs>

                        <!-- Background Connection Path (Static Gray Line) -->
                        <path d="M 200,1280 C 280,1280 320,1215 320,1150 C 320,1085 280,1020 200,1020 C 120,1020 80,955 80,890 C 80,825 120,760 200,760 C 280,760 320,695 320,630 C 320,565 280,500 200,500 C 120,500 80,435 80,370 C 80,305 120,240 200,240 C 280,240 320,175 320,110 C 320,45 200,45 200,45" fill="none" stroke="#e2e8f0" stroke-width="10" stroke-linecap="round"/>
                        
                        <!-- Animated Path for Unlocked Tiers -->
                        <path d="M 200,1280 C 280,1280 320,1215 320,1150 C 320,1085 280,1020 200,1020 C 120,1020 80,955 80,890 C 80,825 120,760 200,760 C 280,760 320,695 320,630 C 320,565 280,500 200,500 C 120,500 80,435 80,370 C 80,305 120,240 200,240 C 280,240 320,175 320,110 C 320,45 200,45 200,45" fill="none" stroke="url(#activeTrackGrad)" stroke-width="10" stroke-linecap="round" stroke-dasharray="14, 8" class="animate-conveyor"/>
                        
                        <style>
                            .animate-conveyor {
                                animation: conveyorDash 2s linear infinite;
                            }
                            @keyframes conveyorDash {
                                to { stroke-dashoffset: -44; }
                            }
                            .roadmap-hover-target:hover .stone-ring {
                                transform: scale(1.15);
                            }
                        </style>

                        <!-- Milestones Nodes (Climbing Upwards) -->
                        @foreach($milestones as $index => $ms)
                            @php
                                $c = $coords[$ms->level] ?? ['x' => 200, 'y' => 600];
                                $unlocked = $user->coins >= $ms->coins_required;
                                $isCurrent = $currentMilestone->level === $ms->level;
                                
                                // Text label offsets based on curve position to prevent overlapping
                                $textX = $c['x'];
                                $textY = $c['y'];
                                $anchor = 'middle';
                                
                                if ($c['x'] == 200) {
                                    $textY = $c['y'] - 26; // Above/below for center nodes
                                } elseif ($c['x'] == 80) {
                                    $textX = $c['x'] + 32; // To the right of left peaks
                                    $anchor = 'start';
                                } elseif ($c['x'] == 320) {
                                    $textX = $c['x'] - 32; // To the left of right peaks
                                    $anchor = 'end';
                                } elseif ($c['x'] == 120) {
                                    $textX = $c['x'] + 32;
                                    $anchor = 'start';
                                } elseif ($c['x'] == 280) {
                                    $textX = $c['x'] - 32;
                                    $anchor = 'end';
                                }
                                
                                // Material Symbols definition per milestone tier
                                $mIcon = 'star';
                                if ($ms->level >= 20) { $mIcon = 'emoji_events'; }
                                elseif ($ms->level >= 16) { $mIcon = 'diamond'; }
                                elseif ($ms->level >= 12) { $mIcon = 'workspace_premium'; }
                                elseif ($ms->level >= 8) { $mIcon = 'military_tech'; }
                                elseif ($ms->level >= 4) { $mIcon = 'shield'; }
                            @endphp

                            <!-- Level Group Node -->
                            <g class="roadmap-hover-target cursor-help {{ $isCurrent ? 'active-focus-node' : '' }}" data-node-level="{{ $ms->level }}" data-node-name="{{ $ms->name }}" data-node-coins="{{ number_format($ms->coins_required) }}" data-node-badge="{{ $ms->badge }}" data-node-status="{{ $unlocked ? 'Unlocked' : 'Locked' }}">
                                <!-- Pulse ring around current position -->
                                @if($isCurrent)
                                    <circle cx="{{ $c['x'] }}" cy="{{ $c['y'] }}" r="28" fill="none" stroke="{{ $ms->color }}" stroke-width="2" opacity="0.4" class="animate-ping" style="transform-origin: {{ $c['x'] }}px {{ $c['y'] }}px;"/>
                                @endif
                                
                                <!-- Outer Glowing Shadow Border -->
                                <circle cx="{{ $c['x'] }}" cy="{{ $c['y'] }}" r="21" class="stone-ring transition-transform" fill="{{ $unlocked ? $ms->color : '#cbd5e1' }}" opacity="0.3"/>
                                
                                <!-- Stepping circle core -->
                                <circle cx="{{ $c['x'] }}" cy="{{ $c['y'] }}" r="18" fill="{{ $unlocked ? '#ffffff' : '#f1f5f9' }}" stroke="{{ $unlocked ? $ms->color : '#94a3b8' }}" stroke-width="2"/>
                                
                                <!-- Node Level Number text label inside circle -->
                                @if($unlocked)
                                    <!-- Use dynamic material SVG icon path for node center -->
                                    @if($mIcon === 'emoji_events')
                                        <path d="M {{ $c['x']-6 }} {{ $c['y']-7 }} H {{ $c['x']+6 }} V {{ $c['y']-2 }} Q {{ $c['x']+6 }} {{ $c['y']+3 }} {{ $c['x'] }} {{ $c['y']+3 }} Q {{ $c['x']-6 }} {{ $c['y']+3 }} {{ $c['x']-6 }} {{ $c['y']-2 }} Z M {{ $c['x'] }} {{ $c['y']+3 }} V {{ $c['y']+7 }} H {{ $c['x']-3 }} V {{ $c['y']+9 }} H {{ $c['x']+3 }} V {{ $c['y']+7 }} H {{ $c['x'] }} Z" fill="{{ $ms->color }}" />
                                    @elseif($mIcon === 'diamond')
                                        <path d="M {{ $c['x'] }} {{ $c['y']-8 }} L {{ $c['x']+7 }} {{ $c['y']-2 }} L {{ $c['x'] }} {{ $c['y']+8 }} L {{ $c['x']-7 }} {{ $c['y']-2 }} Z" fill="{{ $ms->color }}" />
                                    @elseif($mIcon === 'workspace_premium')
                                        <circle cx="{{ $c['x'] }}" cy="{{ $c['y']-2 }}" r="5" stroke="{{ $ms->color }}" stroke-width="2" fill="none" />
                                        <path d="M {{ $c['x']-2 }} {{ $c['y']+3 }} L {{ $c['x']-4 }} {{ $c['y']+8 }} L {{ $c['x'] }} {{ $c['y']+6 }} L {{ $c['x']+4 }} {{ $c['y']+8 }} L {{ $c['x']+2 }} {{ $c['y']+3 }}" fill="{{ $ms->color }}" />
                                    @elseif($mIcon === 'military_tech')
                                        <path d="M {{ $c['x']-4 }} {{ $c['y']-7 }} L {{ $c['x']+4 }} {{ $c['y']-7 }} L {{ $c['x']+6 }} {{ $c['y']+1 }} L {{ $c['x'] }} {{ $c['y']+8 }} L {{ $c['x']-6 }} {{ $c['y']+1 }} Z" fill="{{ $ms->color }}" opacity="0.3"/>
                                        <circle cx="{{ $c['x'] }}" cy="{{ $c['y']-1 }}" r="3" fill="{{ $ms->color }}"/>
                                    @elseif($mIcon === 'shield')
                                        <path d="M {{ $c['x'] }} {{ $c['y']-8 }} L {{ $c['x']-6 }} {{ $c['y']-5 }} V {{ $c['y'] }} C {{ $c['x']-6 }} {{ $c['y']+4 }} {{ $c['x'] }} {{ $c['y']+8 }} {{ $c['x'] }} {{ $c['y']+8 }} C {{ $c['x'] }} {{ $c['y']+8 }} {{ $c['x']+6 }} {{ $c['y']+4 }} {{ $c['x']+6 }} {{ $c['y'] }} V {{ $c['y']-5 }} Z" fill="{{ $ms->color }}" />
                                    @else
                                        <!-- Star -->
                                        <path d="M {{ $c['x'] }} {{ $c['y']-7 }} L {{ $c['x']+2 }} {{ $c['y']-2 }} H {{ $c['x']+7 }} L {{ $c['x']+3 }} {{ $c['y']+1 }} L {{ $c['x']+5 }} {{ $c['y']+6 }} L {{ $c['x'] }} {{ $c['y']+3 }} L {{ $c['x']-5 }} {{ $c['y']+6 }} L {{ $c['x']-3 }} {{ $c['y']+1 }} L {{ $c['x']-7 }} {{ $c['y']-2 }} H {{ $c['x']-2 }} Z" fill="{{ $ms->color }}" />
                                    @endif
                                @else
                                    <!-- Lock symbol for locked nodes -->
                                    <path d="M {{ $c['x']-4 }} {{ $c['y'] }} V {{ $c['y']-3 }} C {{ $c['x']-4 }} {{ $c['y']-5.5 }} {{ $c['x']+4 }} {{ $c['y']-5.5 }} {{ $c['x']+4 }} {{ $c['y']-3 }} V {{ $c['y'] }} H {{ $c['x']-4 }} Z M {{ $c['x']-5 }} {{ $c['y'] }} H {{ $c['x']+5 }} V {{ $c['y']+6 }} H {{ $c['x']-5 }} Z" fill="#94a3b8" />
                                @endif

                                <!-- Text label beside node -->
                                <text x="{{ $textX }}" y="{{ $textY + 3 }}" font-size="10" font-weight="900" font-family="Plus Jakarta Sans, sans-serif" text-anchor="{{ $anchor }}" fill="{{ $unlocked ? $ms->color : '#94a3b8' }}" class="uppercase tracking-wide">
                                    {{ $ms->name }}
                                </text>
                            </g>
                        @endforeach
                    </svg>

                    <!-- Roadmap Tooltip element inside viewport -->
                    <div id="roadmap-tooltip" class="absolute hidden bg-slate-900 text-white p-3 rounded-2xl text-[10px] w-48 shadow-xl border border-white/10 z-30 leading-relaxed pointer-events-none transition-opacity duration-200">
                        <div class="flex justify-between font-extrabold items-center">
                            <span id="tooltip-title" class="text-sm">Milestone</span>
                            <span id="tooltip-level" class="text-[9px] uppercase px-1.5 py-0.2 bg-white/20 rounded font-black text-amber-300">Lvl</span>
                        </div>
                        <p id="tooltip-badge" class="text-slate-400 mt-1 font-bold">Badge Title</p>
                        <p id="tooltip-coins" class="text-slate-500 mt-0.5">0 coins required</p>
                        <p id="tooltip-status" class="text-emerald-400 font-extrabold uppercase mt-1">Unlocked</p>
                    </div>
                </div>
            </div>

            <!-- Earning Rules -->
            <div class="border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-4 rounded-2xl shadow-sm space-y-3.5 text-left">
                <h3 class="text-xs font-black uppercase text-slate-550 tracking-wider flex items-center gap-1.5">
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
        </div>
    </div>
</div>

<!-- Inline Javascript triggers for Interactive Winding Roadmap -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('roadmap-scroll-container');
        const tooltip = document.getElementById('roadmap-tooltip');
        
        // Auto scroll to active user focus node on load
        const activeNode = container ? container.querySelector('.active-focus-node') : null;
        if (container && activeNode) {
            // Get bounding coordinates relative to container
            const activeRect = activeNode.getBoundingClientRect();
            const containerRect = container.getBoundingClientRect();
            
            // Calculate scroll position to center the active node
            const scrollPos = activeNode.getBBox().y - (container.clientHeight / 2);
            container.scrollTop = scrollPos;
        }

        // Event listeners on milestones group
        document.querySelectorAll('.roadmap-hover-target').forEach(node => {
            node.addEventListener('mouseenter', function(e) {
                const name = node.getAttribute('data-node-name');
                const level = node.getAttribute('data-node-level');
                const coins = node.getAttribute('data-node-coins');
                const badge = node.getAttribute('data-node-badge');
                const status = node.getAttribute('data-node-status');

                // Populate tooltip contents
                document.getElementById('tooltip-title').innerText = name;
                document.getElementById('tooltip-level').innerText = `Lvl ${level}`;
                document.getElementById('tooltip-badge').innerText = badge;
                document.getElementById('tooltip-coins').innerText = `${coins} coins required`;
                
                const statusEl = document.getElementById('tooltip-status');
                statusEl.innerText = status === 'Unlocked' ? 'Unlocked ✓' : 'Locked 🔒';
                statusEl.className = status === 'Unlocked' ? 'text-emerald-400 font-extrabold uppercase mt-1' : 'text-slate-500 font-extrabold uppercase mt-1';

                // Display and position tooltip
                tooltip.classList.remove('hidden');
                tooltip.style.opacity = '1';
            });

            node.addEventListener('mousemove', function(e) {
                if (!container) return;
                const containerRect = container.getBoundingClientRect();
                
                // Position tooltip relative to scroll container
                const x = e.clientX - containerRect.left + container.scrollLeft + 15;
                const y = e.clientY - containerRect.top + container.scrollTop - 40;
                
                tooltip.style.left = `${x}px`;
                tooltip.style.top = `${y}px`;
            });

            node.addEventListener('mouseleave', function() {
                tooltip.style.opacity = '0';
                setTimeout(() => {
                    tooltip.classList.add('hidden');
                }, 100);
            });
        });
    });
</script>
@endsection
