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
                <span class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-150/60 shadow-sm overflow-hidden">
                    @if(\Illuminate\Support\Str::startsWith($category->icon, ['http://', 'https://']) || \Illuminate\Support\Str::contains($category->icon, '/'))
                        <img src="{{ $category->icon }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                    @elseif($category->icon == 'chat-bubble-left-right')
                        <span class="material-symbols-outlined text-xl">forum</span>
                    @elseif($category->icon == 'photo')
                        <span class="material-symbols-outlined text-xl">photo_library</span>
                    @elseif($category->icon == 'sparkles')
                        <span class="material-symbols-outlined text-xl">auto_awesome</span>
                    @elseif(\Illuminate\Support\Str::startsWith($category->icon, 'fa'))
                        <i class="{{ $category->icon }} text-xl"></i>
                    @else
                        <span class="material-symbols-outlined text-xl">{{ $category->icon ?: 'tag' }}</span>
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
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center justify-between text-xs font-bold text-slate-500 tracking-wider uppercase">
            <span>Discussions</span>
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
                           data-user-avatar="{{ $thread->user->avatar_url }}" 
                           data-user-banner="{{ $thread->user->banner_color }}"
                           data-user-banner-path="{{ $thread->user->banner_path }}"
                           class="w-12 h-12 rounded-full overflow-hidden border border-slate-200 shadow-sm mt-1 flex-shrink-0 block relative group hover:border-blue-500 transition-colors">
                            <img src="{{ $thread->user->avatar_url }}" class="w-full h-full object-cover" alt="avatar">
                        </a>

                        <!-- Thread details -->
                        <div class="space-y-1 text-left">
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                @if($thread->is_pinned)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-amber-50 text-amber-600 border border-amber-250 shadow-sm">
                                        📌 Pinned
                                    </span>
                                @endif
                                @if($thread->is_locked)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-slate-100 text-slate-500 border border-slate-200 shadow-sm">
                                        🔒 Locked
                                    </span>
                                @endif
                                <h2 class="font-bold text-slate-900 text-base hover:text-blue-600 transition-colors leading-tight">
                                    <a href="{{ route('threads.show', $thread->slug) }}">{{ $thread->title }}</a>
                                </h2>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium flex-wrap">
                                <span>By</span>
                                <a href="{{ route('profile.show', $thread->user->name) }}" 
                                   data-user-hover="true" 
                                   data-user-name="{{ $thread->user->name }}" 
                                   data-user-badge="{{ $thread->user->title_badge }}" 
                                   data-user-joined="{{ $thread->user->created_at->format('M d, Y') }}" 
                                   data-user-threads="{{ $thread->user->threads()->count() }}" 
                                   data-user-posts="{{ $thread->user->posts()->count() }}" 
                                   data-user-uploads="{{ $thread->user->attachments()->count() }}" 
                                   data-user-avatar="{{ $thread->user->avatar_url }}" 
                                   data-user-banner="{{ $thread->user->banner_color }}"
                                   data-user-banner-path="{{ $thread->user->banner_path }}"
                                   class="font-bold text-slate-700 hover:text-blue-600 transition-colors">{{ $thread->user->name }}</a>
                                <span class="text-slate-300">•</span>
                                <span>{{ $thread->created_at->diffForHumans() }}</span>
                                @if($thread->tags)
                                    <span class="text-slate-300">•</span>
                                    <span class="flex items-center gap-1 flex-wrap">
                                        @foreach(explode(',', $thread->tags) as $tag)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-bold uppercase tracking-wider bg-indigo-50 text-indigo-600 border border-indigo-100 shadow-sm leading-none">
                                                #{{ trim($tag) }}
                                            </span>
                                        @endforeach
                                    </span>
                                @endif
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
                                        <img src="{{ $thread->lastPost->user->avatar_url }}" class="w-full h-full object-cover" alt="avatar">
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
