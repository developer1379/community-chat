@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header path info -->
    <div class="mb-8">
        <nav class="flex text-sm text-slate-500 font-medium mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors">Forums</a>
                </li>
                <li>
                    <span class="mx-2 text-slate-300">/</span>
                </li>
                <li aria-current="page">
                    <span class="text-slate-900 font-semibold">Members Directory</span>
                </li>
            </ol>
        </nav>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight flex items-center gap-3">
                    <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600 shadow-sm border border-blue-100">
                        <span class="material-symbols-outlined text-2xl">group</span>
                    </span>
                    Registered Members
                </h1>
                <p class="mt-2 text-sm text-slate-500 max-w-2xl font-medium leading-relaxed">Discover active community specialists, check custom badges, see followers, and follow your favorite developers.</p>
            </div>
        </div>
    </div>

    <!-- Search and Filter Bar Card -->
    <div class="bg-white border border-slate-200 shadow-sm rounded-xl p-4 mb-8 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <!-- Filter Tabs -->
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('members.index', ['filter' => 'all', 'search' => $search]) }}" 
               class="px-4 py-2 text-sm font-medium rounded-lg transition-all {{ $filter === 'all' ? 'bg-slate-900 text-white shadow-sm' : 'bg-transparent text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                All Members
            </a>
            <a href="{{ route('members.index', ['filter' => 'active', 'search' => $search]) }}" 
               class="px-4 py-2 text-sm font-medium rounded-lg transition-all flex items-center gap-1.5 {{ $filter === 'active' ? 'bg-slate-900 text-white shadow-sm' : 'bg-transparent text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <span class="material-symbols-outlined text-[16px] {{ $filter === 'active' ? 'text-orange-400' : 'text-slate-400' }}">local_fire_department</span> Most Active
            </a>
            <a href="{{ route('members.index', ['filter' => 'newest', 'search' => $search]) }}" 
               class="px-4 py-2 text-sm font-medium rounded-lg transition-all flex items-center gap-1.5 {{ $filter === 'newest' ? 'bg-slate-900 text-white shadow-sm' : 'bg-transparent text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <span class="material-symbols-outlined text-[16px] {{ $filter === 'newest' ? 'text-blue-400' : 'text-slate-400' }}">new_releases</span> Newest
            </a>
            @auth
                <a href="{{ route('members.index', ['filter' => 'following', 'search' => $search]) }}" 
                   class="px-4 py-2 text-sm font-medium rounded-lg transition-all flex items-center gap-1.5 {{ $filter === 'following' ? 'bg-slate-900 text-white shadow-sm' : 'bg-transparent text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <span class="material-symbols-outlined text-[16px] {{ $filter === 'following' ? 'text-indigo-400' : 'text-slate-400' }}">group</span> Following ({{ Auth::user()->following()->count() }})
                </a>
                <a href="{{ route('members.index', ['filter' => 'followers', 'search' => $search]) }}" 
                   class="px-4 py-2 text-sm font-medium rounded-lg transition-all flex items-center gap-1.5 {{ $filter === 'followers' ? 'bg-slate-900 text-white shadow-sm' : 'bg-transparent text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <span class="material-symbols-outlined text-[16px] {{ $filter === 'followers' ? 'text-rose-400' : 'text-slate-400' }}">campaign</span> Followers ({{ Auth::user()->followers()->count() }})
                </a>
            @endauth
        </div>

        <!-- Search Input Form -->
        <form action="{{ route('members.index') }}" method="GET" class="relative w-full lg:w-80 shrink-0">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="material-symbols-outlined text-slate-400 text-lg">search</span>
                </div>
                <input type="text" name="search" value="{{ $search }}" class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg leading-5 bg-slate-50 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors" placeholder="Search members...">
            </div>
        </form>
    </div>

    <!-- Members Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($users as $user)
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl overflow-hidden hover:shadow-md transition-shadow duration-300 flex flex-col group relative">
                <!-- Cover Photo Header -->
                <div class="h-24 relative w-full bg-cover bg-center" style="background: {{ $user->banner_path ? 'url(' . $user->banner_path . ')' : $user->banner_color }}">
                    <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors duration-300"></div>
                </div>

                <!-- Member details container -->
                <div class="px-5 pb-5 relative flex flex-col items-center text-center grow">
                    <!-- Avatar -->
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
                       class="w-20 h-20 rounded-full overflow-hidden border-4 border-white bg-slate-100 shadow-sm -mt-10 mb-3 relative block shrink-0 transition-transform group-hover:scale-105 duration-300">
                        @if($user->avatar_path)
                            <img src="{{ str_starts_with($user->avatar_path, 'http') ? $user->avatar_path : asset('storage/' . $user->avatar_path) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-xl font-bold text-slate-400">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                        @endif
                    </a>

                    <!-- Name and Badge -->
                    <h3 class="font-bold text-slate-900 text-lg hover:text-blue-600 transition-colors truncate w-full mb-1.5">
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
                           data-user-banner-path="{{ $user->banner_path }}">{{ $user->name }}</a>
                    </h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold text-white shadow-sm uppercase tracking-wider" style="background: {{ $user->banner_color }}">
                        {{ $user->title_badge }}
                    </span>
                    <p class="text-xs text-slate-500 mt-3 font-medium">Joined {{ $user->created_at->format('M Y') }}</p>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-3 border-t border-slate-100 bg-slate-50 p-4 text-center divide-x divide-slate-200">
                    <div class="flex flex-col">
                        <span class="text-sm font-bold text-slate-900">{{ $user->threads()->count() }}</span>
                        <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider mt-0.5">Threads</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm font-bold text-slate-900">{{ $user->posts()->count() }}</span>
                        <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider mt-0.5">Replies</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm font-bold text-slate-900" id="follower-count-{{ $user->id }}">{{ $user->followers()->count() }}</span>
                        <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider mt-0.5">Followers</span>
                    </div>
                </div>

                <!-- Action Footer -->
                <div class="p-4 bg-white border-t border-slate-100">
                    @auth
                        @if(Auth::id() === $user->id)
                            <div class="w-full py-2 text-center text-[11px] font-bold text-slate-400 uppercase tracking-wider bg-slate-50 rounded-lg border border-slate-100">
                                This is You 👑
                            </div>
                        @else
                            <div class="flex items-center gap-2">
                                <button type="button" 
                                        onclick="toggleFollowUser('{{ $user->name }}', '{{ $user->id }}')" 
                                        id="follow-btn-{{ $user->id }}" 
                                        class="flex-1 text-xs font-semibold py-2 px-3 rounded-lg transition-all cursor-pointer border flex items-center justify-center gap-1.5 shadow-sm 
                                        {{ Auth::user()->isFollowing($user) 
                                            ? 'bg-slate-50 border-slate-200 text-slate-700 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 group/follow' 
                                            : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                                    @if(Auth::user()->isFollowing($user))
                                        <span class="material-symbols-outlined text-[14px] group-hover/follow:hidden">check</span>
                                        <span class="group-hover/follow:hidden font-semibold">Following</span>
                                        <span class="material-symbols-outlined text-[14px] hidden group-hover/follow:inline-block">person_remove</span>
                                        <span class="hidden group-hover/follow:inline font-semibold">Unfollow</span>
                                    @else
                                        <span class="material-symbols-outlined text-[14px]">person_add</span>
                                        <span class="font-semibold">Follow</span>
                                    @endif
                                </button>
                                <button type="button" 
                                        onclick="startDirectChat('{{ $user->name }}')" 
                                        class="flex-1 text-xs font-semibold py-2 px-3 rounded-lg transition-all cursor-pointer bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center gap-1.5 shadow-sm">
                                    <span class="material-symbols-outlined text-[14px]">chat</span>
                                    <span class="font-semibold">Message</span>
                                </button>
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="w-full block text-center bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 font-semibold text-xs py-2 px-3 rounded-lg transition-colors">
                            Login to Interact
                        </a>
                    @endauth
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center mui-card border border-slate-200 bg-white p-8">
                <span class="material-symbols-outlined text-4xl text-slate-300 mb-2">person_search</span>
                <h3 class="font-bold text-slate-800 text-base mb-1">No Members Found</h3>
                <p class="text-xs text-slate-450 max-w-sm mx-auto font-semibold">We couldn't find any registered members matching your search or filters. Try adjusting your search term!</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination links -->
    <div class="mt-6">
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
                        btn.className = "flex-1 text-xs font-semibold py-2 px-3 rounded-lg transition-all cursor-pointer border flex items-center justify-center gap-1.5 shadow-sm bg-slate-50 border-slate-200 text-slate-700 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 group/follow";
                        btn.innerHTML = `
                            <span class="material-symbols-outlined text-[14px] group-hover/follow:hidden">check</span>
                            <span class="group-hover/follow:hidden font-semibold">Following</span>
                            <span class="material-symbols-outlined text-[14px] hidden group-hover/follow:inline-block">person_remove</span>
                            <span class="hidden group-hover/follow:inline font-semibold">Unfollow</span>
                        `;
                    } else {
                        btn.className = "flex-1 text-xs font-semibold py-2 px-3 rounded-lg transition-all cursor-pointer border flex items-center justify-center gap-1.5 shadow-sm bg-white border-slate-200 text-slate-700 hover:bg-slate-50";
                        btn.innerHTML = `
                            <span class="material-symbols-outlined text-[14px]">person_add</span>
                            <span class="font-semibold">Follow</span>
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
