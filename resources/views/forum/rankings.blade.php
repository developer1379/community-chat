@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Beautiful Anime & Entertainment Welcome Banner with Animated Mesh -->
    <div class="relative rounded-none overflow-hidden shadow-sm border border-slate-200 bg-gradient-to-r from-purple-700 via-indigo-700 to-blue-800 p-6 sm:p-8 text-white animated-mesh-banner">
        <!-- Background absolute decorative shapes -->
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-pink-500/15 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 space-y-2 text-left">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-none text-xs font-extrabold bg-white/20 text-purple-100 border border-white/25 uppercase tracking-widest leading-none">
                <span class="material-symbols-outlined text-xs animate-spin">auto_awesome</span> Otaku Leaderboard & Tiers
            </span>
            <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight font-sans">
                XenProfessional Otaku Rankings
            </h1>
            <p class="text-sm sm:text-base text-purple-100/95 max-w-3xl font-medium leading-relaxed">
                Rank up your Otaku profile by starting discussions, posting replies, and earning reactions. Filter by creative specialties like illustrators, analysts, and guild moderators.
            </p>
        </div>
    </div>

    <!-- Tab navigation for Rankings Categories (Highly User Friendly scroll layout with sharp borders) -->
    <div class="flex border border-slate-200 bg-white p-1 rounded-none shadow-sm overflow-x-auto hide-scrollbar justify-start sm:justify-between items-center gap-1">
        <div class="flex items-center gap-1 flex-nowrap w-full">
            <a href="{{ route('rankings.index', ['tab' => 'all']) }}" class="px-4 py-2.5 text-xs font-extrabold transition-all rounded-none flex items-center gap-1.5 flex-shrink-0 {{ $currentTab === 'all' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }}">
                <span class="material-symbols-outlined text-sm">public</span> All Community Ranks
            </a>
            <a href="{{ route('rankings.index', ['tab' => 'creatives']) }}" class="px-4 py-2.5 text-xs font-extrabold transition-all rounded-none flex items-center gap-1.5 flex-shrink-0 {{ $currentTab === 'creatives' ? 'bg-pink-600 text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }}">
                <span class="material-symbols-outlined text-sm">palette</span> Creative Creators
            </a>
            <a href="{{ route('rankings.index', ['tab' => 'critics']) }}" class="px-4 py-2.5 text-xs font-extrabold transition-all rounded-none flex items-center gap-1.5 flex-shrink-0 {{ $currentTab === 'critics' ? 'bg-purple-600 text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }}">
                <span class="material-symbols-outlined text-sm">rate_review</span> Anime Critics
            </a>
            <a href="{{ route('rankings.index', ['tab' => 'guild']) }}" class="px-4 py-2.5 text-xs font-extrabold transition-all rounded-none flex items-center gap-1.5 flex-shrink-0 {{ $currentTab === 'guild' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }}">
                <span class="material-symbols-outlined text-sm">shield</span> Guild Staff
            </a>
        </div>
    </div>

    <!-- Desktop List Layout (Visible on Desktop & Tablet, rounded-none) -->
    <div class="hidden md:block space-y-3">
        @forelse($users as $user)
            <div class="border border-slate-200 bg-white p-4 rounded-none flex items-center justify-between gap-4 shadow-sm hover:border-blue-300 hover:shadow transition-all duration-200 leaderboard-row">
                <!-- Left Section: Rank + Member info -->
                <div class="flex items-center gap-4 min-w-0">
                    <!-- Rank Position Medal -->
                    <div class="w-10 flex-shrink-0 text-center font-extrabold text-slate-800">
                        @if($loop->iteration === 1)
                            <span class="inline-flex w-8 h-8 rounded-full items-center justify-center text-sm font-extrabold bg-amber-500 text-white border border-amber-400 shadow-md animate-float-gold cursor-help" title="1st Place Gold Champion">🥇</span>
                        @elseif($loop->iteration === 2)
                            <span class="inline-flex w-8 h-8 rounded-full items-center justify-center text-sm font-extrabold bg-slate-400 text-white border border-slate-350 shadow-md animate-float-silver cursor-help" title="2nd Place Silver Finalist">🥈</span>
                        @elseif($loop->iteration === 3)
                            <span class="inline-flex w-8 h-8 rounded-full items-center justify-center text-sm font-extrabold bg-amber-700 text-white border border-amber-600 shadow-md animate-float-bronze cursor-help" title="3rd Place Bronze Medalist">🥉</span>
                        @else
                            <span class="text-xs text-slate-450 font-bold bg-slate-100 w-6.5 h-6.5 inline-flex items-center justify-center rounded-none border border-slate-200">#{{ $loop->iteration }}</span>
                        @endif
                    </div>

                    <!-- Member Avatar -->
                    <a href="{{ route('profile.show', $user->name) }}"
                       data-user-hover="true"
                       data-user-name="{{ $user->name }}"
                       data-user-badge="{{ $user->title_badge }}"
                       data-user-joined="{{ $user->created_at->format('M d, Y') }}"
                       data-user-threads="{{ $user->threads()->count() }}"
                       data-user-posts="{{ $user->posts()->count() }}"
                       data-user-uploads="{{ $user->attachments()->count() }}"
                       data-user-avatar="{{ $user->avatar_path ? (str_starts_with($user->avatar_path, 'http') ? $user->avatar_path : asset('storage/' . $user->avatar_path)) : '' }}"
                       data-user-banner="{{ $user->banner_color }}"
                       data-user-banner-path="{{ $user->banner_path }}"
                       class="w-10 h-10 rounded-none overflow-hidden border border-slate-200 shadow-sm flex-shrink-0 block hover:border-blue-500 transition-colors">
                        @if($user->avatar_path)
                            <img src="{{ str_starts_with($user->avatar_path, 'http') ? $user->avatar_path : asset('storage/' . $user->avatar_path) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-550 rounded-none">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                        @endif
                    </a>

                    <!-- Username -->
                    <div class="min-w-0 leading-tight text-left">
                        <a href="{{ route('profile.show', $user->name) }}"
                           data-user-hover="true"
                           data-user-name="{{ $user->name }}"
                           data-user-badge="{{ $user->title_badge }}"
                           data-user-joined="{{ $user->created_at->format('M d, Y') }}"
                           data-user-threads="{{ $user->threads()->count() }}"
                           data-user-posts="{{ $user->posts()->count() }}"
                           data-user-uploads="{{ $user->attachments()->count() }}"
                           data-user-avatar="{{ $user->avatar_path ? (str_starts_with($user->avatar_path, 'http') ? $user->avatar_path : asset('storage/' . $user->avatar_path)) : '' }}"
                           data-user-banner="{{ $user->banner_color }}"
                           data-user-banner-path="{{ $user->banner_path }}"
                           class="font-extrabold text-slate-800 hover:text-blue-600 text-sm truncate block">{{ $user->name }}</a>
                        <p class="text-[10px] text-slate-450 font-bold mt-0.5">Joined {{ $user->created_at->format('M Y') }}</p>
                    </div>
                </div>

                <!-- Middle Section: Ranks and specialties -->
                <div class="flex items-center gap-3 flex-shrink-0">
                    <!-- Otaku Tier -->
                    @php
                        $tier = $user->anime_tier;
                        $tierName = $tier['name'];
                        $tierColor = $tier['color'];
                        
                        $iconClass = '';
                        $iconName = 'star';
                        
                        if (str_contains($tierName, 'Ninja')) {
                            $iconName = 'spa';
                            $iconClass = 'animate-leaf text-emerald-200';
                        } elseif (str_contains($tierName, 'Adventurer')) {
                            $iconName = 'shield';
                            $iconClass = 'text-blue-200';
                        } elseif (str_contains($tierName, 'Super Saiyan')) {
                            $iconName = 'bolt';
                            $iconClass = 'animate-bolt text-amber-200';
                        } elseif (str_contains($tierName, 'Soul Reaper')) {
                            $iconName = 'brightness_low';
                            $iconClass = 'animate-pulse text-violet-200';
                        } elseif (str_contains($tierName, 'Pirate King')) {
                            $iconName = 'flag';
                            $iconClass = 'animate-shimmer text-rose-200';
                        }
                    @endphp
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-extrabold uppercase shadow-sm text-white rounded-none animate-shimmer" style="background-color: {{ $tierColor }}">
                        <span class="material-symbols-outlined text-[13px] leading-none {{ $iconClass }}">{{ $iconName }}</span>
                        <span>{{ $tierName }}</span>
                    </span>

                    <!-- Specialty badge -->
                    <span class="inline-flex px-2.5 py-1.5 text-xs font-bold uppercase bg-slate-50 text-slate-700 border border-slate-200 rounded-none shadow-sm" style="border-left: 3px solid {{ $user->banner_color ?? '#3b82f6' }}">
                        {{ $user->title_badge ?? 'Otaku Member' }}
                    </span>
                </div>

                <!-- Right Section: Stats counters + Points -->
                <div class="flex items-center gap-6 flex-shrink-0">
                    <!-- Stats -->
                    <div class="flex items-center gap-3 text-xs font-bold text-slate-450">
                        <span class="bg-slate-50 border border-slate-200 px-2 py-1 rounded-none" title="Threads started">📚 {{ $user->threads()->count() }}</span>
                        <span class="bg-slate-50 border border-slate-200 px-2 py-1 rounded-none" title="Replies posted">💬 {{ $user->posts()->count() }}</span>
                    </div>

                    <!-- Points pill -->
                    <span class="inline-block font-extrabold text-blue-700 text-xs px-3 py-1.5 rounded-none bg-blue-50 border border-blue-150 shadow-sm transform duration-200 w-24 text-center group-hover:scale-105 duration-200">{{ $user->calculated_points }} pts</span>
                </div>
            </div>
        @empty
            <div class="border border-slate-200 bg-white p-12 text-center text-xs text-slate-450 font-semibold rounded-none shadow-sm">
                No Otaku members matched this tab rank filter.
            </div>
        @endforelse
    </div>

    <!-- Mobile Card Layout (Visible only on Mobile, zero overflow, rounded-none) -->
    <div class="block md:hidden space-y-4">
        @forelse($users as $user)
            <div class="border border-slate-200 bg-white p-4 rounded-none shadow-sm hover:shadow transition-all duration-200 flex flex-col gap-3">
                <!-- Top Row: Rank + User profile info -->
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <!-- Rank Medal -->
                        <div class="w-8 flex-shrink-0 text-center font-extrabold text-slate-800">
                            @if($loop->iteration === 1)
                                <span class="inline-flex w-7 h-7 rounded-full items-center justify-center text-xs bg-amber-500 text-white border border-amber-400 shadow animate-float-gold">🥇</span>
                            @elseif($loop->iteration === 2)
                                <span class="inline-flex w-7 h-7 rounded-full items-center justify-center text-xs bg-slate-400 text-white border border-slate-350 shadow animate-float-silver">🥈</span>
                            @elseif($loop->iteration === 3)
                                <span class="inline-flex w-7 h-7 rounded-full items-center justify-center text-xs bg-amber-700 text-white border border-amber-600 shadow animate-float-bronze">🥉</span>
                            @else
                                <span class="text-[10px] text-slate-450 font-bold bg-slate-100 w-6 h-6 inline-flex items-center justify-center rounded-none border border-slate-200">#{{ $loop->iteration }}</span>
                            @endif
                        </div>

                        <!-- Avatar -->
                        <a href="{{ route('profile.show', $user->name) }}" class="w-8 h-8 rounded-none overflow-hidden border border-slate-200 shadow-sm flex-shrink-0 block">
                            @if($user->avatar_path)
                                <img src="{{ str_starts_with($user->avatar_path, 'http') ? $user->avatar_path : asset('storage/' . $user->avatar_path) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-550 rounded-none">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                            @endif
                        </a>

                        <!-- Username -->
                        <div class="min-w-0 text-left">
                            <a href="{{ route('profile.show', $user->name) }}" class="font-extrabold text-slate-800 hover:text-blue-600 text-xs truncate block">{{ $user->name }}</a>
                            <p class="text-[9px] text-slate-450 font-bold leading-none mt-0.5">Joined {{ $user->created_at->format('M Y') }}</p>
                        </div>
                    </div>

                    <!-- Points pill (Sticky Right) -->
                    <span class="inline-block font-extrabold text-blue-700 text-[10px] px-2 py-1 rounded-none bg-blue-50 border border-blue-150 shadow-sm">{{ $user->calculated_points }} pts</span>
                </div>

                <!-- Mid Row: Badges side by side -->
                <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-slate-100">
                    <!-- Otaku Tier -->
                    @php
                        $tier = $user->anime_tier;
                        $tierName = $tier['name'];
                        $tierColor = $tier['color'];
                        
                        $iconClass = '';
                        $iconName = 'star';
                        
                        if (str_contains($tierName, 'Ninja')) { $iconName = 'spa'; $iconClass = 'animate-leaf text-emerald-200'; }
                        elseif (str_contains($tierName, 'Adventurer')) { $iconName = 'shield'; $iconClass = 'text-blue-200'; }
                        elseif (str_contains($tierName, 'Super Saiyan')) { $iconName = 'bolt'; $iconClass = 'animate-bolt text-amber-200'; }
                        elseif (str_contains($tierName, 'Soul Reaper')) { $iconName = 'brightness_low'; $iconClass = 'animate-pulse text-violet-200'; }
                        elseif (str_contains($tierName, 'Pirate King')) { $iconName = 'flag'; $iconClass = 'animate-shimmer text-rose-200'; }
                    @endphp
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-extrabold uppercase shadow-sm text-white rounded-none animate-shimmer" style="background-color: {{ $tierColor }}">
                        <span class="material-symbols-outlined text-[11px] leading-none {{ $iconClass }}">{{ $iconName }}</span>
                        <span>{{ $tierName }}</span>
                    </span>

                    <!-- Specialty badge -->
                    <span class="inline-flex px-2 py-0.5 text-[9px] font-bold uppercase bg-slate-50 text-slate-700 border border-slate-200 rounded-none shadow-sm" style="border-left: 2px solid {{ $user->banner_color ?? '#3b82f6' }}">
                        {{ $user->title_badge ?? 'Otaku Member' }}
                    </span>
                </div>

                <!-- Bottom Row: Simple Stats counts -->
                <div class="flex items-center justify-between border-t border-slate-100/60 pt-2 text-[9px] text-slate-450 font-bold bg-slate-50/20 px-2 py-1 rounded-none">
                    <span>📚 {{ $user->threads()->count() }} Threads</span>
                    <span>💬 {{ $user->posts()->count() }} Replies</span>
                </div>
            </div>
        @empty
            <div class="border border-slate-200 bg-white p-8 text-center text-xs text-slate-450 font-semibold rounded-none shadow-sm">
                No Otaku members matched this tab rank filter.
            </div>
        @endforelse
</div>
</div>
@endsection
