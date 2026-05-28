@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Sleek Trending Carousel Banner -->
    <div class="relative rounded-2xl overflow-hidden shadow-md border border-slate-200 bg-white h-56 sm:h-72">
        <!-- Slides Wrapper -->
        <div id="carousel-wrapper" class="relative w-full h-full flex transition-transform duration-500 ease-out" style="width: 300%;">
            
            <!-- Slide 1: General Discussion -->
            <div class="w-full h-full relative flex-shrink-0 flex items-center p-6 sm:p-12">
                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&w=1200&q=80'); filter: brightness(0.35);"></div>
                <div class="relative z-10 max-w-xl text-left space-y-2">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[9px] font-extrabold bg-blue-500/20 text-blue-300 border border-blue-500/30 uppercase tracking-widest">
                        <span class="material-symbols-outlined text-[10px] animate-pulse">local_fire_department</span> Trending Topic
                    </span>
                    <h2 class="text-xl sm:text-3xl font-extrabold text-white leading-tight">
                        Building Future Community Hubs with High-End Aesthetics
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-200 line-clamp-2">
                        Discover the ultimate blueprints for creating blazingly fast forum engines with sleek glassmorphism, HSL tailormade colors, and advanced caching layers.
                    </p>
                    <a href="{{ route('categories.show', 'general-discussion') }}" class="xen-button inline-flex text-xs font-bold text-white px-4 py-2 rounded-lg shadow mt-2">
                        Explore Board
                    </a>
                </div>
            </div>

            <!-- Slide 2: GIF Showroom -->
            <div class="w-full h-full relative flex-shrink-0 flex items-center p-6 sm:p-12">
                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=1200&q=80'); filter: brightness(0.35);"></div>
                <div class="relative z-10 max-w-xl text-left space-y-2">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[9px] font-extrabold bg-pink-500/20 text-pink-300 border border-pink-500/30 uppercase tracking-widest">
                        <span class="material-symbols-outlined text-[10px]">movie</span> Media Gallery
                    </span>
                    <h2 class="text-xl sm:text-3xl font-extrabold text-white leading-tight">
                        Upload Your Custom Imagery & Animated GIFs
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-200 line-clamp-2">
                        Check out the hottest animated reactions and WebP showroom. Integrated directly with ImgBB cloud storage for instant delivery.
                    </p>
                    <a href="{{ route('categories.show', 'images-and-gifs') }}" class="xen-button inline-flex text-xs font-bold text-white px-4 py-2 rounded-lg shadow mt-2">
                        View Showroom
                    </a>
                </div>
            </div>

            <!-- Slide 3: Web Dev Theme Showcase -->
            <div class="w-full h-full relative flex-shrink-0 flex items-center p-6 sm:p-12">
                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=1200&q=80'); filter: brightness(0.35);"></div>
                <div class="relative z-10 max-w-xl text-left space-y-2">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[9px] font-extrabold bg-purple-500/20 text-purple-300 border border-purple-500/30 uppercase tracking-widest">
                        <span class="material-symbols-outlined text-[10px]">magic_button</span> Theme Showcase
                    </span>
                    <h2 class="text-xl sm:text-3xl font-extrabold text-white leading-tight">
                        Modern TailwindCSS v4 Browser Dynamic Engines
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-200 line-clamp-2">
                        Who needs complex asset builders? Leverage standard browser engines to render beautiful dynamic variables with zero server lag.
                    </p>
                    <a href="{{ route('categories.show', 'web-dev-styles') }}" class="xen-button inline-flex text-xs font-bold text-white px-4 py-2 rounded-lg shadow mt-2">
                        View Showcases
                    </a>
                </div>
            </div>

        </div>

        <!-- Next/Prev Chevron Buttons -->
        <button onclick="prevSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-slate-950/40 hover:bg-slate-950/70 border border-white/10 text-white flex items-center justify-center transition-all z-20 cursor-pointer">
            <span class="material-symbols-outlined text-sm font-bold">chevron_left</span>
        </button>
        <button onclick="nextSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-slate-950/40 hover:bg-slate-950/70 border border-white/10 text-white flex items-center justify-center transition-all z-20 cursor-pointer">
            <span class="material-symbols-outlined text-sm font-bold">chevron_right</span>
        </button>

        <!-- Slide Indicators -->
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-1.5 z-20">
            <span onclick="setSlide(0)" class="carousel-dot w-2 h-2 rounded-full bg-white/40 cursor-pointer hover:bg-white transition-all"></span>
            <span onclick="setSlide(1)" class="carousel-dot w-2 h-2 rounded-full bg-white/40 cursor-pointer hover:bg-white transition-all"></span>
            <span onclick="setSlide(2)" class="carousel-dot w-2 h-2 rounded-full bg-white/40 cursor-pointer hover:bg-white transition-all"></span>
        </div>
    </div>

    <!-- Forum Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Forum Categories List (Left - 8 Cols) -->
        <div class="lg:col-span-8 space-y-6">
            <h2 class="text-xs font-extrabold text-slate-500 uppercase tracking-widest flex items-center gap-1.5">
                <span class="material-symbols-outlined text-blue-600 text-sm">hub</span>
                Community Boards
            </h2>

            @foreach($categories as $category)
                <div class="mui-card rounded-2xl overflow-hidden shadow-md border border-slate-200 bg-white">
                    <!-- Category Header -->
                    <div class="bg-slate-50 px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                        <h3 class="font-extrabold text-slate-900 text-sm flex items-center gap-2.5">
                            <span class="w-8.5 h-8.5 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-150/50 shadow-sm">
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
                            <a href="{{ route('categories.show', $category->slug) }}" class="hover:text-blue-600 transition-colors">{{ $category->name }}</a>
                        </h3>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Board #{{ $category->order }}</span>
                    </div>

                    <!-- Category Forums Description -->
                    <div class="px-5 py-2.5 bg-slate-50/50 text-[10px] text-slate-500 border-b border-slate-150 font-medium leading-relaxed">
                        {{ $category->description }}
                    </div>

                    <!-- Forum Threads Grid Table (Desktop & Tablet Viewport) -->
                    <div class="hidden md:block overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
                                    <th class="py-2.5 px-5 font-extrabold">Discussion</th>
                                    <th class="py-2.5 px-4 font-extrabold text-center w-24">Replies</th>
                                    <th class="py-2.5 px-4 font-extrabold text-center w-24">Views</th>
                                    <th class="py-2.5 px-5 font-extrabold text-right w-44">Last Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100/80">
                                @forelse($category->threads as $thread)
                                    <tr class="hover:bg-slate-50/40 transition-colors group">
                                        <!-- Title & User Info -->
                                        <td class="py-3.5 px-5 flex items-start gap-3">
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
                                               class="w-9 h-9 rounded-xl overflow-hidden border border-slate-200 shadow-sm mt-0.5 flex-shrink-0 block">
                                                @if($thread->user->avatar_path)
                                                    <img src="{{ str_starts_with($thread->user->avatar_path, 'http') ? $thread->user->avatar_path : asset('storage/' . $thread->user->avatar_path) }}" class="w-full h-full object-cover" alt="avatar">
                                                @else
                                                    <div class="w-full h-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500">
                                                        {{ strtoupper(substr($thread->user->name, 0, 2)) }}
                                                    </div>
                                                @endif
                                            </a>
                                            <div class="space-y-0.5 min-w-0">
                                                <h4 class="font-bold text-slate-800 text-xs hover:text-blue-600 transition-colors truncate">
                                                    <a href="{{ route('threads.show', $thread->slug) }}">{{ $thread->title }}</a>
                                                </h4>
                                                <div class="flex items-center gap-1 text-[9px] text-slate-400 font-bold">
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
                                                       class="hover:underline text-slate-600 font-bold">{{ $thread->user->name }}</a>
                                                </div>
                                            </div>
                                        </td>
                                        <!-- Replies Count -->
                                        <td class="py-3.5 px-4 text-center">
                                            <span class="inline-block font-extrabold text-slate-700 text-xs px-2.5 py-0.5 rounded-full bg-slate-100 border border-slate-200/50 group-hover:bg-blue-50 group-hover:text-blue-700 group-hover:border-blue-150 transition-colors">{{ $thread->posts()->count() }}</span>
                                        </td>
                                        <!-- Views Count -->
                                        <td class="py-3.5 px-4 text-center">
                                            <span class="inline-block font-extrabold text-slate-500 text-xs">{{ $thread->views_count }}</span>
                                        </td>
                                        <!-- Last Activity info -->
                                        <td class="py-3.5 px-5 text-right">
                                            <p class="text-[9px] text-slate-450 font-bold">{{ $thread->created_at->diffForHumans() }}</p>
                                            <p class="text-[8px] text-slate-400 mt-0.5 font-medium">Started thread</p>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-12 text-center text-xs text-slate-450 font-semibold bg-slate-50/10">
                                            No discussions created yet. Be the first to <a href="{{ route('threads.create', $category->slug) }}" class="text-blue-600 font-bold hover:underline">create a thread</a>!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Forum Threads Cards List (Mobile Viewport Only) -->
                    <div class="block md:hidden divide-y divide-slate-100">
                        @forelse($category->threads as $thread)
                            <div class="p-4 flex flex-col gap-3 hover:bg-slate-50/40 transition-colors">
                                <div class="flex items-start gap-3">
                                    <a href="{{ route('profile.show', $thread->user->name) }}" 
                                       class="w-9 h-9 rounded-xl overflow-hidden border border-slate-200 shadow-sm mt-0.5 flex-shrink-0 block">
                                        @if($thread->user->avatar_path)
                                            <img src="{{ str_starts_with($thread->user->avatar_path, 'http') ? $thread->user->avatar_path : asset('storage/' . $thread->user->avatar_path) }}" class="w-full h-full object-cover" alt="avatar">
                                        @else
                                            <div class="w-full h-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500">
                                                {{ strtoupper(substr($thread->user->name, 0, 2)) }}
                                            </div>
                                        @endif
                                    </a>
                                    <div class="space-y-0.5 min-w-0">
                                        <h4 class="font-bold text-slate-800 text-xs hover:text-blue-600 transition-colors break-words">
                                            <a href="{{ route('threads.show', $thread->slug) }}">{{ $thread->title }}</a>
                                        </h4>
                                        <div class="flex items-center gap-1.5 text-[9px] text-slate-400 font-bold flex-wrap">
                                            <span>By</span>
                                            <a href="{{ route('profile.show', $thread->user->name) }}" class="text-slate-650 hover:underline font-bold">{{ $thread->user->name }}</a>
                                            <span>•</span>
                                            <span>{{ $thread->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between border-t border-slate-100/60 pt-2 text-[9px] text-slate-400 font-bold bg-slate-50/20 px-1 rounded">
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
                    <div class="bg-slate-50 px-5 py-2.5 border-t border-slate-200 text-right">
                        <a href="{{ route('threads.create', $category->slug) }}" class="inline-flex items-center gap-1 text-[10px] font-bold text-blue-600 hover:underline">
                            Create a Thread <span class="material-symbols-outlined text-[10px] font-bold">add</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Sidebar Section (Right - 4 Cols) -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Dynamic Community Stats Card -->
            <div class="mui-card rounded-2xl p-5 border border-slate-200 bg-white shadow-md">
                <h3 class="text-xs font-extrabold tracking-wider text-slate-500 uppercase mb-4 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-blue-600 text-sm">equalizer</span> Forum Stats
                </h3>
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-center">
                        <span class="block text-xl font-extrabold text-slate-900 tracking-tight">{{ $stats['threads_count'] }}</span>
                        <span class="text-[8px] font-bold text-slate-400 tracking-wider uppercase">Threads</span>
                    </div>
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-center">
                        <span class="block text-xl font-extrabold text-slate-900 tracking-tight">{{ $stats['posts_count'] }}</span>
                        <span class="text-[8px] font-bold text-slate-400 tracking-wider uppercase">Replies</span>
                    </div>
                </div>
                <div class="mt-4 pt-3.5 border-t border-slate-100 text-xs text-slate-550 flex justify-between items-center font-semibold">
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
            <div class="mui-card rounded-2xl p-5 border border-slate-200 bg-white shadow-md">
                <h3 class="text-xs font-extrabold tracking-wider text-slate-500 uppercase mb-4 flex items-center justify-between">
                    <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-blue-600 text-sm">group</span> Active Members</span>
                    <span class="text-[8px] px-1.5 py-0.5 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-250 font-bold leading-none">Online</span>
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
                           class="flex items-center gap-1.5 p-1 rounded-lg hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-all group">
                            <div class="relative w-6 h-6 rounded-full overflow-hidden border border-slate-200">
                                @if($user->avatar_path)
                                    <img src="{{ str_starts_with($user->avatar_path, 'http') ? $user->avatar_path : asset('storage/' . $user->avatar_path) }}" class="w-full h-full object-cover" alt="avatar">
                                @else
                                    <div class="w-full h-full bg-slate-100 flex items-center justify-center text-[8px] font-bold text-slate-550">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                @endif
                                <span class="absolute bottom-0 right-0 w-2 h-2 rounded-full bg-emerald-500 border border-white"></span>
                            </div>
                            <div class="truncate leading-tight text-left">
                                <p class="text-[10px] font-bold text-slate-700 group-hover:text-blue-600 truncate">{{ $user->name }}</p>
                                <span class="text-[8px] text-blue-600 font-bold truncate leading-none mt-0.5 block">{{ $user->title_badge }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Recent Activity Sidebar -->
            <div class="mui-card rounded-2xl p-5 border border-slate-200 bg-white shadow-md text-left">
                <h3 class="text-xs font-extrabold tracking-wider text-slate-500 uppercase mb-4 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-blue-600 text-sm">electric_bolt</span> Recent Activity
                </h3>
                <div class="space-y-3.5">
                    @foreach($activeThreads as $activeThread)
                        <div class="text-xs leading-normal border-b border-slate-100 pb-3 last:border-0 last:pb-0">
                            <h4 class="font-bold text-slate-800 hover:text-blue-600 transition-colors">
                                <a href="{{ route('threads.show', $activeThread->slug) }}">{{ $activeThread->title }}</a>
                            </h4>
                            <div class="flex items-center gap-2 mt-1.5 text-slate-450 font-bold text-[9px]">
                                <span class="px-1.5 py-0.5 rounded bg-blue-50 text-blue-600 font-bold border border-blue-150">
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
