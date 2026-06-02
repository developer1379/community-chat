@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Beautiful Anime & Entertainment Welcome Banner with Animated Mesh -->
    <div class="relative rounded-2xl overflow-hidden shadow-md border border-slate-200 bg-gradient-to-r from-purple-700 via-indigo-700 to-blue-800 p-8 sm:p-10 text-white animated-mesh-banner">
        <!-- Background absolute decorative shapes -->
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-pink-500/15 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 space-y-3 text-left">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-white/20 text-purple-50 border border-white/25 uppercase tracking-widest leading-none shadow-sm">
                <span class="material-symbols-outlined text-xs animate-spin">auto_awesome</span> Otaku Leaderboard & Tiers
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight">
                XenProfessional Rankings
            </h1>
            <p class="text-base sm:text-lg text-purple-100 max-w-3xl font-medium leading-relaxed">
                Rank up your profile by starting discussions, posting replies, and earning reactions. Filter by creative specialties like illustrators, analysts, and guild moderators.
            </p>
        </div>
    </div>

    <!-- Tab navigation for Rankings Categories (Highly User Friendly scroll layout with sharp borders) -->
    <div class="flex border border-slate-200 bg-white p-1.5 rounded-xl shadow-sm overflow-x-auto hide-scrollbar justify-start sm:justify-between items-center gap-1.5">
        <div class="flex items-center gap-1 flex-nowrap w-full">
            <a href="{{ route('rankings.index', ['tab' => 'all']) }}" class="px-5 py-2.5 text-sm font-semibold transition-all rounded-lg flex items-center gap-2 flex-shrink-0 {{ $currentTab === 'all' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">
                <span class="material-symbols-outlined text-[18px]">public</span> All Community Ranks
            </a>
            <a href="{{ route('rankings.index', ['tab' => 'creatives']) }}" class="px-5 py-2.5 text-sm font-semibold transition-all rounded-lg flex items-center gap-2 flex-shrink-0 {{ $currentTab === 'creatives' ? 'bg-pink-600 text-white shadow-sm' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">
                <span class="material-symbols-outlined text-[18px]">palette</span> Creative Creators
            </a>
            <a href="{{ route('rankings.index', ['tab' => 'critics']) }}" class="px-5 py-2.5 text-sm font-semibold transition-all rounded-lg flex items-center gap-2 flex-shrink-0 {{ $currentTab === 'critics' ? 'bg-purple-600 text-white shadow-sm' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">
                <span class="material-symbols-outlined text-[18px]">rate_review</span> Anime Critics
            </a>
            <a href="{{ route('rankings.index', ['tab' => 'guild']) }}" class="px-5 py-2.5 text-sm font-semibold transition-all rounded-lg flex items-center gap-2 flex-shrink-0 {{ $currentTab === 'guild' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">
                <span class="material-symbols-outlined text-[18px]">shield</span> Guild Staff
            </a>
        </div>
    </div>

    @if(count($users) >= 3)
        <!-- Visual 3D Glassmorphic Champion Podium Showcase -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end justify-center py-6 px-4 bg-gradient-to-b from-slate-900/5 via-transparent to-transparent rounded-3xl border border-slate-200/40">
            <!-- 2nd Place (Silver) Podium - Left -->
            @if(isset($users[1]))
                @php $secondUser = $users[1]; @endphp
                <div class="flex flex-col items-center group">
                    <a href="{{ route('profile.show', $secondUser->name) }}" class="relative block shrink-0" data-user-hover="true" data-user-name="{{ $secondUser->name }}" data-user-badge="{{ $secondUser->title_badge }}" data-user-joined="{{ $secondUser->created_at->format('M d, Y') }}" data-user-threads="{{ $secondUser->threads()->count() }}" data-user-posts="{{ $secondUser->posts()->count() }}" data-user-uploads="{{ $secondUser->attachments()->count() }}" data-user-avatar="{{ $secondUser->avatar_url }}" data-user-banner="{{ $secondUser->banner_color }}" data-user-banner-path="{{ $secondUser->banner_path }}">
                        <!-- Silver Ring Glow -->
                        <div class="absolute inset-0 rounded-full bg-slate-400 opacity-20 blur-md animate-pulse"></div>
                        <div class="w-20 h-20 rounded-full overflow-hidden border-4 border-slate-300 shadow-md relative z-10 transition-transform group-hover:scale-105">
                            <img src="{{ $secondUser->avatar_url }}" class="w-full h-full object-cover" alt="avatar">
                        </div>
                        <span class="absolute -bottom-2 -right-2 bg-slate-400 text-white font-black text-xs w-6 h-6 rounded-full flex items-center justify-center border-2 border-white z-20">2</span>
                    </a>
                    <h3 class="font-extrabold text-slate-800 text-sm mt-4 truncate max-w-[150px]">{{ $secondUser->name }}</h3>
                    <span class="text-[9px] font-black uppercase tracking-wider text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full border border-slate-200 mt-1">{{ $secondUser->title_badge }}</span>
                    <!-- The Silver Podium block -->
                    <div class="w-48 bg-gradient-to-t from-slate-100 to-slate-200 border-t border-slate-300 h-20 mt-4 rounded-t-2xl shadow-inner flex flex-col items-center justify-center">
                        <span class="text-xs font-black text-slate-500">🥈 SILVER</span>
                        <span class="text-[10px] font-bold text-slate-450 mt-1">{{ number_format($secondUser->calculated_points) }} pts</span>
                    </div>
                </div>
            @endif

            <!-- 1st Place (Gold) Podium - Center (Highest!) -->
            @if(isset($users[0]))
                @php $firstUser = $users[0]; @endphp
                <div class="flex flex-col items-center group -mt-6 z-20">
                    <!-- Floating Crown -->
                    <span class="text-3xl animate-bounce mb-1">👑</span>
                    <a href="{{ route('profile.show', $firstUser->name) }}" class="relative block shrink-0" data-user-hover="true" data-user-name="{{ $firstUser->name }}" data-user-badge="{{ $firstUser->title_badge }}" data-user-joined="{{ $firstUser->created_at->format('M d, Y') }}" data-user-threads="{{ $firstUser->threads()->count() }}" data-user-posts="{{ $firstUser->posts()->count() }}" data-user-uploads="{{ $firstUser->attachments()->count() }}" data-user-avatar="{{ $firstUser->avatar_url }}" data-user-banner="{{ $firstUser->banner_color }}" data-user-banner-path="{{ $firstUser->banner_path }}">
                        <!-- Golden Ring Glow -->
                        <div class="absolute inset-0 rounded-full bg-amber-500 opacity-30 blur-lg animate-pulse"></div>
                        <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-amber-400 shadow-xl relative z-10 transition-transform group-hover:scale-105">
                            <img src="{{ $firstUser->avatar_url }}" class="w-full h-full object-cover" alt="avatar">
                        </div>
                        <span class="absolute -bottom-2 -right-2 bg-amber-500 text-white font-black text-xs w-7 h-7 rounded-full flex items-center justify-center border-2 border-white z-20">1</span>
                    </a>
                    <h3 class="font-extrabold text-slate-900 text-base mt-4 truncate max-w-[150px]">{{ $firstUser->name }}</h3>
                    <span class="text-[9px] font-black uppercase tracking-wider text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full border border-amber-200 mt-1">{{ $firstUser->title_badge }}</span>
                    <!-- The Gold Podium block -->
                    <div class="w-52 bg-gradient-to-t from-amber-100/85 via-yellow-50 to-amber-200/50 border-t border-amber-300 h-28 mt-4 rounded-t-3xl shadow-inner flex flex-col items-center justify-center">
                        <span class="text-xs font-black text-amber-600">🥇 CHAMPION</span>
                        <span class="text-xs font-black text-amber-700 mt-1">{{ number_format($firstUser->calculated_points) }} pts</span>
                    </div>
                </div>
            @endif

            <!-- 3rd Place (Bronze) Podium - Right -->
            @if(isset($users[2]))
                @php $thirdUser = $users[2]; @endphp
                <div class="flex flex-col items-center group">
                    <a href="{{ route('profile.show', $thirdUser->name) }}" class="relative block shrink-0" data-user-hover="true" data-user-name="{{ $thirdUser->name }}" data-user-badge="{{ $thirdUser->title_badge }}" data-user-joined="{{ $thirdUser->created_at->format('M d, Y') }}" data-user-threads="{{ $thirdUser->threads()->count() }}" data-user-posts="{{ $thirdUser->posts()->count() }}" data-user-uploads="{{ $thirdUser->attachments()->count() }}" data-user-avatar="{{ $thirdUser->avatar_url }}" data-user-banner="{{ $thirdUser->banner_color }}" data-user-banner-path="{{ $thirdUser->banner_path }}">
                        <!-- Bronze Ring Glow -->
                        <div class="absolute inset-0 rounded-full bg-amber-700 opacity-20 blur-md animate-pulse"></div>
                        <div class="w-20 h-20 rounded-full overflow-hidden border-4 border-amber-600 shadow-md relative z-10 transition-transform group-hover:scale-105">
                            <img src="{{ $thirdUser->avatar_url }}" class="w-full h-full object-cover" alt="avatar">
                        </div>
                        <span class="absolute -bottom-2 -right-2 bg-amber-700 text-white font-black text-xs w-6 h-6 rounded-full flex items-center justify-center border-2 border-white z-20">3</span>
                    </a>
                    <h3 class="font-extrabold text-slate-800 text-sm mt-4 truncate max-w-[150px]">{{ $thirdUser->name }}</h3>
                    <span class="text-[9px] font-black uppercase tracking-wider text-amber-700 bg-amber-50 px-2.5 py-1 rounded-full border border-amber-250 mt-1">{{ $thirdUser->title_badge }}</span>
                    <!-- The Bronze Podium block -->
                    <div class="w-48 bg-gradient-to-t from-amber-50 to-amber-100 border-t border-amber-250 h-16 mt-4 rounded-t-2xl shadow-inner flex flex-col items-center justify-center">
                        <span class="text-xs font-black text-amber-700">🥉 BRONZE</span>
                        <span class="text-[10px] font-bold text-slate-450 mt-1">{{ number_format($thirdUser->calculated_points) }} pts</span>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Desktop List Layout (Visible on Desktop & Tablet, rounded-none) -->
    <div class="hidden md:block space-y-4">
        @forelse($users as $user)
            <div class="border border-slate-200 bg-white p-5 rounded-2xl flex items-center justify-between gap-4 shadow-sm hover:border-blue-300 hover:shadow-md transition-all duration-300 leaderboard-row">
                <!-- Left Section: Rank + Member info -->
                <div class="flex items-center gap-5 min-w-0">
                    <!-- Rank Position Medal -->
                    <div class="w-12 flex-shrink-0 text-center font-extrabold text-slate-800">
                        @if($loop->iteration === 1)
                            <span class="inline-flex w-10 h-10 rounded-full items-center justify-center text-lg bg-amber-500 text-white border border-amber-400 shadow-md animate-float-gold cursor-help" title="1st Place Gold Champion">🥇</span>
                        @elseif($loop->iteration === 2)
                            <span class="inline-flex w-10 h-10 rounded-full items-center justify-center text-lg bg-slate-400 text-white border border-slate-350 shadow-md animate-float-silver cursor-help" title="2nd Place Silver Finalist">🥈</span>
                        @elseif($loop->iteration === 3)
                            <span class="inline-flex w-10 h-10 rounded-full items-center justify-center text-lg bg-amber-700 text-white border border-amber-600 shadow-md animate-float-bronze cursor-help" title="3rd Place Bronze Medalist">🥉</span>
                        @else
                            <span class="text-sm text-slate-500 font-bold bg-slate-50 w-8 h-8 inline-flex items-center justify-center rounded-full border border-slate-200 shadow-sm">#{{ $loop->iteration }}</span>
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
                       data-user-avatar="{{ $user->avatar_url }}"
                       data-user-banner="{{ $user->banner_color }}"
                       data-user-banner-path="{{ $user->banner_path }}"
                       class="w-12 h-12 rounded-full overflow-hidden border-2 border-white shadow flex-shrink-0 block hover:border-blue-500 transition-colors bg-slate-100">
                        <img src="{{ $user->avatar_url }}" class="w-full h-full object-cover" alt="avatar">
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
                           data-user-avatar="{{ $user->avatar_url }}"
                           data-user-banner="{{ $user->banner_color }}"
                           data-user-banner-path="{{ $user->banner_path }}"
                           class="font-bold text-slate-900 hover:text-blue-600 text-base truncate block transition-colors">{{ $user->name }}</a>
                        <p class="text-xs text-slate-500 font-medium mt-1">Joined {{ $user->created_at->format('M Y') }}</p>
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
                    <span class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold uppercase shadow-sm text-white rounded-full animate-shimmer" style="background-color: {{ $tierColor }}">
                        <span class="material-symbols-outlined text-[14px] leading-none {{ $iconClass }}">{{ $iconName }}</span>
                        <span>{{ $tierName }}</span>
                    </span>

                    <!-- Specialty badge -->
                    <span class="inline-flex px-3 py-1.5 text-xs font-bold uppercase bg-slate-50 text-slate-700 border border-slate-200 rounded-lg shadow-sm" style="border-left: 3px solid {{ $user->banner_color ?? '#3b82f6' }}">
                        {{ $user->title_badge ?? 'Otaku Member' }}
                    </span>
                </div>

                <!-- Right Section: Stats counters + Points -->
                <div class="flex items-center gap-8 flex-shrink-0">
                    <!-- Stats -->
                    <div class="flex items-center gap-3 text-xs font-bold text-slate-500">
                        <span class="bg-slate-50 border border-slate-200 px-2.5 py-1.5 rounded-lg shadow-sm" title="Threads started">📚 {{ $user->threads()->count() }}</span>
                        <span class="bg-slate-50 border border-slate-200 px-2.5 py-1.5 rounded-lg shadow-sm" title="Replies posted">💬 {{ $user->posts()->count() }}</span>
                    </div>

                    <!-- Points pill -->
                    <span class="inline-block font-extrabold text-blue-700 text-sm px-4 py-2 rounded-xl bg-blue-50 border border-blue-150 shadow-sm transition-transform duration-200 w-28 text-center hover:scale-105">{{ $user->calculated_points }} pts</span>
                </div>
            </div>
        @empty
            <div class="border border-slate-200 bg-white p-12 text-center text-sm text-slate-500 font-medium rounded-2xl shadow-sm">
                No Otaku members matched this tab rank filter.
            </div>
        @endforelse
    </div>

    <!-- Mobile Card Layout (Visible only on Mobile, zero overflow, rounded-none) -->
    <div class="block md:hidden space-y-4">
        @forelse($users as $user)
            <div class="border border-slate-200 bg-white p-4 rounded-2xl shadow-sm hover:shadow transition-all duration-200 flex flex-col gap-4">
                <!-- Top Row: Rank + User profile info -->
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <!-- Rank Medal -->
                        <div class="w-8 flex-shrink-0 text-center font-extrabold text-slate-800">
                            @if($loop->iteration === 1)
                                <span class="inline-flex w-8 h-8 rounded-full items-center justify-center text-sm bg-amber-500 text-white border border-amber-400 shadow animate-float-gold">🥇</span>
                            @elseif($loop->iteration === 2)
                                <span class="inline-flex w-8 h-8 rounded-full items-center justify-center text-sm bg-slate-400 text-white border border-slate-350 shadow animate-float-silver">🥈</span>
                            @elseif($loop->iteration === 3)
                                <span class="inline-flex w-8 h-8 rounded-full items-center justify-center text-sm bg-amber-700 text-white border border-amber-600 shadow animate-float-bronze">🥉</span>
                            @else
                                <span class="text-[10px] text-slate-500 font-bold bg-slate-50 w-7 h-7 inline-flex items-center justify-center rounded-full border border-slate-200 shadow-sm">#{{ $loop->iteration }}</span>
                            @endif
                        </div>

                        <!-- Avatar -->
                        <a href="{{ route('profile.show', $user->name) }}" class="w-10 h-10 rounded-full overflow-hidden border border-slate-200 shadow flex-shrink-0 block bg-slate-100">
                            <img src="{{ $user->avatar_url }}" class="w-full h-full object-cover" alt="avatar">
                        </a>

                        <!-- Username -->
                        <div class="min-w-0 text-left">
                            <a href="{{ route('profile.show', $user->name) }}" class="font-bold text-slate-900 hover:text-blue-600 text-sm truncate block">{{ $user->name }}</a>
                            <p class="text-[10px] text-slate-500 font-medium leading-none mt-1">Joined {{ $user->created_at->format('M Y') }}</p>
                        </div>
                    </div>

                    <!-- Points pill (Sticky Right) -->
                    <span class="inline-block font-extrabold text-blue-700 text-[10px] px-2.5 py-1.5 rounded-lg bg-blue-50 border border-blue-150 shadow-sm">{{ $user->calculated_points }} pts</span>
                </div>

                <!-- Mid Row: Badges side by side -->
                <div class="flex flex-wrap items-center gap-2 pt-3 border-t border-slate-100">
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
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-bold uppercase shadow-sm text-white rounded-full animate-shimmer" style="background-color: {{ $tierColor }}">
                        <span class="material-symbols-outlined text-[12px] leading-none {{ $iconClass }}">{{ $iconName }}</span>
                        <span>{{ $tierName }}</span>
                    </span>

                    <!-- Specialty badge -->
                    <span class="inline-flex px-2.5 py-1 text-[10px] font-bold uppercase bg-slate-50 text-slate-700 border border-slate-200 rounded-lg shadow-sm" style="border-left: 2px solid {{ $user->banner_color ?? '#3b82f6' }}">
                        {{ $user->title_badge ?? 'Otaku Member' }}
                    </span>
                </div>

                <!-- Bottom Row: Simple Stats counts -->
                <div class="flex items-center justify-between border-t border-slate-100/60 pt-3 text-[10px] text-slate-500 font-bold bg-slate-50/20 px-3 py-2 rounded-xl">
                    <span>📚 {{ $user->threads()->count() }} Threads</span>
                    <span>💬 {{ $user->posts()->count() }} Replies</span>
                </div>
            </div>
        @empty
            <div class="border border-slate-200 bg-white p-8 text-center text-sm text-slate-500 font-medium rounded-2xl shadow-sm">
                No Otaku members matched this tab rank filter.
            </div>
        @endforelse
</div>
</div>
@endsection
