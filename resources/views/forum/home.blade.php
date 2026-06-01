@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Beautiful Welcome & Quick Start Hub Hero Panel -->
    <div class="relative rounded-[2rem] overflow-hidden shadow-xl border border-white/10 bg-[#0B1120] p-8 sm:p-12 sm:py-16 text-white mb-10">
        <!-- Background absolute decorative shapes -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/20 via-indigo-500/10 to-purple-500/20 pointer-events-none"></div>
        <div class="absolute -right-32 -top-32 w-[30rem] h-[30rem] bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-32 -bottom-32 w-[30rem] h-[30rem] bg-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col items-center text-center max-w-4xl mx-auto gap-8">
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold bg-white/5 text-blue-300 border border-white/10 shadow-[0_0_15px_rgba(59,130,246,0.15)] backdrop-blur-md">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                </span>
                XenProfessional Community
            </span>
            
            <h1 class="text-4xl sm:text-6xl font-black tracking-tight leading-tight bg-clip-text text-transparent bg-gradient-to-b from-white to-white/70">
                Where professionals gather to <br class="hidden sm:block" />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">chat & collaborate</span>
            </h1>
            
            <p class="text-lg sm:text-xl text-slate-400 font-medium leading-relaxed max-w-2xl">
                Join high-end discussions, share insights, and connect with industry leaders in real-time. Beautifully fast, highly interactive.
            </p>
            
            <div class="flex flex-col sm:flex-row items-center gap-4 mt-2">
                <button onclick="openSearchModal()" class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-white text-slate-900 hover:bg-blue-50 hover:scale-105 transition-all duration-300 font-bold text-sm shadow-[0_0_20px_rgba(255,255,255,0.3)] flex items-center justify-center gap-2 cursor-pointer border-0">
                    <span class="material-symbols-outlined text-[18px]">search</span> Explore Discussions
                </button>
                @auth
                    <button onclick="toggleChatDrawer()" class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-white/5 hover:bg-white/10 backdrop-blur-md border border-white/10 hover:border-white/20 hover:scale-105 text-white transition-all duration-300 font-bold text-sm flex items-center justify-center gap-2 cursor-pointer shadow-lg">
                        <span class="material-symbols-outlined text-[18px]">chat</span> Launch Direct Chat
                    </button>
                @endauth
            </div>
        </div>
    </div>

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
            <h2 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] flex items-center gap-2 mb-6">
                <span class="w-8 h-px bg-slate-200"></span> Community Boards <span class="w-8 h-px bg-slate-200"></span>
            </h2>

            @foreach($categories as $category)
                <div class="bg-white rounded-[2rem] mb-8 shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-shadow">
                    <!-- Category Header -->
                    <div class="bg-gradient-to-r from-slate-50 to-white px-6 sm:px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6">
                            <span class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center text-blue-600 border border-slate-150 shadow-sm flex-shrink-0">
                                @if($category->icon == 'chat-bubble-left-right')
                                    <span class="material-symbols-outlined text-2xl">forum</span>
                                @elseif($category->icon == 'photo')
                                    <span class="material-symbols-outlined text-2xl">photo_library</span>
                                @elseif($category->icon == 'sparkles')
                                    <span class="material-symbols-outlined text-2xl">auto_awesome</span>
                                @else
                                    <span class="material-symbols-outlined text-2xl">tag</span>
                                @endif
                            </span>
                            <div>
                                <h3 class="font-black text-slate-900 text-xl tracking-tight">
                                    <a href="{{ route('categories.show', $category->slug) }}" class="hover:text-blue-600 transition-colors">{{ $category->name }}</a>
                                </h3>
                                <p class="text-sm text-slate-500 mt-1 font-medium leading-relaxed">{{ $category->description }}</p>
                            </div>
                        </div>
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
                                       class="w-10 h-10 rounded-full overflow-hidden border border-slate-200 mt-0.5 flex-shrink-0 block shadow-sm hover:shadow transition-shadow">
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
                                       class="w-10 h-10 rounded-full overflow-hidden border border-slate-200 mt-0.5 flex-shrink-0 block shadow-sm">
                                        @if($thread->user->avatar_path)
                                            <img src="{{ str_starts_with($thread->user->avatar_path, 'http') ? $thread->user->avatar_path : asset('storage/' . $thread->user->avatar_path) }}" class="w-full h-full object-cover" alt="avatar">
                                        @else
                                            <div class="w-full h-full bg-slate-100 flex items-center justify-center text-sm font-bold text-slate-500 rounded-full">
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
                           data-user-avatar="{{ $user->avatar_path ? (str_starts_with($user->avatar_path, 'http') ? $user->avatar_path : asset('storage/' . $user->avatar_path)) : '' }}" 
                           data-user-banner="{{ $user->banner_color }}"
                           data-user-banner-path="{{ $user->banner_path }}"
                           class="flex items-center gap-1.5 p-1.5 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-all group">
                            <div class="relative w-6 h-6 rounded-2xl overflow-hidden border border-slate-200 flex-shrink-0">
                                @if($user->avatar_path)
                                    <img src="{{ str_starts_with($user->avatar_path, 'http') ? $user->avatar_path : asset('storage/' . $user->avatar_path) }}" class="w-full h-full object-cover" alt="avatar">
                                @else
                                    <div class="w-full h-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-550 rounded-2xl">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                @endif
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
