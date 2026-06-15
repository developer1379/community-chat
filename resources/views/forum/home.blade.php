@extends('layouts.app')

@section('content')
<div class="space-y-6 w-full">

    <!-- Header App Bar Area -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-2 text-left px-4 sm:px-0">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">{{ config('app.name', 'XenProfessional') }}</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Welcome to the community discussions feed.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}?tab=latest" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md transition-all">
                <span class="material-symbols-outlined text-[16px]">bolt</span> New posts
            </a>
            <a href="{{ route('categories.show', 'general-discussion') }}/create" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white dark:bg-slate-800 dark:hover:bg-slate-700 font-bold text-xs shadow-md transition-all">
                <span class="material-symbols-outlined text-[16px]">edit</span> Post thread...
            </a>
        </div>
    </div>

    <!-- Grid Layout Container for Boards and Sidebar -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Main Boards Column (8 Cols) -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Unified Community Boards Card -->
            <div class="bg-white dark:bg-slate-900 border-y sm:border border-slate-200 dark:border-slate-800 rounded-none sm:rounded-3xl shadow-sm overflow-hidden text-left">
                <!-- Section Header -->
                <div class="bg-slate-50 dark:bg-slate-950/40 px-4 py-3 sm:px-6 sm:py-4 border-b border-slate-200 dark:border-slate-800">
                    <h2 class="text-xs font-black text-slate-700 dark:text-slate-350 uppercase tracking-[0.2em] flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm text-blue-600 dark:text-blue-400">forum</span> Discussion Boards
                    </h2>
                </div>

                <!-- Categories Table Grid -->
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($categories as $category)
                        @php
                            // Count threads
                            $threadsCount = $category->threads->count();
                            // Count total posts (each thread represents 1 post, plus replies count)
                            $postsCount = 0;
                            foreach($category->threads as $t) {
                                $postsCount += 1 + $t->posts->count();
                            }
                            
                            // Format stats counts
                            $fmtThreads = $threadsCount >= 1000 ? number_format($threadsCount / 1000, 1) . 'K' : $threadsCount;
                            $fmtPosts = $postsCount >= 1000 ? number_format($postsCount / 1000, 1) . 'K' : $postsCount;
                            
                            // Find latest thread based on last update
                            $latestThread = $category->threads->sortByDesc('updated_at')->first();
                            $lastPostUser = null;
                            $lastPostTime = null;
                            if ($latestThread) {
                                $lastPost = $latestThread->posts->sortByDesc('created_at')->first();
                                if ($lastPost) {
                                    $lastPostUser = $lastPost->user;
                                    $lastPostTime = $lastPost->created_at->diffForHumans();
                                } else {
                                    $lastPostUser = $latestThread->user;
                                    $lastPostTime = $latestThread->created_at->diffForHumans();
                                }
                            }
                        @endphp
                        
                        <div class="px-4 py-3 sm:px-6 sm:py-4 flex items-center justify-between gap-4 hover:bg-slate-50/50 dark:hover:bg-slate-950/20 transition-all">
                            
                            <!-- Left: Icon & Category details -->
                            <div class="flex items-start gap-3 flex-grow min-w-0 md:max-w-[50%]">
                                <!-- Category Icon -->
                                <div class="w-8.5 h-8.5 sm:w-10 sm:h-10 rounded-xl bg-blue-50 dark:bg-blue-950/30 flex items-center justify-center text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-900/30 shadow-sm flex-shrink-0 overflow-hidden mt-0.5">
                                    @if(\Illuminate\Support\Str::startsWith($category->icon, ['http://', 'https://']) || \Illuminate\Support\Str::contains($category->icon, '/'))
                                        <img src="{{ $category->icon }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                                    @elseif($category->icon == 'chat-bubble-left-right')
                                        <span class="material-symbols-outlined text-xl">forum</span>
                                    @elseif($category->icon == 'photo')
                                        <span class="material-symbols-outlined text-xl">photo_library</span>
                                    @elseif($category->icon == 'sparkles')
                                        <span class="material-symbols-outlined text-xl">auto_awesome</span>
                                    @elseif(\Illuminate\Support\Str::startsWith($category->icon, 'fa'))
                                        <i class="{{ $category->icon }} text-base"></i>
                                    @else
                                        <span class="material-symbols-outlined text-xl">{{ $category->icon ?: 'tag' }}</span>
                                    @endif
                                </div>
                                <!-- Name & description -->
                                <div class="min-w-0 leading-tight space-y-0.5 flex-grow">
                                    <h3 class="font-extrabold text-slate-900 dark:text-white text-sm sm:text-base hover:text-blue-600 dark:hover:text-blue-400 transition-colors truncate">
                                        <a href="{{ route('categories.show', $category->slug) }}">{{ $category->name }}</a>
                                    </h3>
                                    <p class="text-xs text-slate-450 dark:text-slate-400 font-medium line-clamp-1 leading-normal">{{ $category->description }}</p>
                                    
                                    <!-- Mobile-only inline stats & activity -->
                                    <div class="md:hidden flex items-center gap-1.5 text-[10px] font-bold text-slate-400 dark:text-slate-500 leading-none pt-0.5 flex-wrap">
                                        <span>{{ $fmtThreads }} threads</span>
                                        <span>•</span>
                                        <span>{{ $fmtPosts }} posts</span>
                                        @if($latestThread && $lastPostUser)
                                            <span>•</span>
                                            <span class="truncate max-w-[130px] sm:max-w-[180px]">
                                                Last: <a href="{{ route('threads.show', $latestThread->slug) }}" class="text-slate-550 dark:text-slate-455 font-extrabold hover:underline" style="{{ $lastPostUser->username_style_css }}">{{ $latestThread->title }}</a>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Right side: stats & last action (Only visible on MD grids and larger) -->
                            <div class="hidden md:flex items-center gap-6 sm:gap-10 md:gap-14 flex-shrink-0 justify-between md:justify-end">
                                
                                <!-- Stats: Threads & Posts -->
                                <div class="flex items-center gap-6 sm:gap-10">
                                    <div class="text-center w-12 flex-shrink-0">
                                        <span class="text-[10px] font-black text-slate-400 dark:text-slate-500 block uppercase tracking-wider">Threads</span>
                                        <span class="text-xs font-black text-slate-700 dark:text-slate-350 mt-0.5 block">{{ $fmtThreads }}</span>
                                    </div>
                                    <div class="text-center w-12 flex-shrink-0">
                                        <span class="text-[10px] font-black text-slate-400 dark:text-slate-500 block uppercase tracking-wider">Posts</span>
                                        <span class="text-xs font-black text-slate-700 dark:text-slate-350 mt-0.5 block">{{ $fmtPosts }}</span>
                                    </div>
                                </div>

                                <!-- Last Post Activity -->
                                <div class="w-48 text-left flex items-center gap-2.5 min-w-0">
                                    @if($latestThread && $lastPostUser)
                                        <!-- Last Post User Avatar -->
                                        <a href="{{ route('profile.show', $lastPostUser->name) }}" 
                                           data-user-hover="true" 
                                           data-user-name="{{ $lastPostUser->name }}" 
                                           data-user-badge="{{ $lastPostUser->title_badge }}" 
                                           data-user-joined="{{ $lastPostUser->created_at->format('M d, Y') }}" 
                                           data-user-threads="{{ $lastPostUser->threads()->count() }}" 
                                           data-user-posts="{{ $lastPostUser->posts()->count() }}" 
                                           data-user-uploads="{{ $lastPostUser->attachments()->count() }}" 
                                           data-user-avatar="{{ $lastPostUser->avatar_url }}" 
                                           data-user-banner="{{ $lastPostUser->banner_color }}"
                                           data-user-banner-path="{{ $lastPostUser->banner_path }}"
                                           class="w-8 h-8 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-800 flex-shrink-0 shadow-sm block hover:shadow transition-shadow">
                                             <img src="{{ $lastPostUser->avatar_url }}" class="w-full h-full object-cover">
                                        </a>
                                        <div class="min-w-0 leading-none">
                                            <!-- Latest thread title link -->
                                            <a href="{{ route('threads.show', $latestThread->slug) }}" class="text-xs font-extrabold text-slate-750 dark:text-slate-350 hover:text-blue-600 dark:hover:text-blue-400 truncate block max-w-[130px] sm:max-w-[160px] tracking-tight leading-normal" title="{{ $latestThread->title }}">
                                                {{ $latestThread->title }}
                                            </a>
                                            <!-- Timestamp & User -->
                                            <span class="text-[9px] text-slate-400 dark:text-slate-500 font-bold block mt-0.5 leading-normal">
                                                {{ $lastPostTime }} • <a href="{{ route('profile.show', $lastPostUser->name) }}" 
                                                                       data-user-hover="true" 
                                                                       data-user-name="{{ $lastPostUser->name }}" 
                                                                       data-user-badge="{{ $lastPostUser->title_badge }}" 
                                                                       data-user-joined="{{ $lastPostUser->created_at->format('M d, Y') }}" 
                                                                       data-user-threads="{{ $lastPostUser->threads()->count() }}" 
                                                                       data-user-posts="{{ $lastPostUser->posts()->count() }}" 
                                                                       data-user-uploads="{{ $lastPostUser->attachments()->count() }}" 
                                                                       data-user-avatar="{{ $lastPostUser->avatar_url }}" 
                                                                       data-user-banner="{{ $lastPostUser->banner_color }}"
                                                                       data-user-banner-path="{{ $lastPostUser->banner_path }}"
                                                                       class="hover:underline font-extrabold text-slate-550 dark:text-slate-455"
                                                                       style="{{ $lastPostUser->username_style_css }}">{{ $lastPostUser->name }}</a>
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium tracking-tight">No activity yet</span>
                                    @endif
                                </div>

                            </div>

                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Primary Community Threads Feed -->
            <div class="bg-white dark:bg-slate-900 border-y sm:border border-slate-200 dark:border-slate-800 rounded-none sm:rounded-3xl shadow-sm overflow-hidden text-left">
                <!-- Section Header -->
                <div class="bg-slate-50 dark:bg-slate-950/40 px-4 py-3 sm:px-6 sm:py-4 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <h2 class="text-xs font-black text-slate-700 dark:text-slate-350 uppercase tracking-[0.2em] flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm text-blue-600 dark:text-blue-400">forum</span> All Discussions
                    </h2>
                    <span class="text-xs text-slate-400 dark:text-slate-500 font-bold">Showing latest active threads</span>
                </div>

                <!-- Feed Thread List -->
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($threads as $thread)
                        @php
                            $firstAttachment = $thread->attachments->first();
                            $repliesCount = $thread->posts->count() - 1;
                            if($repliesCount < 0) $repliesCount = 0;
                            
                            $lastPost = $thread->lastPost;
                            $lastPostUser = $lastPost ? $lastPost->user : $thread->user;
                            $lastPostTime = $lastPost ? $lastPost->created_at->diffForHumans() : $thread->created_at->diffForHumans();
                        @endphp
                        <div class="px-4 py-4 sm:px-6 flex items-start gap-4 hover:bg-slate-50/50 dark:hover:bg-slate-950/10 transition-all relative group">
                            
                            <!-- Avatar Column -->
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
                               class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 flex-shrink-0 shadow-sm block hover:scale-105 transition-transform">
                                <img src="{{ $thread->user->avatar_url }}" class="w-full h-full object-cover">
                            </a>

                            <!-- Main Thread Info Column -->
                            <div class="flex-grow min-w-0 space-y-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <!-- Category Badge -->
                                    <a href="{{ route('categories.show', $thread->category->slug) }}" class="inline-block text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-900/30">
                                        {{ $thread->category->name }}
                                    </a>
                                    
                                    <!-- Status Badges -->
                                    @if($thread->is_pinned)
                                        <span class="inline-flex items-center gap-0.5 text-[9px] font-extrabold uppercase px-1.5 py-0.5 rounded bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 border border-amber-250 dark:border-amber-900/30">
                                            📌 Pinned
                                        </span>
                                    @endif
                                    @if($thread->is_locked)
                                        <span class="inline-flex items-center gap-0.5 text-[9px] font-extrabold uppercase px-1.5 py-0.5 rounded bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-455 border border-rose-250 dark:border-rose-900/30">
                                            🔒 Locked
                                        </span>
                                    @endif
                                </div>

                                <!-- Thread Title -->
                                @php
                                    $hasTitleStyle = $thread->is_title_styled;
                                    $hasHighlight = $thread->is_highlighted;
                                    $animClass = '';
                                    if ($thread->title_animation === 'glow') $animClass = 'animate-glow';
                                    elseif ($thread->title_animation === 'pulse') $animClass = 'animate-pulse';
                                    elseif ($thread->title_animation === 'crackle') $animClass = 'animate-bolt';
                                    elseif ($thread->title_animation === 'shimmer') $animClass = 'animate-shimmer';
                                    
                                    $colorStyle = ($hasTitleStyle && $thread->title_color) ? 'color: ' . $thread->title_color . ';' : '';
                                    $defaultClass = ($hasTitleStyle && !$thread->title_color) ? 'text-rose-600 dark:text-rose-400 drop-shadow-[0_1px_1px_rgba(244,63,94,0.15)]' : 'text-slate-900 dark:text-white';
                                @endphp
                                <h3 class="text-sm sm:text-base font-extrabold tracking-tight hover:text-blue-600 dark:hover:text-blue-400 transition-colors leading-snug {{ $hasHighlight ? 'bg-amber-500/10 px-1 rounded' : '' }} {{ $hasTitleStyle ? 'font-black' : '' }} {{ $defaultClass }} {{ $animClass }}" style="{{ $colorStyle }}">
                                    <a href="{{ route('threads.show', $thread->slug) }}">{{ $thread->title }}</a>
                                </h3>

                                <!-- First Post Content Snippet (Content Full!) -->
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium line-clamp-2 leading-relaxed pt-0.5">
                                    {{ Str::limit(strip_tags($thread->firstPost->content ?? ''), 150) }}
                                </p>

                                <!-- Thread Meta Info Row -->
                                <div class="flex items-center gap-2 pt-1.5 text-[10px] font-bold text-slate-400 dark:text-slate-500 flex-wrap">
                                    <span>Started by</span>
                                    <a href="{{ route('profile.show', $thread->user->name) }}"
                                       data-user-hover="true"
                                       data-user-name="{{ $thread->user->name }}"
                                       class="hover:underline font-extrabold text-slate-655 dark:text-slate-355"
                                       style="{{ $thread->user->username_style_css }}">{{ $thread->user->name }}</a>
                                    <span>•</span>
                                    <span>{{ $thread->created_at->diffForHumans() }}</span>
                                    
                                    <span class="sm:hidden">•</span>
                                    <span class="sm:hidden">💬 {{ $repliesCount }}</span>
                                    <span class="sm:hidden">•</span>
                                    <span class="sm:hidden">👁️ {{ $thread->views_count }}</span>
                                </div>
                            </div>

                            <!-- Image Attachment Thumbnail if exists -->
                            @if($firstAttachment)
                                <div class="hidden sm:block w-16 h-16 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 flex-shrink-0 bg-slate-50">
                                    <img src="{{ $firstAttachment->file_path }}" class="w-full h-full object-cover">
                                </div>
                            @endif

                            <!-- Engagement Column (Replies & Views) -->
                            <div class="hidden sm:flex flex-col items-end justify-center text-right flex-shrink-0 w-20 space-y-1">
                                <div class="text-xs font-black text-slate-700 dark:text-slate-300">
                                    💬 {{ $repliesCount }}
                                    <span class="text-[9px] font-bold text-slate-400 dark:text-slate-500 block uppercase tracking-wider">Replies</span>
                                </div>
                                <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500">
                                    👁️ {{ $thread->views_count }}
                                    <span class="text-[8px] font-bold text-slate-400 dark:text-slate-500 block uppercase tracking-wider">Views</span>
                                </div>
                            </div>

                            <!-- Last Activity User Column -->
                            <div class="hidden md:flex items-center gap-2.5 w-40 flex-shrink-0 border-l border-slate-100 dark:border-slate-800 pl-4">
                                <a href="{{ route('profile.show', $lastPostUser->name) }}"
                                   data-user-hover="true"
                                   data-user-name="{{ $lastPostUser->name }}"
                                   class="w-7 h-7 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-800 flex-shrink-0 shadow-sm block">
                                    <img src="{{ $lastPostUser->avatar_url }}" class="w-full h-full object-cover">
                                </a>
                                <div class="min-w-0 leading-none">
                                    <a href="{{ route('profile.show', $lastPostUser->name) }}"
                                       data-user-hover="true"
                                       data-user-name="{{ $lastPostUser->name }}"
                                       class="text-[11px] font-extrabold text-slate-700 dark:text-slate-300 hover:text-blue-600 truncate block max-w-full"
                                       style="{{ $lastPostUser->username_style_css }}">
                                        {{ $lastPostUser->name }}
                                    </a>
                                    <span class="text-[9px] text-slate-400 dark:text-slate-500 font-bold block mt-0.5">{{ $lastPostTime }}</span>
                                </div>
                            </div>

                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-400 dark:text-slate-500 font-bold">
                            No threads have been created yet. Be the first to start a discussion!
                        </div>
                    @endforelse
                </div>

                <!-- Pagination Links -->
                @if($threads->hasPages())
                    <div class="p-4 bg-slate-50 dark:bg-slate-950/20 border-t border-slate-200 dark:border-slate-800">
                        {{ $threads->links() }}
                    </div>
                @endif
            </div>

        </div>

        <!-- Sidebar Section (Right - 4 Cols) -->
        <div class="lg:col-span-4 space-y-6">

            <!-- Latest Profile Posts Widget -->
            @php
                $latestProfilePosts = \App\Models\Post::whereHas('attachments')
                    ->with(['user', 'thread'])
                    ->latest()
                    ->take(3)
                    ->get();
            @endphp
            @if($latestProfilePosts->isNotEmpty())
                <div class="border-y sm:border border-slate-200 dark:border-slate-800 p-5 bg-white dark:bg-slate-900 rounded-none sm:rounded-3xl shadow-sm text-left">
                    <h3 class="text-xs font-black tracking-wider text-slate-400 dark:text-slate-500 uppercase mb-4 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-blue-600 text-sm">rss_feed</span> Latest profile posts
                    </h3>
                    <div class="space-y-4">
                        @foreach($latestProfilePosts as $post)
                            @php
                                $firstAttach = $post->attachments->first();
                            @endphp
                            <div class="space-y-2 border-b border-slate-100 dark:border-slate-850 pb-3.5 last:border-0 last:pb-0">
                                <!-- User & Title -->
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('profile.show', $post->user->name) }}"
                                       data-user-hover="true"
                                       data-user-name="{{ $post->user->name }}"
                                       data-user-badge="{{ $post->user->title_badge }}"
                                       data-user-joined="{{ $post->user->created_at->format('M d, Y') }}"
                                       data-user-threads="{{ $post->user->threads()->count() }}"
                                       data-user-posts="{{ $post->user->posts()->count() }}"
                                       data-user-uploads="{{ $post->user->attachments()->count() }}"
                                       data-user-avatar="{{ $post->user->avatar_url }}"
                                       data-user-banner="{{ $post->user->banner_color }}"
                                       data-user-banner-path="{{ $post->user->banner_path }}"
                                       class="w-6 h-6 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-800 flex-shrink-0 block">
                                        <img src="{{ $post->user->avatar_url }}" class="w-full h-full object-cover">
                                    </a>
                                    <div class="leading-none">
                                        <a href="{{ route('profile.show', $post->user->name) }}"
                                           data-user-hover="true"
                                           data-user-name="{{ $post->user->name }}"
                                           data-user-badge="{{ $post->user->title_badge }}"
                                           data-user-joined="{{ $post->user->created_at->format('M d, Y') }}"
                                           data-user-threads="{{ $post->user->threads()->count() }}"
                                           data-user-posts="{{ $post->user->posts()->count() }}"
                                           data-user-uploads="{{ $post->user->attachments()->count() }}"
                                           data-user-avatar="{{ $post->user->avatar_url }}"
                                           data-user-banner="{{ $post->user->banner_color }}"
                                           data-user-banner-path="{{ $post->user->banner_path }}"
                                           class="text-xs font-extrabold text-blue-600 dark:text-blue-455 hover:underline"
                                           style="{{ $post->user->username_style_css }}">{{ $post->user->name }}</a>
                                        <span class="text-[8px] text-slate-400 font-bold block mt-0.5">{{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <!-- Post snippet text -->
                                <p class="text-xs text-slate-650 dark:text-slate-355 leading-relaxed line-clamp-2">
                                    {{ strip_tags($post->content) }}
                                </p>
                                <!-- Image attachment preview at bottom if exists -->
                                @if($firstAttach)
                                    <div class="h-20 w-full rounded-xl overflow-hidden border border-slate-250 dark:border-slate-800 bg-slate-50">
                                        <img src="{{ $firstAttach->file_path }}" class="w-full h-full object-cover">
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Recent Activity Sidebar -->
            <div class="border-y sm:border border-slate-200 dark:border-slate-800 p-5 bg-white dark:bg-slate-900 rounded-none sm:rounded-2xl shadow-sm text-left">
                <h3 class="text-sm font-extrabold tracking-wider text-slate-550 dark:text-slate-400 uppercase mb-4 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-blue-600 text-sm">electric_bolt</span> Recent Activity
                </h3>
                <div class="space-y-3.5">
                    @foreach($activeThreads as $activeThread)
                        <div class="text-xs leading-normal border-b border-slate-100 dark:border-slate-850 pb-3 last:border-0 last:pb-0">
                            <h4 class="font-bold text-slate-850 dark:text-slate-205 hover:text-blue-600 dark:hover:text-blue-400 transition-colors text-sm">
                                <a href="{{ route('threads.show', $activeThread->slug) }}">{{ $activeThread->title }}</a>
                            </h4>
                            <div class="flex items-center gap-2 mt-1.5 text-slate-450 dark:text-slate-500 font-bold text-xs">
                                <span class="px-1.5 py-0.5 rounded-2xl bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-450 font-bold border border-blue-150 dark:border-blue-900/30">
                                    {{ $activeThread->category->name }}
                                </span>
                                <span>•</span>
                                <span>{{ $activeThread->posts_count }} replies</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
