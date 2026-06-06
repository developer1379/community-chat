@extends('layouts.app')

@section('content')
<div class="space-y-6 w-full">

    <!-- Quick Features Shortcuts Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
        <!-- Card 1 -->
        <a href="{{ route('categories.show', 'general-discussion') }}" class="group relative bg-white rounded-3xl p-6 shadow-sm border border-slate-200 hover:border-blue-200 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-start gap-5 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-50 to-transparent rounded-bl-full -z-0 opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10 w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300 flex-shrink-0 shadow-sm">
                <span class="material-symbols-outlined text-2xl">forum</span>
            </div>
            <div class="relative z-10 text-left min-w-0 mt-1">
                <h3 class="text-base font-extrabold text-slate-900 truncate group-hover:text-blue-600 transition-colors">General Discussion</h3>
                <p class="text-sm text-slate-500 mt-1.5 leading-relaxed">Top-tier coding, frameworks & server discussion.</p>
            </div>
        </a>

        <!-- Card 2 -->
        <a href="{{ route('categories.show', 'images-and-gifs') }}" class="group relative bg-white rounded-3xl p-6 shadow-sm border border-slate-200 hover:border-pink-200 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-start gap-5 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-pink-50 to-transparent rounded-bl-full -z-0 opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10 w-14 h-14 rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center border border-pink-100 group-hover:bg-pink-600 group-hover:text-white transition-colors duration-300 flex-shrink-0 shadow-sm">
                <span class="material-symbols-outlined text-2xl">photo_library</span>
            </div>
            <div class="relative z-10 text-left min-w-0 mt-1">
                <h3 class="text-base font-extrabold text-slate-900 truncate group-hover:text-pink-600 transition-colors">Media Showroom</h3>
                <p class="text-sm text-slate-500 mt-1.5 leading-relaxed">Upload visual guides & animated memes securely.</p>
            </div>
        </a>

        <!-- Card 3 -->
        <a href="#" onclick="openSearchModal(); return false;" class="group relative bg-white rounded-3xl p-6 shadow-sm border border-slate-200 hover:border-indigo-200 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-start gap-5 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-indigo-50 to-transparent rounded-bl-full -z-0 opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10 w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300 flex-shrink-0 shadow-sm">
                <span class="material-symbols-outlined text-2xl">shield_person</span>
            </div>
            <div class="relative z-10 text-left min-w-0 mt-1">
                <h3 class="text-base font-extrabold text-slate-900 truncate group-hover:text-indigo-600 transition-colors">Global Directory</h3>
                <p class="text-sm text-slate-500 mt-1.5 leading-relaxed">Browse experts, authors & trusted moderators.</p>
            </div>
        </a>
    </div>

    <!-- Header App Bar Area -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 text-left">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">{{ config('app.name', 'XenProfessional') }}</h1>
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

    <!-- Featured Threads Section (Slider Interface) -->
    @if(isset($featuredThreads) && $featuredThreads->isNotEmpty())
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 shadow-md text-left mb-8 relative group/slider">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xs font-black text-slate-700 dark:text-slate-350 uppercase tracking-[0.2em] flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm text-blue-600 dark:text-blue-400">star</span> Featured Threads
                </h2>
                <!-- Slider controls navigation buttons -->
                <div class="flex items-center gap-2">
                    <button onclick="prevSlide()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-750 text-slate-700 dark:text-slate-200 flex items-center justify-center border border-slate-200/50 dark:border-slate-700/50 shadow-sm transition-all focus:outline-none">
                        <span class="material-symbols-outlined text-base">chevron_left</span>
                    </button>
                    <button onclick="nextSlide()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-750 text-slate-700 dark:text-slate-200 flex items-center justify-center border border-slate-200/50 dark:border-slate-700/50 shadow-sm transition-all focus:outline-none">
                        <span class="material-symbols-outlined text-base">chevron_right</span>
                    </button>
                </div>
            </div>

            <!-- Slider Viewport Window -->
            <div class="overflow-hidden w-full rounded-2xl">
                <div id="featured-slider" class="flex transition-transform duration-500 ease-out gap-5">
                    @foreach($featuredThreads as $thread)
                        @php
                            $firstAttachment = \App\Models\Attachment::where('thread_id', $thread->id)->first();
                            $previewUrl = $firstAttachment ? $firstAttachment->file_path : 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=600&q=80';
                            $repliesCount = $thread->posts->count();
                        @endphp
                        <div class="flex-none w-full md:w-[calc(33.333%-14px)] flex flex-col rounded-2xl overflow-hidden bg-slate-50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800 group hover:shadow-lg transition-all duration-300">
                            <div class="h-40 w-full relative overflow-hidden bg-slate-200 dark:bg-slate-900">
                                <img src="{{ $previewUrl }}" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-500" alt="">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/10 to-transparent"></div>
                                
                                <!-- Overlay Info -->
                                <div class="absolute inset-x-0 bottom-0 p-3 flex items-center justify-between text-white">
                                    <div class="flex items-center gap-1.5 min-w-0">
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
                                           class="w-6 h-6 rounded-full overflow-hidden border border-white/20 flex-shrink-0 block hover:scale-105 transition-transform">
                                            <img src="{{ $thread->user->avatar_url }}" class="w-full h-full object-cover">
                                        </a>
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
                                           class="text-[9px] font-bold truncate hover:underline">{{ $thread->user->name }}</a>
                                    </div>
                                    <div class="flex items-center gap-2 text-[8px] font-bold opacity-90">
                                        <span>💬 {{ $repliesCount }}</span>
                                        <span>👁️ {{ $thread->views_count }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3.5 flex-grow flex flex-col justify-between space-y-2 text-left">
                                <div class="space-y-1">
                                    <span class="inline-block text-[8px] font-black uppercase tracking-wider px-2 py-0.5 rounded bg-blue-100 dark:bg-blue-950/50 text-blue-700 dark:text-blue-450 border border-blue-200/40 dark:border-blue-900/30">
                                        {{ $thread->category->name }}
                                    </span>
                                    <h3 class="font-extrabold text-slate-900 dark:text-white text-xs sm:text-sm group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-2 leading-snug">
                                        <a href="{{ route('threads.show', $thread->slug) }}">{{ $thread->title }}</a>
                                    </h3>
                                </div>
                                <span class="text-[9px] text-slate-400 dark:text-slate-500 font-bold block">{{ $thread->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <script>
            let currentOffset = 0;
            function getSlideWidth() {
                const slider = document.getElementById('featured-slider');
                if (!slider || !slider.firstElementChild) return 0;
                return slider.firstElementChild.offsetWidth + 20; // width + gap
            }
            function getMaxOffset() {
                const slider = document.getElementById('featured-slider');
                if (!slider) return 0;
                const totalWidth = slider.scrollWidth;
                const viewportWidth = slider.parentElement.offsetWidth;
                return Math.max(0, totalWidth - viewportWidth);
            }
            function nextSlide() {
                const slider = document.getElementById('featured-slider');
                const max = getMaxOffset();
                currentOffset += getSlideWidth();
                if (currentOffset > max) {
                    currentOffset = 0; // Wrap around to start
                }
                slider.style.transform = `translateX(-${currentOffset}px)`;
            }
            function prevSlide() {
                const slider = document.getElementById('featured-slider');
                const max = getMaxOffset();
                currentOffset -= getSlideWidth();
                if (currentOffset < 0) {
                    currentOffset = Math.floor(max / getSlideWidth()) * getSlideWidth(); // Wrap around to end
                }
                slider.style.transform = `translateX(-${currentOffset}px)`;
            }
            // Auto slide every 8 seconds
            setInterval(() => {
                nextSlide();
            }, 8000);
        </script>
    @endif

    <!-- Most Reactions Circular Highlights Section -->
    @if(isset($topReactedThreads) && $topReactedThreads->isNotEmpty())
        <div class="mb-8 text-left bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 shadow-md">
            <h2 class="text-xs font-black text-slate-700 dark:text-slate-350 uppercase tracking-[0.2em] mb-4 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm text-pink-600 dark:text-pink-400">local_fire_department</span> Most reactions - Past 7 days
            </h2>
            
            <div class="flex items-center gap-6 overflow-x-auto pb-2 scrollbar-thin scrollbar-thumb-slate-200 dark:scrollbar-thumb-slate-800 scrollbar-track-transparent">
                @foreach($topReactedThreads as $thread)
                    @php
                        $firstAttach = $thread->attachments->first();
                        $previewUrl = $firstAttach ? $firstAttach->file_path : 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=150&q=80';
                    @endphp
                    <a href="{{ route('threads.show', $thread->slug) }}" class="flex flex-col items-center flex-shrink-0 group text-center select-none w-16">
                        <div class="relative w-14 h-14 rounded-full overflow-hidden p-0.5 border-2 border-pink-500/85 dark:border-pink-600/85 group-hover:scale-105 transition-transform duration-300 shadow-sm bg-white dark:bg-slate-950">
                            <img src="{{ $previewUrl }}" class="w-full h-full object-cover rounded-full animate-fade-in" alt="reaction thumb">
                            <!-- Overlay user avatar mini bubble at bottom right -->
                            <div class="absolute -bottom-0.5 -right-0.5 w-5 h-5 rounded-full overflow-hidden border border-white dark:border-slate-900 bg-white shadow-sm">
                                <img src="{{ $thread->user->avatar_url }}" class="w-full h-full object-cover">
                            </div>
                        </div>
                        <p class="text-[9px] font-black text-slate-650 dark:text-slate-350 mt-2 truncate w-full group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                            {{ $thread->user->name }}
                        </p>
                        <span class="text-[8px] font-black px-1.5 py-0.5 rounded-full bg-pink-50 dark:bg-pink-950/40 text-pink-600 dark:text-pink-400 border border-pink-100 dark:border-pink-900/30 mt-0.5 leading-none shadow-sm flex items-center gap-0.5">
                            🔥 {{ $thread->total_reacts ?? 15 }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Grid Layout Container for Boards and Sidebar -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Main Boards Column (8 Cols) -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Unified Community Boards Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-md overflow-hidden text-left mb-8">
                <!-- Section Header -->
                <div class="bg-slate-50 dark:bg-slate-950/40 px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                    <h2 class="text-xs font-black text-slate-700 dark:text-slate-350 uppercase tracking-[0.2em] flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm text-blue-600 dark:text-blue-400">forum</span> Discussion Boards
                    </h2>
                    <p class="text-[10px] sm:text-xs text-slate-400 mt-1">Browse and participate in our professional coding, design, and media showcase categories.</p>
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
                        
                        <div class="px-6 py-4 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-slate-50/50 dark:hover:bg-slate-950/20 transition-all">
                            
                            <!-- Left: Icon & Category details -->
                            <div class="flex items-start gap-4 flex-grow min-w-0 md:max-w-[50%]">
                                <!-- Category Icon -->
                                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/30 flex items-center justify-center text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-900/30 shadow-sm flex-shrink-0 overflow-hidden mt-0.5">
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
                                <div class="min-w-0 leading-tight space-y-1">
                                    <h3 class="font-extrabold text-slate-900 dark:text-white text-sm sm:text-base hover:text-blue-600 dark:hover:text-blue-400 transition-colors truncate">
                                        <a href="{{ route('categories.show', $category->slug) }}">{{ $category->name }}</a>
                                    </h3>
                                    <p class="text-xs text-slate-450 dark:text-slate-400 font-medium line-clamp-1 leading-normal">{{ $category->description }}</p>
                                </div>
                            </div>

                            <!-- Right side: stats & last action (Only visible on MD grids and larger) -->
                            <div class="flex items-center gap-6 sm:gap-10 md:gap-14 flex-shrink-0 justify-between md:justify-end">
                                
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
                                                                       class="hover:underline font-extrabold text-slate-550 dark:text-slate-450">{{ $lastPostUser->name }}</a>
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

            <!-- Latest and Viral Threads Grid Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                
                <!-- Latest Threads Widget -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-md overflow-hidden text-left flex flex-col">
                    <div class="bg-slate-50 dark:bg-slate-950/40 px-5 py-3.5 border-b border-slate-200 dark:border-slate-800 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm text-blue-600 dark:text-blue-400">schedule</span>
                        <h3 class="text-xs font-black text-slate-700 dark:text-slate-350 uppercase tracking-[0.15em]">Latest Threads</h3>
                    </div>
                    <div class="p-4 divide-y divide-slate-100 dark:divide-slate-800 flex-grow">
                        @forelse($latestThreads as $thread)
                            <div class="py-3 first:pt-0 last:pb-0 flex items-start gap-3">
                                <a href="{{ route('profile.show', $thread->user->name) }}" data-user-hover="true" data-user-name="{{ $thread->user->name }}" class="w-8 h-8 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-850 flex-shrink-0">
                                    <img src="{{ $thread->user->avatar_url }}" class="w-full h-full object-cover">
                                </a>
                                <div class="min-w-0">
                                    <a href="{{ route('threads.show', $thread->slug) }}" class="text-xs font-extrabold text-slate-850 dark:text-slate-200 hover:text-blue-600 dark:hover:text-blue-450 transition-colors line-clamp-1 leading-snug">
                                        {{ $thread->title }}
                                    </a>
                                    <span class="text-[9px] font-semibold text-slate-400 dark:text-slate-500 block mt-0.5">
                                        by <a href="{{ route('profile.show', $thread->user->name) }}" data-user-hover="true" data-user-name="{{ $thread->user->name }}" class="hover:underline font-bold text-slate-500 dark:text-slate-400">{{ $thread->user->name }}</a> • {{ $thread->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 text-center py-4">No latest threads found</p>
                        @endforelse
                    </div>
                </div>

                <!-- Viral Threads Widget -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-md overflow-hidden text-left flex flex-col">
                    <div class="bg-slate-50 dark:bg-slate-950/40 px-5 py-3.5 border-b border-slate-200 dark:border-slate-800 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm text-pink-600 dark:text-pink-400">trending_up</span>
                        <h3 class="text-xs font-black text-slate-700 dark:text-slate-350 uppercase tracking-[0.15em]">Viral Threads</h3>
                    </div>
                    <div class="p-4 divide-y divide-slate-100 dark:divide-slate-800 flex-grow">
                        @forelse($viralThreads as $thread)
                            <div class="py-3 first:pt-0 last:pb-0 flex items-start gap-3">
                                <a href="{{ route('profile.show', $thread->user->name) }}" data-user-hover="true" data-user-name="{{ $thread->user->name }}" class="w-8 h-8 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-850 flex-shrink-0">
                                    <img src="{{ $thread->user->avatar_url }}" class="w-full h-full object-cover">
                                </a>
                                <div class="min-w-0">
                                    <a href="{{ route('threads.show', $thread->slug) }}" class="text-xs font-extrabold text-slate-850 dark:text-slate-200 hover:text-blue-600 dark:hover:text-blue-450 transition-colors line-clamp-1 leading-snug">
                                        {{ $thread->title }}
                                    </a>
                                    <span class="text-[9px] font-semibold text-slate-400 dark:text-slate-500 block mt-0.5">
                                        🔥 {{ $thread->views_count }} views • by <a href="{{ route('profile.show', $thread->user->name) }}" data-user-hover="true" data-user-name="{{ $thread->user->name }}" class="hover:underline font-bold text-slate-500 dark:text-slate-400">{{ $thread->user->name }}</a>
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 text-center py-4">No viral threads found</p>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- Most Reactions / Most Liked Thread Section -->
            @if(isset($mostLikedThread))
                @php
                    $mostLikedAttachment = \App\Models\Attachment::where('thread_id', $mostLikedThread->id)->first();
                    $mostLikedPreviewUrl = $mostLikedAttachment ? $mostLikedAttachment->file_path : 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=800&q=80';
                @endphp
                <div class="mt-6 text-left">
                    <h2 class="text-xs font-black text-slate-700 dark:text-slate-350 uppercase tracking-[0.2em] mb-4 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-sm text-amber-500">favorite</span> Most reactions - Past 7 days
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                        <!-- Main Thread Left Box (Spans 9 columns in its own white/dark card) -->
                        <div class="md:col-span-9 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 shadow-md flex flex-col justify-between space-y-4">
                            <div class="space-y-3">
                                <!-- Category/Forum Name -->
                                <span class="text-xs font-extrabold uppercase text-blue-600 dark:text-blue-400 tracking-wider block">
                                    {{ $mostLikedThread->category->name }}
                                </span>
                                
                                <!-- Thread Creator Row -->
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('profile.show', $mostLikedThread->user->name) }}"
                                       data-user-hover="true"
                                       data-user-name="{{ $mostLikedThread->user->name }}"
                                       data-user-badge="{{ $mostLikedThread->user->title_badge }}"
                                       data-user-joined="{{ $mostLikedThread->user->created_at->format('M d, Y') }}"
                                       data-user-threads="{{ $mostLikedThread->user->threads()->count() }}"
                                       data-user-posts="{{ $mostLikedThread->user->posts()->count() }}"
                                       data-user-uploads="{{ $mostLikedThread->user->attachments()->count() }}"
                                       data-user-avatar="{{ $mostLikedThread->user->avatar_url }}"
                                       data-user-banner="{{ $mostLikedThread->user->banner_color }}"
                                       data-user-banner-path="{{ $mostLikedThread->user->banner_path }}"
                                       class="w-6 h-6 rounded-full overflow-hidden border border-slate-200 dark:border-slate-800 flex-shrink-0 block">
                                        <img src="{{ $mostLikedThread->user->avatar_url }}" class="w-full h-full object-cover">
                                    </a>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 font-semibold flex flex-wrap items-center gap-1">
                                        <a href="{{ route('profile.show', $mostLikedThread->user->name) }}"
                                           data-user-hover="true"
                                           data-user-name="{{ $mostLikedThread->user->name }}"
                                           class="text-red-650 dark:text-red-500 hover:underline font-black">{{ $mostLikedThread->user->name }}</a>
                                        <span>• {{ $mostLikedThread->created_at->diffForHumans() }}</span>
                                        <span>• {{ $mostLikedThread->category->name }}</span>
                                        <span>• {{ $mostLikedThread->user->title_badge }}</span>
                                    </div>
                                </div>
                                
                                <!-- Yellow/Orange Title -->
                                <h3 class="text-base sm:text-lg font-black italic tracking-wide text-amber-500 dark:text-amber-400 uppercase leading-snug">
                                    <a href="{{ route('threads.show', $mostLikedThread->slug) }}" class="hover:underline">
                                        {{ $mostLikedThread->title }}
                                    </a>
                                </h3>
                            </div>
                            
                            <!-- Large Main Attachment Image -->
                            <div class="relative rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 aspect-[16/10] w-full shadow-sm">
                                <a href="{{ route('threads.show', $mostLikedThread->slug) }}" class="block w-full h-full group">
                                    <img src="{{ $mostLikedPreviewUrl }}" class="w-full h-full object-cover group-hover:scale-[1.01] transition-all duration-550" alt="{{ $mostLikedThread->title }}">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                                        <span class="text-white text-xs font-bold flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">visibility</span> View Thread
                                        </span>
                                    </div>
                                </a>
                            </div>

                            <!-- Footer reactions bar -->
                            <div class="flex items-center gap-2 pt-2 border-t border-slate-100 dark:border-slate-850">
                                <span class="flex items-center -space-x-1">
                                    <span class="w-5 h-5 rounded-full bg-blue-500 text-white flex items-center justify-center text-[10px] shadow-sm border border-white dark:border-slate-900 font-bold">👍</span>
                                    <span class="w-5 h-5 rounded-full bg-red-500 text-white flex items-center justify-center text-[10px] shadow-sm border border-white dark:border-slate-900 font-bold">❤️</span>
                                    <span class="w-5 h-5 rounded-full bg-yellow-500 text-white flex items-center justify-center text-[10px] shadow-sm border border-white dark:border-slate-900 font-bold">😆</span>
                                </span>
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">
                                    {{ $mostLikedThread->total_reacts ?? 35 }} reactions
                                </span>
                            </div>
                        </div>
                        
                        <!-- Side Images (Spans 3 columns of separate standalone cards) -->
                        <div class="md:col-span-3 flex flex-row md:flex-col gap-4 overflow-x-auto md:overflow-x-visible pb-2 md:pb-0 scrollbar-thin scrollbar-thumb-slate-200 dark:scrollbar-thumb-slate-800 scrollbar-track-transparent">
                            @foreach($featuredThreads->take(3) as $ft)
                                @php
                                    $ftAttachment = \App\Models\Attachment::where('thread_id', $ft->id)->first();
                                    $ftPreviewUrl = $ftAttachment ? $ftAttachment->file_path : 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=600&q=80';
                                @endphp
                                <a href="{{ route('threads.show', $ft->slug) }}" class="flex-shrink-0 w-28 md:w-full group block bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-2 shadow-sm hover:shadow-md transition-shadow">
                                    <div class="relative aspect-[16/10] rounded-xl overflow-hidden bg-white dark:bg-slate-950 group-hover:border-blue-500 transition-all">
                                        <img src="{{ $ftPreviewUrl }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="{{ $ft->title }}">
                                        <!-- Title Overlay -->
                                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center p-2 text-center">
                                            <p class="text-[8px] font-extrabold text-white line-clamp-2 leading-tight">{{ $ft->title }}</p>
                                        </div>
                                    </div>
                                    <p class="text-[9px] font-extrabold text-slate-500 dark:text-slate-400 mt-2 px-1 line-clamp-1 group-hover:text-blue-650 dark:group-hover:text-blue-400 transition-colors md:block hidden">
                                        {{ $ft->title }}
                                    </p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar Section (Right - 4 Cols) -->
        <div class="lg:col-span-4 space-y-6">
            @auth
                <!-- Visitor Panel / Profile Info Card -->
                <div class="border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 rounded-3xl overflow-hidden shadow-md text-left">
                    <!-- User Cover Banner (Mini) -->
                    <div class="h-20 w-full relative bg-slate-100 dark:bg-slate-950 border-b border-slate-200 dark:border-slate-800" style="background-color: {{ auth()->user()->banner_color ?? '#3b82f6' }}; background-image: url('{{ auth()->user()->banner_path }}'); background-size: cover; background-position: center;">
                        <div class="absolute inset-0 bg-black/10"></div>
                    </div>
                    
                    <div class="px-5 pb-5 pt-3 relative">
                        <!-- User Avatar overlay -->
                        <a href="{{ route('profile.show', auth()->user()->name) }}" 
                           data-user-hover="true" 
                           data-user-name="{{ auth()->user()->name }}" 
                           data-user-badge="{{ auth()->user()->title_badge }}" 
                           data-user-joined="{{ auth()->user()->created_at->format('M d, Y') }}" 
                           data-user-threads="{{ auth()->user()->threads()->count() }}" 
                           data-user-posts="{{ auth()->user()->posts()->count() }}" 
                           data-user-uploads="{{ auth()->user()->attachments()->count() }}" 
                           data-user-avatar="{{ auth()->user()->avatar_url }}" 
                           data-user-banner="{{ auth()->user()->banner_color }}"
                           data-user-banner-path="{{ auth()->user()->banner_path }}"
                           class="absolute -top-10 left-4 w-16 h-16 rounded-2xl overflow-hidden border-4 border-white dark:border-slate-900 shadow-md bg-white block hover:shadow transition-shadow">
                            <img src="{{ auth()->user()->avatar_url }}" class="w-full h-full object-cover">
                        </a>
                        
                        <!-- User Name and Badges -->
                        <div class="pl-20 min-h-[40px] flex flex-col justify-center">
                            <h3 class="font-extrabold text-slate-900 dark:text-white text-sm sm:text-base leading-tight truncate">
                                <a href="{{ route('profile.show', auth()->user()->name) }}" 
                                   data-user-hover="true" 
                                   data-user-name="{{ auth()->user()->name }}" 
                                   data-user-badge="{{ auth()->user()->title_badge }}" 
                                   data-user-joined="{{ auth()->user()->created_at->format('M d, Y') }}" 
                                   data-user-threads="{{ auth()->user()->threads()->count() }}" 
                                   data-user-posts="{{ auth()->user()->posts()->count() }}" 
                                   data-user-uploads="{{ auth()->user()->attachments()->count() }}" 
                                   data-user-avatar="{{ auth()->user()->avatar_url }}" 
                                   data-user-banner="{{ auth()->user()->banner_color }}"
                                   data-user-banner-path="{{ auth()->user()->banner_path }}"
                                   class="hover:underline">{{ auth()->user()->name }}</a>
                            </h3>
                            @php $tier = auth()->user()->computed_anime_tier; @endphp
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded leading-none text-white shadow-sm" style="background-color: {{ $tier['color'] }}">
                                    {{ $tier['name'] }}
                                </span>
                            </div>
                        </div>

                        <!-- Stats grid -->
                        <div class="grid grid-cols-3 gap-2.5 mt-5 pt-4 border-t border-slate-100 dark:border-slate-800/60 text-center">
                            <div class="space-y-0.5">
                                <span class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest block">Threads</span>
                                <span class="text-xs font-black text-slate-700 dark:text-slate-350 block">{{ auth()->user()->threads()->count() }}</span>
                            </div>
                            <div class="space-y-0.5">
                                <span class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest block">Replies</span>
                                <span class="text-xs font-black text-slate-700 dark:text-slate-350 block">{{ auth()->user()->posts()->count() }}</span>
                            </div>
                            <div class="space-y-0.5">
                                <span class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest block">Coins</span>
                                <span class="text-xs font-black text-amber-600 dark:text-amber-400 block flex items-center justify-center gap-0.5 leading-none">
                                    <span class="material-symbols-outlined text-[12px] text-amber-500 animate-pulse">monetization_on</span>
                                    {{ auth()->user()->coins }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endauth

            <!-- Interactive Quick Start Guide Card -->
            <div class="border border-slate-200 p-6 sm:p-8 bg-white rounded-[2rem] shadow-sm text-left relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-blue-500/10 rounded-full blur-2xl pointer-events-none group-hover:bg-blue-500/20 transition-colors"></div>
                <h3 class="text-xs font-black tracking-[0.2em] text-slate-400 uppercase mb-4 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100">
                        <span class="material-symbols-outlined text-[14px]">military_tech</span>
                    </span>
                    Ranking Guide
                </h3>
                <p class="text-sm text-slate-500 font-medium leading-relaxed mb-6">
                    Earn Activity Points to level up your profile tier, unlock custom animated badges, and dominate the global leaderboard!
                </p>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 hover:bg-white border border-slate-100 hover:border-blue-100 transition-all duration-300 hover:shadow-sm group/item">
                        <span class="flex items-center gap-3 text-sm font-bold text-slate-700">
                            <span class="w-8 h-8 rounded-xl bg-white flex items-center justify-center border border-slate-200 shadow-sm text-slate-400 group-hover/item:text-blue-500 transition-colors">
                                <span class="material-symbols-outlined text-sm">edit_document</span>
                            </span>
                            Start a Thread
                        </span>
                        <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-600 font-extrabold text-xs border border-emerald-100 shadow-sm">+10 pts</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 hover:bg-white border border-slate-100 hover:border-blue-100 transition-all duration-300 hover:shadow-sm group/item">
                        <span class="flex items-center gap-3 text-sm font-bold text-slate-700">
                            <span class="w-8 h-8 rounded-xl bg-white flex items-center justify-center border border-slate-200 shadow-sm text-slate-400 group-hover/item:text-blue-500 transition-colors">
                                <span class="material-symbols-outlined text-sm">chat_bubble</span>
                            </span>
                            Post a Reply
                        </span>
                        <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-600 font-extrabold text-xs border border-emerald-100 shadow-sm">+5 pts</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 hover:bg-white border border-slate-100 hover:border-blue-100 transition-all duration-300 hover:shadow-sm group/item">
                        <span class="flex items-center gap-3 text-sm font-bold text-slate-700">
                            <span class="w-8 h-8 rounded-xl bg-white flex items-center justify-center border border-slate-200 shadow-sm text-slate-400 group-hover/item:text-pink-500 transition-colors">
                                <span class="material-symbols-outlined text-sm">favorite</span>
                            </span>
                            Earn Reaction
                        </span>
                        <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-600 font-extrabold text-xs border border-emerald-100 shadow-sm">+2 pts</span>
                    </div>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100">
                    <a href="{{ route('rules') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline transition-colors">
                        View complete ranking tiers <span class="material-symbols-outlined text-xs">arrow_forward</span>
                    </a>
                </div>
            </div>

            <!-- Latest Profile Posts Widget -->
            @php
                $latestProfilePosts = \App\Models\Post::whereHas('attachments')
                    ->with(['user', 'thread'])
                    ->latest()
                    ->take(3)
                    ->get();
            @endphp
            @if($latestProfilePosts->isNotEmpty())
                <div class="border border-slate-200 dark:border-slate-800 p-5 bg-white dark:bg-slate-900 rounded-3xl shadow-sm text-left">
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
                                           class="text-xs font-extrabold text-blue-600 dark:text-blue-450 hover:underline">{{ $post->user->name }}</a>
                                        <span class="text-[8px] text-slate-400 font-bold block mt-0.5">{{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <!-- Post snippet text -->
                                <p class="text-xs text-slate-650 dark:text-slate-350 leading-relaxed line-clamp-2">
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

            <!-- Active Members Sidebar -->
            <div class="border border-slate-200 p-5 bg-white rounded-2xl shadow-sm text-left">
                <h3 class="text-sm font-extrabold tracking-wider text-slate-500 uppercase mb-4 flex items-center justify-between">
                    <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-blue-600 text-sm">group</span> Active Members</span>
                    <span class="text-xs px-1.5 py-0.5 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-250 font-bold leading-none">Online</span>
                </h3>
                <div class="grid grid-cols-2 gap-2">
                    @foreach($onlineUsers as $user)
                        <a href="{{ route('profile.show', $user->name) }}" 
                           data-user-hover="true" 
                           data-user-name="{{ $user->name }}" 
                           data-user-badge="{{ $user->title_badge }}" 
                           data-user-joined="{{ $user->created_at->format('M d, Y') }}" 
                           data-user-threads="{{ $user->threads()->count() }}" 
                           data-user-posts="{{ $user->posts()->count() }}" 
                           data-user-uploads="{{ $user->attachments()->count() }}" 
                           data-user-avatar="{{ $user->avatar_url }}" 
                           data-user-banner="{{ $user->banner_color }}"
                           data-user-banner-path="{{ $user->banner_path }}"
                           class="flex items-center gap-1.5 p-1.5 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-all group">
                            <div class="relative w-6 h-6 rounded-2xl overflow-hidden border border-slate-200 flex-shrink-0">
                                <img src="{{ $user->avatar_url }}" class="w-full h-full object-cover" alt="avatar">
                                <span class="absolute bottom-0 right-0 w-2 h-2 rounded-2xl bg-emerald-500 border border-white"></span>
                            </div>
                            <div class="truncate leading-tight text-left">
                                <p class="text-xs font-bold text-slate-700 group-hover:text-blue-600 truncate">{{ $user->name }}</p>
                                <span class="text-[10px] text-blue-600 font-bold truncate leading-none mt-0.5 block">{{ $user->title_badge }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Recent Activity Sidebar -->
            <div class="border border-slate-200 p-5 bg-white rounded-2xl shadow-sm text-left">
                <h3 class="text-sm font-extrabold tracking-wider text-slate-500 uppercase mb-4 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-blue-600 text-sm">electric_bolt</span> Recent Activity
                </h3>
                <div class="space-y-3.5">
                    @foreach($activeThreads as $activeThread)
                        <div class="text-xs leading-normal border-b border-slate-100 pb-3 last:border-0 last:pb-0">
                            <h4 class="font-bold text-slate-800 hover:text-blue-600 transition-colors text-sm">
                                <a href="{{ route('threads.show', $activeThread->slug) }}">{{ $activeThread->title }}</a>
                            </h4>
                            <div class="flex items-center gap-2 mt-1.5 text-slate-450 font-bold text-xs">
                                <span class="px-1.5 py-0.5 rounded-2xl bg-blue-50 text-blue-600 font-bold border border-blue-150">
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
