@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Beautiful Welcome & Quick Start Hub Hero Panel -->
    <div class="relative rounded-none overflow-hidden shadow-sm border border-slate-200 bg-gradient-to-br from-blue-600 via-indigo-600 to-indigo-700 p-6 sm:p-8 text-white">
        <!-- Background absolute decorative shapes -->
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-indigo-500/20 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2 text-left">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-none text-xs font-extrabold bg-white/10 text-blue-100 border border-white/20 uppercase tracking-widest leading-none">
                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-soft-pulse"></span> Welcome to XenProfessional
                </span>
                <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight">
                    Where professional developers gather to chat & collaborate
                </h1>
                <p class="text-sm sm:text-base text-blue-100/95 max-w-3xl font-medium leading-relaxed">
                    Join high-end discussions, share images & GIFs via ImgBB service, and direct message partners in real-time. Beautifully fast and highly interactive.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3 flex-shrink-0">
                <button onclick="openSearchModal()" class="px-5 py-2.5 rounded-none bg-white text-indigo-600 hover:bg-slate-50 transition-all font-bold text-sm shadow flex items-center gap-1.5 active:scale-95 cursor-pointer border-0">
                    <span class="material-symbols-outlined text-base">search</span> Search Forum
                </button>
                @auth
                    <button onclick="toggleChatDrawer()" class="px-5 py-2.5 rounded-none bg-indigo-550 hover:bg-indigo-600 border border-white/20 text-white transition-all font-bold text-sm flex items-center gap-1.5 active:scale-95 cursor-pointer">
                        <span class="material-symbols-outlined text-base">chat</span> Direct Chat DMs
                    </button>
                @endauth
            </div>
        </div>
    </div>

    <!-- Quick Features Shortcuts Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Card 1 -->
        <a href="{{ route('categories.show', 'general-discussion') }}" class="border border-slate-200 bg-white p-5 flex items-center gap-4 hover:border-blue-300 transition-all group rounded-none shadow-sm">
            <span class="w-12 h-12 rounded-none bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 flex-shrink-0 shadow-none">
                <span class="material-symbols-outlined text-xl">forum</span>
            </span>
            <div class="text-left min-w-0">
                <h3 class="text-sm font-bold text-slate-800 truncate">General Discussion</h3>
                <p class="text-xs text-slate-500 truncate mt-1">Top-tier coding, frameworks & server discussion.</p>
            </div>
        </a>

        <!-- Card 2 -->
        <a href="{{ route('categories.show', 'images-and-gifs') }}" class="border border-slate-200 bg-white p-5 flex items-center gap-4 hover:border-pink-300 transition-all group rounded-none shadow-sm">
            <span class="w-12 h-12 rounded-none bg-pink-50 text-pink-600 flex items-center justify-center border border-pink-100 group-hover:bg-pink-600 group-hover:text-white transition-all duration-300 flex-shrink-0 shadow-none">
                <span class="material-symbols-outlined text-xl">photo_library</span>
            </span>
            <div class="text-left min-w-0">
                <h3 class="text-sm font-bold text-slate-800 truncate">GIFs & Images Showroom</h3>
                <p class="text-xs text-slate-500 truncate mt-1">Upload visual guides & memes via ImgBB.</p>
            </div>
        </a>

        <!-- Card 3 -->
        <a href="#" onclick="openSearchModal(); return false;" class="border border-slate-200 bg-white p-5 flex items-center gap-4 hover:border-indigo-300 transition-all group rounded-none shadow-sm">
            <span class="w-12 h-12 rounded-none bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300 flex-shrink-0 shadow-none">
                <span class="material-symbols-outlined text-xl">shield_person</span>
            </span>
            <div class="text-left min-w-0">
                <h3 class="text-sm font-bold text-slate-800 truncate">Global Directory</h3>
                <p class="text-xs text-slate-500 truncate mt-1">Browse experts, authors & moderator boards.</p>
            </div>
        </a>
    </div>

    <!-- Sleek Trending Carousel Banner -->
    <div class="relative rounded-none overflow-hidden border border-slate-200 bg-white h-56 sm:h-72 shadow-sm">
        <!-- Slides Wrapper -->
        <div id="carousel-wrapper" class="relative w-full h-full flex transition-transform duration-500 ease-out" style="width: 300%;">
            
            <!-- Slide 1: General Discussion -->
            <div class="w-full h-full relative flex-shrink-0 flex items-center p-6 sm:p-12">
                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&w=1200&q=80'); filter: brightness(0.35);"></div>
                <div class="relative z-10 max-w-xl text-left space-y-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-none text-xs font-extrabold bg-blue-500/20 text-blue-300 border border-blue-500/30 uppercase tracking-widest">
                        <span class="material-symbols-outlined text-xs animate-pulse">local_fire_department</span> Trending Topic
                    </span>
                    <h2 class="text-xl sm:text-3xl font-extrabold text-white leading-tight">
                        Building Future Community Hubs with High-End Aesthetics
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-200 line-clamp-2">
                        Discover the ultimate blueprints for creating blazingly fast forum engines with sleek glassmorphism, HSL tailormade colors, and advanced caching layers.
                    </p>
                    <a href="{{ route('categories.show', 'general-discussion') }}" class="xen-button inline-flex text-xs font-bold text-white px-4 py-2 rounded-none shadow mt-2">
                        Explore Board
                    </a>
                </div>
            </div>

            <!-- Slide 2: GIF Showroom -->
            <div class="w-full h-full relative flex-shrink-0 flex items-center p-6 sm:p-12">
                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=1200&q=80'); filter: brightness(0.35);"></div>
                <div class="relative z-10 max-w-xl text-left space-y-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-none text-xs font-extrabold bg-pink-500/20 text-pink-300 border border-pink-500/30 uppercase tracking-widest">
                        <span class="material-symbols-outlined text-xs">movie</span> Media Gallery
                    </span>
                    <h2 class="text-xl sm:text-3xl font-extrabold text-white leading-tight">
                        Upload Your Custom Imagery & Animated GIFs
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-200 line-clamp-2">
                        Check out the hottest animated reactions and WebP showroom. Integrated directly with ImgBB cloud storage for instant delivery.
                    </p>
                    <a href="{{ route('categories.show', 'images-and-gifs') }}" class="xen-button inline-flex text-xs font-bold text-white px-4 py-2 rounded-none shadow mt-2">
                        View Showroom
                    </a>
                </div>
            </div>

            <!-- Slide 3: Web Dev Theme Showcase -->
            <div class="w-full h-full relative flex-shrink-0 flex items-center p-6 sm:p-12">
                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=1200&q=80'); filter: brightness(0.35);"></div>
                <div class="relative z-10 max-w-xl text-left space-y-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-none text-xs font-extrabold bg-purple-500/20 text-purple-300 border border-purple-500/30 uppercase tracking-widest">
                        <span class="material-symbols-outlined text-xs">magic_button</span> Theme Showcase
                    </span>
                    <h2 class="text-xl sm:text-3xl font-extrabold text-white leading-tight">
                        Modern TailwindCSS v4 Browser Dynamic Engines
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-200 line-clamp-2">
                        Who needs complex asset builders? Leverage standard browser engines to render beautiful dynamic variables with zero server lag.
                    </p>
                    <a href="{{ route('categories.show', 'web-dev-styles') }}" class="xen-button inline-flex text-xs font-bold text-white px-4 py-2 rounded-none shadow mt-2">
                        View Showcases
                    </a>
                </div>
            </div>

        </div>

        <!-- Next/Prev Chevron Buttons -->
        <button onclick="prevSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 rounded-none bg-slate-950/40 hover:bg-slate-950/70 border border-white/10 text-white flex items-center justify-center transition-all z-20 cursor-pointer">
            <span class="material-symbols-outlined text-sm font-bold">chevron_left</span>
        </button>
        <button onclick="nextSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 w-8 h-8 rounded-none bg-slate-950/40 hover:bg-slate-950/70 border border-white/10 text-white flex items-center justify-center transition-all z-20 cursor-pointer">
            <span class="material-symbols-outlined text-sm font-bold">chevron_right</span>
        </button>

        <!-- Slide Indicators -->
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-1.5 z-20">
            <span onclick="setSlide(0)" class="carousel-dot w-2 h-2 rounded-none bg-white/40 cursor-pointer hover:bg-white transition-all"></span>
            <span onclick="setSlide(1)" class="carousel-dot w-2 h-2 rounded-none bg-white/40 cursor-pointer hover:bg-white transition-all"></span>
            <span onclick="setSlide(2)" class="carousel-dot w-2 h-2 rounded-none bg-white/40 cursor-pointer hover:bg-white transition-all"></span>
        </div>
    </div>

    <!-- Forum Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Forum Categories List (Left - 8 Cols) -->
        <div class="lg:col-span-8 space-y-6">
            <h2 class="text-sm font-extrabold text-slate-500 uppercase tracking-widest flex items-center gap-1.5">
                <span class="material-symbols-outlined text-blue-600 text-sm">hub</span>
                Community Boards
            </h2>

            @foreach($categories as $category)
                <div class="border border-slate-200 bg-white rounded-none mb-6 shadow-sm">
                    <!-- Category Header -->
                    <div class="bg-slate-50 px-4 py-3.5 border-b border-slate-200 flex items-center justify-between rounded-none">
                        <h3 class="font-extrabold text-slate-905 text-base flex items-center gap-2.5">
                            <span class="w-8.5 h-8.5 rounded-none bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-150/50">
                                @if($category->icon == 'chat-bubble-left-right')
                                    <span class="material-symbols-outlined text-base">forum</span>
                                @elseif($category->icon == 'photo')
                                    <span class="material-symbols-outlined text-base">photo_library</span>
                                @elseif($category->icon == 'sparkles')
                                    <span class="material-symbols-outlined text-base">auto_awesome</span>
                                @else
                                    <span class="material-symbols-outlined text-base">tag</span>
                                @endif
                            </span>
                            <a href="{{ route('categories.show', $category->slug) }}" class="hover:text-blue-600 transition-colors text-base font-extrabold">{{ $category->name }}</a>
                        </h3>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Board #{{ $category->order }}</span>
                    </div>

                    <!-- Category Forums Description -->
                    <div class="px-4 py-2.5 bg-slate-50/50 text-xs text-slate-500 border-b border-slate-150 font-semibold leading-relaxed text-left">
                        {{ $category->description }}
                    </div>

                    <!-- Forum Threads List Layout (Desktop & Tablet Viewport) -->
                    <div class="hidden md:block divide-y divide-slate-100">
                        <!-- Header Row -->
                        <div class="bg-slate-50/50 border-b border-slate-100 px-4 py-2.5 flex items-center justify-between text-xs font-extrabold uppercase tracking-wider text-slate-400">
                            <div class="flex-grow text-left">Discussion</div>
                            <div class="flex items-center gap-12 text-center">
                                <div class="w-20 text-center">Replies</div>
                                <div class="w-20 text-center">Views</div>
                                <div class="w-36 text-right">Last Action</div>
                            </div>
                        </div>

                        @forelse($category->threads as $thread)
                            <div class="px-4 py-3.5 flex items-center justify-between gap-4 hover:bg-slate-50/40 transition-colors group">
                                <!-- Left side: Title & Creator avatar/username -->
                                <div class="flex items-start gap-3 flex-grow min-w-0">
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
                                       class="w-9 h-9 rounded-none overflow-hidden border border-slate-200 mt-0.5 flex-shrink-0 block">
                                        @if($thread->user->avatar_path)
                                            <img src="{{ str_starts_with($thread->user->avatar_path, 'http') ? $thread->user->avatar_path : asset('storage/' . $thread->user->avatar_path) }}" class="w-full h-full object-cover" alt="avatar">
                                        @else
                                            <div class="w-full h-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-550 rounded-none">
                                                {{ strtoupper(substr($thread->user->name, 0, 2)) }}
                                            </div>
                                        @endif
                                    </a>
                                    <div class="space-y-0.5 min-w-0 text-left">
                                        <h4 class="font-bold text-slate-800 text-sm hover:text-blue-600 transition-colors truncate">
                                            <a href="{{ route('threads.show', $thread->slug) }}">{{ $thread->title }}</a>
                                        </h4>
                                        <div class="flex items-center gap-1.5 text-xs text-slate-450 font-bold">
                                            <span>By</span>
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
                                               class="hover:underline text-slate-650 font-extrabold">{{ $thread->user->name }}</a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right side stats & activity -->
                                <div class="flex items-center gap-12 flex-shrink-0">
                                    <!-- Replies Count -->
                                    <div class="w-20 text-center">
                                        <span class="inline-block font-extrabold text-slate-700 text-xs px-2.5 py-0.5 rounded-none bg-slate-100 border border-slate-200/50 group-hover:bg-blue-50 group-hover:text-blue-700 group-hover:border-blue-150 transition-colors">{{ $thread->posts()->count() }}</span>
                                    </div>
                                    <!-- Views Count -->
                                    <div class="w-20 text-center">
                                        <span class="inline-block font-extrabold text-slate-500 text-xs">{{ $thread->views_count }}</span>
                                    </div>
                                    <!-- Last Action -->
                                    <div class="w-36 text-right">
                                        <p class="text-xs text-slate-450 font-bold">{{ $thread->created_at->diffForHumans() }}</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5 font-semibold">Started thread</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center text-xs text-slate-450 font-semibold bg-slate-50/10">
                                No discussions created yet. Be the first to <a href="{{ route('threads.create', $category->slug) }}" class="text-blue-600 font-bold hover:underline">create a thread</a>!
                            </div>
                        @endforelse
                    </div>

                    <!-- Forum Threads Cards List (Mobile Viewport Only) -->
                    <div class="block md:hidden divide-y divide-slate-100">
                        @forelse($category->threads as $thread)
                            <div class="p-4 flex flex-col gap-3 hover:bg-slate-50/40 transition-colors">
                                <div class="flex items-start gap-3">
                                    <a href="{{ route('profile.show', $thread->user->name) }}" 
                                       class="w-9 h-9 rounded-none overflow-hidden border border-slate-200 mt-0.5 flex-shrink-0 block">
                                        @if($thread->user->avatar_path)
                                            <img src="{{ str_starts_with($thread->user->avatar_path, 'http') ? $thread->user->avatar_path : asset('storage/' . $thread->user->avatar_path) }}" class="w-full h-full object-cover" alt="avatar">
                                        @else
                                            <div class="w-full h-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-550 rounded-none">
                                                {{ strtoupper(substr($thread->user->name, 0, 2)) }}
                                            </div>
                                        @endif
                                    </a>
                                    <div class="space-y-0.5 min-w-0">
                                        <h4 class="font-bold text-slate-800 text-sm hover:text-blue-600 transition-colors break-words">
                                            <a href="{{ route('threads.show', $thread->slug) }}">{{ $thread->title }}</a>
                                        </h4>
                                        <div class="flex items-center gap-1.5 text-xs text-slate-400 font-bold flex-wrap">
                                            <span>By</span>
                                            <a href="{{ route('profile.show', $thread->user->name) }}" class="text-slate-650 hover:underline font-bold">{{ $thread->user->name }}</a>
                                            <span>•</span>
                                            <span>{{ $thread->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between border-t border-slate-100/60 pt-2 text-xs text-slate-400 font-bold bg-slate-50/20 px-2 rounded-none">
                                    <div class="flex items-center gap-4">
                                        <span>💬 {{ $thread->posts()->count() }} Replies</span>
                                        <span>👁️ {{ $thread->views_count }} Views</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-xs text-slate-450 font-semibold">
                                No discussions created yet. Be the first to <a href="{{ route('threads.create', $category->slug) }}" class="text-blue-600 font-bold hover:underline">create a thread</a>!
                            </div>
                        @endforelse
                    </div>

                    <!-- Footer Action -->
                    <div class="bg-slate-50 px-4 py-2 border-t border-slate-200 text-right rounded-none">
                        <a href="{{ route('threads.create', $category->slug) }}" class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:underline">
                            Create a Thread <span class="material-symbols-outlined text-[10px] font-bold">add</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Sidebar Section (Right - 4 Cols) -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Interactive Quick Start Guide Card -->
            <div class="border border-slate-200 p-5 bg-white rounded-none shadow-sm text-left relative overflow-hidden bg-gradient-to-br from-white to-slate-50">
                <div class="absolute -right-6 -top-6 w-20 h-20 bg-blue-500/5 rounded-full blur-xl pointer-events-none"></div>
                <h3 class="text-sm font-extrabold tracking-wider text-slate-500 uppercase mb-3 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-blue-600 text-sm animate-pulse">explore</span> Otaku Quick Guide
                </h3>
                <p class="text-xs text-slate-500 font-medium leading-relaxed mb-4">
                    Earn Activity Points to level up your Otaku profile tier, unlock custom animated badges, and rank up on our global board!
                </p>
                <div class="space-y-2 text-xs font-bold text-slate-700">
                    <div class="flex items-center justify-between p-2 bg-slate-50 border border-slate-150">
                        <span class="flex items-center gap-1.5">📝 Start a Thread</span>
                        <span class="text-blue-600 font-extrabold">+10 pts</span>
                    </div>
                    <div class="flex items-center justify-between p-2 bg-slate-50 border border-slate-150">
                        <span class="flex items-center gap-1.5">💬 Post a Reply</span>
                        <span class="text-blue-600 font-extrabold">+5 pts</span>
                    </div>
                    <div class="flex items-center justify-between p-2 bg-slate-50 border border-slate-150">
                        <span class="flex items-center gap-1.5">❤️ Earn Reaction</span>
                        <span class="text-blue-600 font-extrabold">+2 pts</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ route('rules') }}" class="text-[10px] font-extrabold text-blue-600 hover:underline uppercase tracking-wider flex items-center gap-1">
                        Read full rules & guide <span class="material-symbols-outlined text-[10px] font-bold">arrow_forward</span>
                    </a>
                </div>
            </div>

            <!-- Dynamic Community Stats Card -->
            <div class="border border-slate-200 p-5 bg-white rounded-none shadow-sm text-left">
                <h3 class="text-sm font-extrabold tracking-wider text-slate-500 uppercase mb-4 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-blue-600 text-sm">equalizer</span> Forum Stats
                </h3>
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-50 p-3 border border-slate-200 text-center rounded-none">
                        <span class="block text-2xl font-extrabold text-slate-900 tracking-tight">{{ $stats['threads_count'] }}</span>
                        <span class="text-xs font-bold text-slate-450 tracking-wider uppercase">Threads</span>
                    </div>
                    <div class="bg-slate-50 p-3 border border-slate-200 text-center rounded-none">
                        <span class="block text-2xl font-extrabold text-slate-900 tracking-tight">{{ $stats['posts_count'] }}</span>
                        <span class="text-xs font-bold text-slate-450 tracking-wider uppercase">Replies</span>
                    </div>
                </div>
                <div class="mt-4 pt-3.5 border-t border-slate-100 text-sm text-slate-600 flex justify-between items-center font-semibold">
                    <span>Newest Member:</span>
                    <a href="{{ route('profile.show', $stats['latest_user']->name) }}" 
                       data-user-hover="true" 
                       data-user-name="{{ $stats['latest_user']->name }}" 
                       data-user-badge="{{ $stats['latest_user']->title_badge }}" 
                       data-user-joined="{{ $stats['latest_user']->created_at->format('M d, Y') }}" 
                       data-user-threads="{{ $stats['latest_user']->threads()->count() }}" 
                       data-user-posts="{{ $stats['latest_user']->posts()->count() }}" 
                       data-user-uploads="{{ $stats['latest_user']->attachments()->count() }}" 
                       data-user-avatar="{{ $stats['latest_user']->avatar_path ? (str_starts_with($stats['latest_user']->avatar_path, 'http') ? $stats['latest_user']->avatar_path : asset('storage/' . $stats['latest_user']->avatar_path)) : '' }}" 
                       data-user-banner="{{ $stats['latest_user']->banner_color }}"
                       data-user-banner-path="{{ $stats['latest_user']->banner_path }}"
                       class="font-bold text-blue-600 hover:underline flex items-center gap-1">
                        {{ $stats['latest_user']->name }}
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                    </a>
                </div>
            </div>

            <!-- Active Members Sidebar -->
            <div class="border border-slate-200 p-5 bg-white rounded-none shadow-sm text-left">
                <h3 class="text-sm font-extrabold tracking-wider text-slate-500 uppercase mb-4 flex items-center justify-between">
                    <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-blue-600 text-sm">group</span> Active Members</span>
                    <span class="text-xs px-1.5 py-0.5 rounded-none bg-emerald-50 text-emerald-600 border border-emerald-250 font-bold leading-none">Online</span>
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
                           data-user-avatar="{{ $user->avatar_path ? (str_starts_with($user->avatar_path, 'http') ? $user->avatar_path : asset('storage/' . $user->avatar_path)) : '' }}" 
                           data-user-banner="{{ $user->banner_color }}"
                           data-user-banner-path="{{ $user->banner_path }}"
                           class="flex items-center gap-1.5 p-1.5 rounded-none hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-all group">
                            <div class="relative w-6 h-6 rounded-none overflow-hidden border border-slate-200 flex-shrink-0">
                                @if($user->avatar_path)
                                    <img src="{{ str_starts_with($user->avatar_path, 'http') ? $user->avatar_path : asset('storage/' . $user->avatar_path) }}" class="w-full h-full object-cover" alt="avatar">
                                @else
                                    <div class="w-full h-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-550 rounded-none">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                @endif
                                <span class="absolute bottom-0 right-0 w-2 h-2 rounded-none bg-emerald-500 border border-white"></span>
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
            <div class="border border-slate-200 p-5 bg-white rounded-none shadow-sm text-left">
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
                                <span class="px-1.5 py-0.5 rounded-none bg-blue-50 text-blue-600 font-bold border border-blue-150">
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
