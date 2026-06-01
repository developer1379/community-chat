@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <!-- Breadcrumbs -->
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-2">
                <a href="{{ route('home') }}" class="hover:text-blue-600">Forums</a>
                <span>/</span>
                <span class="text-blue-600 font-semibold">Search Results</span>
            </div>
            <!-- Title -->
            <h1 class="text-3xl font-extrabold text-slate-950 tracking-tight flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-150/60 shadow-sm">
                    <span class="material-symbols-outlined text-xl">search</span>
                </span>
                Search Results
            </h1>
            <p class="text-xs text-slate-500 font-medium leading-relaxed mt-1.5">
                @if($query)
                    Showing results for "<span class="font-bold text-slate-800">{{ $query }}</span>"
                @else
                    Enter a query to start searching the forum.
                @endif
            </p>
        </div>
        
        <div>
            <!-- Search Form Again -->
            <form action="{{ route('search') }}" method="GET" class="relative">
                <input type="text" name="q" value="{{ request('q') }}" class="w-full sm:w-64 bg-white border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all font-bold shadow-sm" placeholder="Search again...">
                <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-sm">search</span>
                <button type="submit" class="hidden">Search</button>
            </form>
        </div>
    </div>

    <!-- Threads Listing Panel -->
    <div class="mui-card rounded-2xl overflow-hidden shadow-md border border-slate-200 bg-white">
        <!-- Panel Header -->
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center justify-between text-xs font-bold text-slate-500 tracking-wider uppercase">
            <span>Matched Discussions</span>
            <div class="hidden md:flex items-center gap-24 mr-4 text-center">
                <span class="w-20">Stats</span>
                <span class="w-48 text-right">Last Message</span>
            </div>
        </div>

        <!-- Threads Loop -->
        <div class="divide-y divide-slate-100">
            @forelse($threads as $thread)
                <div class="px-6 py-5 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-slate-50/50 transition-colors">
                    <div class="flex items-start gap-4 flex-grow">
                        <!-- Creator avatar -->
                        <a href="{{ route('profile.show', $thread->user->name) }}" 
                           data-user-hover="true" 
                           data-user-name="{{ $thread->user->name }}" 
                           data-user-badge="{{ $thread->user->title_badge }}" 
                           data-user-joined="{{ $thread->user->created_at->format('M d, Y') }}" 
                           data-user-threads="{{ $thread->user->threads()->count() }}" 
                           data-user-posts="{{ $thread->user->posts()->count() }}" 
                           data-user-uploads="{{ $thread->user->attachments()->count() }}" 
                           data-user-avatar="{{ $thread->user->avatar_path ? (str_starts_with($thread->user->avatar_path, 'http') ? $thread->user->avatar_path : asset('storage/' . $thread->user->avatar_path)) : '' }}" 
                           data-user-banner="{{ $thread->user->banner_color }}"
                           data-user-banner-path="{{ $thread->user->banner_path }}"
                           class="w-12 h-12 rounded-full overflow-hidden border border-slate-200 shadow-sm mt-1 flex-shrink-0 block relative group hover:border-blue-500 transition-colors">
                            @if($thread->user->avatar_path)
                                <img src="{{ str_starts_with($thread->user->avatar_path, 'http') ? $thread->user->avatar_path : asset('storage/' . $thread->user->avatar_path) }}" class="w-full h-full object-cover" alt="avatar">
                            @else
                                <div class="w-full h-full bg-slate-100 flex items-center justify-center text-sm font-bold text-slate-500">
                                    {{ strtoupper(substr($thread->user->name, 0, 2)) }}
                                </div>
                            @endif
                        </a>

                        <!-- Thread details -->
                        <div class="space-y-1 text-left">
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                <span class="px-2 py-0.5 rounded text-[9px] font-extrabold uppercase bg-blue-50 text-blue-600 border border-blue-200 shadow-sm">
                                    {{ $thread->category->name }}
                                </span>
                                <h2 class="font-bold text-slate-900 text-base hover:text-blue-600 transition-colors leading-tight">
                                    <a href="{{ route('threads.show', $thread->slug) }}">{{ $thread->title }}</a>
                                </h2>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                                <span>By</span>
                                <a href="{{ route('profile.show', $thread->user->name) }}" class="font-bold text-slate-700 hover:text-blue-600 transition-colors">{{ $thread->user->name }}</a>
                                <span class="text-slate-300">•</span>
                                <span>{{ $thread->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Side Stats & Last Reply info -->
                    <div class="flex flex-row md:items-center justify-between md:justify-end gap-6 md:gap-16 flex-shrink-0 border-t md:border-t-0 border-slate-100 pt-3 md:pt-0 mt-3 md:mt-0">
                        <!-- Stats -->
                        <div class="flex items-center gap-4 text-xs text-slate-500 font-medium">
                            <div class="text-center w-16 bg-slate-50 rounded-lg p-1.5 border border-slate-150">
                                <span class="block font-extrabold text-slate-800 text-sm">{{ $thread->posts_count }}</span>
                                <span class="text-[10px] uppercase font-bold text-slate-400">replies</span>
                            </div>
                            <div class="text-center w-16 bg-slate-50 rounded-lg p-1.5 border border-slate-150">
                                <span class="block font-extrabold text-slate-800 text-sm">{{ $thread->views_count }}</span>
                                <span class="text-[10px] uppercase font-bold text-slate-400">views</span>
                            </div>
                        </div>

                        <!-- Last reply info block -->
                        <div class="text-xs text-slate-500 font-medium w-48 text-left md:text-right">
                            @if($thread->lastPost)
                                <div class="flex items-center md:justify-end gap-1.5 truncate mb-0.5">
                                    <a href="{{ route('profile.show', $thread->lastPost->user->name) }}" class="w-5 h-5 rounded-full overflow-hidden flex-shrink-0 block bg-slate-100 border border-slate-200">
                                        @if($thread->lastPost->user->avatar_path)
                                            <img src="{{ str_starts_with($thread->lastPost->user->avatar_path, 'http') ? $thread->lastPost->user->avatar_path : asset('storage/' . $thread->lastPost->user->avatar_path) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-[8px] font-bold text-slate-500">
                                                {{ strtoupper(substr($thread->lastPost->user->name, 0, 2)) }}
                                            </div>
                                        @endif
                                    </a>
                                    <span class="truncate">
                                        <a href="{{ route('profile.show', $thread->lastPost->user->name) }}" class="font-bold text-slate-700 hover:text-blue-600 transition-colors">
                                            {{ $thread->lastPost->user->name }}
                                        </a>
                                    </span>
                                </div>
                                <span class="text-[11px] text-slate-400 font-semibold block">
                                    {{ $thread->lastPost->created_at->diffForHumans() }}
                                </span>
                            @else
                                <span class="text-slate-400">No replies yet</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-16 text-center">
                    <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 mx-auto mb-4 border border-slate-200 shadow-inner">
                        <span class="material-symbols-outlined text-2xl">search_off</span>
                    </div>
                    <h3 class="font-bold text-slate-800 text-base mb-1">No Results Found</h3>
                    <p class="text-xs text-slate-450 max-w-sm mx-auto mb-4 font-medium">We couldn't find any threads matching your query. Try different keywords.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    @if($threads->hasPages())
        <div class="mt-6">
            {{ $threads->appends(['q' => $query])->links() }}
        </div>
    @endif
</div>
@endsection
