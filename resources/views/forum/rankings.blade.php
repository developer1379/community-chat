@extends('layouts.app')

@section('title')
Member Rankings & Leaderboard | XenForo Professional
@endsection
@section('meta_description')
Explore the community leaderboard. Level up by creating threads, posting replies, earning coins, and unlocking reputation milestones.
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- LEFT SIDEBAR -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Rankings Menu -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm rounded-none">
                <div class="p-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-955/50">
                    <h3 class="font-bold text-slate-800 dark:text-slate-200 text-sm tracking-tight flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg text-blue-500">leaderboard</span>
                        Rankings
                    </h3>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-850">
                    <a href="{{ route('rankings.index', ['tab' => 'all']) }}" class="flex items-center justify-between px-4 py-3 text-xs font-bold transition-all {{ $currentTab === 'all' ? 'bg-blue-50/50 dark:bg-blue-955/20 text-blue-650 dark:text-blue-400 border-l-4 border-blue-655' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-850' }}">
                        <span>All Community</span>
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </a>
                    <a href="{{ route('rankings.index', ['tab' => 'creatives']) }}" class="flex items-center justify-between px-4 py-3 text-xs font-bold transition-all {{ $currentTab === 'creatives' ? 'bg-blue-50/50 dark:bg-blue-955/20 text-blue-650 dark:text-blue-400 border-l-4 border-blue-655' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-850' }}">
                        <span>Creatives & Artists</span>
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </a>
                    <a href="{{ route('rankings.index', ['tab' => 'critics']) }}" class="flex items-center justify-between px-4 py-3 text-xs font-bold transition-all {{ $currentTab === 'critics' ? 'bg-blue-50/50 dark:bg-blue-955/20 text-blue-650 dark:text-blue-400 border-l-4 border-blue-655' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-850' }}">
                        <span>Critics & Analysts</span>
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </a>
                    <a href="{{ route('rankings.index', ['tab' => 'guild']) }}" class="flex items-center justify-between px-4 py-3 text-xs font-bold transition-all {{ $currentTab === 'guild' ? 'bg-blue-50/50 dark:bg-blue-955/20 text-blue-650 dark:text-blue-400 border-l-4 border-blue-655' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-850' }}">
                        <span>Guild & Admins</span>
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </a>
                </div>
            </div>

            <!-- Points Formula Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm rounded-none p-4">
                <h3 class="font-bold text-slate-800 dark:text-slate-200 text-xs uppercase tracking-wider mb-3">Points System</h3>
                <div class="space-y-2.5">
                    <div class="flex items-center justify-between text-xs border-b border-slate-100 dark:border-slate-850 pb-1.5">
                        <span class="text-slate-550 dark:text-slate-400">Thread Created</span>
                        <span class="font-bold text-emerald-600 dark:text-emerald-400">+10 pts</span>
                    </div>
                    <div class="flex items-center justify-between text-xs border-b border-slate-100 dark:border-slate-850 pb-1.5">
                        <span class="text-slate-550 dark:text-slate-400">Post Reply</span>
                        <span class="font-bold text-emerald-600 dark:text-emerald-400">+5 pts</span>
                    </div>
                    <div class="flex items-center justify-between text-xs pb-0.5">
                        <span class="text-slate-550 dark:text-slate-400">Reaction Received</span>
                        <span class="font-bold text-emerald-600 dark:text-emerald-400">+2 pts</span>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800 text-[10px] text-slate-400 leading-normal">
                    Points are updated automatically. Level milestones scale progressively as you gain points.
                </div>
            </div>

            <!-- Personal Rank Spotlight Card -->
            @auth
                @php
                    $currentUserId = Auth::id();
                    $currentUserRank = null;
                    // Sort collection to get numeric keys for search
                    $indexedUsers = $users->values();
                    $searchedIndex = $indexedUsers->search(fn($u) => $u->id === $currentUserId);
                    if ($searchedIndex !== false) {
                        $currentUserRank = $searchedIndex + 1;
                    }
                    $currentUserObj = Auth::user();
                    $currTier = $currentUserObj->computed_anime_tier;
                @endphp
                @if($currentUserRank !== null)
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm rounded-none p-4">
                        <h3 class="font-bold text-slate-800 dark:text-slate-200 text-xs uppercase tracking-wider mb-3">Your Position</h3>
                        <div class="flex items-center gap-3">
                            <img src="{{ $currentUserObj->avatar_url }}" class="w-10 h-10 rounded-full object-cover border border-slate-200 dark:border-slate-800 shrink-0" data-user-hover="true" data-user-name="{{ $currentUserObj->name }}">
                            <div class="min-w-0">
                                <h4 class="font-bold text-xs text-slate-900 dark:text-white truncate {{ $currentUserObj->username_style }}" style="{{ $currentUserObj->username_style_css }}" data-user-hover="true" data-user-name="{{ $currentUserObj->name }}">
                                    {{ $currentUserObj->name }}
                                </h4>
                                <div class="text-[10px] text-slate-450 dark:text-slate-500 mt-0.5 font-semibold">
                                    Ranked <strong class="text-blue-650 dark:text-blue-400">#{{ $currentUserRank }}</strong> overall
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 text-center">
                            <div>
                                <span class="block text-[8px] uppercase tracking-wider text-slate-400">Score</span>
                                <span class="text-xs font-black text-slate-800 dark:text-white">{{ number_format($currentUserObj->activity_points) }}</span>
                            </div>
                            <div>
                                <span class="block text-[8px] uppercase tracking-wider text-slate-400">Level</span>
                                <span class="text-xs font-black" style="color: {{ $currTier['color'] }}">{{ $currTier['level'] }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            @endauth
        </div>

        <!-- MAIN AREA -->
        <div class="lg:col-span-3 space-y-6">
            <!-- Header Block -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-none shadow-sm">
                <span class="inline-block text-[10px] font-black uppercase text-blue-600 dark:text-blue-400 tracking-widest mb-1.5">Leaderboard</span>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white leading-tight">
                    @switch($currentTab)
                        @case('all') Community Rankings @break
                        @case('creatives') Creatives & Artists @break
                        @case('critics') Critics & Analysts @break
                        @case('guild') Guild & Admins @break
                    @endswitch
                </h1>
                <p class="text-xs text-slate-550 dark:text-slate-450 mt-1.5 leading-relaxed">
                    @switch($currentTab)
                        @case('all') Browse the overall community leaderboard based on posts, thread creations, and user activity metrics. @break
                        @case('creatives') Top creators, designers, animators, mangakas, and artists active in the community. @break
                        @case('critics') Prominent columnists, writers, historians, reviewers, and analysts of the forum. @break
                        @case('guild') Administrative board members, moderators, shinigamis, and founders of the community. @break
                    @endswitch
                </p>
            </div>

            @php
                // Extract top 3 users for the podium
                $top3 = $users->take(3)->values();
            @endphp

            @if(count($top3) > 0)
                <!-- Podium display for Top 3 -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-none shadow-sm">
                    <div class="flex flex-col sm:flex-row items-end justify-center gap-6 sm:gap-2 pt-4 pb-2">
                        
                        <!-- 2ND PLACE (SILVER) -->
                        @if(isset($top3[1]))
                            @php
                                $u2 = $top3[1];
                                $tier2 = $u2->computed_anime_tier;
                            @endphp
                            <div class="flex flex-col items-center w-full sm:w-44 order-2 sm:order-1">
                                <div class="relative group">
                                    <div class="absolute inset-0 rounded-full bg-slate-400 opacity-20 blur-md"></div>
                                    <a href="{{ route('profile.show', $u2->name) }}" class="block shrink-0 relative z-10" data-user-hover="true" data-user-name="{{ $u2->name }}">
                                        <img src="{{ $u2->avatar_url }}" class="w-14 h-14 rounded-full object-cover border-2 border-slate-300 shadow-md">
                                    </a>
                                </div>
                                <h3 class="font-extrabold text-xs text-slate-850 dark:text-slate-200 mt-2.5 truncate max-w-[130px] leading-tight text-center">
                                    <a href="{{ route('profile.show', $u2->name) }}" class="{{ $u2->username_style }}" style="{{ $u2->username_style_css }}" data-user-hover="true" data-user-name="{{ $u2->name }}">{{ $u2->name }}</a>
                                </h3>
                                <span class="text-[9px] font-bold text-slate-400 dark:text-slate-500 mt-0.5 text-center">{{ $u2->title_badge ?: 'Community Member' }}</span>
                                
                                <!-- Silver Podium Base -->
                                <div class="w-full bg-slate-50 dark:bg-slate-950/30 border border-slate-200 dark:border-slate-800 h-16 mt-3 flex flex-col items-center justify-center gap-1 shadow-sm">
                                    <span class="text-sm font-black text-slate-500 dark:text-slate-400">#2</span>
                                    <span class="text-[9px] font-bold text-slate-450">{{ number_format($u2->activity_points) }} pts</span>
                                </div>
                            </div>
                        @endif

                        <!-- 1ST PLACE (GOLD) -->
                        @if(isset($top3[0]))
                            @php
                                $u1 = $top3[0];
                                $tier1 = $u1->computed_anime_tier;
                            @endphp
                            <div class="flex flex-col items-center w-full sm:w-48 order-1 sm:order-2 z-10 -mt-2">
                                <div class="relative group">
                                    <div class="absolute inset-0 rounded-full bg-yellow-450/20 opacity-30 blur-lg"></div>
                                    <!-- Crown Icon above avatar -->
                                    <span class="material-symbols-outlined text-yellow-500 text-lg absolute -top-4 left-1/2 -translate-x-1/2 drop-shadow-md z-20">workspace_premium</span>
                                    <a href="{{ route('profile.show', $u1->name) }}" class="block shrink-0 relative z-10" data-user-hover="true" data-user-name="{{ $u1->name }}">
                                        <img src="{{ $u1->avatar_url }}" class="w-18 h-18 rounded-full object-cover border-2 border-yellow-400 shadow-xl">
                                    </a>
                                </div>
                                <h3 class="font-extrabold text-sm text-slate-900 dark:text-white mt-2.5 truncate max-w-[140px] leading-tight text-center">
                                    <a href="{{ route('profile.show', $u1->name) }}" class="{{ $u1->username_style }}" style="{{ $u1->username_style_css }}" data-user-hover="true" data-user-name="{{ $u1->name }}">{{ $u1->name }}</a>
                                </h3>
                                <span class="text-[9px] font-bold text-yellow-600 dark:text-yellow-450 mt-0.5 text-center">{{ $u1->title_badge ?: 'Community Member' }}</span>
                                
                                <!-- Gold Podium Base -->
                                <div class="w-full bg-amber-50/10 dark:bg-amber-950/10 border border-amber-200 dark:border-amber-900/30 h-22 mt-3 flex flex-col items-center justify-center gap-1 shadow-sm">
                                    <span class="text-base font-black text-yellow-600 dark:text-yellow-500">#1</span>
                                    <span class="text-[9px] font-black text-yellow-600 dark:text-yellow-500">{{ number_format($u1->activity_points) }} pts</span>
                                </div>
                            </div>
                        @endif

                        <!-- 3RD PLACE (BRONZE) -->
                        @if(isset($top3[2]))
                            @php
                                $u3 = $top3[2];
                                $tier3 = $u3->computed_anime_tier;
                            @endphp
                            <div class="flex flex-col items-center w-full sm:w-44 order-3">
                                <div class="relative group">
                                    <div class="absolute inset-0 rounded-full bg-amber-700/20 opacity-20 blur-md"></div>
                                    <a href="{{ route('profile.show', $u3->name) }}" class="block shrink-0 relative z-10" data-user-hover="true" data-user-name="{{ $u3->name }}">
                                        <img src="{{ $u3->avatar_url }}" class="w-14 h-14 rounded-full object-cover border-2 border-amber-600 shadow-md">
                                    </a>
                                </div>
                                <h3 class="font-extrabold text-xs text-slate-850 dark:text-slate-200 mt-2.5 truncate max-w-[130px] leading-tight text-center">
                                    <a href="{{ route('profile.show', $u3->name) }}" class="{{ $u3->username_style }}" style="{{ $u3->username_style_css }}" data-user-hover="true" data-user-name="{{ $u3->name }}">{{ $u3->name }}</a>
                                </h3>
                                <span class="text-[9px] font-bold text-slate-400 dark:text-slate-500 mt-0.5 text-center">{{ $u3->title_badge ?: 'Community Member' }}</span>
                                
                                <!-- Bronze Podium Base -->
                                <div class="w-full bg-slate-50 dark:bg-slate-955/30 border border-slate-200 dark:border-slate-800 h-12 mt-3 flex flex-col items-center justify-center gap-1 shadow-sm">
                                    <span class="text-xs font-black text-amber-700 dark:text-amber-500">#3</span>
                                    <span class="text-[9px] font-bold text-slate-450">{{ number_format($u3->activity_points) }} pts</span>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            @endif

            <!-- Detailed Rankings Table -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm rounded-none">
                <div class="p-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-955/50 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 dark:text-slate-200 text-xs uppercase tracking-wider">Rankings Board</h3>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-850">
                    @forelse($users as $user)
                        @php
                            $userTier = $user->computed_anime_tier;
                            $level = $userTier['level'] ?? 1;
                            $hasStatus = !empty($user->status) || !empty($user->status_image);
                            $isFollowing = Auth::check() ? Auth::user()->isFollowing($user) : false;
                            $rankIndex = $loop->iteration;
                        @endphp
                        <div class="p-4 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <!-- Rank Medal or Index -->
                                <div class="w-7 shrink-0 flex justify-center">
                                    @if($rankIndex === 1)
                                        <span class="material-symbols-outlined text-yellow-500 text-xl" title="1st Place">workspace_premium</span>
                                    @elseif($rankIndex === 2)
                                        <span class="material-symbols-outlined text-slate-450 text-xl" title="2nd Place">workspace_premium</span>
                                    @elseif($rankIndex === 3)
                                        <span class="material-symbols-outlined text-amber-700 text-xl" title="3rd Place">workspace_premium</span>
                                    @else
                                        <span class="text-xs font-black text-slate-400 dark:text-slate-500">#{{ $rankIndex }}</span>
                                    @endif
                                </div>

                                <!-- Avatar -->
                                <div class="relative w-10 h-10 shrink-0">
                                    <img src="{{ $user->avatar_url }}" class="w-full h-full object-cover rounded-full border border-slate-200 dark:border-slate-800" alt="avatar" data-user-hover="true" data-user-name="{{ $user->name }}">
                                </div>

                                <!-- Member Details -->
                                <div class="min-w-0">
                                    <h4 class="font-bold text-xs text-slate-900 dark:text-white truncate {{ $user->username_style }}" style="{{ $user->username_style_css }}">
                                        <a href="{{ route('profile.show', $user->name) }}" data-user-hover="true" data-user-name="{{ $user->name }}">{{ $user->name }}</a>
                                    </h4>
                                    <div class="text-[10px] text-slate-550 dark:text-slate-455 mt-0.5 flex items-center gap-1.5 flex-wrap">
                                        <span>{{ $user->title_badge ?: 'Community Member' }}</span>
                                        <span class="text-slate-200 dark:text-slate-850">•</span>
                                        <span class="px-1.5 py-0.2 text-[8px] font-black uppercase text-white shadow-sm leading-none" style="background-color: {{ $userTier['color'] }}">
                                            Lvl {{ $level }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2 mt-1 flex-wrap text-[9px] text-slate-400 dark:text-slate-500 font-semibold font-sans">
                                        <span>Posts: <strong class="text-slate-600 dark:text-slate-350">{{ number_format($user->posts()->count()) }}</strong></span>
                                        <span>•</span>
                                        <span>Threads: <strong class="text-slate-600 dark:text-slate-350">{{ number_format($user->threads()->count()) }}</strong></span>
                                        <span>•</span>
                                        <span>Coins: <strong class="text-emerald-600 dark:text-emerald-450">{{ number_format($user->coins) }}</strong></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Metric / Follow Action -->
                            <div class="flex items-center gap-3 shrink-0">
                                <div class="text-right">
                                    <span class="text-xs font-black text-slate-900 dark:text-white block">{{ number_format($user->activity_points) }}</span>
                                    <span class="block text-[8px] uppercase tracking-wider text-slate-400 font-bold">score</span>
                                </div>

                                @auth
                                    @if(Auth::id() !== $user->id)
                                        <button type="button" 
                                                onclick="toggleFollowUser('{{ $user->name }}', '{{ $user->id }}')" 
                                                id="follow-btn-{{ $user->id }}" 
                                                class="text-[10px] font-bold py-1 px-3 border transition-all cursor-pointer min-w-[76px] text-center
                                                {{ $isFollowing 
                                                    ? 'bg-slate-100 dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300' 
                                                    : 'bg-white dark:bg-slate-900 border-blue-600 text-blue-600 hover:bg-blue-50' }}">
                                            {{ $isFollowing ? 'Following' : 'Follow' }}
                                        </button>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    @empty
                        <div class="py-16 text-center">
                            <span class="material-symbols-outlined text-4xl text-slate-350 mb-2">person_search</span>
                            <h3 class="font-bold text-slate-800 dark:text-slate-200 text-base mb-1">No Members Found</h3>
                            <p class="text-xs text-slate-450 dark:text-slate-500 max-w-sm mx-auto">We couldn't find any registered members matching this filter.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@auth
    <!-- Follow System Asynchronous API Controller -->
    <script>
        function toggleFollowUser(username, userId) {
            const btn = document.getElementById(`follow-btn-${userId}`);
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
                    if (data.following) {
                        btn.className = "text-[10px] font-bold py-1 px-3 border transition-all cursor-pointer min-w-[76px] text-center bg-slate-100 dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300";
                        btn.innerText = 'Following';
                    } else {
                        btn.className = "text-[10px] font-bold py-1 px-3 border transition-all cursor-pointer min-w-[76px] text-center bg-white dark:bg-slate-900 border-blue-600 text-blue-600 hover:bg-blue-50";
                        btn.innerText = 'Follow';
                    }
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
            });
        }
    </script>
@endauth

<script>
    // Force run hover card listeners on page load
    document.addEventListener('DOMContentLoaded', () => {
        if (window.setupHoverCardListeners) {
            window.setupHoverCardListeners();
        }
    });
</script>
@endsection
