@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Board Navigation Path & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <!-- Breadcrumbs -->
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-2">
                <a href="{{ route('home') }}" class="hover:text-blue-600">Forums</a>
                <span>/</span>
                <span class="text-blue-600 font-semibold">{{ $category->name }}</span>
            </div>
            <!-- Category Title -->
            <h1 class="text-3xl font-extrabold text-slate-950 tracking-tight flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-150/60 shadow-sm">
                    @if($category->icon == 'chat-bubble-left-right')
                        <span class="material-symbols-outlined text-xl">forum</span>
                    @elseif($category->icon == 'photo')
                        <span class="material-symbols-outlined text-xl">photo_library</span>
                    @elseif($category->icon == 'sparkles')
                        <span class="material-symbols-outlined text-xl">auto_awesome</span>
                    @else
                        <span class="material-symbols-outlined text-xl">tag</span>
                    @endif
                </span>
                {{ $category->name }}
            </h1>
            <p class="text-xs text-slate-500 font-medium leading-relaxed mt-1.5">{{ $category->description }}</p>
        </div>

        <div>
            <a href="{{ route('threads.create', $category->slug) }}" class="xen-button inline-flex items-center gap-2 text-xs font-bold text-white px-5 py-2.5 rounded-xl shadow-md cursor-pointer hover:shadow-lg transition-all">
                <span class="material-symbols-outlined text-sm">add</span>
                Post New Thread
            </a>
        </div>
    </div>

    <!-- Threads Listing Panel -->
    <div class="mui-card rounded-2xl overflow-hidden shadow-md border border-slate-200 bg-white">
        <!-- Panel Header -->
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center justify-between text-[10px] font-bold text-slate-500 tracking-wider uppercase">
            <span>Discussions / Threads</span>
            <div class="hidden md:flex items-center gap-24 mr-4">
                <span>Stats</span>
                <span class="w-36">Last Message</span>
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
                           class="w-11 h-11 rounded-xl overflow-hidden border border-slate-200 shadow-sm mt-1 flex-shrink-0 block relative group">
                            @if($thread->user->avatar_path)
                                <img src="{{ str_starts_with($thread->user->avatar_path, 'http') ? $thread->user->avatar_path : asset('storage/' . $thread->user->avatar_path) }}" class="w-full h-full object-cover" alt="avatar">
                            @else
                                <div class="w-full h-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-500">
                                    {{ strtoupper(substr($thread->user->name, 0, 2)) }}
                                </div>
                            @endif
                        </a>

                        <!-- Thread details -->
                        <div class="space-y-1 text-left">
                            <div class="flex items-center gap-2 flex-wrap">
                                @if($thread->is_pinned)
                                    <span class="px-2 py-0.5 rounded text-[8px] font-bold uppercase bg-amber-50 text-amber-600 border border-amber-250">
                                        📌 Pinned
                                    </span>
                                @endif
                                @if($thread->is_locked)
                                    <span class="px-2 py-0.5 rounded text-[8px] font-bold uppercase bg-slate-100 text-slate-500 border border-slate-200">
                                        🔒 Locked
                                    </span>
                                @endif
                                <h2 class="font-bold text-slate-800 text-sm hover:text-blue-600 transition-colors leading-tight">
                                    <a href="{{ route('threads.show', $thread->slug) }}">{{ $thread->title }}</a>
                                </h2>
                            </div>
                            <div class="flex items-center gap-2 text-[10px] text-slate-450 font-bold">
                                <span class="text-slate-400">By</span>
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
                                   class="font-bold text-slate-650 hover:underline">{{ $thread->user->name }}</a>
                                <span>•</span>
                                <span>{{ $thread->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Side Stats & Last Reply info -->
                    <div class="flex flex-row md:items-center justify-between md:justify-end gap-6 md:gap-16 flex-shrink-0 border-t md:border-t-0 border-slate-100 pt-3 md:pt-0">
                        <!-- Stats -->
                        <div class="flex items-center gap-6 text-[10px] text-slate-450 font-bold">
                            <div class="text-center w-12">
                                <span class="block font-extrabold text-slate-800 text-xs">{{ $thread->posts_count }}</span>
                                <span class="text-[9px] text-slate-400">replies</span>
                            </div>
                            <div class="text-center w-12">
                                <span class="block font-extrabold text-slate-800 text-xs">{{ $thread->views_count }}</span>
                                <span class="text-[9px] text-slate-400">views</span>
                            </div>
                        </div>

                        <!-- Last reply info block -->
                        <div class="text-[10px] text-slate-450 font-bold w-40 truncate text-left md:text-right">
                            @if($thread->lastPost)
                                <div class="flex items-center md:justify-end gap-1.5 truncate">
                                    <span class="text-slate-400">By</span>
                                    <a href="{{ route('profile.show', $thread->lastPost->user->name) }}" class="font-bold text-slate-650 hover:underline truncate">
                                        {{ $thread->lastPost->user->name }}
                                    </a>
                                </div>
                                <span class="text-[9px] text-slate-400 font-semibold block mt-0.5">
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
                        <span class="material-symbols-outlined text-2xl">inbox</span>
                    </div>
                    <h3 class="font-bold text-slate-800 text-base mb-1">No Threads Found</h3>
                    <p class="text-xs text-slate-450 max-w-sm mx-auto mb-4 font-medium">There are no active discussions in this forum yet. Be the first to start a conversation!</p>
                    <a href="{{ route('threads.create', $category->slug) }}" class="xen-button text-xs font-bold text-white px-5 py-2 rounded-xl shadow-md cursor-pointer">
                        Create First Thread
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $threads->links() }}
    </div>
</div>
@endsection
