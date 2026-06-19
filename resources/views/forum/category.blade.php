@extends('layouts.app')

@section('title')
{{ $category->name }} Discussions | XenForo Professional
@endsection
@section('meta_description')
{{ \Illuminate\Support\Str::limit(strip_tags($category->description ?? 'Browse active discussions, threads, replies, and community files in ' . $category->name . ' category.'), 155) }}
@endsection
@section('meta_keywords')
{{ $category->name }}, discussions, forum, community, threads
@endsection
@section('og_type')
website
@endsection
@section('og_image')
{{ \Illuminate\Support\Str::startsWith($category->icon, ['http://', 'https://']) ? $category->icon : '' }}
@endsection

@section('content')
<div class="space-y-6 max-w-7xl mx-auto px-0 sm:px-6">
    <!-- Board Navigation Path & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-left px-4 sm:px-0">
        <div>
            <!-- Breadcrumbs -->
            <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-500 mb-2.5">
                <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Forums</a>
                <span class="material-symbols-outlined text-[12px] text-slate-400">chevron_right</span>
                <span class="text-blue-600 dark:text-blue-400 font-bold">{{ $category->name }}</span>
            </div>
            <!-- Category Title -->
            <div class="flex items-center gap-3.5">
                <span class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/20 flex items-center justify-center text-blue-600 dark:text-blue-405 border border-blue-100 dark:border-blue-900/30 shadow-sm overflow-hidden flex-shrink-0">
                    @if(\Illuminate\Support\Str::startsWith($category->icon, ['http://', 'https://']) || \Illuminate\Support\Str::contains($category->icon, '/'))
                        <img src="{{ $category->icon }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                    @elseif($category->icon == 'chat-bubble-left-right')
                        <span class="material-symbols-outlined text-2xl">forum</span>
                    @elseif($category->icon == 'photo')
                        <span class="material-symbols-outlined text-2xl">photo_library</span>
                    @elseif($category->icon == 'sparkles')
                        <span class="material-symbols-outlined text-2xl">auto_awesome</span>
                    @elseif(\Illuminate\Support\Str::startsWith($category->icon, 'fa'))
                        <i class="{{ $category->icon }} text-xl"></i>
                    @else
                        <span class="material-symbols-outlined text-2xl">{{ $category->icon ?: 'tag' }}</span>
                    @endif
                </span>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight leading-tight">
                        {{ $category->name }}
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-medium leading-relaxed mt-1 max-w-3xl">{{ $category->description }}</p>
                </div>
            </div>
        </div>

        <div>
            <a href="{{ route('threads.create', $category->slug) }}" class="xen-button inline-flex items-center gap-1.5 text-xs font-bold text-white px-5 py-3 rounded-xl shadow-md cursor-pointer hover:shadow-lg transition-all bg-blue-600 hover:bg-blue-700">
                <span class="material-symbols-outlined text-sm">add</span>
                Post New Thread
            </a>
        </div>
    </div>

    <!-- Threads Listing Panel -->
    <div class="rounded-none sm:rounded-3xl overflow-hidden shadow-sm border-y sm:border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-left">
        <!-- Panel Header -->
        <div class="bg-slate-50 dark:bg-slate-950/40 px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between text-[11px] font-black text-slate-450 dark:text-slate-400 uppercase tracking-wider">
            <span>Discussions</span>
            <div class="hidden md:flex items-center gap-16 mr-4 text-center">
                <span class="w-16">Replies</span>
                <span class="w-16">Views</span>
                <span class="w-44 text-right">Last Message</span>
            </div>
        </div>

        <!-- Threads Loop -->
        <div class="divide-y divide-slate-100 dark:divide-slate-800">
            @forelse($threads as $thread)
                @php
                    $repliesCount = $thread->posts_count - 1;
                    if ($repliesCount < 0) $repliesCount = 0;

                    $lastPost = $thread->lastPost;
                    $lastPostUser = $lastPost ? $lastPost->user : $thread->user;
                    $lastPostTime = $lastPost ? $lastPost->created_at->diffForHumans() : $thread->created_at->diffForHumans();
                @endphp
                <div class="px-4 py-4 sm:px-6 flex items-start sm:items-center justify-between gap-4 hover:bg-slate-50/70 dark:hover:bg-slate-950/20 transition-all relative group hover:shadow-[inset_4px_0_0_0_#3b82f6] dark:hover:shadow-[inset_4px_0_0_0_#3b82f6]">
                    
                    <!-- Left: Avatar + Title & Meta -->
                    <div class="flex items-start gap-3.5 flex-grow min-w-0">
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
                           class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 shadow-sm flex-shrink-0 block relative group/avatar hover:border-blue-500 transition-colors">
                            <img src="{{ $thread->user->avatar_url }}" class="w-full h-full object-cover" alt="avatar">
                        </a>

                        <!-- Thread details -->
                        <div class="space-y-1 text-left min-w-0 flex-grow">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                @if($thread->is_pinned)
                                    <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-[9px] font-black uppercase bg-amber-50 dark:bg-amber-950/35 text-amber-600 dark:text-amber-450 border border-amber-200 dark:border-amber-900/20 shadow-sm">
                                        📌 Pinned
                                    </span>
                                @endif
                                @if($thread->is_locked)
                                    <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-[9px] font-black uppercase bg-slate-100 dark:bg-slate-800 text-slate-550 dark:text-slate-400 border border-slate-200 dark:border-slate-700 shadow-sm">
                                        🔒 Locked
                                    </span>
                                @endif
                                
                                @php
                                    $hasTitleStyle = $thread->is_title_styled;
                                    $hasHighlight = $thread->is_highlighted;
                                    $animClass = '';
                                    if ($thread->title_animation === 'glow') $animClass = 'animate-glow';
                                    elseif ($thread->title_animation === 'pulse') $animClass = 'animate-pulse';
                                    elseif ($thread->title_animation === 'crackle') $animClass = 'animate-bolt';
                                    elseif ($thread->title_animation === 'shimmer') $animClass = 'animate-shimmer';
                                    
                                    $colorStyle = ($hasTitleStyle && $thread->title_color) ? 'color: ' . $thread->title_color . ';' : '';
                                    $defaultClass = ($hasTitleStyle && !$thread->title_color) ? 'text-rose-600 dark:text-rose-400 drop-shadow-[0_1px_1px_rgba(244,63,94,0.2)]' : '';
                                @endphp
                                
                                <h2 class="font-extrabold text-slate-900 dark:text-slate-100 text-sm sm:text-[15px] hover:text-blue-600 dark:hover:text-blue-400 transition-colors leading-snug truncate {{ $hasHighlight ? 'px-2 py-0.5 rounded bg-amber-500/10 border border-amber-500/20 dark:bg-amber-500/5 dark:border-amber-550/20' : '' }}">
                                    <a href="{{ route('threads.show', $thread->slug) }}" class="{{ $hasTitleStyle ? 'font-black tracking-wide' : '' }} {{ $defaultClass }} {{ $animClass }}" style="{{ $colorStyle }}">{!! $thread->prefix_badge !!}{{ $thread->title }}</a>
                                </h2>
                            </div>

                            <!-- Snippet preview from the thread content -->
                            @if($thread->firstPost)
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium line-clamp-1 leading-normal select-none">
                                    {{ Str::limit(strip_tags($thread->firstPost->content ?? ''), 120) }}
                                </p>
                            @endif
                            
                            <!-- Subtitle / Meta info -->
                            <div class="flex items-center gap-1.5 text-[10px] sm:text-xs text-slate-400 dark:text-slate-500 font-bold flex-wrap leading-none pt-0.5">
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
                                   class="text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                                   style="{{ $thread->user->username_style_css }}">{{ $thread->user->name }}</a>
                                <span>•</span>
                                <span>{{ $thread->created_at->diffForHumans() }}</span>
                                
                                <!-- Mobile Stats Indicators -->
                                <span class="md:hidden">•</span>
                                <span class="md:hidden flex items-center gap-1.5">
                                    <span>💬 {{ $repliesCount }} replies</span>
                                    <span>•</span>
                                    <span>👁️ {{ $thread->views_count }} views</span>
                                </span>

                                @if($thread->tags)
                                    <span>•</span>
                                    <span class="flex items-center gap-1 flex-wrap">
                                        @foreach(explode(',', $thread->tags) as $tag)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-black uppercase tracking-wider bg-indigo-50 dark:bg-indigo-950/30 text-indigo-650 dark:text-indigo-400 border border-indigo-100/30 shadow-none leading-none">
                                                #{{ trim($tag) }}
                                            </span>
                                        @endforeach
                                    </span>
                                @endif

                                <!-- Mobile Last Reply snippet -->
                                @if($thread->lastPost)
                                    <span class="sm:hidden text-slate-400 dark:text-slate-600">•</span>
                                    <span class="sm:hidden text-slate-400 dark:text-slate-650 font-medium">
                                        Last: {{ $thread->lastPost->user->name }} ({{ $thread->lastPost->created_at->diffForHumans() }})
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Right: Desktop Statistics & Last Reply blocks (Hidden on Mobile) -->
                    <div class="hidden md:flex items-center gap-16 flex-shrink-0">
                        <!-- Stats -->
                        <div class="flex items-center gap-16 text-center text-xs text-slate-500 dark:text-slate-400 font-semibold">
                            <span class="w-16 block font-bold text-slate-800 dark:text-slate-200 text-sm">{{ number_format($repliesCount) }}</span>
                            <span class="w-16 block font-bold text-slate-800 dark:text-slate-200 text-sm">{{ number_format($thread->views_count) }}</span>
                        </div>

                        <!-- Last reply info block -->
                        <div class="text-xs text-slate-550 dark:text-slate-400 font-medium w-44 text-right">
                            @if($thread->lastPost)
                                <div class="flex items-center justify-end gap-1.5 truncate mb-0.5">
                                    <a href="{{ route('profile.show', $thread->lastPost->user->name) }}" class="w-5 h-5 rounded-full overflow-hidden flex-shrink-0 block bg-slate-100 border border-slate-200 dark:border-slate-850">
                                        <img src="{{ $thread->lastPost->user->avatar_url }}" class="w-full h-full object-cover" alt="avatar">
                                    </a>
                                    <span class="truncate">
                                        <a href="{{ route('profile.show', $thread->lastPost->user->name) }}" class="font-extrabold text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                            {{ $thread->lastPost->user->name }}
                                        </a>
                                    </span>
                                </div>
                                <span class="text-[10px] text-slate-400 dark:text-slate-505 font-bold block">
                                    {{ $thread->lastPost->created_at->diffForHumans() }}
                                </span>
                            @else
                                <span class="text-slate-450 dark:text-slate-650 font-bold block">No replies yet</span>
                            @endif
                        </div>
                    </div>

                </div>
            @empty
                <div class="px-6 py-16 text-center">
                    <div class="w-14 h-14 bg-slate-50 dark:bg-slate-950/50 rounded-2xl flex items-center justify-center text-slate-450 dark:text-slate-650 mx-auto mb-4 border border-slate-200 dark:border-slate-850 shadow-inner">
                        <span class="material-symbols-outlined text-2xl animate-pulse">inbox</span>
                    </div>
                    <h3 class="font-bold text-slate-800 dark:text-slate-200 text-base mb-1">No Threads Found</h3>
                    <p class="text-xs text-slate-450 dark:text-slate-500 max-w-sm mx-auto mb-4 font-medium">There are no active discussions in this category yet. Be the first to start a conversation!</p>
                    <a href="{{ route('threads.create', $category->slug) }}" class="xen-button text-xs font-bold text-white px-5 py-2.5 rounded-xl shadow-md cursor-pointer bg-blue-600 hover:bg-blue-700">
                        Create First Thread
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    @if($threads->hasPages())
        <div class="mt-6 px-4 sm:px-0">
            {{ $threads->links() }}
        </div>
    @endif
</div>
@endsection
