@extends('layouts.app')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">
    <!-- Premium Rules & Guide Header Banner -->
    <div class="relative rounded-3xl overflow-hidden shadow-lg border border-slate-200 bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-700 p-6 sm:p-10 text-white animated-mesh-banner">
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-emerald-500/25 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 space-y-3 text-left">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-white/20 text-emerald-100 border border-white/25 uppercase tracking-widest leading-none">
                <span class="material-symbols-outlined text-xs animate-pulse">auto_stories</span> Community Almanac
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight font-sans">
                XenProfessional Rules & Guides
            </h1>
            <p class="text-sm sm:text-lg text-emerald-100/95 max-w-3xl font-medium leading-relaxed">
                Welcome to our universe! Here is a transparent guide on how points accumulate, how Otaku rank badges are unlocked, and the general conduct expected in our guild boards.
            </p>
        </div>
    </div>

    <!-- Quick Guide Stats Breakdown Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card 1: Thread Points -->
        <div class="border border-slate-200 bg-white p-6 rounded-2xl shadow-sm hover:border-emerald-300 transition-all flex items-start gap-4">
            <span class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-150 flex-shrink-0">
                <span class="material-symbols-outlined text-2xl font-bold">rate_review</span>
            </span>
            <div class="text-left space-y-1">
                <h3 class="text-base font-extrabold text-slate-800">Create a Discussion</h3>
                <p class="text-xs text-slate-500 font-medium">Post a new thought or anime topic to begin a thread.</p>
                <div class="pt-2">
                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-150">+10 Guild Points</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Reply Points -->
        <div class="border border-slate-200 bg-white p-6 rounded-2xl shadow-sm hover:border-blue-300 transition-all flex items-start gap-4">
            <span class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-150 flex-shrink-0">
                <span class="material-symbols-outlined text-2xl font-bold">forum</span>
            </span>
            <div class="text-left space-y-1">
                <h3 class="text-base font-extrabold text-slate-800">Reply to Conversations</h3>
                <p class="text-xs text-slate-500 font-medium">Engage with other Otaku, answer questions, or start debates.</p>
                <div class="pt-2">
                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-extrabold bg-blue-50 text-blue-700 border border-blue-150">+5 Guild Points</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Reaction Points -->
        <div class="border border-slate-200 bg-white p-6 rounded-2xl shadow-sm hover:border-pink-300 transition-all flex items-start gap-4">
            <span class="w-12 h-12 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center border border-pink-150 flex-shrink-0">
                <span class="material-symbols-outlined text-2xl font-bold">favorite</span>
            </span>
            <div class="text-left space-y-1">
                <h3 class="text-base font-extrabold text-slate-800">Earn Positive Reactions</h3>
                <p class="text-xs text-slate-500 font-medium">Receive reactions on your reviews or image uploads.</p>
                <div class="pt-2">
                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-extrabold bg-pink-50 text-pink-700 border border-pink-150">+2 Guild Points</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Flex Panels -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 text-left">
        
        <!-- Left Side: Interactive Guidelines List (8 cols) -->
        <div class="lg:col-span-8 space-y-6">
            <div class="border border-slate-200 bg-white rounded-3xl p-6 shadow-sm">
                <h2 class="text-lg font-extrabold text-slate-900 mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-teal-600">gavel</span> The 5 Golden Conduct Rules
                </h2>

                <div class="space-y-4">
                    <!-- Rule 1 -->
                    <div class="p-4 rounded-xl border border-slate-100 hover:border-teal-200 transition-colors bg-slate-50/20">
                        <div class="flex gap-3">
                            <span class="w-6 h-6 rounded-full bg-teal-500 text-white font-extrabold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">1</span>
                            <div class="space-y-1">
                                <h4 class="font-extrabold text-slate-800 text-sm">Respect the Creative Commons</h4>
                                <p class="text-xs text-slate-500 leading-relaxed">Ensure you upload premium artwork, original descriptions, and cite specific creators or anime titles when doing detailed analysis. High quality keeps the forum intellectual and friendly.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Rule 2 -->
                    <div class="p-4 rounded-xl border border-slate-100 hover:border-teal-200 transition-colors bg-slate-50/20">
                        <div class="flex gap-3">
                            <span class="w-6 h-6 rounded-full bg-teal-500 text-white font-extrabold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">2</span>
                            <div class="space-y-1">
                                <h4 class="font-extrabold text-slate-800 text-sm">Strictly Safe Media Content</h4>
                                <p class="text-xs text-slate-500 leading-relaxed">Our cloud GIF/Image upload integrations with ImgBB must only contain appropriate visual assets. Prohibited or explicitly offensive content will result in an immediate tier reset and permanent account lockdown.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Rule 3 -->
                    <div class="p-4 rounded-xl border border-slate-100 hover:border-teal-200 transition-colors bg-slate-50/20">
                        <div class="flex gap-3">
                            <span class="w-6 h-6 rounded-full bg-teal-500 text-white font-extrabold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">3</span>
                            <div class="space-y-1">
                                <h4 class="font-extrabold text-slate-800 text-sm">No Spam or Point Farming</h4>
                                <p class="text-xs text-slate-500 leading-relaxed">Posting consecutive single-character replies or repetitive low-effort threads to inflate your Leaderboard position is strictly monitored. Help keep conversation threads dense and readable.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Rule 4 -->
                    <div class="p-4 rounded-xl border border-slate-100 hover:border-teal-200 transition-colors bg-slate-50/20">
                        <div class="flex gap-3">
                            <span class="w-6 h-6 rounded-full bg-teal-500 text-white font-extrabold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">4</span>
                            <div class="space-y-1">
                                <h4 class="font-extrabold text-slate-800 text-sm">Friendly DM Conversations</h4>
                                <p class="text-xs text-slate-500 leading-relaxed">Use our real-time Direct Messages to chat privately, form fan groups, or plan new review projects. Harassment inside DMs is strictly prohibited and subject to guild moderator inspection upon reports.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Rule 5 -->
                    <div class="p-4 rounded-xl border border-slate-100 hover:border-teal-200 transition-colors bg-slate-50/20">
                        <div class="flex gap-3">
                            <span class="w-6 h-6 rounded-full bg-teal-500 text-white font-extrabold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">5</span>
                            <div class="space-y-1">
                                <h4 class="font-extrabold text-slate-800 text-sm">Follow Staff Notifications</h4>
                                <p class="text-xs text-slate-500 leading-relaxed">Guild Staff can pin important updates or locks on specific threads. Always follow pinned instructions within forums. Active moderators have a custom security badge on their profiles.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Beautiful Otaku Badge Explanations (4 cols) -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Otaku Tiers Almanac -->
            <div class="border border-slate-200 bg-white rounded-3xl p-6 shadow-sm">
                <h3 class="text-base font-extrabold text-slate-900 mb-5 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-amber-500 text-sm animate-spin">stars</span> Unlocking Otaku Ranks
                </h3>
                <p class="text-xs text-slate-500 font-semibold leading-relaxed mb-4">
                    Your dynamic tier automatically upgrades as your points threshold increases. Unlocking a tier changes your global profile badge style and adds animated ambient properties!
                </p>

                <div class="space-y-4">
                    <!-- Tier 1 -->
                    <div class="flex items-center gap-3 pb-3.5 border-b border-slate-100 last:border-0 last:pb-0">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-500 text-white font-bold animate-shimmer flex-shrink-0 shadow-sm">
                            <span class="material-symbols-outlined text-sm animate-leaf">spa</span>
                        </span>
                        <div class="min-w-0">
                            <h4 class="text-xs font-extrabold text-slate-800 leading-tight">Wandering Ninja</h4>
                            <p class="text-[10px] text-slate-450 font-bold">Points: <span class="text-emerald-600 font-extrabold">0 - 49 pts</span></p>
                        </div>
                    </div>

                    <!-- Tier 2 -->
                    <div class="flex items-center gap-3 pb-3.5 border-b border-slate-100 last:border-0 last:pb-0">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-500 text-white font-bold animate-shimmer flex-shrink-0 shadow-sm">
                            <span class="material-symbols-outlined text-sm">shield</span>
                        </span>
                        <div class="min-w-0">
                            <h4 class="text-xs font-extrabold text-slate-800 leading-tight">Guild Adventurer</h4>
                            <p class="text-[10px] text-slate-450 font-bold">Points: <span class="text-blue-600 font-extrabold">50 - 199 pts</span></p>
                        </div>
                    </div>

                    <!-- Tier 3 -->
                    <div class="flex items-center gap-3 pb-3.5 border-b border-slate-100 last:border-0 last:pb-0">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-500 text-white font-bold animate-shimmer flex-shrink-0 shadow-sm">
                            <span class="material-symbols-outlined text-sm animate-bolt">bolt</span>
                        </span>
                        <div class="min-w-0">
                            <h4 class="text-xs font-extrabold text-slate-800 leading-tight">Super Saiyan</h4>
                            <p class="text-[10px] text-slate-450 font-bold">Points: <span class="text-amber-600 font-extrabold">200 - 499 pts</span></p>
                        </div>
                    </div>

                    <!-- Tier 4 -->
                    <div class="flex items-center gap-3 pb-3.5 border-b border-slate-100 last:border-0 last:pb-0">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-purple-500 text-white font-bold animate-shimmer flex-shrink-0 shadow-sm">
                            <span class="material-symbols-outlined text-sm animate-pulse">brightness_low</span>
                        </span>
                        <div class="min-w-0">
                            <h4 class="text-xs font-extrabold text-slate-800 leading-tight">Soul Reaper</h4>
                            <p class="text-[10px] text-slate-450 font-bold">Points: <span class="text-purple-600 font-extrabold">500 - 999 pts</span></p>
                        </div>
                    </div>

                    <!-- Tier 5 -->
                    <div class="flex items-center gap-3 last:border-0">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-rose-500 text-white font-bold animate-shimmer flex-shrink-0 shadow-sm">
                            <span class="material-symbols-outlined text-sm animate-shimmer">flag</span>
                        </span>
                        <div class="min-w-0">
                            <h4 class="text-xs font-extrabold text-slate-800 leading-tight">Pirate King</h4>
                            <p class="text-[10px] text-slate-450 font-bold">Points: <span class="text-rose-600 font-extrabold">1,000+ pts</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
