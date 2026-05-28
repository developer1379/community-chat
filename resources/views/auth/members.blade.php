@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header path info -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-2">
                <a href="{{ route('home') }}" class="hover:text-blue-600">Forums</a>
                <span>/</span>
                <span class="text-blue-600 font-semibold">Members Directory</span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-950 tracking-tight flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-150/60 shadow-sm">
                    <span class="material-symbols-outlined text-xl">group</span>
                </span>
                Registered Members
            </h1>
            <p class="text-xs text-slate-500 font-medium leading-relaxed mt-1.5">Discover active community specialists, check custom badges, see followers, and follow your favorite developers.</p>
        </div>
    </div>

    <!-- Search and Filter Bar Card -->
    <div class="mui-card p-5 border border-slate-200 bg-white shadow-md rounded-2xl flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Filter Tabs -->
        <div class="flex flex-wrap gap-2 text-xs font-semibold">
            <a href="{{ route('members.index', ['filter' => 'all', 'search' => $search]) }}" 
               class="px-4 py-2 rounded-xl transition-all {{ $filter === 'all' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/10' : 'bg-slate-50 border border-slate-200 text-slate-650 hover:bg-slate-100' }}">
                All Members
            </a>
            <a href="{{ route('members.index', ['filter' => 'active', 'search' => $search]) }}" 
               class="px-4 py-2 rounded-xl transition-all {{ $filter === 'active' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/10' : 'bg-slate-50 border border-slate-200 text-slate-650 hover:bg-slate-100' }}">
                🔥 Most Active
            </a>
            <a href="{{ route('members.index', ['filter' => 'newest', 'search' => $search]) }}" 
               class="px-4 py-2 rounded-xl transition-all {{ $filter === 'newest' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/10' : 'bg-slate-50 border border-slate-200 text-slate-650 hover:bg-slate-100' }}">
                ✨ Newest
            </a>
            @auth
                <a href="{{ route('members.index', ['filter' => 'following', 'search' => $search]) }}" 
                   class="px-4 py-2 rounded-xl transition-all {{ $filter === 'following' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/10' : 'bg-slate-50 border border-slate-200 text-slate-650 hover:bg-slate-100' }}">
                    🤝 Following ({{ Auth::user()->following()->count() }})
                </a>
                <a href="{{ route('members.index', ['filter' => 'followers', 'search' => $search]) }}" 
                   class="px-4 py-2 rounded-xl transition-all {{ $filter === 'followers' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/10' : 'bg-slate-50 border border-slate-200 text-slate-650 hover:bg-slate-100' }}">
                    📣 Followers ({{ Auth::user()->followers()->count() }})
                </a>
            @endauth
        </div>

        <!-- Search Input Form -->
        <form action="{{ route('members.index') }}" method="GET" class="relative w-full md:w-80 flex-shrink-0">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <input type="text" name="search" value="{{ $search }}" class="w-full bg-slate-50 border border-slate-250 rounded-xl pl-9 pr-4 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all placeholder:text-slate-400 font-semibold" placeholder="Search by name or title badge...">
            <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-sm">search</span>
        </form>
    </div>

    <!-- Members Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($users as $user)
            <div class="mui-card overflow-hidden bg-white border border-slate-200 shadow-md rounded-2xl flex flex-col justify-between group">
                <div>
                    <!-- Cover Photo Header (Mini version of profile cover!) -->
                    <div class="h-16 relative bg-cover bg-center" style="background: {{ $user->banner_path ? 'url(' . $user->banner_path . ')' : $user->banner_color }}">
                        <div class="absolute inset-0 bg-black/5"></div>
                    </div>

                    <!-- Member details container -->
                    <div class="p-5 pt-0 relative flex flex-col items-center text-center -mt-8">
                        <!-- Avatar circular frame -->
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
                           class="w-16 h-16 rounded-full overflow-hidden border-2 border-white bg-slate-50 shadow-md mb-2 relative block">
                            @if($user->avatar_path)
                                <img src="{{ str_starts_with($user->avatar_path, 'http') ? $user->avatar_path : asset('storage/' . $user->avatar_path) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-slate-100 flex items-center justify-center text-base font-extrabold text-slate-500">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                            @endif
                        </a>

                        <!-- Name and custom Badge -->
                        <h3 class="font-extrabold text-slate-900 text-sm hover:text-blue-600 transition-colors truncate w-full">
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
                        <span class="text-[7.5px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wider text-white mt-1 border border-slate-300 shadow-sm leading-none" style="background: {{ $user->banner_color }}">
                            {{ $user->title_badge }}
                        </span>
                        <p class="text-[9px] text-slate-400 font-semibold mt-2.5">Joined {{ $user->created_at->format('M Y') }}</p>
                    </div>
                </div>

                <div>
                    <!-- Activity row stats -->
                    <div class="grid grid-cols-3 border-t border-slate-100 bg-slate-50/50 text-center divide-x divide-slate-100 text-[10px] py-2 font-bold text-slate-450">
                        <div>
                            <span class="block font-extrabold text-slate-800 text-xs">{{ $user->threads()->count() }}</span>
                            <span class="text-[8px] text-slate-400 uppercase tracking-widest leading-none">Threads</span>
                        </div>
                        <div>
                            <span class="block font-extrabold text-slate-800 text-xs">{{ $user->posts()->count() }}</span>
                            <span class="text-[8px] text-slate-400 uppercase tracking-widest leading-none">Replies</span>
                        </div>
                        <div>
                            <span class="block font-extrabold text-slate-800 text-xs" id="follower-count-{{ $user->id }}">{{ $user->followers()->count() }}</span>
                            <span class="text-[8px] text-slate-400 uppercase tracking-widest leading-none">Followers</span>
                        </div>
                    </div>

                    <!-- Follow Button action footer -->
                    <div class="p-3 bg-slate-50 border-t border-slate-100 text-center">
                        @auth
                            @if(Auth::id() === $user->id)
                                <span class="inline-block text-[9px] font-bold text-slate-400 uppercase tracking-wider py-1.5">This is You 👑</span>
                            @else
                                    <div class="flex items-center gap-2">
                                        <button type="button" 
                                                onclick="toggleFollowUser('{{ $user->name }}', '{{ $user->id }}')" 
                                                id="follow-btn-{{ $user->id }}" 
                                                class="flex-1 text-[10px] font-bold py-1.5 px-3 rounded-xl transition-all cursor-pointer border flex items-center justify-center gap-1
                                                {{ Auth::user()->isFollowing($user) 
                                                    ? 'bg-blue-50 border-blue-200 text-blue-700 hover:bg-rose-50 hover:text-rose-700 hover:border-rose-200 group/follow active:scale-97' 
                                                    : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-100 active:scale-97' }}">
                                            @if(Auth::user()->isFollowing($user))
                                                <span class="material-symbols-outlined text-[11px] group-hover/follow:hidden">check</span>
                                                <span class="group-hover/follow:hidden font-bold">Following</span>
                                                <span class="material-symbols-outlined text-[11px] hidden group-hover/follow:inline-block">person_remove</span>
                                                <span class="hidden group-hover/follow:inline font-bold">Unfollow</span>
                                            @else
                                                <span class="material-symbols-outlined text-[11px]">person_add</span>
                                                <span class="font-bold">Follow</span>
                                            @endif
                                        </button>
                                        <button type="button" 
                                                onclick="startDirectChat('{{ $user->name }}')" 
                                                class="flex-1 text-[10px] font-bold py-1.5 px-3 rounded-xl transition-all cursor-pointer border border-slate-200 bg-white text-slate-700 hover:bg-slate-100 active:scale-97 flex items-center justify-center gap-1 shadow-sm">
                                            <span class="material-symbols-outlined text-[11px]">chat</span>
                                            <span class="font-bold">Message</span>
                                        </button>
                                    </div>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="w-full inline-block text-center bg-white border border-slate-200 text-slate-700 font-bold text-[10px] py-1.5 px-3 rounded-xl hover:bg-slate-100 transition-all">
                                Login to Follow
                            </a>
                        @endauth
                    </div>
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
                        btn.className = "w-full text-[10px] font-bold py-1.5 px-3 rounded-xl transition-all cursor-pointer border flex items-center justify-center gap-1.5 bg-blue-50 border-blue-200 text-blue-700 hover:bg-rose-50 hover:text-rose-700 hover:border-rose-200 group/follow active:scale-97";
                        btn.innerHTML = `
                            <span class="material-symbols-outlined text-[11px] group-hover/follow:hidden">check</span>
                            <span class="group-hover/follow:hidden font-bold">Following</span>
                            <span class="material-symbols-outlined text-[11px] hidden group-hover/follow:inline-block">person_remove</span>
                            <span class="hidden group-hover/follow:inline font-bold">Unfollow</span>
                        `;
                    } else {
                        btn.className = "w-full text-[10px] font-bold py-1.5 px-3 rounded-xl transition-all cursor-pointer border flex items-center justify-center gap-1.5 bg-white border-slate-200 text-slate-700 hover:bg-slate-100 active:scale-97";
                        btn.innerHTML = `
                            <span class="material-symbols-outlined text-[11px]">person_add</span>
                            <span class="font-bold">Follow</span>
                        `;
                    }
                }
            })
            .catch(error => {
                btn.disabled = false;
                console.error('Follow Error:', error);
                alert('Could not toggle follow status. Please try again.');
            });
        }
    </script>
@endauth
@endsection
