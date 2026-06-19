@extends('layouts.app')

@section('title')
Community Media Showroom | XenForo Professional
@endsection
@section('meta_description')
Browse, search, and discover photos, illustrations, and GIFs uploaded across our community discussions.
@endsection
@section('meta_keywords')
media showroom, image gallery, photos, gifs, community uploads
@endsection

@section('content')
<div class="space-y-6 w-full">
    <!-- Premium Header and Banner -->
    <div class="relative rounded-none sm:rounded-3xl overflow-hidden bg-gradient-to-tr from-slate-900 via-indigo-950 to-slate-900 border-y sm:border border-slate-850 dark:border-slate-800 shadow-2xl p-6 sm:p-12 text-center space-y-4">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-blue-900/20 via-transparent to-transparent opacity-60"></div>
        <div class="relative z-10 space-y-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black tracking-widest text-indigo-400 bg-indigo-500/10 border border-indigo-500/20 uppercase">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-pulse"></span>
                Community Showroom
            </span>
            <h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight leading-none">Media Explorer</h1>
            <p class="text-[11px] sm:text-sm text-slate-400 max-w-lg mx-auto font-medium">Browse, search, and discover photos, illustrations, and GIFs uploaded across our discussions.</p>
        </div>

        <!-- Sleek Search Form Inside Banner -->
        <div class="relative max-w-md mx-auto z-10 pt-2 sm:pt-4">
            <form action="{{ route('media.index') }}" method="GET" class="relative group">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="material-symbols-outlined text-slate-400 text-[18px] group-focus-within:text-indigo-400 transition-colors">search</span>
                </span>
                <input type="text" name="q" value="{{ $search }}" class="w-full bg-slate-950/70 border border-slate-800 rounded-2xl pl-11 pr-24 py-3 text-white text-xs sm:text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-slate-950 transition-all placeholder:text-slate-500 placeholder:font-semibold shadow-inner" placeholder="Search uploaded images by file name...">
                <button type="submit" class="absolute right-1.5 top-1.5 bottom-1.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl px-4 text-xs font-bold transition-all shadow-md cursor-pointer flex items-center justify-center">
                    Search
                </button>
            </form>
        </div>
    </div>

    <!-- Gallery Grid Panel -->
    <div class="space-y-6">
        @if($media->count() > 0)
            <!-- Masonry-style Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6 px-3 sm:px-0">
                @foreach($media as $attach)
                    <div class="group relative rounded-none sm:rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 shadow-md hover:shadow-xl hover:border-indigo-500/30 dark:hover:border-indigo-400/30 transition-all duration-300 flex flex-col transform hover:-translate-y-1">
                        <!-- Thumbnail Area -->
                        <div class="relative h-44 sm:h-56 overflow-hidden rounded-t-none sm:rounded-t-2xl bg-slate-100 dark:bg-slate-950 flex-shrink-0">
                            <!-- Overlay Shadow Gradient -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-60 group-hover:opacity-80 transition-opacity duration-300 z-10 pointer-events-none"></div>

                            <!-- Image -->
                            <img src="{{ $attach->url }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $attach->file_name }}">

                            <!-- Quick Stats overlay (e.g. category pill and format tag) -->
                            <div class="absolute top-3 left-3 right-3 flex items-center justify-between z-20">
                                @if($attach->thread && $attach->thread->category)
                                    <span class="px-2 py-0.5 rounded-md text-[8.5px] font-black uppercase tracking-wider bg-indigo-600/90 text-white backdrop-blur-sm border border-indigo-400/20 shadow-sm">
                                        {{ $attach->thread->category->name }}
                                    </span>
                                @endif
                                
                                @if(str_contains($attach->file_name, '.gif') || str_contains($attach->file_type, 'gif'))
                                    <span class="px-2 py-0.5 rounded-md text-[8.5px] font-black uppercase tracking-wider bg-pink-500 text-white shadow-sm animate-pulse">
                                        GIF
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-md text-[8.5px] font-black uppercase tracking-wider bg-slate-900/60 text-slate-200 backdrop-blur-sm border border-slate-700/30 shadow-sm">
                                        IMAGE
                                    </span>
                                @endif
                            </div>

                            <!-- Floating Action Buttons on Hover -->
                            <div class="absolute inset-0 flex items-center justify-center gap-3 opacity-0 group-hover:opacity-100 scale-95 group-hover:scale-100 transition-all duration-300 z-20">
                                <!-- Zoom Trigger -->
                                <button onclick="openLightbox('{{ $attach->url }}', '{{ $attach->file_name }}')" class="w-10 h-10 rounded-full bg-white text-slate-900 hover:bg-indigo-600 hover:text-white flex items-center justify-center transition-all cursor-pointer shadow-lg hover:scale-110" title="Zoom In">
                                    <span class="material-symbols-outlined text-[20px] font-bold">zoom_in</span>
                                </button>
                                
                                <!-- Thread Link -->
                                @if($attach->thread)
                                    <a href="{{ route('threads.show', $attach->thread->slug) }}" class="w-10 h-10 rounded-full bg-white text-slate-900 hover:bg-indigo-600 hover:text-white flex items-center justify-center transition-all shadow-lg hover:scale-110" title="View Discussion Thread">
                                        <span class="material-symbols-outlined text-[18px] font-bold">forum</span>
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Card Footer Details -->
                        <div class="p-4 flex-grow flex flex-col justify-between gap-3 bg-white dark:bg-slate-900 rounded-b-none sm:rounded-b-2xl">
                            <!-- File Title & Location -->
                            <div class="space-y-1 min-w-0">
                                <p class="text-xs font-black text-slate-800 dark:text-slate-105 truncate hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors" title="{{ $attach->file_name }}">{{ $attach->file_name }}</p>
                                @if($attach->thread)
                                    <div class="flex items-center gap-1 text-[9.5px] font-semibold text-slate-400 dark:text-slate-500">
                                        <span class="material-symbols-outlined text-[11px]">topic</span>
                                        <span class="truncate">Shared in:</span>
                                        <a href="{{ route('threads.show', $attach->thread->slug) }}" class="font-extrabold text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors truncate max-w-[120px] sm:max-w-[150px]">{{ $attach->thread->title }}</a>
                                    </div>
                                @endif
                            </div>

                            <!-- Reaction Section (Re-styled for maximum premium feel) -->
                            @if($attach->post)
                                <div class="flex items-center justify-between gap-2 py-2 border-y border-slate-100 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-950/20 px-2 rounded-xl">
                                    @auth
                                        <div class="relative group/react flex items-center" onclick="handleReactContainerClick(event, this)">
                                            @php
                                                $userReact = $attach->post->reacts->where('user_id', Auth::id())->first();
                                                $activeType = $userReact ? $userReact->type : null;
                                                
                                                $label = 'React';
                                                $colorClass = 'text-slate-500 hover:text-indigo-600 dark:text-slate-400';
                                                $icon = 'thumb_up';

                                                if ($activeType === 'like') { $label = 'Like'; $colorClass = 'text-blue-600 font-extrabold'; }
                                                elseif ($activeType === 'love') { $label = 'Love'; $colorClass = 'text-pink-600 font-extrabold'; $icon = 'favorite'; }
                                                elseif ($activeType === 'haha') { $label = 'Haha'; $colorClass = 'text-amber-500 font-extrabold'; $icon = 'sentiment_very_satisfied'; }
                                                elseif ($activeType === 'wow') { $label = 'Wow'; $colorClass = 'text-indigo-500 font-extrabold'; $icon = 'sentiment_satisfied'; }
                                                elseif ($activeType === 'sad') { $label = 'Sad'; $colorClass = 'text-sky-500 font-extrabold'; $icon = 'sentiment_dissatisfied'; }
                                                elseif ($activeType === 'angry') { $label = 'Angry'; $colorClass = 'text-rose-600 font-extrabold'; $icon = 'sentiment_extremely_dissatisfied'; }
                                            @endphp
                                            
                                            <!-- Primary trigger button -->
                                            <button id="react-btn-{{ $attach->post->id }}" onclick="toggleReaction('{{ $attach->post->id }}', 'like')" class="flex items-center justify-center gap-1.5 px-2.5 py-1.5 rounded-lg hover:bg-white dark:hover:bg-slate-850 text-[10.5px] font-bold transition-all cursor-pointer shadow-sm border border-slate-200 dark:border-slate-800 {{ $colorClass }}">
                                                <span class="material-symbols-outlined text-[13px]">{{ $icon }}</span>
                                                <span>{{ $label }}</span>
                                            </button>

                                            <!-- Floating Reactions selector tray -->
                                            <div class="reaction-tray">
                                                <button onclick="toggleReaction('{{ $attach->post->id }}', 'like')" class="reaction-emoji" title="Like">👍</button>
                                                <button onclick="toggleReaction('{{ $attach->post->id }}', 'love')" class="reaction-emoji" title="Love">❤️</button>
                                                <button onclick="toggleReaction('{{ $attach->post->id }}', 'haha')" class="reaction-emoji" title="Haha">😆</button>
                                                <button onclick="toggleReaction('{{ $attach->post->id }}', 'wow')" class="reaction-emoji" title="Wow">😮</button>
                                                <button onclick="toggleReaction('{{ $attach->post->id }}', 'sad')" class="reaction-emoji" title="Sad">😢</button>
                                                <button onclick="toggleReaction('{{ $attach->post->id }}', 'angry')" class="reaction-emoji" title="Angry">😡</button>
                                            </div>
                                        </div>
                                    @else
                                        <a href="{{ route('login') }}" class="flex items-center justify-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-[10.5px] text-slate-500 font-bold border border-slate-200 dark:border-slate-800 transition-all shadow-sm">
                                            <span class="material-symbols-outlined text-[13px]">thumb_up</span>
                                            <span>React</span>
                                        </a>
                                    @endauth

                                    <!-- Reactions Count display -->
                                    <div id="reactions-count-{{ $attach->post->id }}" class="flex items-center gap-1 text-[9px] font-bold text-slate-400">
                                        @php
                                            $reactStats = $attach->post->reacts
                                                ->groupBy('type')
                                                ->map(fn($item) => $item->count())
                                                ->toArray();
                                            $totalReactsCount = array_sum($reactStats);
                                        @endphp
                                        
                                        @if($totalReactsCount > 0)
                                            <div class="flex items-center gap-1 bg-white dark:bg-slate-850 px-2 py-0.5 rounded-full border border-slate-200 dark:border-slate-800 shadow-sm leading-none">
                                                <div class="flex items-center gap-0.5">
                                                    @if(isset($reactStats['like'])) 👍 @endif
                                                    @if(isset($reactStats['love'])) ❤️ @endif
                                                    @if(isset($reactStats['haha'])) 😆 @endif
                                                    @if(isset($reactStats['wow'])) 😮 @endif
                                                    @if(isset($reactStats['sad'])) 😢 @endif
                                                    @if(isset($reactStats['angry'])) 😡 @endif
                                                </div>
                                                <span class="text-[9px] font-black text-slate-700 dark:text-slate-200">{{ $totalReactsCount }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Divider / Uploader Details -->
                            <div class="flex items-center justify-between pt-1">
                                <!-- Uploader info -->
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="w-6 h-6 rounded-full overflow-hidden border border-slate-200 dark:border-slate-700 flex-shrink-0 bg-slate-50 shadow-sm">
                                        <img src="{{ $attach->user->avatar_url }}" class="w-full h-full object-cover" alt="avatar">
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <a href="{{ route('profile.show', $attach->user->name) }}" class="text-[10px] font-black text-slate-700 dark:text-slate-350 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors truncate" style="{{ $attach->user->username_style_css }}">{{ $attach->user->name }}</a>
                                        <span class="text-[8px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest">{{ $attach->created_at->diffForHumans(null, true) }} ago</span>
                                    </div>
                                </div>

                                <!-- Post Redirect Button -->
                                @if($attach->thread)
                                    <a href="{{ route('threads.show', $attach->thread->slug) }}" class="flex items-center justify-center w-7 h-7 rounded-xl bg-indigo-50 hover:bg-indigo-650 dark:bg-slate-800 dark:hover:bg-indigo-600 text-indigo-600 dark:text-indigo-300 hover:text-white dark:hover:text-white transition-all shadow-sm" title="Go to Thread">
                                        <span class="material-symbols-outlined text-[13px] font-black">arrow_forward</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Custom Premium Pagination Links -->
            <div class="pt-8 px-4 sm:px-0">
                {{ $media->links() }}
            </div>
        @else
            <!-- Empty Showcase State -->
            <div class="max-w-md mx-auto my-12 bg-white dark:bg-slate-900 rounded-none sm:rounded-3xl border-y sm:border border-slate-200 dark:border-slate-800 shadow-md p-8 sm:p-12 text-center space-y-5">
                <div class="w-16 h-16 mx-auto bg-slate-50 dark:bg-slate-950 text-slate-350 dark:text-slate-650 rounded-2xl flex items-center justify-center border border-slate-100 dark:border-slate-850 shadow-inner">
                    <span class="material-symbols-outlined text-3xl">photo_library</span>
                </div>
                <div class="space-y-1">
                    <h3 class="text-base font-extrabold text-slate-800 dark:text-white">No media matches found</h3>
                    <p class="text-xs text-slate-450 dark:text-slate-400 font-medium leading-relaxed">
                        @if($search)
                            We couldn't find any public uploaded files matching "{{ $search }}". Please try refining your keywords.
                        @else
                            There are currently no public illustrations or GIFs uploaded by community members.
                        @endif
                    </p>
                </div>
                @if($search)
                    <div class="pt-1">
                        <a href="{{ route('media.index') }}" class="xen-button text-[10px] font-bold text-white px-4 py-2 rounded-xl shadow-md cursor-pointer inline-flex items-center gap-1 hover:opacity-90">
                            Clear Search
                        </a>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>

<script>
    // Handle tap-to-toggle reaction tray on mobile interfaces
    function handleReactContainerClick(e, container) {
        // Only run on mobile/touch interfaces (screen widths <= 768px)
        if (window.matchMedia('(max-width: 768px)').matches) {
            // Prevent instantly Liked action if the reactions tray is closed
            if (!container.classList.contains('active')) {
                e.preventDefault();
                e.stopPropagation();
                
                // Close any other open reaction trays on the page
                document.querySelectorAll('.group\\/react').forEach(c => {
                    if (c !== container) c.classList.remove('active');
                });
                
                container.classList.add('active');
            }
        }
    }

    // Dismiss active mobile reaction trays when clicking anywhere else
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.group\\/react')) {
            document.querySelectorAll('.group\\/react').forEach(c => c.classList.remove('active'));
        }
    });

    // High-End Multi-Reaction System AJAX Controller
    function toggleReaction(postId, reactionType) {
        const btn = document.getElementById(`react-btn-${postId}`);
        const countBox = document.getElementById(`reactions-count-${postId}`);
        if (!btn) return;

        // Temporarily disable button to prevent race clicks
        btn.disabled = true;

        fetch(`/posts/${postId}/react`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ type: reactionType })
        })
        .then(res => {
            if (!res.ok) throw new Error('Reaction failed.');
            return res.json();
        })
        .then(data => {
            btn.disabled = false;
            
            // Close active mobile reaction trays
            document.querySelectorAll('.group\\/react').forEach(c => c.classList.remove('active'));
            
            // 1. Re-render button visual states dynamically
            let iconText = 'thumb_up';
            let labelText = 'React';
            let activeColorClass = 'text-slate-500 hover:text-blue-600 dark:text-slate-400';

            if (data.active_type === 'like') { labelText = 'Like'; activeColorClass = 'text-blue-600 font-bold'; }
            else if (data.active_type === 'love') { labelText = 'Love'; activeColorClass = 'text-pink-600 font-bold'; iconText = 'favorite'; }
            else if (data.active_type === 'haha') { labelText = 'Haha'; activeColorClass = 'text-amber-500 font-bold'; iconText = 'sentiment_very_satisfied'; }
            else if (data.active_type === 'wow') { labelText = 'Wow'; activeColorClass = 'text-indigo-500 font-bold'; iconText = 'sentiment_satisfied'; }
            else if (data.active_type === 'sad') { labelText = 'Sad'; activeColorClass = 'text-sky-500 font-bold'; iconText = 'sentiment_dissatisfied'; }
            else if (data.active_type === 'angry') { labelText = 'Angry'; activeColorClass = 'text-rose-600 font-bold'; iconText = 'sentiment_extremely_dissatisfied'; }

            btn.className = `flex items-center gap-1 px-2 py-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-900 text-[10px] font-bold transition-all cursor-pointer shadow-sm border border-slate-200 dark:border-slate-800 ${activeColorClass}`;
            btn.innerHTML = `
                <span class="material-symbols-outlined text-[11px]">${iconText}</span>
                <span class="font-bold">${labelText}</span>
            `;

            // 2. Re-render Aggregate reaction status counts dynamically
            if (countBox) {
                const total = data.total_count || 0;
                if (total === 0) {
                    countBox.innerHTML = '';
                    return;
                }

                let iconsHtml = '';
                if (data.stats.like) iconsHtml += '👍';
                if (data.stats.love) iconsHtml += '❤️';
                if (data.stats.haha) iconsHtml += '😆';
                if (data.stats.wow) iconsHtml += '😮';
                if (data.stats.sad) iconsHtml += '😢';
                if (data.stats.angry) iconsHtml += '😡';

                countBox.innerHTML = `
                    <div class="flex items-center gap-1 bg-slate-100 dark:bg-slate-900 px-1.5 py-0.5 rounded-full border border-slate-200 dark:border-slate-800 shadow-sm leading-none">
                        <div class="flex items-center gap-0.5">
                            ${iconsHtml}
                        </div>
                        <span class="text-[8.5px] font-extrabold text-slate-600 dark:text-slate-300">${total}</span>
                    </div>
                `;
            }
        })
        .catch(err => {
            btn.disabled = false;
            console.error('Reaction error:', err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Could not record reaction. Please try again.',
                confirmButtonColor: '#1e293b'
            });
        });
    }
</script>
@endsection

