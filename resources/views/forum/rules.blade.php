@extends('layouts.app')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">
    <!-- Premium Rules & Guide Header Banner -->
    <div class="relative rounded-[28px] overflow-hidden border border-slate-200 dark:border-slate-800 bg-slate-900 dark:bg-slate-950 p-6 sm:p-10 text-white shadow-xl">
        <div class="absolute -right-16 -top-16 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 w-80 h-80 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="space-y-4 text-left max-w-2xl">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 uppercase tracking-widest leading-none">
                    Community Almanac
                </span>
                <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-white font-sans">
                    Rules & Almanac Guide
                </h1>
                <p class="text-base text-slate-355 font-medium leading-relaxed">
                    Welcome to our workspace. Learn how activity points accumulate, understand the 20 reputation milestone levels, and explore the golden rules of behavior in our community.
                </p>
            </div>
            
            <!-- Open Source Vector Illustration -->
            <div class="hidden md:block flex-shrink-0">
                <svg class="w-40 h-40 opacity-90 drop-shadow-[0_8px_20px_rgba(16,185,129,0.2)]" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="60" cy="60" r="50" fill="url(#rulesHeaderGrad)" stroke="#10B981" stroke-width="1.5" stroke-dasharray="3 3"/>
                    <rect x="42" y="32" width="36" height="56" rx="4" fill="#1E293B" stroke="#334155" stroke-width="2"/>
                    <path d="M48 44H62" stroke="#10B981" stroke-width="2" stroke-linecap="round"/>
                    <path d="M48 52H72" stroke="#64748B" stroke-width="2" stroke-linecap="round"/>
                    <path d="M48 60H68" stroke="#64748B" stroke-width="2" stroke-linecap="round"/>
                    <path d="M48 68H58" stroke="#10B981" stroke-width="2" stroke-linecap="round"/>
                    <defs>
                        <linearGradient id="rulesHeaderGrad" x1="10" y1="10" x2="110" y2="110" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#022c22" />
                            <stop offset="100%" stop-color="#064e3b" />
                        </linearGradient>
                    </defs>
                </svg>
            </div>
        </div>
    </div>

    <!-- Quick Guide Stats Breakdown Card (MD3 Outlined Cards) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card 1: Thread Points -->
        <div class="border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm hover:border-emerald-300 dark:hover:border-emerald-800 transition-all flex items-start gap-4">
            <span class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-100 dark:border-emerald-900/30 flex-shrink-0">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 20h9M3 20v-8a2 2 0 012-2h6M3 12V6a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2h-6M12 8H7M12 12H7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <div class="text-left space-y-1">
                <h3 class="text-base font-extrabold text-slate-800 dark:text-white">Create a Discussion</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Post a new thought or creative topic to start a thread.</p>
                <div class="pt-2">
                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-extrabold bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30">+10 Activity Points</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Reply Points -->
        <div class="border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm hover:border-blue-300 dark:hover:border-blue-800 transition-all flex items-start gap-4">
            <span class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 flex items-center justify-center border border-blue-100 dark:border-blue-900/30 flex-shrink-0">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <div class="text-left space-y-1">
                <h3 class="text-base font-extrabold text-slate-800 dark:text-white">Reply to Conversations</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Engage with other members and answer questions.</p>
                <div class="pt-2">
                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-extrabold bg-blue-50 dark:bg-blue-950/30 text-blue-700 dark:text-blue-400 border border-blue-100 dark:border-blue-900/30">+5 Activity Points</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Reaction Points -->
        <div class="border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm hover:border-pink-300 dark:hover:border-pink-800 transition-all flex items-start gap-4">
            <span class="w-12 h-12 rounded-xl bg-pink-50 dark:bg-pink-950/20 text-pink-600 dark:text-pink-400 flex items-center justify-center border border-pink-100 dark:border-pink-900/30 flex-shrink-0">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </span>
            <div class="text-left space-y-1">
                <h3 class="text-base font-extrabold text-slate-800 dark:text-white">Earn Positive Reactions</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Get positive emoji reactions on your replies or media.</p>
                <div class="pt-2">
                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-extrabold bg-pink-50 dark:bg-pink-950/30 text-pink-700 dark:text-pink-400 border border-pink-100 dark:border-pink-900/30">+2 Activity Points</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Flex Panels -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 text-left">
        <!-- Left Side: Conduct Guidelines (8 cols) -->
        <div class="lg:col-span-8 space-y-6">
            <div class="border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-sm">
                <h2 class="text-lg font-extrabold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-teal-600" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="2"/>
                        <path d="M12 8V12M12 16H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    The 5 Golden Conduct Rules
                </h2>

                <div class="space-y-4">
                    <!-- Rule 1 -->
                    <div class="p-4 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-teal-200 transition-colors bg-slate-50/20 dark:bg-slate-950/20">
                        <div class="flex gap-3">
                            <span class="w-6 h-6 rounded-full bg-teal-500 text-white font-extrabold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">1</span>
                            <div class="space-y-1">
                                <h4 class="font-extrabold text-slate-800 dark:text-white text-sm">Respect Creators</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">Ensure you upload proper original content or cite authors when uploading discussions. High-quality references keep the forum productive.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Rule 2 -->
                    <div class="p-4 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-teal-200 transition-colors bg-slate-50/20 dark:bg-slate-950/20">
                        <div class="flex gap-3">
                            <span class="w-6 h-6 rounded-full bg-teal-500 text-white font-extrabold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">2</span>
                            <div class="space-y-1">
                                <h4 class="font-extrabold text-slate-800 dark:text-white text-sm">Safe Media Uploads Only</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">Uploaded files and images in threads must be clean and safe for work. Spam or offensive uploads will result in an immediate rank/coin reset and lockdown.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Rule 3 -->
                    <div class="p-4 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-teal-200 transition-colors bg-slate-50/20 dark:bg-slate-950/20">
                        <div class="flex gap-3">
                            <span class="w-6 h-6 rounded-full bg-teal-500 text-white font-extrabold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">3</span>
                            <div class="space-y-1">
                                <h4 class="font-extrabold text-slate-800 dark:text-white text-sm">No Point & Coin Farming</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">Posting consecutive low-effort messages or spamming empty replies is monitored. Help maintain highly readable discussions.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Rule 4 -->
                    <div class="p-4 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-teal-200 transition-colors bg-slate-50/20 dark:bg-slate-950/20">
                        <div class="flex gap-3">
                            <span class="w-6 h-6 rounded-full bg-teal-500 text-white font-extrabold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">4</span>
                            <div class="space-y-1">
                                <h4 class="font-extrabold text-slate-800 dark:text-white text-sm">Friendly Private Messages</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">Use real-time Direct Messages to chat privately with members or administrators. Respectful interactions are mandatory across DMs.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Rule 5 -->
                    <div class="p-4 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-teal-200 transition-colors bg-slate-50/20 dark:bg-slate-950/20">
                        <div class="flex gap-3">
                            <span class="w-6 h-6 rounded-full bg-teal-500 text-white font-extrabold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">5</span>
                            <div class="space-y-1">
                                <h4 class="font-extrabold text-slate-800 dark:text-white text-sm">Abide by Pinned Staff Notices</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">Administrators or moderators can pin important announcements, category limits, or thread updates. Always follow them.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Reputation Milestones Almanac (4 cols) -->
        <div class="lg:col-span-4 space-y-6">
            <div class="border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-sm flex flex-col h-full">
                <h3 class="text-base font-extrabold text-slate-900 dark:text-white mb-3 flex items-center gap-1.5">
                    <svg class="w-5 h-5 text-amber-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" stroke-dasharray="3 3"/>
                        <path d="M12 7L13.5 10L16.8 10.5L14.4 12.8L15 16L12 14.5L9 16L9.6 12.8L7.2 10.5L10.5 10L12 7Z" fill="currentColor"/>
                    </svg>
                    Reputation Milestones
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold leading-relaxed mb-4">
                    Tiers are unlocked by saving **Coins** (earned via activity or interactions). Upgrading unlocks exclusive profile cards, title badges, and colors!
                </p>

                <!-- Scrollable list of the 20 milestones -->
                <div class="space-y-3.5 max-h-[460px] overflow-y-auto custom-scrollbar pr-1.5 text-left">
                    @php
                        $milestones = \App\Models\RankMilestone::orderBy('level', 'asc')->get();
                    @endphp
                    @foreach($milestones as $ms)
                        <div class="flex items-center justify-between p-2.5 rounded-xl border border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-950/20 hover:border-blue-200 hover:bg-white dark:hover:bg-slate-900 transition-all">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <!-- Level Shield SVG Image -->
                                <svg class="w-6 h-6 flex-shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: {{ $ms->color }}">
                                    <path d="M12 2L3 5V11C3 16.55 6.84 21.74 12 23C17.16 21.74 21 16.55 21 11V5L12 2Z" fill="currentColor" fill-opacity="0.2" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                    @if($ms->level >= 20)
                                        <path d="M8 15L10 9L12 11.5L14 9L16 15H8Z" fill="currentColor"/>
                                    @elseif($ms->level >= 16)
                                        <path d="M12 8L13 10L15.5 10.3L13.7 11.8L14.2 14L12 13L9.8 14L10.3 11.8L8.5 10.3L11 10L12 8Z" fill="currentColor"/>
                                    @elseif($ms->level >= 12)
                                        <circle cx="10" cy="12" r="1.5" fill="currentColor"/>
                                        <circle cx="14" cy="12" r="1.5" fill="currentColor"/>
                                    @elseif($ms->level >= 8)
                                        <path d="M12 9L14 11L12 13L10 11L12 9Z" fill="currentColor"/>
                                    @else
                                        <circle cx="12" cy="12" r="2" fill="currentColor"/>
                                    @endif
                                </svg>
                                <div class="min-w-0 leading-tight">
                                    <h4 class="text-xs font-black text-slate-800 dark:text-slate-200 truncate">{{ $ms->name }} {{ $ms->icon }}</h4>
                                    <span class="text-[9px] font-extrabold uppercase text-slate-400">Lvl {{ $ms->level }} • {{ $ms->badge }}</span>
                                </div>
                            </div>
                            <span class="text-[10px] font-black px-2 py-0.5 rounded-lg bg-blue-50 dark:bg-blue-950/20 text-blue-700 dark:text-blue-400 border border-blue-100/30">{{ number_format($ms->coins_required) }} c</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
