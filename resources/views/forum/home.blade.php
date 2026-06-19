@extends('layouts.app')

@section('title')
Community Discussion Forums | XenForo Professional
@endsection
@section('meta_description')
Join the XenForo Professional community. Discover active discussions, trending topics, member rankings, and browse shared photos and GIFs.
@endsection
@section('meta_keywords')
xenforo, community, forums, discussion boards, image sharing, leaderboard
@endsection

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

                            // Define premium colors
                            $accentColor = 'border-l-blue-600 dark:border-l-blue-500';
                            $iconBg = 'bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-450 border-blue-100 dark:border-blue-900/30';
                            
                            if ($category->slug === 'general-discussion') {
                                $accentColor = 'border-l-indigo-500 dark:border-l-indigo-400';
                                $iconBg = 'bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 border-indigo-100 dark:border-indigo-900/30';
                            } elseif ($category->slug === 'images-and-gifs' || $category->slug === 'images-gifs-showroom') {
                                $accentColor = 'border-l-pink-500 dark:border-l-pink-400';
                                $iconBg = 'bg-pink-50 dark:bg-pink-950/30 text-pink-600 dark:text-pink-400 border-pink-100 dark:border-pink-900/30';
                            } elseif ($category->slug === 'web-dev-and-xenforo-styles' || $category->slug === 'web-dev-xenforo-styles') {
                                $accentColor = 'border-l-sky-500 dark:border-l-sky-400';
                                $iconBg = 'bg-sky-50 dark:bg-sky-950/30 text-sky-600 dark:text-sky-450 border-sky-100 dark:border-sky-900/30';
                            } elseif ($category->slug === 'tech-support-inquiries' || $category->slug === 'tech-support') {
                                $accentColor = 'border-l-emerald-500 dark:border-l-emerald-400';
                                $iconBg = 'bg-emerald-50 dark:bg-emerald-950/30 text-emerald-650 dark:text-emerald-450 border-emerald-100 dark:border-emerald-900/30';
                            }
                        @endphp
                        
                        <div class="px-4 py-3 sm:px-6 sm:py-4 flex items-center justify-between gap-4 hover:bg-slate-50/50 dark:hover:bg-slate-950/20 transition-all border-l-4 {{ $accentColor }}">
                            
                            <!-- Left: Icon & Category details -->
                            <div class="flex items-start gap-3 flex-grow min-w-0 md:max-w-[50%]">
                                <!-- Category Icon -->
                                <div class="w-8.5 h-8.5 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center border shadow-sm flex-shrink-0 overflow-hidden mt-0.5 {{ $iconBg }}">
                                    @if(\Illuminate\Support\Str::startsWith($category->icon, ['http://', 'https://']) || \Illuminate\Support\Str::contains($category->icon, '/'))
                                        <img src="{{ $category->icon }}" alt="{{ $category->name }}" class="w-full h-full object-cover" loading="lazy">
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
                                <div class="flex items-center gap-2">
                                    <div class="flex flex-col items-center justify-center px-2.5 py-1 rounded-xl bg-slate-50 dark:bg-slate-950/40 border border-slate-100 dark:border-slate-850 w-14 shadow-inner">
                                        <span class="text-[10px] font-black text-slate-700 dark:text-slate-300">{{ $fmtThreads }}</span>
                                        <span class="text-[7.5px] font-black text-slate-400 uppercase tracking-widest leading-none mt-0.5">threads</span>
                                    </div>
                                    <div class="flex flex-col items-center justify-center px-2.5 py-1 rounded-xl bg-slate-50 dark:bg-slate-950/40 border border-slate-100 dark:border-slate-850 w-14 shadow-inner">
                                        <span class="text-[10px] font-black text-slate-700 dark:text-slate-300">{{ $fmtPosts }}</span>
                                        <span class="text-[7.5px] font-black text-slate-400 uppercase tracking-widest leading-none mt-0.5">posts</span>
                                    </div>
                                </div>

                                <!-- Last Post Activity -->
                                <div class="w-48 text-left flex items-center gap-2 px-2 py-1.5 rounded-2xl bg-slate-50/50 dark:bg-slate-950/20 border border-slate-100 dark:border-slate-850 min-w-0 shadow-inner">
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
                                           class="w-7 h-7 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-800 flex-shrink-0 shadow-sm block hover:scale-105 transition-transform">
                                             <img src="{{ $lastPostUser->avatar_url }}" class="w-full h-full object-cover" loading="lazy">
                                        </a>
                                        <div class="min-w-0 leading-tight">
                                            <!-- Latest thread title link -->
                                            <a href="{{ route('threads.show', $latestThread->slug) }}" class="text-[11px] font-extrabold text-slate-800 dark:text-slate-200 hover:text-blue-600 dark:hover:text-blue-400 truncate block max-w-[130px]" title="{{ $latestThread->title }}">
                                                {{ $latestThread->title }}
                                            </a>
                                            <!-- Timestamp & User -->
                                            <span class="text-[9px] text-slate-400 dark:text-slate-500 font-semibold block mt-0.5 truncate leading-none">
                                                <a href="{{ route('profile.show', $lastPostUser->name) }}" 
                                                                       data-user-hover="true" 
                                                                       data-user-name="{{ $lastPostUser->name }}" 
                                                                       class="hover:underline font-extrabold text-slate-600 dark:text-slate-400"
                                                                       style="{{ $lastPostUser->username_style_css }}">{{ $lastPostUser->name }}</a> • {{ $lastPostTime }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-[10px] text-slate-400 dark:text-slate-500 font-semibold tracking-tight px-1 py-1">No activity yet</span>
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
                        <div class="px-4 py-4 sm:px-6 flex items-start gap-4 hover:bg-slate-50/70 dark:hover:bg-slate-950/20 transition-all relative group hover:shadow-[inset_4px_0_0_0_#3b82f6] dark:hover:shadow-[inset_4px_0_0_0_#3b82f6]">
                            
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
                                <img src="{{ $thread->user->avatar_url }}" class="w-full h-full object-cover" loading="lazy">
                            </a>

                            <!-- Main Thread Info Column -->
                            <div class="flex-grow min-w-0 space-y-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <!-- Category Badge -->
                                    @php
                                        $catColor = 'bg-blue-50/80 text-blue-600 border-blue-200/50 dark:bg-blue-950/30 dark:text-blue-400 dark:border-blue-900/30';
                                        if ($thread->category->slug === 'general-discussion') {
                                            $catColor = 'bg-indigo-50/80 text-indigo-650 border-indigo-200/50 dark:bg-indigo-950/30 dark:text-indigo-400 dark:border-indigo-900/30';
                                        } elseif ($thread->category->slug === 'images-and-gifs' || $thread->category->slug === 'images-gifs-showroom') {
                                            $catColor = 'bg-pink-50/80 text-pink-650 border-pink-200/50 dark:bg-pink-950/30 dark:text-pink-400 dark:border-pink-900/30';
                                        } elseif ($thread->category->slug === 'web-dev-and-xenforo-styles' || $thread->category->slug === 'web-dev-xenforo-styles') {
                                            $catColor = 'bg-sky-50/80 text-sky-655 border-sky-200/50 dark:bg-sky-950/30 dark:text-sky-400 dark:border-sky-900/30';
                                        } elseif ($thread->category->slug === 'tech-support-inquiries' || $thread->category->slug === 'tech-support') {
                                            $catColor = 'bg-emerald-50/80 text-emerald-650 border-emerald-200/50 dark:bg-emerald-950/30 dark:text-emerald-400 dark:border-emerald-900/30';
                                        }
                                    @endphp
                                    <a href="{{ route('categories.show', $thread->category->slug) }}" class="inline-block text-[9px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full border {{ $catColor }}">
                                        {{ $thread->category->name }}
                                    </a>
                                    
                                    <!-- Status Badges -->
                                    @if($thread->is_pinned)
                                        <span class="inline-flex items-center gap-0.5 text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-gradient-to-r from-amber-500 to-orange-500 text-white shadow-sm">
                                            📌 Pinned
                                        </span>
                                    @endif
                                    @if($thread->is_locked)
                                        <span class="inline-flex items-center gap-0.5 text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-gradient-to-r from-rose-500 to-red-655 text-white shadow-sm">
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
                                <h3 class="text-sm sm:text-[15px] font-bold tracking-tight hover:text-blue-600 dark:hover:text-blue-405 hover:underline transition-colors leading-snug {{ $hasHighlight ? 'bg-amber-500/10 px-1 rounded' : '' }} {{ $hasTitleStyle ? 'font-black' : '' }} {{ $defaultClass }} {{ $animClass }}" style="{{ $colorStyle }}">
                                    <a href="{{ route('threads.show', $thread->slug) }}">{!! $thread->prefix_badge !!}{{ $thread->title }}</a>
                                </h3>

                                <!-- First Post Content Snippet (Content Full!) -->
                                <p class="text-xs text-slate-550 dark:text-slate-400 font-medium line-clamp-2 leading-relaxed pt-0.5 select-none">
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
                                <a href="#" onclick="openLightbox('{{ $firstAttachment->file_path }}', '{{ $thread->title }}'); return false;" class="hidden sm:block w-14 h-14 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 flex-shrink-0 bg-slate-50 hover:scale-105 hover:shadow-md transition-all duration-300">
                                    <img src="{{ $firstAttachment->file_path }}" class="w-full h-full object-cover" loading="lazy">
                                </a>
                            @endif

                            <!-- Engagement Column (Replies & Views) -->
                            <div class="hidden sm:flex items-center gap-2 flex-shrink-0 w-24 justify-end text-right px-2">
                                <div class="flex flex-col items-center justify-center p-1 bg-slate-50/50 dark:bg-slate-950/35 border border-slate-100 dark:border-slate-850 rounded-xl w-10 shadow-inner" title="Replies">
                                    <span class="text-[10px] font-black text-slate-650 dark:text-slate-300">💬</span>
                                    <span class="text-[10px] font-extrabold text-slate-700 dark:text-slate-200 leading-none mt-0.5">{{ $repliesCount }}</span>
                                </div>
                                <div class="flex flex-col items-center justify-center p-1 bg-slate-50/50 dark:bg-slate-950/35 border border-slate-100 dark:border-slate-850 rounded-xl w-10 shadow-inner" title="Views">
                                    <span class="text-[10px] font-black text-slate-650 dark:text-slate-300">👁️</span>
                                    <span class="text-[10px] font-extrabold text-slate-700 dark:text-slate-200 leading-none mt-0.5">{{ $thread->views_count }}</span>
                                </div>
                            </div>

                            <!-- Last Activity User Column -->
                            <div class="hidden md:flex items-center gap-2 w-44 flex-shrink-0 border-l border-slate-100 dark:border-slate-850 pl-4">
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
                                   class="w-7 h-7 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-800 flex-shrink-0 shadow-sm block hover:scale-105 transition-transform">
                                    <img src="{{ $lastPostUser->avatar_url }}" class="w-full h-full object-cover" loading="lazy">
                                </a>
                                <div class="min-w-0 leading-tight">
                                    <a href="{{ route('profile.show', $lastPostUser->name) }}"
                                       data-user-hover="true"
                                       data-user-name="{{ $lastPostUser->name }}"
                                       class="text-[11px] font-extrabold text-slate-800 dark:text-slate-205 hover:text-blue-600 truncate block max-w-full"
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

            <!-- Latest & Trending Media Sidebar Widget -->
            <div class="border-y sm:border border-slate-200 dark:border-slate-800 p-4 bg-white dark:bg-slate-900 rounded-none sm:rounded-2xl shadow-sm text-left">
                <!-- Header with Tabs -->
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-2 mb-3">
                    <h3 class="text-[11px] font-black tracking-[0.15em] text-slate-400 dark:text-slate-500 uppercase flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-rose-600 dark:text-rose-400 text-[16px]">photo_library</span> Media Gallery
                    </h3>
                    <!-- Tab Switchers -->
                    <div class="flex gap-2 text-[10px] font-extrabold">
                        <button id="tab-trending-btn" onclick="switchMediaTab('trending')" class="px-2 py-0.5 rounded-md text-rose-600 bg-rose-50 dark:bg-rose-950/40 transition-colors">
                            Trending
                        </button>
                        <button id="tab-latest-btn" onclick="switchMediaTab('latest')" class="px-2 py-0.5 rounded-md text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 transition-colors">
                            Latest
                        </button>
                    </div>
                </div>

                <!-- Trending Images Panel -->
                <div id="panel-trending" class="space-y-3 block">
                    @if($trendingImages->isNotEmpty())
                        <div class="grid grid-cols-3 gap-2">
                            @foreach($trendingImages as $img)
                                @php
                                    $targetUrl = '#';
                                    if ($img->thread) {
                                        $targetUrl = route('threads.show', $img->thread->slug);
                                        if ($img->post_id) {
                                            $targetUrl .= '#post-' . $img->post_id;
                                        }
                                    }
                                @endphp
                                <a href="{{ $targetUrl }}" 
                                   class="group relative aspect-square rounded-xl overflow-hidden border border-slate-200 dark:border-slate-850 bg-slate-50 dark:bg-slate-950 flex-shrink-0 block hover:scale-102 hover:shadow-md transition-all duration-300">
                                    <img src="{{ $img->file_path }}" alt="Trending" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy">
                                    <!-- Hover Overlay -->
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-1.5">
                                        <span class="text-[8px] font-bold text-white truncate w-full leading-none">
                                            {{ $img->thread ? $img->thread->title : 'View' }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="py-6 text-center text-xs text-slate-400 dark:text-slate-500 font-bold">
                            No trending images yet
                        </div>
                    @endif
                </div>

                <!-- Latest Images Panel -->
                <div id="panel-latest" class="space-y-3 hidden">
                    @if($latestImages->isNotEmpty())
                        <div class="grid grid-cols-3 gap-2">
                            @foreach($latestImages as $img)
                                @php
                                    $targetUrl = '#';
                                    if ($img->thread) {
                                        $targetUrl = route('threads.show', $img->thread->slug);
                                        if ($img->post_id) {
                                            $targetUrl .= '#post-' . $img->post_id;
                                        }
                                    }
                                @endphp
                                <a href="{{ $targetUrl }}" 
                                   class="group relative aspect-square rounded-xl overflow-hidden border border-slate-200 dark:border-slate-850 bg-slate-50 dark:bg-slate-950 flex-shrink-0 block hover:scale-102 hover:shadow-md transition-all duration-300">
                                    <img src="{{ $img->file_path }}" alt="Latest" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy">
                                    <!-- Hover Overlay -->
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-1.5">
                                        <span class="text-[8px] font-bold text-white truncate w-full leading-none">
                                            {{ $img->thread ? $img->thread->title : 'View' }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="py-6 text-center text-xs text-slate-400 dark:text-slate-500 font-bold">
                            No latest images yet
                        </div>
                    @endif
                </div>

                <!-- Footer Link to Showroom -->
                <div class="mt-3 pt-2 border-t border-slate-100 dark:border-slate-850 flex justify-end">
                    <a href="{{ route('media.index') }}" class="text-[10px] font-black text-rose-600 dark:text-rose-400 hover:underline flex items-center gap-0.5">
                        Browse Showroom <span class="material-symbols-outlined text-[12px]">arrow_forward</span>
                    </a>
                </div>
            </div>

            <!-- Tab Switching Script -->
            <script>
                function switchMediaTab(tab) {
                    const trendingBtn = document.getElementById('tab-trending-btn');
                    const latestBtn = document.getElementById('tab-latest-btn');
                    const trendingPanel = document.getElementById('panel-trending');
                    const latestPanel = document.getElementById('panel-latest');

                    if (tab === 'trending') {
                        // Activate Trending
                        trendingBtn.className = 'px-2 py-0.5 rounded-md text-rose-600 bg-rose-50 dark:bg-rose-950/40 transition-colors';
                        latestBtn.className = 'px-2 py-0.5 rounded-md text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 transition-colors';
                        
                        trendingPanel.classList.remove('hidden');
                        trendingPanel.classList.add('block');
                        latestPanel.classList.remove('block');
                        latestPanel.classList.add('hidden');
                    } else {
                        // Activate Latest
                        latestBtn.className = 'px-2 py-0.5 rounded-md text-rose-600 bg-rose-50 dark:bg-rose-950/40 transition-colors';
                        trendingBtn.className = 'px-2 py-0.5 rounded-md text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 transition-colors';
                        
                        latestPanel.classList.remove('hidden');
                        latestPanel.classList.add('block');
                        trendingPanel.classList.remove('block');
                        trendingPanel.classList.add('hidden');
                    }
                }
            </script>

            <!-- Latest Profile Posts Widget -->
            @php
                $latestProfilePosts = \App\Models\Post::whereHas('attachments')
                    ->with(['user', 'thread'])
                    ->latest()
                    ->take(4)
                    ->get();
            @endphp
            @if($latestProfilePosts->isNotEmpty())
                <div class="border-y sm:border border-slate-200 dark:border-slate-800 p-4 bg-white dark:bg-slate-900 rounded-none sm:rounded-2xl shadow-sm text-left">
                    <h3 class="text-[11px] font-black tracking-[0.15em] text-slate-400 dark:text-slate-500 uppercase mb-3 flex items-center gap-1.5 border-b border-slate-100 dark:border-slate-800 pb-2">
                        <span class="material-symbols-outlined text-blue-600 text-[16px]">rss_feed</span> Latest Profile Posts
                    </h3>
                    <div class="space-y-3">
                        @foreach($latestProfilePosts as $post)
                            @php
                                $firstAttach = $post->attachments->first();
                            @endphp
                            <div class="flex gap-3 items-start border-b border-slate-100/70 dark:border-slate-850 pb-3 last:border-0 last:pb-0">
                                <!-- User Avatar -->
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
                                   class="w-7 h-7 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-800 flex-shrink-0 block shadow-sm">
                                    <img src="{{ $post->user->avatar_url }}" class="w-full h-full object-cover" loading="lazy">
                                </a>
                                
                                <!-- Text Content and Title Info -->
                                <div class="flex-grow min-w-0 space-y-0.5">
                                    <div class="flex items-center justify-between gap-1 leading-none">
                                        <a href="{{ route('profile.show', $post->user->name) }}"
                                           data-user-hover="true"
                                           data-user-name="{{ $post->user->name }}"
                                           class="text-[11px] font-extrabold text-slate-800 dark:text-slate-200 hover:text-blue-600 hover:underline truncate"
                                           style="{{ $post->user->username_style_css }}">{{ $post->user->name }}</a>
                                        <span class="text-[9px] text-slate-400 font-bold whitespace-nowrap">{{ $post->created_at->diffForHumans(null, true) }}</span>
                                    </div>
                                    <!-- Post Content Snippet -->
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-snug line-clamp-2">
                                        {{ strip_tags($post->content) }}
                                    </p>
                                    
                                    @if($post->thread)
                                        <div class="flex items-center gap-1.5 text-[9px] font-bold text-slate-400">
                                            <span>in</span>
                                            <a href="{{ route('threads.show', $post->thread->slug) }}" class="text-blue-600 dark:text-blue-400 hover:underline truncate max-w-[120px]">{{ $post->thread->title }}</a>
                                        </div>
                                    @endif
                                </div>

                                <!-- Compact Attachment Thumbnail -->
                                @if($firstAttach)
                                    <a href="#" onclick="openLightbox('{{ $firstAttach->file_path }}', '{{ $post->thread ? $post->thread->title : 'Attachment' }}'); return false;" class="w-10 h-10 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-800 bg-slate-50 flex-shrink-0 block hover:scale-105 transition-transform shadow-sm">
                                        <img src="{{ $firstAttach->file_path }}" class="w-full h-full object-cover" loading="lazy">
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Recent Activity Sidebar -->
            <div class="border-y sm:border border-slate-200 dark:border-slate-800 p-4 bg-white dark:bg-slate-900 rounded-none sm:rounded-2xl shadow-sm text-left">
                <h3 class="text-[11px] font-black tracking-[0.15em] text-slate-400 dark:text-slate-500 uppercase mb-3 flex items-center gap-1.5 border-b border-slate-100 dark:border-slate-800 pb-2">
                    <span class="material-symbols-outlined text-blue-600 text-[16px]">electric_bolt</span> Recent Activity
                </h3>
                <div class="space-y-3">
                    @foreach($activeThreads as $activeThread)
                        @php
                            $activeLastPost = $activeThread->lastPost;
                            $activeLastUser = $activeLastPost ? $activeLastPost->user : $activeThread->user;
                            $activeLastTime = $activeLastPost ? $activeLastPost->created_at->diffForHumans(null, true) : $activeThread->created_at->diffForHumans(null, true);
                        @endphp
                        <div class="flex gap-2.5 items-start border-b border-slate-100/70 dark:border-slate-850 pb-2.5 last:border-0 last:pb-0">
                            <!-- Thread Creator Avatar -->
                            <a href="{{ route('profile.show', $activeThread->user->name) }}"
                               data-user-hover="true"
                               data-user-name="{{ $activeThread->user->name }}"
                               data-user-badge="{{ $activeThread->user->title_badge }}"
                               data-user-joined="{{ $activeThread->user->created_at->format('M d, Y') }}"
                               data-user-threads="{{ $activeThread->user->threads()->count() }}"
                               data-user-posts="{{ $activeThread->user->posts()->count() }}"
                               data-user-uploads="{{ $activeThread->user->attachments()->count() }}"
                               data-user-avatar="{{ $activeThread->user->avatar_url }}"
                               data-user-banner="{{ $activeThread->user->banner_color }}"
                               data-user-banner-path="{{ $activeThread->user->banner_path }}"
                               class="w-7 h-7 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-800 flex-shrink-0 block shadow-sm mt-0.5">
                                <img src="{{ $activeThread->user->avatar_url }}" class="w-full h-full object-cover" loading="lazy">
                            </a>

                            <!-- Thread Info -->
                            <div class="flex-grow min-w-0 space-y-0.5">
                                <h4 class="font-extrabold text-slate-800 dark:text-slate-200 hover:text-blue-600 dark:hover:text-blue-400 transition-colors text-xs leading-snug truncate">
                                    <a href="{{ route('threads.show', $activeThread->slug) }}">{!! $activeThread->prefix_badge !!}{{ $activeThread->title }}</a>
                                </h4>
                                
                                <!-- Detail Row -->
                                <div class="flex items-center justify-between text-[9px] font-bold text-slate-400 leading-none">
                                    <div class="flex items-center gap-1.5">
                                        <span class="px-1 py-0.2 rounded bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 font-extrabold text-[8px] uppercase">
                                            {{ $activeThread->category->name }}
                                        </span>
                                        <span>💬 {{ $activeThread->posts_count }} replies</span>
                                    </div>
                                    
                                    <!-- Last Poster Info -->
                                    <span class="text-right">
                                        last: <a href="{{ route('profile.show', $activeLastUser->name) }}"
                                                 data-user-hover="true"
                                                 data-user-name="{{ $activeLastUser->name }}"
                                                 class="text-slate-655 dark:text-slate-355 hover:underline font-extrabold"
                                                 style="{{ $activeLastUser->username_style_css }}">{{ $activeLastUser->name }}</a> • {{ $activeLastTime }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
