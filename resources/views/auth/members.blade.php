@extends('layouts.app')

@section('content')
@php
    $followingIds = Auth::check() ? Auth::user()->following()->pluck('users.id')->toArray() : [];
@endphp
<div class="space-y-6 max-w-7xl mx-auto px-0 sm:px-4">
    <!-- Header path info -->
    <div class="mb-6 px-4 sm:px-0">
        <nav class="flex text-sm text-slate-500 dark:text-slate-400 font-medium mb-3" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors">Forums</a>
                </li>
                <li>
                    <span class="mx-2 text-slate-300 dark:text-slate-700">/</span>
                </li>
                <li aria-current="page">
                    <span class="text-slate-900 dark:text-white font-semibold">Members Directory</span>
                </li>
            </ol>
        </nav>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                    <span class="flex h-10 w-10 sm:h-12 sm:w-12 items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 shadow-sm border border-blue-100 dark:border-blue-900/30">
                        <span class="material-symbols-outlined text-xl sm:text-2xl">group</span>
                    </span>
                    Registered Members
                </h1>
                <p class="mt-2 text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-2xl font-medium leading-relaxed">Discover active community specialists, check custom badges, see followers, and follow your favorite developers.</p>
            </div>
        </div>
    </div>

    <!-- Search and Filter Bar Card -->
    <div class="bg-white dark:bg-slate-900 border-y sm:border border-slate-200 dark:border-slate-800 rounded-none sm:rounded-2xl p-4 mb-6 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <!-- Filter Tabs -->
        <div class="flex flex-wrap items-center gap-1.5">
            <a href="{{ route('members.index', ['filter' => 'all', 'search' => $search]) }}" 
               class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all {{ $filter === 'all' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900 shadow-sm' : 'bg-transparent text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                All Members
            </a>
            <a href="{{ route('members.index', ['filter' => 'active', 'search' => $search]) }}" 
               class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all flex items-center gap-1 {{ $filter === 'active' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900 shadow-sm' : 'bg-transparent text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                <span class="material-symbols-outlined text-[14px] {{ $filter === 'active' ? 'text-orange-400' : 'text-slate-400 dark:text-slate-550' }}">local_fire_department</span> Most Active
            </a>
            <a href="{{ route('members.index', ['filter' => 'newest', 'search' => $search]) }}" 
               class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all flex items-center gap-1 {{ $filter === 'newest' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900 shadow-sm' : 'bg-transparent text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                <span class="material-symbols-outlined text-[14px] {{ $filter === 'newest' ? 'text-blue-400' : 'text-slate-400 dark:text-slate-550' }}">new_releases</span> Newest
            </a>
            @auth
                <a href="{{ route('members.index', ['filter' => 'following', 'search' => $search]) }}" 
                   class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all flex items-center gap-1 {{ $filter === 'following' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900 shadow-sm' : 'bg-transparent text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <span class="material-symbols-outlined text-[14px] {{ $filter === 'following' ? 'text-indigo-450' : 'text-slate-400 dark:text-slate-550' }}">group</span> Following ({{ Auth::user()->following()->count() }})
                </a>
                <a href="{{ route('members.index', ['filter' => 'followers', 'search' => $search]) }}" 
                   class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all flex items-center gap-1 {{ $filter === 'followers' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900 shadow-sm' : 'bg-transparent text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <span class="material-symbols-outlined text-[14px] {{ $filter === 'followers' ? 'text-rose-400' : 'text-slate-400 dark:text-slate-550' }}">campaign</span> Followers ({{ Auth::user()->followers()->count() }})
                </a>
            @endauth
        </div>

        <!-- Search Input Form -->
        <form action="{{ route('members.index') }}" method="GET" class="relative w-full lg:w-72 shrink-0">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="material-symbols-outlined text-slate-400 dark:text-slate-500 text-base">search</span>
                </div>
                <input type="text" name="search" value="{{ $search }}" class="block w-full pl-9 pr-3 py-2 border border-slate-205 dark:border-slate-800 rounded-xl leading-5 bg-slate-50 dark:bg-slate-950 placeholder-slate-400 dark:placeholder-slate-500 text-slate-800 dark:text-slate-200 focus:outline-none focus:bg-white focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-xs sm:text-sm transition-all" placeholder="Search members...">
            </div>
        </form>
    </div>

    <!-- Members Grid / List (List on mobile, Grid Cards on sm+) -->
    <div class="block sm:grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-0 sm:gap-6 divide-y divide-slate-100 dark:divide-slate-850 sm:divide-y-0">
        @forelse($users as $user)
            @php
                $userTier = $user->computed_anime_tier;
                $points = $user->activity_points;
                $level = $userTier['level'] ?? 1;
                
                // Add unique premium glows on profiles
                $glowClass = 'border-slate-200 dark:border-slate-800 shadow-sm';
                $avatarGlow = 'bg-white dark:bg-slate-900';
                if ($level >= 20) {
                    $glowClass = 'border-rose-500/30 dark:border-rose-500/20 shadow-[0_0_15px_rgba(225,29,72,0.15)] ring-1 ring-rose-500/5';
                    $avatarGlow = 'bg-gradient-to-tr from-rose-500 to-orange-500';
                } elseif ($level >= 16) {
                    $glowClass = 'border-purple-500/30 dark:border-purple-500/20 shadow-[0_0_12px_rgba(124,58,237,0.15)]';
                    $avatarGlow = 'bg-gradient-to-tr from-purple-500 to-indigo-500';
                }

                $hasStatus = !empty($user->status) || !empty($user->status_image);

                $latestPost = $user->latestPost;
                $latestThread = $user->latestThread;
                $isFollowing = in_array($user->id, $followingIds);

                $activityText = null;
                $activityTime = null;
                $activityLink = '#';

                if ($latestPost && (!$latestThread || $latestPost->created_at->gt($latestThread->created_at))) {
                    $activityText = 'Replied: "' . Str::limit(strip_tags($latestPost->content), 45) . '"';
                    $activityTime = $latestPost->created_at->diffForHumans();
                    if ($latestPost->thread) {
                        $activityLink = route('threads.show', $latestPost->thread->slug) . '#post-' . $latestPost->id;
                    }
                } elseif ($latestThread) {
                    $activityText = 'Posted: "' . Str::limit($latestThread->title, 45) . '"';
                    $activityTime = $latestThread->created_at->diffForHumans();
                    $activityLink = route('threads.show', $latestThread->slug);
                }
            @endphp

            <!-- Mobile Row List Item (visible on mobile, hidden on sm) -->
            <div class="flex sm:hidden flex-col p-4 bg-white dark:bg-slate-900 relative gap-2.5">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-start gap-3 min-w-0">
                        <!-- Avatar with status ring -->
                        <div class="relative w-12 h-12 shrink-0 @if($hasStatus) cursor-pointer hover:scale-105 transition-transform duration-200 @endif"
                             @if($hasStatus) onclick="viewUserStatus('{{ $user->id }}', '{{ addslashes($user->name) }}', '{{ $user->avatar_url }}', '{{ addslashes($user->title_badge ?: 'Community Member') }}', '{{ addslashes($user->status) }}', '{{ $user->status_image }}')" @endif>
                            <div class="w-full h-full rounded-full @if($hasStatus) p-[2px] bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-650 animate-[spin_8s_linear_infinite] @else p-[2.5px] {{ $avatarGlow }} @endif overflow-hidden shadow-sm relative">
                                <div class="w-full h-full rounded-full overflow-hidden bg-white dark:bg-slate-900 p-[0.5px] relative">
                                    <img src="{{ $user->avatar_url }}" class="w-full h-full object-cover rounded-full" alt="avatar">
                                </div>
                            </div>
                            @if($hasStatus)
                                <span class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-650 rounded-full border border-white dark:border-slate-900 flex items-center justify-center text-[8px] shadow-sm select-none pointer-events-none">💬</span>
                            @endif
                        </div>

                        <!-- Member Info -->
                        <div class="min-w-0 leading-tight">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <h3 class="font-bold text-slate-900 dark:text-white text-sm hover:underline hover:text-blue-600 dark:hover:text-blue-450 transition-colors truncate {{ $user->username_style }}" style="{{ $user->username_style_css }}">
                                    <a href="{{ route('profile.show', $user->name) }}" data-user-hover="true" data-user-name="{{ $user->name }}">{{ $user->name }}</a>
                                </h3>
                            </div>
                            
                            <!-- Headline (Tier & Level) -->
                            <div class="flex items-center gap-1.5 mt-0.5 text-[10px] font-bold text-slate-505 dark:text-slate-400">
                                <span>Lvl {{ $level }}</span>
                                <span class="text-slate-300 dark:text-slate-700">•</span>
                                <span style="color: {{ $userTier['color'] }}">{{ $userTier['badge'] }}</span>
                            </div>

                            <!-- Social / Coins Stats -->
                            <div class="flex items-center gap-2 mt-1 flex-wrap text-[9px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                <div class="flex items-center gap-0.5 bg-amber-50 dark:bg-amber-955/25 border border-amber-200/50 dark:border-amber-900/30 px-1.5 py-0.2 rounded text-amber-700 dark:text-amber-400 shrink-0 font-bold">
                                    <span class="material-symbols-outlined text-[10px] text-amber-505">monetization_on</span>
                                    <span>{{ number_format($user->coins) }}</span>
                                </div>
                                <span>{{ $user->followers()->count() }} followers</span>
                            </div>

                            <!-- Status Message (if present) -->
                            @if($user->status)
                                <div class="mt-1.5 text-[10px] font-medium text-slate-550 dark:text-slate-400 truncate max-w-[170px] sm:max-w-xs cursor-pointer hover:underline flex items-center gap-1" 
                                     title="View status"
                                     onclick="viewUserStatus('{{ $user->id }}', '{{ addslashes($user->name) }}', '{{ $user->avatar_url }}', '{{ addslashes($user->title_badge ?: 'Community Member') }}', '{{ addslashes($user->status) }}', '{{ $user->status_image }}')">
                                    <span class="shrink-0">💬</span>
                                    <span class="truncate">"{{ $user->status }}"</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Follow / Action Buttons -->
                    <div class="flex flex-col gap-1.5 shrink-0 items-end">
                        @auth
                            @if(Auth::id() !== $user->id)
                                <button type="button" 
                                        onclick="toggleFollowUser('{{ $user->name }}', '{{ $user->id }}')" 
                                        id="follow-btn-mobile-{{ $user->id }}" 
                                        class="text-[10px] font-bold py-1 px-3 rounded-full transition-all cursor-pointer border text-center min-w-[72px]
                                        {{ $isFollowing 
                                            ? 'bg-slate-105 dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300' 
                                            : 'bg-white dark:bg-slate-900 border-blue-600 text-blue-600 hover:bg-blue-50' }}">
                                    {{ $isFollowing ? 'Following' : 'Follow' }}
                                </button>
                                <button type="button" 
                                        onclick="startDirectChat('{{ $user->name }}')" 
                                        class="text-[10px] font-bold py-1 px-3 rounded-full bg-blue-600 hover:bg-blue-700 text-white shadow-sm transition-all cursor-pointer text-center min-w-[72px]">
                                    Message
                                </button>
                            @else
                                <span class="text-[9px] font-bold uppercase text-slate-400 dark:text-slate-550 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded">You</span>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="text-[10px] font-bold py-1 px-3 rounded-full border border-blue-600 text-blue-600 hover:bg-blue-50 transition-colors text-center min-w-[72px]">
                                Login
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Recent Activity Feed -->
                @if($activityText)
                    <div class="mt-1.5 pt-2 border-t border-slate-100 dark:border-slate-850/60 flex flex-col gap-0.5 text-left pl-1">
                        <div class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-[9px] text-slate-400 dark:text-slate-500">history</span>
                            <span class="text-[8px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider">Recent Activity</span>
                        </div>
                        <a href="{{ $activityLink }}" class="text-[10px] text-slate-650 dark:text-slate-350 hover:text-blue-600 dark:hover:text-blue-400 truncate block font-semibold">
                            {{ $activityText }} <span class="text-[8.5px] text-slate-405 dark:text-slate-500 font-normal ml-1">({{ $activityTime }})</span>
                        </a>
                    </div>
                @endif
            </div>

            <!-- Tablet/Desktop Card View (hidden on mobile, visible on sm+) -->
            <div class="hidden sm:flex bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-850 rounded-2xl overflow-hidden hover:shadow-lg transition-all duration-300 flex-col group relative {{ $glowClass }}">
                <!-- Cover Photo Header -->
                <div class="h-16 relative w-full bg-cover bg-center" style="background: {{ $user->banner_path ? 'url(' . $user->banner_path . ')' : $user->banner_color }}">
                    <div class="absolute inset-0 bg-black/5 dark:bg-black/10 transition-colors duration-300"></div>
                </div>

                <!-- Member details container -->
                <div class="px-3 pb-2.5 relative flex flex-col items-center grow">
                    <!-- Avatar -->
                    <div class="relative w-14 h-14 sm:w-16 sm:h-16 -mt-7 sm:-mt-8 mb-2 block shrink-0 z-10 @if($hasStatus) cursor-pointer hover:scale-110 transition-transform duration-200 @endif"
                         @if($hasStatus) onclick="viewUserStatus('{{ $user->id }}', '{{ addslashes($user->name) }}', '{{ $user->avatar_url }}', '{{ addslashes($user->title_badge ?: 'Community Member') }}', '{{ addslashes($user->status) }}', '{{ $user->status_image }}')" @endif>
                        <div class="w-full h-full rounded-full @if($hasStatus) p-[2px] bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-650 animate-[spin_8s_linear_infinite] @else p-[2.5px] {{ $avatarGlow }} @endif overflow-hidden shadow-sm relative transition-transform group-hover:scale-105 duration-300">
                            <div class="w-full h-full rounded-full overflow-hidden bg-white dark:bg-slate-900 p-[1px] relative">
                                <img src="{{ $user->avatar_url }}" class="w-full h-full object-cover rounded-full" alt="avatar">
                            </div>
                        </div>
                        @if($hasStatus)
                            <span class="absolute bottom-0.5 right-0.5 w-4.5 h-4.5 bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-650 rounded-full border border-white dark:border-slate-900 flex items-center justify-center text-[9px] shadow-md select-none pointer-events-none">💬</span>
                        @endif
                    </div>

                    <!-- Name and Badge -->
                    <div class="w-full flex flex-col items-center text-center">
                        <h3 class="font-semibold text-slate-900 dark:text-white text-sm hover:underline hover:text-blue-600 dark:hover:text-blue-400 transition-colors truncate w-full mb-0.5 {{ $user->username_style }}" style="{{ $user->username_style_css }}">
                            <a href="{{ route('profile.show', $user->name) }}"
                               data-user-hover="true" 
                               data-user-name="{{ $user->name }}">{{ $user->name }}</a>
                        </h3>
                        
                        <!-- Headline / Title -->
                        <p class="text-[11px] text-slate-500 dark:text-slate-405 font-normal truncate w-full px-2 text-center mt-0.5">
                            {{ $user->title_badge ?: 'Community Member' }}
                        </p>

                        <!-- Status Bubble (if present) -->
                        @if($user->status)
                            <div class="mt-1 px-2 py-0.5 bg-slate-50 dark:bg-slate-850/80 rounded-full border border-slate-100 dark:border-slate-800 text-[10px] font-semibold text-slate-500 dark:text-slate-400 max-w-[95%] truncate shadow-sm cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" 
                                 title="View status"
                                 onclick="viewUserStatus('{{ $user->id }}', '{{ addslashes($user->name) }}', '{{ $user->avatar_url }}', '{{ addslashes($user->title_badge ?: 'Community Member') }}', '{{ addslashes($user->status) }}', '{{ $user->status_image }}')">
                                💬 {{ $user->status }}
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex items-center gap-1 mt-1.5 flex-wrap justify-center text-[9px] font-bold">
                        <span class="px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                            Lvl {{ $level }}
                        </span>
                        <span class="px-1.5 py-0.5 rounded" style="background: {{ $userTier['color'] }}20; color: {{ $userTier['color'] }}">
                            {{ $userTier['badge'] }}
                        </span>
                        <!-- Coins Pill -->
                        <div class="flex items-center gap-0.5 bg-amber-50 dark:bg-amber-950/20 border border-amber-200/50 dark:border-amber-900/30 px-1.5 py-0.5 rounded text-[9px] font-bold text-amber-700 dark:text-amber-400 shadow-sm shrink-0">
                            <span class="material-symbols-outlined text-[11px] text-amber-500">monetization_on</span>
                            <span>{{ number_format($user->coins) }}</span>
                        </div>
                    </div>

                    <!-- Social Proof / Connections -->
                    <div class="mt-2.5 pt-2 border-t border-slate-105 dark:border-slate-850 w-full flex justify-center text-[10px] text-slate-400 dark:text-slate-505 font-medium">
                        <span><span id="follower-count-{{ $user->id }}" class="font-semibold text-slate-605 dark:text-slate-300">{{ number_format($user->followers()->count()) }}</span> followers</span>
                        <span class="mx-1.5 text-slate-300 dark:text-slate-700">•</span>
                        <span><span class="font-semibold text-slate-605 dark:text-slate-300">{{ number_format($user->threads()->count()) }}</span> threads</span>
                        <span class="mx-1.5 text-slate-300 dark:text-slate-700">•</span>
                        <span><span class="font-semibold text-slate-655 dark:text-slate-300">{{ number_format($user->posts()->count()) }}</span> replies</span>
                    </div>

                    <!-- Recent Activity -->
                    @if($activityText)
                        <div class="mt-2 pt-2 border-t border-slate-105 dark:border-slate-850 w-full text-center">
                            <a href="{{ $activityLink }}" class="block text-[10px] text-slate-550 dark:text-slate-405 truncate hover:text-blue-500 dark:hover:text-blue-400 transition-colors font-medium" title="{{ $activityText }}">
                                <span class="font-bold text-[8.5px] uppercase tracking-wider text-slate-400 dark:text-slate-500 mr-0.5">Latest:</span>
                                {{ $activityText }}
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Action Footer -->
                <div class="p-2.5 bg-white dark:bg-slate-900 mt-auto w-full border-t border-slate-100 dark:border-slate-850">
                    @auth
                        @if(Auth::id() === $user->id)
                            <div class="w-full py-1 text-center text-xs font-semibold text-slate-505 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 rounded-full border border-transparent">
                                This is you
                            </div>
                        @else
                            <div class="flex gap-1.5 w-full">
                                <button type="button" 
                                        onclick="toggleFollowUser('{{ $user->name }}', '{{ $user->id }}')" 
                                        id="follow-btn-{{ $user->id }}" 
                                        class="flex-1 text-[11px] font-semibold py-1 px-2.5 rounded-full transition-all cursor-pointer border flex items-center justify-center gap-1 shadow-sm 
                                        {{ $isFollowing 
                                            ? 'bg-slate-100 dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-rose-50 dark:hover:bg-rose-955/30 hover:text-rose-600 hover:border-rose-200 dark:hover:border-rose-800 group/follow' 
                                            : 'bg-white dark:bg-slate-900 border-blue-600 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950/30' }}">
                                    @if($isFollowing)
                                        <span class="material-symbols-outlined text-[13px] group-hover/follow:hidden">check</span>
                                        <span class="group-hover/follow:hidden">Following</span>
                                        <span class="material-symbols-outlined text-[13px] hidden group-hover/follow:inline-block">person_remove</span>
                                        <span class="hidden group-hover/follow:inline">Unfollow</span>
                                    @else
                                        <span class="material-symbols-outlined text-[13px]">person_add</span>
                                        <span>Follow</span>
                                    @endif
                                </button>
                                <button type="button" 
                                        onclick="startDirectChat('{{ $user->name }}')" 
                                        class="flex-1 text-[11px] font-semibold py-1 px-2.5 rounded-full transition-all cursor-pointer bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center gap-1 shadow-sm">
                                    <span class="material-symbols-outlined text-[13px]">chat</span>
                                    <span>Message</span>
                                </button>
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="w-full block text-center border border-blue-600 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950/30 font-semibold text-[11px] py-1 px-2.5 rounded-full transition-colors">
                            Login to Interact
                        </a>
                    @endauth
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center mui-card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-8 rounded-none sm:rounded-2xl">
                <span class="material-symbols-outlined text-4xl text-slate-305 dark:text-slate-600 mb-2">person_search</span>
                <h3 class="font-bold text-slate-800 dark:text-slate-205 text-base mb-1">No Members Found</h3>
                <p class="text-xs text-slate-450 dark:text-slate-500 max-w-sm mx-auto font-semibold">We couldn't find any registered members matching your search or filters. Try adjusting your search term!</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination links -->
    <div class="mt-6 px-4 sm:px-0">
        {{ $users->links() }}
    </div>
</div>

@auth
    <!-- Follow System Asynchronous API Controller -->
    <script>
        function toggleFollowUser(username, userId) {
            const btn = document.getElementById(`follow-btn-${userId}`) || document.getElementById(`follow-btn-mobile-${userId}`);
            const followerCount = document.getElementById(`follower-count-${userId}`);
            if (!btn) return;

            // Instantly disable temporarily to avoid race conditions
            btn.disabled = true;

            const url = `/members/${encodeURIComponent(username)}/follow`;

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Follow action failed.');
                }
                return response.json();
            })
            .then(data => {
                btn.disabled = false;
                if (data.success) {
                    // Update follower statistic counter
                    if (followerCount) {
                        followerCount.innerText = data.followers_count;
                    }

                    // Update BOTH mobile and desktop button displays if they exist on the page
                    const buttons = [
                        document.getElementById(`follow-btn-${userId}`),
                        document.getElementById(`follow-btn-mobile-${userId}`)
                    ];

                    buttons.forEach(b => {
                        if (!b) return;
                        if (data.following) {
                            if (b.id.includes('mobile')) {
                                b.className = "text-[11px] font-bold py-1 px-3 rounded-full transition-all cursor-pointer border bg-slate-100 dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-350 hover:bg-slate-200";
                                b.innerText = 'Following';
                            } else {
                                b.className = "flex-1 text-[11px] font-semibold py-1 px-2.5 rounded-full transition-all cursor-pointer border flex items-center justify-center gap-1 shadow-sm bg-slate-100 dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-rose-50 dark:hover:bg-rose-955/20 hover:text-rose-600 hover:border-rose-200 dark:hover:border-rose-900 group/follow";
                                b.innerHTML = `
                                    <span class="material-symbols-outlined text-[13px] group-hover/follow:hidden">check</span>
                                    <span class="group-hover/follow:hidden text-[11px]">Following</span>
                                    <span class="material-symbols-outlined text-[13px] hidden group-hover/follow:inline-block">person_remove</span>
                                    <span class="hidden group-hover/follow:inline text-[11px]">Unfollow</span>
                                `;
                            }
                        } else {
                            if (b.id.includes('mobile')) {
                                b.className = "text-[11px] font-bold py-1 px-3 rounded-full transition-all cursor-pointer border bg-white dark:bg-slate-900 border-blue-600 text-blue-600 hover:bg-blue-50";
                                b.innerText = 'Follow';
                            } else {
                                b.className = "flex-1 text-[11px] font-semibold py-1 px-2.5 rounded-full transition-all cursor-pointer border flex items-center justify-center gap-1 shadow-sm bg-white dark:bg-slate-900 border-blue-600 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-955/30";
                                b.innerHTML = `
                                    <span class="material-symbols-outlined text-[13px]">person_add</span>
                                    <span>Follow</span>
                                `;
                            }
                        }
                    });
                }
            })
            .catch(error => {
                btn.disabled = false;
                console.error('Follow Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Action Failed',
                    text: 'Could not toggle follow status. Please try again.',
                    confirmButtonColor: '#0f172a'
            });
        }
    </script>
@endauth

<script>
    function viewUserStatus(userId, userName, avatarUrl, titleBadge, statusText, statusImage) {
        const currentUserId = "{{ Auth::id() }}";
        const isOwner = currentUserId === userId;

        // Log status view if viewer is authenticated and not owner
        if (currentUserId && !isOwner) {
            fetch(`/profile/${userId}/view-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .catch(err => console.error("Error logging status view:", err));
        }

        // Build viewer information HTML
        let viewersSection = '';
        if (isOwner) {
            viewersSection = `
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 text-left">
                    <span class="block text-[10px] font-black text-slate-400 dark:text-slate-505 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[13px]">visibility</span>
                        Seen by
                    </span>
                    <div id="status-viewers-list" class="flex flex-col gap-2 max-h-[120px] overflow-y-auto pr-1">
                        <div class="text-[11px] text-slate-400 font-semibold italic text-center py-2">Loading viewers...</div>
                    </div>
                </div>
            `;

            // Load viewers list asynchronously
            setTimeout(() => {
                fetch(`/profile/${userId}/status-viewers`)
                .then(res => res.json())
                .then(data => {
                    const container = document.getElementById('status-viewers-list');
                    if (!container) return;
                    if (data.success && data.viewers.length > 0) {
                        let html = '';
                        data.viewers.forEach(viewer => {
                            html += `
                                <div class="flex items-center justify-between py-1 border-b border-slate-50 last:border-0 dark:border-slate-800/40">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full overflow-hidden border border-slate-200 dark:border-slate-700">
                                            <img src="${viewer.avatar_url}" class="w-full h-full object-cover">
                                        </div>
                                        <a href="/profile/${encodeURIComponent(viewer.name)}" class="text-xs font-bold text-slate-800 dark:text-slate-200 hover:text-blue-500 hover:underline truncate max-w-[120px]">${viewer.name}</a>
                                    </div>
                                    <span class="text-[9px] px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-550 font-bold uppercase tracking-wider">${viewer.title_badge}</span>
                                </div>
                            `;
                        });
                        container.innerHTML = html;
                    } else {
                        container.innerHTML = `<div class="text-[11px] text-slate-400 font-semibold italic text-center py-2">No views yet</div>`;
                    }
                })
                .catch(err => {
                    console.error("Error fetching status viewers:", err);
                    const container = document.getElementById('status-viewers-list');
                    if (container) container.innerHTML = `<div class="text-[11px] text-rose-500 font-semibold italic text-center py-2">Failed to load viewers</div>`;
                });
            }, 100);
        }

        Swal.fire({
            html: `
                <div class="instagram-story-card relative overflow-hidden rounded-2xl bg-gradient-to-b from-slate-900 via-slate-955 to-black text-white p-5 shadow-2xl border border-slate-800 flex flex-col justify-between min-h-[460px] font-sans">
                    <!-- Progress Bar Header -->
                    <div class="flex gap-1 mb-3">
                        <div class="h-1 flex-1 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full"></div>
                    </div>

                    <!-- User Header -->
                    <div class="flex items-center gap-3 text-left">
                        <div class="w-10 h-10 rounded-full p-[2px] bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-650">
                            <div class="w-full h-full rounded-full overflow-hidden border border-black bg-slate-900">
                                <img src="${avatarUrl}" class="w-full h-full object-cover">
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-black tracking-tight text-white">${userName}</h4>
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">${titleBadge}</span>
                        </div>
                    </div>

                    <!-- Main Story Content -->
                    <div class="flex-grow flex flex-col justify-center items-center py-4 text-center space-y-3">
                        ${statusImage ? `
                            <div class="w-full max-h-[160px] rounded-xl overflow-hidden border border-white/10 shadow-lg bg-black/40">
                                <img src="${statusImage}" class="w-full h-full object-contain cursor-zoom-in" onclick="window.open('${statusImage}', '_blank')">
                            </div>
                        ` : ''}

                        ${statusText ? `
                            <p class="text-sm font-extrabold text-white leading-relaxed max-w-[280px] drop-shadow-md bg-white/5 backdrop-blur-sm py-2 px-3 rounded-xl border border-white/5">
                                "${statusText}"
                            </p>
                        ` : ''}
                    </div>

                    <!-- Interaction Actions Bar -->
                    <div class="flex items-center justify-between border-t border-white/5 pt-3 pb-2 px-1">
                        <div class="flex items-center gap-4">
                            <!-- Like Button -->
                            <button id="status-like-btn" class="flex items-center gap-1.5 focus:outline-none group transition-transform active:scale-95 cursor-pointer bg-transparent border-0 text-left">
                                <span id="status-like-icon" class="material-symbols-outlined text-[20px] transition-colors duration-250 text-slate-450 hover:text-rose-500">favorite</span>
                                <span id="status-likes-count" class="text-xs font-bold text-slate-400">0</span>
                            </button>

                            <!-- Comment Count Display -->
                            <div class="flex items-center gap-1.5 text-slate-450">
                                <span class="material-symbols-outlined text-[20px]">chat_bubble</span>
                                <span id="status-comments-count" class="text-xs font-bold">0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Comments List Container -->
                    <div class="border-t border-white/5 pt-2 text-left">
                        <span class="block text-[9px] font-black text-slate-450 uppercase tracking-wider mb-1.5">Comments</span>
                        <div id="status-comments-list" class="flex flex-col gap-1.5 max-h-[90px] overflow-y-auto pr-1">
                            <div class="text-[10px] text-slate-500 font-semibold italic text-center py-2">No comments yet</div>
                        </div>
                    </div>

                    <!-- Comment Input Box -->
                    <div class="mt-2.5">
                        @auth
                            <div class="flex items-center gap-2 bg-white/5 border border-white/10 rounded-full py-1 px-3">
                                <input type="text" id="status-comment-input" placeholder="Type a comment..." class="bg-transparent border-0 text-xs text-white focus:outline-none focus:ring-0 flex-1 placeholder-slate-500" minlength="1" maxlength="500">
                                <button id="status-comment-submit" class="text-xs font-bold text-blue-500 hover:text-blue-450 focus:outline-none active:scale-95 transition-transform cursor-pointer bg-transparent border-0">Send</button>
                            </div>
                        @else
                            <div class="text-center py-1.5 text-[10px] text-slate-500 bg-white/5 rounded-full font-bold">
                                <a href="{{ route('login') }}" class="text-blue-450 hover:underline">Login</a> to like or comment
                            </div>
                        @endauth
                    </div>

                    <!-- Footer Details (Viewers) -->
                    ${viewersSection}
                </div>
            `,
            showConfirmButton: false,
            showCloseButton: true,
            background: 'transparent',
            width: '380px',
            customClass: {
                popup: 'bg-transparent border-0 shadow-none p-0 overflow-visible',
                closeButton: 'text-white border-0 bg-transparent hover:text-red-500'
            },
            didOpen: () => {
                const likeIcon = document.getElementById('status-like-icon');
                const likesCountSpan = document.getElementById('status-likes-count');
                const commentsCountSpan = document.getElementById('status-comments-count');
                const commentsList = document.getElementById('status-comments-list');
                const commentInput = document.getElementById('status-comment-input');
                const commentSubmit = document.getElementById('status-comment-submit');
                const likeBtn = document.getElementById('status-like-btn');

                function updateLikesUI(count, hasLiked) {
                    if (likesCountSpan) likesCountSpan.textContent = count;
                    if (likeIcon) {
                        if (hasLiked) {
                            likeIcon.style.fontVariationSettings = "'FILL' 1, 'wght' 600, 'GRAD' 0, 'opsz' 20";
                            likeIcon.classList.remove('text-slate-455');
                            likeIcon.classList.add('text-rose-500');
                        } else {
                            likeIcon.style.fontVariationSettings = "'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 20";
                            likeIcon.classList.remove('text-rose-500');
                            likeIcon.classList.add('text-slate-455');
                        }
                    }
                }

                function updateCommentsUI(comments) {
                    if (commentsCountSpan) commentsCountSpan.textContent = comments.length;
                    if (!commentsList) return;

                    if (comments.length > 0) {
                        let html = '';
                        comments.forEach(c => {
                            html += `
                                <div class="flex items-start gap-2 py-1.5 border-b border-white/5 last:border-0">
                                    <div class="w-6 h-6 rounded-full overflow-hidden shrink-0 border border-white/10">
                                        <img src="${c.avatar_url}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center justify-between">
                                            <span class="text-[9.5px] font-black text-slate-350 truncate">${c.name}</span>
                                            <span class="text-[8px] text-slate-500">${c.time_ago}</span>
                                        </div>
                                        <p class="text-[10px] text-slate-200 break-words mt-0.5 leading-normal font-semibold">${c.comment}</p>
                                    </div>
                                </div>
                            `;
                        });
                        commentsList.innerHTML = html;
                    } else {
                        commentsList.innerHTML = `<div class="text-[10px] text-slate-500 font-semibold italic text-center py-2">No comments yet</div>`;
                    }
                }

                // Fetch initial status interactions
                fetch(`/profile/${userId}/status-interactions`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            updateLikesUI(data.likes_count, data.has_liked);
                            updateCommentsUI(data.comments);
                        }
                    })
                    .catch(err => console.error("Error loading interactions:", err));

                // Like status action
                if (likeBtn) {
                    likeBtn.onclick = () => {
                        fetch(`/profile/${userId}/like-status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                updateLikesUI(data.likes_count, data.liked);
                            }
                        })
                        .catch(err => console.error("Error liking status:", err));
                    };
                }

                // Comment status action
                if (commentSubmit && commentInput) {
                    const submitComment = () => {
                        const text = commentInput.value.trim();
                        if (!text) return;

                        commentSubmit.disabled = true;
                        commentSubmit.textContent = '...';

                        fetch(`/profile/${userId}/comment-status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ comment: text })
                        })
                        .then(res => res.json())
                        .then(data => {
                            commentSubmit.disabled = false;
                            commentSubmit.textContent = 'Send';
                            if (data.success) {
                                commentInput.value = '';
                                updateCommentsUI(data.comments);
                                commentsList.scrollTop = commentsList.scrollHeight;
                            }
                        })
                        .catch(err => {
                            console.error("Error posting comment:", err);
                            commentSubmit.disabled = false;
                            commentSubmit.textContent = 'Send';
                        });
                    };

                    commentSubmit.onclick = submitComment;
                    commentInput.onkeydown = (e) => {
                        if (e.key === 'Enter') {
                            submitComment();
                        }
                    };
                }
            }
        });
    }
</script>
@endsection

