@extends('layouts.app')

@section('content')
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
                $avatarGlow = 'border-4 border-white dark:border-slate-900';
                if ($level >= 20) {
                    $glowClass = 'border-rose-500/30 dark:border-rose-500/20 shadow-[0_0_15px_rgba(225,29,72,0.15)] ring-1 ring-rose-500/5';
                    $avatarGlow = 'border-4 border-rose-500 shadow-[0_0_8px_rgba(225,29,72,0.3)] ring-1 ring-rose-500/20';
                } elseif ($level >= 16) {
                    $glowClass = 'border-purple-500/30 dark:border-purple-500/20 shadow-[0_0_12px_rgba(124,58,237,0.15)]';
                    $avatarGlow = 'border-4 border-purple-500 shadow-[0_0_6px_rgba(124,58,237,0.25)]';
                }

                $hasStatus = !empty($user->status) || !empty($user->status_image);
            @endphp
            
            <!-- Mobile Row List Item (visible on block, hidden on sm) -->
            <div class="flex sm:hidden items-center justify-between p-4 bg-white dark:bg-slate-900 relative">
                <div class="flex items-center gap-3 min-w-0">
                    <!-- Avatar with Instagram status ring -->
                    <div class="relative w-11 h-11 shrink-0">
                        <div class="w-full h-full rounded-xl @if($hasStatus) p-[2px] bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-600 animate-[spin_8s_linear_infinite] @endif overflow-hidden bg-slate-100 dark:bg-slate-800 shadow-sm relative">
                            <div class="w-full h-full rounded-xl overflow-hidden bg-slate-50 dark:bg-slate-800 relative @if($hasStatus) p-[0.5px] bg-white dark:bg-slate-900 @endif">
                                <img src="{{ $user->avatar_url }}" class="w-full h-full object-cover rounded-lg" alt="avatar">
                            </div>
                        </div>
                        @if($hasStatus)
                            <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-650 rounded-full border border-white dark:border-slate-900 flex items-center justify-center text-[7.5px] shadow-sm select-none pointer-events-none">💬</span>
                        @endif
                    </div>
                    
                    <!-- Member Info -->
                    <div class="min-w-0 leading-tight">
                        <h3 class="font-extrabold text-slate-900 dark:text-white text-sm hover:text-blue-600 dark:hover:text-blue-450 transition-colors truncate {{ $user->username_style }}" style="{{ $user->username_style_css }}">
                            <a href="{{ route('profile.show', $user->name) }}" data-user-hover="true" data-user-name="{{ $user->name }}">{{ $user->name }}</a>
                        </h3>
                        <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                            <span class="text-[8px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ $user->title_badge }}</span>
                            <span class="text-[8px] text-slate-400 dark:text-slate-500 font-extrabold">Level {{ $level }}</span>
                            <span class="text-[8.5px] font-bold text-slate-400 dark:text-slate-500">{{ $user->followers()->count() }} followers</span>
                        </div>
                    </div>
                </div>

                <!-- Follow / Action Controls for Mobile List -->
                <div class="flex items-center gap-1.5 shrink-0">
                    @auth
                        @if(Auth::id() !== $user->id)
                            <button type="button" 
                                    onclick="toggleFollowUser('{{ $user->name }}', '{{ $user->id }}')" 
                                    id="follow-btn-mobile-{{ $user->id }}" 
                                    class="text-[10px] font-bold py-1 px-2.5 rounded bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-100 transition-all cursor-pointer">
                                {{ Auth::user()->isFollowing($user) ? 'Following' : 'Follow' }}
                            </button>
                            <button type="button" 
                                    onclick="startDirectChat('{{ $user->name }}')" 
                                    class="text-[10px] font-bold py-1 px-2.5 rounded bg-blue-600 hover:bg-blue-700 text-white shadow-sm transition-all cursor-pointer">
                                DM
                            </button>
                        @else
                            <span class="text-[9px] font-black uppercase text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-slate-950 px-2 py-0.5 rounded">You</span>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="text-[10px] font-bold py-1 px-2.5 rounded bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 transition-colors">
                            Login
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Tablet/Desktop Card View (hidden on mobile, visible on sm+) -->
            <div class="hidden sm:flex bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-850 rounded-2xl overflow-hidden hover:shadow-md transition-shadow duration-300 flex-col group relative {{ $glowClass }}">
                <!-- Cover Photo Header -->
                <div class="h-20 sm:h-24 relative w-full bg-cover bg-center" style="background: {{ $user->banner_path ? 'url(' . $user->banner_path . ')' : $user->banner_color }}">
                    <div class="absolute inset-0 bg-black/10 dark:bg-black/20 group-hover:bg-transparent transition-colors duration-300"></div>
                </div>

                <!-- Member details container -->
                <div class="px-5 pb-4 relative flex flex-col items-center text-center grow">
                    <!-- Avatar -->
                    <div class="relative w-18 h-18 sm:w-20 sm:h-20 -mt-9 sm:-mt-10 mb-3 block shrink-0 z-15">
                        <div class="w-full h-full rounded-2xl @if($hasStatus) p-[2.5px] bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-600 animate-[spin_8s_linear_infinite] @else {{ $avatarGlow }} @endif overflow-hidden bg-slate-100 dark:bg-slate-800 shadow-sm relative transition-transform group-hover:scale-103 duration-300">
                            <div class="w-full h-full rounded-2xl overflow-hidden bg-slate-50 dark:bg-slate-800 relative @if($hasStatus) p-[1px] bg-white dark:bg-slate-900 @endif">
                                <img src="{{ $user->avatar_url }}" class="w-full h-full object-cover rounded-xl" alt="avatar">
                            </div>
                        </div>
                        @if($hasStatus)
                            <span class="absolute bottom-0.5 right-0.5 w-4.5 h-4.5 bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-650 rounded-full border border-white dark:border-slate-900 flex items-center justify-center text-[9px] shadow-sm select-none pointer-events-none">💬</span>
                        @endif
                    </div>

                    <!-- Name and Badge -->
                    <h3 class="font-black text-slate-900 dark:text-white text-base hover:text-blue-600 dark:hover:text-blue-400 transition-colors truncate w-full mb-1 {{ $user->username_style }}" style="{{ $user->username_style_css }}">
                        <a href="{{ route('profile.show', $user->name) }}"
                           data-user-hover="true" 
                           data-user-name="{{ $user->name }}">{{ $user->name }}</a>
                    </h3>
                    
                    <div class="flex items-center gap-1 mt-0.5">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-black text-white shadow-sm uppercase tracking-wider leading-none" style="background: {{ $user->banner_color }}">
                            {{ $user->title_badge }}
                        </span>
                        <span class="text-[8px] px-1 py-0.2 rounded bg-slate-100 dark:bg-slate-800 text-slate-500 font-extrabold uppercase tracking-wider" style="color: {{ $userTier['color'] }}">
                            {{ $userTier['badge'] }}
                        </span>
                    </div>

                    <p class="text-[10px] text-slate-450 dark:text-slate-500 mt-2.5 font-bold">Joined {{ $user->created_at->format('M Y') }}</p>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-3 border-t border-slate-100 dark:border-slate-850 bg-slate-50/50 dark:bg-slate-950/30 p-3 text-center divide-x divide-slate-200/50 dark:divide-slate-800">
                    <div class="flex flex-col">
                        <span class="text-xs font-black text-slate-800 dark:text-slate-205">{{ $user->threads()->count() }}</span>
                        <span class="text-[8.5px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-0.5">Threads</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs font-black text-slate-800 dark:text-slate-205">{{ $user->posts()->count() }}</span>
                        <span class="text-[8.5px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-0.5">Replies</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs font-black text-slate-800 dark:text-slate-205" id="follower-count-{{ $user->id }}">{{ $user->followers()->count() }}</span>
                        <span class="text-[8.5px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-0.5">Followers</span>
                    </div>
                </div>

                <!-- Action Footer -->
                <div class="p-3 bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-850 mt-auto">
                    @auth
                        @if(Auth::id() === $user->id)
                            <div class="w-full py-1.5 text-center text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider bg-slate-55/40 dark:bg-slate-950/40 rounded-lg border border-slate-100 dark:border-slate-850">
                                This is You 👑
                            </div>
                        @else
                            <div class="flex items-center gap-2">
                                <button type="button" 
                                        onclick="toggleFollowUser('{{ $user->name }}', '{{ $user->id }}')" 
                                        id="follow-btn-{{ $user->id }}" 
                                        class="flex-1 text-[11px] font-bold py-1.5 px-2.5 rounded-lg transition-all cursor-pointer border flex items-center justify-center gap-1 shadow-sm 
                                        {{ Auth::user()->isFollowing($user) 
                                            ? 'bg-slate-50 dark:bg-slate-955/30 border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-350 hover:bg-rose-50 dark:hover:bg-rose-955/20 hover:text-rose-600 hover:border-rose-200 dark:hover:border-rose-900 group/follow' 
                                            : 'bg-white dark:bg-slate-850 border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                                    @if(Auth::user()->isFollowing($user))
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
                                        class="flex-1 text-[11px] font-bold py-1.5 px-2.5 rounded-lg transition-all cursor-pointer bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center gap-1 shadow-sm">
                                    <span class="material-symbols-outlined text-[13px]">chat</span>
                                    <span>Message</span>
                                </button>
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="w-full block text-center bg-slate-50 dark:bg-slate-955/45 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs py-1.5 px-3 rounded-lg transition-colors">
                            Login to Interact
                        </a>
                    @endauth
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center mui-card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-8 rounded-none sm:rounded-2xl">
                <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600 mb-2">person_search</span>
                <h3 class="font-bold text-slate-800 dark:text-slate-200 text-base mb-1">No Members Found</h3>
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
            const btn = document.getElementById(`follow-btn-${userId}`);
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

                    // Redesign button style on-the-fly
                    if (data.following) {
                        btn.className = "flex-1 text-[11px] font-bold py-1.5 px-2.5 rounded-lg transition-all cursor-pointer border flex items-center justify-center gap-1 shadow-sm bg-slate-50 dark:bg-slate-950/50 border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-rose-50 dark:hover:bg-rose-955/20 hover:text-rose-600 hover:border-rose-200 dark:hover:border-rose-900 group/follow";
                        btn.innerHTML = `
                            <span class="material-symbols-outlined text-[13px] group-hover/follow:hidden">check</span>
                            <span class="group-hover/follow:hidden">Following</span>
                            <span class="material-symbols-outlined text-[13px] hidden group-hover/follow:inline-block">person_remove</span>
                            <span class="hidden group-hover/follow:inline">Unfollow</span>
                        `;
                    } else {
                        btn.className = "flex-1 text-[11px] font-bold py-1.5 px-2.5 rounded-lg transition-all cursor-pointer border flex items-center justify-center gap-1 shadow-sm bg-white dark:bg-slate-850 border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800";
                        btn.innerHTML = `
                            <span class="material-symbols-outlined text-[13px]">person_add</span>
                            <span>Follow</span>
                        `;
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
@endsection

