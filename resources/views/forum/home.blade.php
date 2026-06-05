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

    <!-- Sleek Trending Carousel Banner -->
    <!-- Sleek Trending Carousel Banner -->
    <div class="relative rounded-3xl overflow-hidden border border-slate-200 bg-white h-64 sm:h-80 shadow-lg mb-10 group">
        <!-- Slides Wrapper -->
        <div id="carousel-wrapper" class="relative w-full h-full flex transition-transform duration-700 ease-[cubic-bezier(0.25,1,0.5,1)]" style="width: 300%;">
            
            <!-- Slide 1: General Discussion -->
            <div class="w-full h-full relative flex-shrink-0 flex items-center p-8 sm:p-16">
                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&w=1200&q=80'); filter: brightness(0.4);"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 to-slate-900/10"></div>
                <div class="relative z-10 max-w-2xl text-left space-y-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-blue-500/20 backdrop-blur-md text-blue-300 border border-blue-400/30 uppercase tracking-widest shadow-sm">
                        <span class="material-symbols-outlined text-sm animate-pulse text-blue-400">local_fire_department</span> Trending Topic
                    </span>
                    <h2 class="text-3xl sm:text-5xl font-black text-white leading-tight drop-shadow-md">
                        Building Future Community Hubs with High-End Aesthetics
                    </h2>
                    <p class="text-sm sm:text-lg text-slate-200 line-clamp-2 font-medium max-w-xl">
                        Discover the ultimate blueprints for creating blazingly fast forum engines with sleek glassmorphism, HSL tailormade colors, and advanced caching layers.
                    </p>
                    <div class="pt-4">
                        <a href="{{ route('categories.show', 'general-discussion') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-900 bg-white hover:bg-blue-50 hover:scale-105 transition-all duration-300 px-6 py-3 rounded-2xl shadow-xl">
                            Explore Board <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Slide 2: GIF Showroom -->
            <div class="w-full h-full relative flex-shrink-0 flex items-center p-8 sm:p-16">
                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=1200&q=80'); filter: brightness(0.4);"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 to-slate-900/10"></div>
                <div class="relative z-10 max-w-2xl text-left space-y-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-pink-500/20 backdrop-blur-md text-pink-300 border border-pink-400/30 uppercase tracking-widest shadow-sm">
                        <span class="material-symbols-outlined text-sm text-pink-400">movie</span> Media Gallery
                    </span>
                    <h2 class="text-3xl sm:text-5xl font-black text-white leading-tight drop-shadow-md">
                        Upload Your Custom Imagery & Animated GIFs
                    </h2>
                    <p class="text-sm sm:text-lg text-slate-200 line-clamp-2 font-medium max-w-xl">
                        Check out the hottest animated reactions and WebP showroom. Integrated directly with ImgBB cloud storage for instant delivery.
                    </p>
                    <div class="pt-4">
                        <a href="{{ route('categories.show', 'images-and-gifs') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-900 bg-white hover:bg-pink-50 hover:scale-105 transition-all duration-300 px-6 py-3 rounded-2xl shadow-xl">
                            View Showroom <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Slide 3: Web Dev Theme Showcase -->
            <div class="w-full h-full relative flex-shrink-0 flex items-center p-8 sm:p-16">
                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=1200&q=80'); filter: brightness(0.4);"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 to-slate-900/10"></div>
                <div class="relative z-10 max-w-2xl text-left space-y-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-purple-500/20 backdrop-blur-md text-purple-300 border border-purple-400/30 uppercase tracking-widest shadow-sm">
                        <span class="material-symbols-outlined text-sm text-purple-400">magic_button</span> Theme Showcase
                    </span>
                    <h2 class="text-3xl sm:text-5xl font-black text-white leading-tight drop-shadow-md">
                        Modern TailwindCSS v4 Browser Dynamic Engines
                    </h2>
                    <p class="text-sm sm:text-lg text-slate-200 line-clamp-2 font-medium max-w-xl">
                        Who needs complex asset builders? Leverage standard browser engines to render beautiful dynamic variables with zero server lag.
                    </p>
                    <div class="pt-4">
                        <a href="{{ route('categories.show', 'web-dev-styles') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-900 bg-white hover:bg-purple-50 hover:scale-105 transition-all duration-300 px-6 py-3 rounded-2xl shadow-xl">
                            View Showcases <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <!-- Next/Prev Chevron Buttons -->
        <button onclick="prevSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-slate-950/40 hover:bg-slate-950/70 backdrop-blur-sm border border-white/20 text-white flex items-center justify-center transition-all z-20 cursor-pointer shadow-md hover:scale-105">
            <span class="material-symbols-outlined text-base font-bold">chevron_left</span>
        </button>
        <button onclick="nextSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-slate-950/40 hover:bg-slate-950/70 backdrop-blur-sm border border-white/20 text-white flex items-center justify-center transition-all z-20 cursor-pointer shadow-md hover:scale-105">
            <span class="material-symbols-outlined text-base font-bold">chevron_right</span>
        </button>

        <!-- Slide Indicators -->
        <div class="absolute bottom-5 left-1/2 -translate-x-1/2 flex items-center gap-2 z-20">
            <span onclick="setSlide(0)" class="carousel-dot w-2 h-2 rounded-full bg-white/40 cursor-pointer hover:bg-white transition-all"></span>
            <span onclick="setSlide(1)" class="carousel-dot w-2 h-2 rounded-full bg-white/40 cursor-pointer hover:bg-white transition-all"></span>
            <span onclick="setSlide(2)" class="carousel-dot w-2 h-2 rounded-full bg-white/40 cursor-pointer hover:bg-white transition-all"></span>
        </div>
    </div>

    <!-- Forum Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Forum Categories List (Left - 8 Cols) -->
        <div class="lg:col-span-8 space-y-6">
            @if(isset($featuredThreads) && $featuredThreads->isNotEmpty())
                <div class="bg-gradient-to-r from-amber-500/10 via-yellow-550/5 to-amber-500/10 border border-amber-250 dark:border-amber-900/30 rounded-[2rem] p-6 shadow-sm mb-6 text-left">
                    <h3 class="text-xs font-black text-amber-600 dark:text-amber-400 uppercase tracking-[0.2em] flex items-center gap-2 mb-4">
                        <span class="material-symbols-outlined text-sm text-amber-500">star</span> Featured Discussions
                    </h3>
                    <div class="grid grid-cols-1 gap-3">
                        @foreach($featuredThreads as $thread)
                            <a href="{{ route('threads.show', $thread->slug) }}" class="flex items-center justify-between gap-4 p-4 bg-white/80 dark:bg-slate-900/80 hover:bg-white dark:hover:bg-slate-900 rounded-2xl border border-amber-100 dark:border-amber-900/30 hover:border-amber-300 hover:shadow-md transition-all">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-800 flex-shrink-0">
                                        <img src="{{ $thread->user->avatar_url }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="min-w-0 text-left">
                                        <h4 class="font-extrabold text-slate-900 dark:text-white text-sm truncate flex items-center gap-1.5">
                                            {{ $thread->title }}
                                            <span class="px-2 py-0.5 text-[8px] font-black uppercase rounded bg-amber-100 dark:bg-amber-950/40 text-amber-700 dark:text-amber-450 tracking-wider">FEATURED</span>
                                        </h4>
                                        <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate mt-0.5">by {{ $thread->user->name }} • {{ $thread->category->name }} • {{ $thread->views_count }} views</p>
                                    </div>
                                </div>
                                <span class="material-symbols-outlined text-slate-400 text-[18px]">arrow_forward</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

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
                                <a href="{{ route('profile.show', $lastPostUser->name) }}" class="w-8 h-8 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-800 flex-shrink-0 shadow-sm block">
                                    <img src="{{ $lastPostUser->avatar_url }}" class="w-full h-full object-cover">
                                </a>
                                <div class="min-w-0 leading-none">
                                    <!-- Latest thread title link -->
                                    <a href="{{ route('threads.show', $latestThread->slug) }}" class="text-xs font-extrabold text-slate-750 dark:text-slate-350 hover:text-blue-600 dark:hover:text-blue-400 truncate block max-w-[130px] sm:max-w-[160px] tracking-tight leading-normal" title="{{ $latestThread->title }}">
                                        {{ $latestThread->title }}
                                    </a>
                                    <!-- Timestamp & User -->
                                    <span class="text-[9px] text-slate-400 dark:text-slate-500 font-bold block mt-0.5 leading-normal">
                                        {{ $lastPostTime }} • <a href="{{ route('profile.show', $lastPostUser->name) }}" class="hover:underline font-extrabold text-slate-500 dark:text-slate-400">{{ $lastPostUser->name }}</a>
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
                        <div class="absolute -top-10 left-4 w-16 h-16 rounded-2xl overflow-hidden border-4 border-white dark:border-slate-900 shadow-md bg-white">
                            <img src="{{ auth()->user()->avatar_url }}" class="w-full h-full object-cover">
                        </div>
                        
                        <!-- User Name and Badges -->
                        <div class="pl-20 min-h-[40px] flex flex-col justify-center">
                            <h3 class="font-extrabold text-slate-900 dark:text-white text-sm sm:text-base leading-tight truncate">
                                <a href="{{ route('profile.show', auth()->user()->name) }}" class="hover:underline">{{ auth()->user()->name }}</a>
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

            <!-- Dynamic Community Stats Card -->
            <div class="border border-slate-200 p-5 bg-white rounded-2xl shadow-sm text-left">
                <h3 class="text-sm font-extrabold tracking-wider text-slate-500 uppercase mb-4 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-blue-600 text-sm">equalizer</span> Forum Stats
                </h3>
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-50 p-3 border border-slate-200 text-center rounded-2xl">
                        <span class="block text-2xl font-extrabold text-slate-900 tracking-tight">{{ $stats['threads_count'] }}</span>
                        <span class="text-xs font-bold text-slate-450 tracking-wider uppercase">Threads</span>
                    </div>
                    <div class="bg-slate-50 p-3 border border-slate-200 text-center rounded-2xl">
                        <span class="block text-2xl font-extrabold text-slate-900 tracking-tight">{{ $stats['posts_count'] }}</span>
                        <span class="text-xs font-bold text-slate-450 tracking-wider uppercase">Replies</span>
                    </div>
                </div>
                <div class="mt-4 pt-3.5 border-t border-slate-100 text-sm text-slate-600 flex justify-between items-center font-semibold">
                    <span>Newest Member:</span>
                    @if($stats['latest_user'])
                    <a href="{{ route('profile.show', $stats['latest_user']->name) }}" 
                       data-user-hover="true" 
                       data-user-name="{{ $stats['latest_user']->name }}" 
                       data-user-badge="{{ $stats['latest_user']->title_badge }}" 
                       data-user-joined="{{ $stats['latest_user']->created_at->format('M d, Y') }}" 
                       data-user-threads="{{ $stats['latest_user']->threads()->count() }}" 
                       data-user-posts="{{ $stats['latest_user']->posts()->count() }}" 
                       data-user-uploads="{{ $stats['latest_user']->attachments()->count() }}" 
                       data-user-avatar="{{ $stats['latest_user']->avatar_url }}" 
                       data-user-banner="{{ $stats['latest_user']->banner_color }}"
                       data-user-banner-path="{{ $stats['latest_user']->banner_path }}"
                       class="font-bold text-blue-600 hover:underline flex items-center gap-1">
                        {{ $stats['latest_user']->name }}
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                    </a>
                    @else
                    <span class="text-slate-400 text-xs">None yet</span>
                    @endif
                </div>
            </div>

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

<!-- Carousel Slider Logic Script -->
<script>
    let currentSlideIndex = 0;
    const totalSlides = 3;
    const wrapper = document.getElementById('carousel-wrapper');
    const dots = document.querySelectorAll('.carousel-dot');

    function updateCarousel() {
        wrapper.style.transform = `translateX(-${currentSlideIndex * 33.333}%)`;
        dots.forEach((dot, idx) => {
            if (idx === currentSlideIndex) {
                dot.classList.add('bg-white', 'w-4');
                dot.classList.remove('bg-white/40');
            } else {
                dot.classList.add('bg-white/40');
                dot.classList.remove('bg-white', 'w-4');
            }
        });
    }

    function nextSlide() {
        currentSlideIndex = (currentSlideIndex + 1) % totalSlides;
        updateCarousel();
    }

    function prevSlide() {
        currentSlideIndex = (currentSlideIndex - 1 + totalSlides) % totalSlides;
        updateCarousel();
    }

    function setSlide(index) {
        currentSlideIndex = index;
        updateCarousel();
    }

    setInterval(nextSlide, 5000);
    updateCarousel();
</script>
@endsection
