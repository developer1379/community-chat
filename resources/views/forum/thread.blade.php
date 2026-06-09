@extends('layouts.app')

@section('title')
{{ $thread->title }} | XenForo Professional
@endsection
@section('meta_description')
{{ \Illuminate\Support\Str::limit(strip_tags($posts->first()->content ?? 'Read discussions and replies in this thread.'), 155) }}
@endsection
@section('meta_keywords')
{{ $thread->tags ? $thread->tags : 'forum, discussion, thread, community' }}
@endsection
@section('og_type')
article
@endsection

@section('content')
<!-- JSON-LD Structured Schema for Thread -->
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "DiscussionForumPosting",
  "headline": "{{ e($thread->title) }}",
  "url": "{{ url()->current() }}",
  "datePublished": "{{ $thread->created_at->toIso8601String() }}",
  "author": {
    "@@type": "Person",
    "name": "{{ e($thread->user->name) }}",
    "url": "{{ route('profile.show', $thread->user->name) }}"
  },
  "interactionStatistic": [
    {
      "@@type": "InteractionCounter",
      "interactionType": "https://schema.org/ViewAction",
      "userInteractionCount": {{ $thread->views_count }}
    },
    {
      "@@type": "InteractionCounter",
      "interactionType": "https://schema.org/CommentAction",
      "userInteractionCount": {{ max(0, $posts->total() - 1) }}
    }
  ]
}
</script>
<div class="space-y-6">
    <!-- Thread Breadcrumb Path & Title Header -->
    <div class="px-4 sm:px-0">
        <div class="flex items-center gap-1.5 text-[10px] font-semibold text-slate-500 dark:text-slate-400 mb-1.5">
            <a href="{{ route('home') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">Forums</a>
            <span>/</span>
            <a href="{{ route('categories.show', $thread->category->slug) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">{{ $thread->category->name }}</a>
            <span>/</span>
            <span class="text-indigo-600 dark:text-indigo-300 truncate">{{ $thread->title }}</span>
        </div>
        @php
            $hasTitleStyle = $thread->is_title_styled;
            $animClass = '';
            if ($thread->title_animation === 'glow') $animClass = 'animate-glow';
            elseif ($thread->title_animation === 'pulse') $animClass = 'animate-pulse';
            elseif ($thread->title_animation === 'crackle') $animClass = 'animate-bolt';
            elseif ($thread->title_animation === 'shimmer') $animClass = 'animate-shimmer';
            $colorStyle = ($hasTitleStyle && $thread->title_color) ? 'color: ' . $thread->title_color . ';' : '';
            $defaultClass = ($hasTitleStyle && !$thread->title_color) ? 'text-rose-600 dark:text-rose-400 drop-shadow-[0_1px_1px_rgba(244,63,94,0.2)]' : '';
        @endphp
        <h1 class="text-2xl font-extrabold text-slate-800 dark:text-white tracking-tight flex items-center gap-2 flex-wrap {{ $thread->is_highlighted ? 'px-2 py-1 rounded bg-amber-500/10 border border-amber-500/20 dark:bg-amber-500/5 dark:border-amber-550/20' : '' }}">
            @if($thread->is_pinned)
                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase bg-amber-500/10 text-amber-600 dark:text-amber-300 border border-amber-500/20">📌 Pinned</span>
            @endif
            @if($thread->is_locked)
                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase bg-slate-200 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-300 dark:border-slate-700">🔒 Locked</span>
            @endif
            <span class="{{ $hasTitleStyle ? 'font-black tracking-wide' : '' }} {{ $defaultClass }} {{ $animClass }}" style="{{ $colorStyle }}">{{ $thread->title }}</span>
        </h1>
        @if($thread->tags)
            <div class="flex flex-wrap gap-1.5 mt-2">
                @foreach(explode(',', $thread->tags) as $tag)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[9px] font-extrabold uppercase tracking-wider bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-900/60 shadow-sm shadow-indigo-500/5">
                        #{{ trim($tag) }}
                    </span>
                @endforeach
            </div>
        @endif
        <div class="flex items-center gap-3 text-[10px] text-slate-500 dark:text-slate-400 mt-1.5 flex-wrap">
            <div class="flex items-center gap-1">
                <span>By</span>
                <a href="{{ route('profile.show', $thread->user->name) }}" class="font-bold text-slate-700 dark:text-slate-355 hover:underline" style="{{ $thread->user->username_style_css }}">{{ $thread->user->name }}</a>
            </div>
            <span>•</span>
            <span>Created {{ $thread->created_at->format('M d, Y') }}</span>
            <span>•</span>
            <span>{{ $thread->views_count }} views</span>
            @auth
                @if(Auth::id() === $thread->user_id)
                    <span>•</span>
                    <a href="{{ route('threads.edit', $thread->slug) }}" class="text-blue-650 dark:text-blue-400 hover:underline inline-flex items-center gap-0.5 font-bold">
                        <span class="material-symbols-outlined text-[12px] font-bold">edit</span>
                        <span>Edit</span>
                    </a>
                    <span>•</span>
                    <button onclick="confirmDeleteThread()" class="text-rose-600 dark:text-rose-450 hover:underline inline-flex items-center gap-0.5 bg-transparent border-0 p-0 cursor-pointer font-sans text-[10px] font-bold">
                        <span class="material-symbols-outlined text-[12px] font-bold">delete</span>
                        <span>Delete</span>
                    </button>
                    <span>•</span>
                    <button onclick="openCustomizeTitleModal()" type="button" class="text-indigo-600 hover:text-indigo-750 dark:text-indigo-400 dark:hover:text-indigo-300 hover:underline inline-flex items-center gap-0.5 bg-transparent border-0 p-0 cursor-pointer font-sans text-[10px] font-bold">
                        <span class="material-symbols-outlined text-[12px] font-bold">palette</span>
                        <span>Customize Title</span>
                    </button>
                    <!-- Feature toggle -->
                    @php
                        $hasFeaturedUpgrade = Auth::user()->hasActiveShopItem('featured_homepage_thread');
                        $hasStickyUpgrade = Auth::user()->hasActiveShopItem('sticky_thread');
                    @endphp
                    @if(!$thread->is_featured)
                        <span>•</span>
                        <button onclick="openFeatureModal()" type="button" class="text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300 hover:underline inline-flex items-center gap-0.5 bg-transparent border-0 p-0 cursor-pointer font-sans text-[10px] font-bold">
                            <span class="material-symbols-outlined text-[12px] font-bold">star</span>
                            <span>Feature {{ $hasFeaturedUpgrade ? '(Free)' : '(50 coins)' }}</span>
                        </button>
                    @else
                        <span>•</span>
                        <span class="text-amber-600 dark:text-amber-400 inline-flex items-center gap-0.5 font-sans text-[10px] font-bold" title="This thread is currently featured on the homepage">
                            <span class="material-symbols-outlined text-[12px] font-bold animate-pulse">star</span>
                            <span>Featured</span>
                        </span>
                    @endif

                    <!-- Pin (Sticky) toggle -->
                    @if($hasStickyUpgrade)
                        <span>•</span>
                        <form action="{{ route('threads.pin', $thread->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-indigo-600 hover:text-indigo-750 dark:text-indigo-400 dark:hover:text-indigo-300 hover:underline inline-flex items-center gap-0.5 bg-transparent border-0 p-0 cursor-pointer font-sans text-[10px] font-bold">
                                <span class="material-symbols-outlined text-[12px] font-bold">keep</span>
                                <span>{{ $thread->is_pinned ? 'Unpin' : 'Pin (Sticky)' }}</span>
                            </button>
                        </form>
                    @endif
                    <form id="delete-thread-form" action="{{ route('threads.destroy', $thread->id) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            @endauth
        </div>
    </div>

    <!-- Posts Listing Grid -->
    <div class="space-y-4">
        @foreach($posts as $post)
            <div id="post-{{ $post->id }}" class="glass-panel rounded-none sm:rounded-2xl overflow-hidden border-y sm:border border-slate-300/40 dark:border-slate-800/80 shadow-sm sm:shadow-md flex flex-col md:flex-row">
                <!-- User Info Panel -->
                <div class="w-full md:w-48 bg-slate-100/60 dark:bg-slate-900/60 p-3 sm:p-5 flex flex-row md:flex-col items-center gap-3 md:gap-0 border-b md:border-b-0 md:border-r border-slate-300/40 dark:border-slate-800/60 text-left md:text-center flex-shrink-0">
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
                       class="relative group block flex-shrink-0 md:mb-2">
                        <div class="w-10 h-10 md:w-16 md:h-16 rounded-xl overflow-hidden border border-slate-300 dark:border-slate-700 group-hover:border-indigo-500 transition-all duration-300 shadow-sm">
                            <img src="{{ $post->user->avatar_url }}" class="w-full h-full object-cover" alt="avatar">
                        </div>
                        <span class="absolute bottom-0 md:bottom-0.5 right-0 md:right-0.5 w-2.5 h-2.5 rounded-full bg-emerald-500 border-2 border-white dark:border-slate-950"></span>
                    </a>

                    <!-- Mobile Info Stack -->
                    <div class="flex-grow md:w-full flex flex-col md:items-center">
                        <h3 class="font-bold text-slate-800 dark:text-slate-200 text-sm md:text-xs hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            <a href="{{ route('profile.show', $post->user->name) }}"
                               class="{{ $post->user->username_style }}"
                               style="{{ $post->user->username_style_css }}"
                               data-user-hover="true" 
                               data-user-name="{{ $post->user->name }}" 
                               data-user-badge="{{ $post->user->title_badge }}" 
                               data-user-joined="{{ $post->user->created_at->format('M d, Y') }}" 
                               data-user-threads="{{ $post->user->threads()->count() }}" 
                               data-user-posts="{{ $post->user->posts()->count() }}" 
                               data-user-uploads="{{ $post->user->attachments()->count() }}" 
                               data-user-avatar="{{ $post->user->avatar_url }}" 
                               data-user-banner="{{ $post->user->banner_color }}"
                               data-user-banner-path="{{ $post->user->banner_path }}">{{ $post->user->name }}</a>
                        </h3>
                        <span class="text-[8px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wider mt-1 border border-slate-700/40 shadow-sm w-max md:w-auto" style="color: {{ $post->user->title_color ?: '#e2e8f0' }}; background: {{ $post->user->banner_color }}">
                            {{ $post->user->title_badge }}
                        </span>
                        
                        <!-- Desktop Statistics Block -->
                        <div class="hidden md:block mt-3 w-full pt-3 border-t border-slate-300/40 dark:border-slate-800/40 text-[9px] text-slate-500 dark:text-slate-400 space-y-1 text-left">
                            <div class="flex justify-between">
                                <span>Joined:</span>
                                <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $post->user->created_at->format('M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Threads:</span>
                                <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $post->user->threads()->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Messages:</span>
                                <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $post->user->posts()->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Post Body Content -->
                <div class="flex-grow p-3.5 sm:p-6 flex flex-col justify-between space-y-4 sm:space-y-6">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center text-[10px] text-slate-400 dark:text-slate-500 border-b border-slate-200/50 dark:border-slate-800/40 pb-2">
                            <span>{{ $post->created_at->diffForHumans() }}</span>
                            <span class="font-bold text-indigo-500/60 dark:text-indigo-400/60">#{{ ($posts->currentPage() - 1) * $posts->perPage() + $loop->iteration }}</span>
                        </div>
                        <!-- Content text -->
                        <div class="text-slate-700 dark:text-slate-200 text-xs leading-relaxed font-sans ql-snow">
                            <div class="ql-editor" style="min-height: auto; height: auto; overflow: visible; padding: 0 !important; white-space: normal;">
                                {!! $post->content !!}
                            </div>
                        </div>

                        <!-- Render Attached Images & GIFs Gallery -->
                        @if($post->attachments->count() > 0)
                            <div class="mt-4 pt-4 border-t border-slate-200/50 dark:border-slate-800/40 clear-both">
                                <h4 class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-2">📎 Uploaded Attachments</h4>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    @foreach($post->attachments as $attach)
                                        <div class="relative group rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-900 border border-slate-300/40 dark:border-slate-800/60 shadow-sm">
                                            @if(str_starts_with($attach->file_type, 'image/') || preg_match('/\.(jpe?g|png|gif|webp|bmp)/i', $attach->file_path) || str_contains($attach->file_path, 'ibb.co') || str_contains($attach->file_path, 'imgbb'))
                                                 <button onclick="openLightbox('{{ $attach->url }}', '{{ $attach->file_name }}')" class="block w-full h-24 sm:h-28 overflow-hidden cursor-zoom-in text-left p-0 border-0 outline-none w-full">
                                                     <img src="{{ $attach->url }}" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-200" alt="attached image">
                                                 </button>
                                                <!-- Media Tag Badge (e.g. GIF) -->
                                                @if(str_contains($attach->file_name, '.gif') || str_contains($attach->file_type, 'gif'))
                                                    <span class="absolute top-1.5 right-1.5 px-1 py-0.5 rounded text-[7px] font-bold bg-pink-500 text-white uppercase tracking-widest shadow">
                                                        GIF
                                                    </span>
                                                @endif
                                            @else
                                                <div class="w-full h-24 flex flex-col items-center justify-center p-3">
                                                    <svg class="w-6 h-6 text-slate-400 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12h9m9 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                    </svg>
                                                    <p class="text-[8px] text-slate-500 truncate w-full text-center">{{ $attach->file_name }}</p>
                                                </div>
                                            @endif
                                            <div class="bg-slate-200/60 dark:bg-slate-950/80 p-1.5 text-[8px] text-slate-500 dark:text-slate-400 border-t border-slate-300/30 dark:border-slate-800/40 flex items-center justify-between">
                                                <span class="truncate pr-2 font-medium">{{ $attach->file_name }}</span>
                                                @if(!str_starts_with($attach->file_type, 'image/'))
                                                    <a href="{{ $attach->url }}" download class="hover:text-slate-800 dark:hover:text-white transition-colors" title="Download">
                                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                                        </svg>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- High-End Multi-Reaction System Bar -->
                    <div class="mt-4 pt-3 border-t border-slate-200/50 dark:border-slate-800/40 flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
                        <!-- Left: Interactive Emoticon reactions buttons group -->
                        <div class="flex items-center gap-1.5 flex-wrap">
                            @auth
                                <div class="relative group/react flex items-center" onclick="handleReactContainerClick(event, this)">
                                    @php
                                        $userReact = $post->reacts()->where('user_id', Auth::id())->first();
                                        $activeType = $userReact ? $userReact->type : null;
                                        
                                        $label = 'React';
                                        $colorClass = 'text-slate-500 hover:text-blue-600 dark:text-slate-400';
                                        $icon = 'thumb_up';

                                        if ($activeType === 'like') { $label = 'Like'; $colorClass = 'text-blue-600 font-bold'; }
                                        elseif ($activeType === 'love') { $label = 'Love'; $colorClass = 'text-pink-600 font-bold'; $icon = 'favorite'; }
                                        elseif ($activeType === 'haha') { $label = 'Haha'; $colorClass = 'text-amber-500 font-bold'; $icon = 'sentiment_very_satisfied'; }
                                        elseif ($activeType === 'wow') { $label = 'Wow'; $colorClass = 'text-indigo-500 font-bold'; $icon = 'sentiment_satisfied'; }
                                        elseif ($activeType === 'sad') { $label = 'Sad'; $colorClass = 'text-sky-500 font-bold'; $icon = 'sentiment_dissatisfied'; }
                                        elseif ($activeType === 'angry') { $label = 'Angry'; $colorClass = 'text-rose-600 font-bold'; $icon = 'sentiment_extremely_dissatisfied'; }
                                    @endphp
                                    
                                    <!-- Primary trigger button -->
                                    <button id="react-btn-{{ $post->id }}" onclick="toggleReaction('{{ $post->id }}', 'like')" class="flex items-center justify-center w-full sm:w-auto gap-1 px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-900 text-xs font-bold transition-all cursor-pointer shadow-sm border border-slate-200 dark:border-slate-800 {{ $colorClass }}">
                                        <span class="material-symbols-outlined text-sm">{{ $icon }}</span>
                                        <span class="font-bold">{{ $label }}</span>
                                    </button>

                                    <!-- Floating Reactions selector tray -->
                                    <div class="reaction-tray">
                                        <button onclick="toggleReaction('{{ $post->id }}', 'like')" class="reaction-emoji" title="Like">👍</button>
                                        <button onclick="toggleReaction('{{ $post->id }}', 'love')" class="reaction-emoji" title="Love">❤️</button>
                                        <button onclick="toggleReaction('{{ $post->id }}', 'haha')" class="reaction-emoji" title="Haha">😆</button>
                                        <button onclick="toggleReaction('{{ $post->id }}', 'wow')" class="reaction-emoji" title="Wow">😮</button>
                                        <button onclick="toggleReaction('{{ $post->id }}', 'sad')" class="reaction-emoji" title="Sad">😢</button>
                                        <button onclick="toggleReaction('{{ $post->id }}', 'angry')" class="reaction-emoji" title="Angry">😡</button>
                                    </div>
                                </div>
                                
                                <!-- Edit Button for Auth User -->
                                @if(Auth::id() === $post->user_id)
                                    <button onclick="openEditPostModal('{{ $post->id }}')" class="flex items-center gap-1 px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-900 text-xs font-bold text-slate-500 hover:text-blue-600 dark:text-slate-450 transition-all cursor-pointer shadow-sm border border-slate-200 dark:border-slate-800">
                                        <span class="material-symbols-outlined text-sm">edit</span>
                                        <span>Edit</span>
                                    </button>
                                @endif

                                <!-- Quote / Reply Button -->
                                <button onclick="quotePostReply('{{ $post->user->name }}', '{{ $post->id }}')" class="flex items-center gap-1 px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-900 text-xs font-bold text-slate-500 hover:text-blue-600 dark:text-slate-450 transition-all cursor-pointer shadow-sm border border-slate-200 dark:border-slate-800">
                                    <span class="material-symbols-outlined text-sm">chat</span>
                                    <span>Reply</span>
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="flex items-center justify-center w-full sm:w-auto gap-1 px-3 py-2 rounded-xl bg-white dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs text-slate-500 font-bold border border-slate-200 dark:border-slate-800 transition-all shadow-sm">
                                    <span class="material-symbols-outlined text-sm">thumb_up</span>
                                    <span>React</span>
                                </a>
                            @endauth
                        </div>

                        <!-- Right: Real-time reactions count and aggregate totals -->
                        <div id="reactions-count-{{ $post->id }}" class="flex items-center gap-1 text-[9.5px] font-bold text-slate-450 dark:text-slate-400">
                            @php
                                $reactStats = $post->reacts()
                                    ->select('type', \DB::raw('count(*) as total'))
                                    ->groupBy('type')
                                    ->pluck('total', 'type')
                                    ->toArray();
                                $totalReactsCount = array_sum($reactStats);
                            @endphp
                            
                            @if($totalReactsCount > 0)
                                <div class="flex items-center gap-1 bg-slate-100 dark:bg-slate-900 px-2 py-1 rounded-full border border-slate-200 dark:border-slate-800 shadow-sm leading-none">
                                    <div class="flex items-center gap-0.5 text-base">
                                        @if(isset($reactStats['like'])) 👍 @endif
                                        @if(isset($reactStats['love'])) ❤️ @endif
                                        @if(isset($reactStats['haha'])) 😆 @endif
                                        @if(isset($reactStats['wow'])) 😮 @endif
                                        @if(isset($reactStats['sad'])) 😢 @endif
                                        @if(isset($reactStats['angry'])) 😡 @endif
                                    </div>
                                    <span class="text-xs font-extrabold text-slate-600 dark:text-slate-300 ml-1">{{ $totalReactsCount }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- User signature display -->
                    @if($post->user->signature)
                        <div class="mt-4 pt-3 border-t border-slate-355 dark:border-slate-900 border-dashed text-[10px] text-slate-500 dark:text-slate-400 font-medium italic">
                            {{ $post->user->signature }}
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination links -->
    <div class="mt-4">
        {{ $posts->links() }}
    </div>

    <!-- Quick Reply Form -->
    @auth
        @if(!$thread->is_locked)
            <div class="mui-card rounded-none sm:rounded-2xl overflow-hidden border-y sm:border border-slate-200 dark:border-slate-800 shadow-sm sm:shadow-lg mt-6 bg-white dark:bg-slate-900">
                <div class="bg-slate-50 dark:bg-slate-950/40 px-4 py-3 sm:px-5 sm:py-3.5 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="font-bold text-slate-800 dark:text-slate-200 text-xs flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-sm">reply</span>
                        Write a Quick Reply
                    </h3>
                </div>
                <form id="reply-form" action="{{ route('threads.reply', $thread->slug) }}" method="POST" enctype="multipart/form-data" class="p-3.5 sm:p-5 space-y-4">
                    @csrf
                    <!-- Message Content -->
                    <div class="space-y-1.5">
                        <label for="reply-quill-editor" class="text-[10px] font-bold text-slate-750 uppercase tracking-wider">Reply Message</label>
                        <!-- Hidden real field -->
                        <input type="hidden" id="reply-content-input" name="content" value="{{ old('content') }}">
                        
                        <!-- Quill container -->
                        <div class="relative rounded-xl border border-slate-200 overflow-hidden bg-slate-50">
                            <div id="reply-quill-editor" style="height: 200px; font-size: 13px;">{!! old('content') !!}</div>
                            <!-- Mentions Autocomplete Dropdown -->
                            <div id="mention-dropdown" class="hidden absolute z-50 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xl max-h-48 overflow-y-auto w-56 text-left py-1 text-xs"></div>
                        </div>
                        @error('content')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- File Attachments (Images & GIFs) -->
                    <div class="space-y-3 bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                        <label class="block text-[9px] font-bold text-slate-750 uppercase tracking-wider">📎 Attach Files (Images or GIFs)</label>
                        <p class="text-[9px] text-slate-500 leading-normal">Upload visual content, design guides, dynamic reactions, or memes.</p>
                        <input type="file" id="reply-media-input" name="attachments[]" multiple class="block w-full text-[10px] text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-blue-55 file:text-blue-700 hover:file:bg-blue-100 hover:file:cursor-pointer transition-all">
                        @error('attachments.*')
                            <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror

                        <!-- DYNAMIC IMAGES/GIFS PREVIEWS GRID -->
                        <div id="reply-preview-container" class="hidden grid grid-cols-2 sm:grid-cols-4 gap-3 pt-3 border-t border-slate-200/60">
                            <!-- Selected attachment items will render dynamically -->
                        </div>
                    </div>

                    <!-- Submit action button -->
                    <div class="flex flex-col sm:flex-row items-center sm:justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" onclick="showLiveReplyPreview()" class="w-full sm:w-auto px-6 py-3 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-bold text-sm cursor-pointer transition-all flex items-center justify-center gap-2 shadow-sm">
                            <span class="material-symbols-outlined text-lg">visibility</span> Preview Reply
                        </button>
                        <button type="submit" class="w-full sm:w-auto xen-button text-sm font-bold text-white px-8 py-3 rounded-xl shadow-lg cursor-pointer">
                            Submit Reply
                        </button>
                    </div>
                </form>
            </div>

            <!-- MODERN PROFESSIONAL LIVE PREVIEW SECTION FOR QUICK REPLY -->
            <div id="live-reply-preview-box" class="hidden space-y-3 mt-6 px-4 sm:px-0">
                <div class="flex items-center justify-between">
                    <h2 class="text-xs font-extrabold text-slate-500 uppercase tracking-widest flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-sm">visibility</span> Live Reply Preview
                    </h2>
                    <button onclick="closeLiveReplyPreview()" class="text-xs font-semibold text-rose-600 hover:underline cursor-pointer">Hide Preview</button>
                </div>

                <div class="mui-card rounded-none sm:rounded-2xl overflow-hidden border-y sm:border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-md flex flex-col md:flex-row">
                    <!-- User Left Sidebar Mockup -->
                    <div class="w-full md:w-48 bg-slate-50 dark:bg-slate-950/40 p-4 flex flex-col items-center border-b md:border-b-0 md:border-r border-slate-200 dark:border-slate-800 text-center flex-shrink-0">
                        <div class="w-16 h-16 rounded-xl overflow-hidden border border-slate-300 shadow-sm mb-2 bg-blue-50 flex items-center justify-center font-bold text-blue-600 text-lg">
                            <img src="{{ Auth::user()->avatar_url }}" class="w-full h-full object-cover" alt="avatar">
                        </div>
                        <h3 class="font-bold text-slate-800 text-xs" style="{{ Auth::user()->username_style_css }}">{{ Auth::user()->name }}</h3>
                        <span class="text-[8px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wider text-white mt-1 border border-slate-350 shadow-sm" style="background: {{ Auth::user()->banner_color ?? '#2563eb' }}">
                            {{ Auth::user()->title_badge ?? 'Member' }}
                        </span>
                        <div class="mt-3 w-full pt-3 border-t border-slate-200 text-[9px] text-slate-550 space-y-1 text-left">
                            <div class="flex justify-between">
                                <span>Joined:</span>
                                <span class="font-semibold text-slate-700">{{ Auth::user()->created_at->format('M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Messages:</span>
                                <span class="font-semibold text-slate-700">{{ Auth::user()->posts()->count() + 1 }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Post Body Content -->
                    <div class="flex-grow p-5 sm:p-6 flex flex-col justify-between">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center text-[10px] text-slate-400 border-b border-slate-100 pb-2">
                                <span>Just Now • Preview Mode</span>
                                <span class="font-bold text-blue-600">New Reply</span>
                            </div>
                            <!-- Content text -->
                            <div id="reply-preview-body" class="text-slate-700 text-xs leading-relaxed whitespace-pre-wrap font-sans"></div>

                            <!-- Dynamic media attachment list for reply -->
                            <div id="reply-preview-gallery-container" class="hidden pt-4 border-t border-slate-100">
                                <h4 class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-2">📎 Uploaded Attachments</h4>
                                <div id="reply-preview-gallery-grid" class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    <!-- Files clone dynamically here -->
                                </div>
                            </div>
                        </div>

                        <!-- User signature display -->
                        @if(Auth::user()->signature)
                            <div class="mt-4 pt-3 border-t border-slate-200 border-dashed text-[10px] text-slate-500 font-medium italic">
                                {{ Auth::user()->signature }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="p-5 rounded-2xl border border-slate-300 bg-slate-100 text-center text-slate-500 text-xs mt-6">
                🔒 This thread is locked. You cannot reply to this discussion.
            </div>
        @endif
    @else
        <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50 text-center text-slate-700 text-xs mt-6 shadow-sm">
            👉 Please <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">sign in</a> or <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">register</a> to participate in the conversation!
        </div>
    @endauth
</div>

<!-- JS Controller for Live Selected Replies & Attachments Previewing + Quill Editor -->
<script>
    let replySelectedFiles = [];
    const replyMediaInput = document.getElementById('reply-media-input');
    const replyPreviewContainer = document.getElementById('reply-preview-container');

    // Initialize Quill Rich Text Editor for Quick Reply
    let replyQuill;
    document.addEventListener('DOMContentLoaded', function() {
        const editorEl = document.getElementById('reply-quill-editor');
        if (editorEl) {
            replyQuill = new Quill('#reply-quill-editor', {
                theme: 'snow',
                modules: {
                    toolbar: {
                        container: [
                            [{ 'font': [] }],
                            [{ 'header': [1, 2, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'color': [] }, { 'background': [] }],
                            ['blockquote', 'code-block'],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            ['link', 'image'],
                            ['clean']
                        ],
                        handlers: {
                            image: selectReplyLocalImage
                        }
                    }
                },
                placeholder: 'Type your reply message here...'
            });

            // Mentions Autocomplete logic
            const mentionDropdown = document.getElementById('mention-dropdown');
            let activeIndex = -1;
            let currentMatches = [];
            let lastQuery = '';

            function hideDropdown() {
                if (mentionDropdown) {
                    mentionDropdown.classList.add('hidden');
                }
                activeIndex = -1;
                currentMatches = [];
            }

            replyQuill.on('text-change', function() {
                const range = replyQuill.getSelection();
                if (!range) {
                    hideDropdown();
                    return;
                }

                const text = replyQuill.getText(0, range.index);
                const lastWordMatch = text.match(/@([a-zA-Z0-9_\-]*)$/);

                if (lastWordMatch) {
                    const query = lastWordMatch[1];
                    lastQuery = lastWordMatch[0]; // "@username"
                    
                    fetch(`/dms/search-users?q=${encodeURIComponent(query)}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data && data.length > 0) {
                                currentMatches = data;
                                activeIndex = 0;
                                renderDropdown(boundsPosition(range.index));
                            } else {
                                hideDropdown();
                            }
                        })
                        .catch(() => hideDropdown());
                } else {
                    hideDropdown();
                }
            });

            replyQuill.on('selection-change', function(range) {
                if (!range) {
                    hideDropdown();
                }
            });

            function boundsPosition(index) {
                try {
                    return replyQuill.getBounds(index);
                } catch(e) {
                    return { left: 15, top: 40, height: 15 };
                }
            }

            function renderDropdown(bounds) {
                if (!mentionDropdown) return;
                mentionDropdown.innerHTML = '';
                mentionDropdown.style.left = Math.min(bounds.left, replyQuill.root.clientWidth - 230) + 'px';
                mentionDropdown.style.top = (bounds.top + bounds.height + 5) + 'px';
                mentionDropdown.classList.remove('hidden');

                currentMatches.forEach((user, index) => {
                    const item = document.createElement('div');
                    item.className = `px-3 py-2 cursor-pointer flex items-center gap-2 hover:bg-blue-50 dark:hover:bg-slate-800 transition-colors ${index === activeIndex ? 'bg-blue-50 dark:bg-slate-800' : ''}`;
                    item.innerHTML = `
                        <img src="${user.avatar_url}" class="w-5 h-5 rounded-full object-cover">
                        <div class="min-w-0">
                            <div class="font-bold text-slate-800 dark:text-white truncate">${user.name}</div>
                            ${user.title_badge ? `<div class="text-[9px] text-slate-400 font-medium truncate">${user.title_badge}</div>` : ''}
                        </div>
                    `;
                    item.addEventListener('click', () => selectUser(user));
                    mentionDropdown.appendChild(item);
                });
            }

            function selectUser(user) {
                const range = replyQuill.getSelection();
                if (!range) return;
                
                const text = replyQuill.getText(0, range.index);
                const lastWordMatch = text.match(/@([a-zA-Z0-9_\-]*)$/);
                if (!lastWordMatch) return;

                const startOfMentionIndex = range.index - lastWordMatch[0].length;
                
                replyQuill.deleteText(startOfMentionIndex, lastWordMatch[0].length);
                
                const profileUrl = `/profile/${encodeURIComponent(user.name)}`;
                const html = `<a href="${profileUrl}" class="font-extrabold text-blue-600 dark:text-blue-400">@${user.name}</a>&nbsp;`;
                replyQuill.clipboard.dangerouslyPasteHTML(startOfMentionIndex, html);
                
                setTimeout(() => {
                    replyQuill.setSelection(startOfMentionIndex + user.name.length + 2);
                }, 10);
                
                hideDropdown();
            }

            const editorContainerEl = document.getElementById('reply-quill-editor');
            if (editorContainerEl) {
                editorContainerEl.addEventListener('keydown', function(e) {
                    if (mentionDropdown && !mentionDropdown.classList.contains('hidden')) {
                        if (e.key === 'ArrowDown') {
                            e.preventDefault();
                            activeIndex = (activeIndex + 1) % currentMatches.length;
                            renderDropdown(boundsPosition(replyQuill.getSelection().index));
                        } else if (e.key === 'ArrowUp') {
                            e.preventDefault();
                            activeIndex = (activeIndex - 1 + currentMatches.length) % currentMatches.length;
                            renderDropdown(boundsPosition(replyQuill.getSelection().index));
                        } else if (e.key === 'Enter') {
                            e.preventDefault();
                            if (currentMatches[activeIndex]) {
                                selectUser(currentMatches[activeIndex]);
                            }
                        } else if (e.key === 'Escape') {
                            e.preventDefault();
                            hideDropdown();
                        }
                    }
                }, true);
            }

            document.addEventListener('click', function(e) {
                if (mentionDropdown && !mentionDropdown.contains(e.target) && e.target !== editorContainerEl) {
                    hideDropdown();
                }
            });

            // Intercept form submit to sync Quill HTML content to the hidden content input
            const form = document.getElementById('reply-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const contentInput = document.getElementById('reply-content-input');
                    contentInput.value = replyQuill.root.innerHTML;
                    
                    // If content is empty or only whitespace HTML, fail gracefully
                    const textOnly = replyQuill.getText().trim();
                    if (textOnly.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Empty Reply',
                            text: 'Please enter some content for your reply.',
                            confirmButtonColor: '#1e293b'
                        });
                        e.preventDefault();
                    }
                });
            }
        }
    });

    // Custom image handler for reply Quill instance to upload to ImgBB
    function selectReplyLocalImage() {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/jpeg, image/png, image/jpg, image/gif');
        input.click();

        input.onchange = () => {
            const file = input.files[0];
            if (/^image\//.test(file.type)) {
                uploadReplyImageToImgBB(file);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File',
                    text: 'Only image files (JPEG, PNG, JPG, GIF) are allowed.',
                    confirmButtonColor: '#1e293b'
                });
            }
        };
    }

    function uploadReplyImageToImgBB(file) {
        const formData = new FormData();
        formData.append('image', file);
        formData.append('_token', '{{ csrf_token() }}');

        const range = replyQuill.getSelection(true);
        replyQuill.insertEmbed(range.index, 'image', 'https://media.giphy.com/media/3oEjI6SIIHBdRxXI40/giphy.gif'); // Temp spinner

        fetch('{{ route("media.upload") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Upload failed');
            }
            return response.json();
        })
        .then(data => {
            replyQuill.deleteText(range.index, 1);
            if (data.url) {
                replyQuill.insertEmbed(range.index, 'image', data.url);
                replyQuill.setSelection(range.index + 1);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Upload Failed',
                    text: 'Failed to obtain image URL from ImgBB service.',
                    confirmButtonColor: '#1e293b'
                });
            }
        })
        .catch(error => {
            replyQuill.deleteText(range.index, 1);
            console.error('Reply Quill Image Upload Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Upload Error',
                text: 'An error occurred during image upload to ImgBB. Please try again.',
                confirmButtonColor: '#1e293b'
            });
        });
    }

    if (replyMediaInput) {
        replyMediaInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            files.forEach(file => {
                if (!replySelectedFiles.some(f => f.name === file.name && f.size === file.size)) {
                    replySelectedFiles.push(file);
                }
            });
            updateReplyPreviewsAndInput();
        });
    }

    function updateReplyPreviewsAndInput() {
        const dt = new DataTransfer();
        replySelectedFiles.forEach(file => dt.items.add(file));
        replyMediaInput.files = dt.files;

        replyPreviewContainer.innerHTML = '';
        
        if (replySelectedFiles.length === 0) {
            replyPreviewContainer.classList.add('hidden');
            document.getElementById('reply-preview-gallery-container').classList.add('hidden');
            return;
        }

        replyPreviewContainer.classList.remove('hidden');

        replySelectedFiles.forEach((file, index) => {
            const isImage = file.type.startsWith('image/');
            const item = document.createElement('div');
            item.className = 'relative group rounded-xl overflow-hidden bg-slate-50 border border-slate-200 shadow-sm';
            
            if (isImage) {
                const objectUrl = URL.createObjectURL(file);
                item.innerHTML = `
                    <div class="w-full h-16 overflow-hidden bg-slate-100">
                        <img src="${objectUrl}" class="w-full h-full object-cover">
                    </div>
                    <div class="p-1 text-[8px] text-slate-550 truncate bg-slate-100/50 border-t border-slate-200 flex items-center justify-between">
                        <span class="truncate pr-1 font-semibold">${file.name}</span>
                    </div>
                    <button type="button" onclick="removeSelectedReplyFile(${index})" class="absolute top-1 right-1 w-4.5 h-4.5 rounded-full bg-rose-600 text-white flex items-center justify-center shadow hover:bg-rose-700 cursor-pointer transition-all border border-rose-500 text-[10px] font-bold" title="Delete">
                        ✕
                    </button>
                `;
            } else {
                item.innerHTML = `
                    <div class="w-full h-16 flex flex-col items-center justify-center p-2 bg-slate-50">
                        <span class="material-symbols-outlined text-slate-400 text-sm">description</span>
                        <p class="text-[8px] text-slate-550 truncate w-full text-center mt-1 font-semibold">${file.name}</p>
                    </div>
                    <button type="button" onclick="removeSelectedReplyFile(${index})" class="absolute top-1 right-1 w-4.5 h-4.5 rounded-full bg-rose-600 text-white flex items-center justify-center shadow hover:bg-rose-700 cursor-pointer transition-all border border-rose-500 text-[10px] font-bold" title="Delete">
                        ✕
                    </button>
                `;
            }
            replyPreviewContainer.appendChild(item);
        });

        renderReplyPreviewGallery();
    }

    function removeSelectedReplyFile(index) {
        replySelectedFiles.splice(index, 1);
        updateReplyPreviewsAndInput();
    }

    function renderReplyPreviewGallery() {
        const galleryGrid = document.getElementById('reply-preview-gallery-grid');
        const galleryContainer = document.getElementById('reply-preview-gallery-container');
        if (!galleryGrid || !galleryContainer) return;

        galleryGrid.innerHTML = '';
        const images = replySelectedFiles.filter(f => f.type.startsWith('image/'));
        
        if (images.length === 0) {
            galleryContainer.classList.add('hidden');
            return;
        }

        galleryContainer.classList.remove('hidden');

        images.forEach(file => {
            const objectUrl = URL.createObjectURL(file);
            const card = document.createElement('div');
            card.className = 'relative group rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shadow-sm';
            card.innerHTML = `
                <div class="block w-full h-20 overflow-hidden">
                    <img src="${objectUrl}" class="w-full h-full object-cover">
                </div>
                <div class="bg-slate-100/85 p-1 text-[8px] text-slate-550 border-t border-slate-200 flex items-center justify-between">
                    <span class="truncate pr-2 font-medium">${file.name}</span>
                </div>
            `;
            galleryGrid.appendChild(card);
        });
    }

    function showLiveReplyPreview() {
        if (!replyQuill) return;
        const contentVal = replyQuill.root.innerHTML.trim();

        if (contentVal === '<p><br></p>' || !contentVal) {
            Swal.fire({
                icon: 'warning',
                title: 'Empty Preview',
                text: 'Please write your reply message first to view a preview.',
                confirmButtonColor: '#1e293b'
            });
            return;
        }

        document.getElementById('reply-preview-body').innerHTML = contentVal;

        renderReplyPreviewGallery();

        const previewBox = document.getElementById('live-reply-preview-box');
        previewBox.classList.remove('hidden');
        previewBox.scrollIntoView({ behavior: 'smooth', block: 'end' });
    }

    function closeLiveReplyPreview() {
        document.getElementById('live-reply-preview-box').classList.add('hidden');
    }

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

            btn.className = `flex items-center gap-1 px-2.5 py-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-900 text-[10px] font-bold transition-all cursor-pointer shadow-sm border border-slate-200 dark:border-slate-800 ${activeColorClass}`;
            btn.innerHTML = `
                <span class="material-symbols-outlined text-xs">${iconText}</span>
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
                    <div class="flex items-center gap-1 bg-slate-100 dark:bg-slate-900 px-2 py-0.5 rounded-full border border-slate-200 dark:border-slate-800 shadow-sm leading-none">
                        <div class="flex items-center gap-0.5">
                            ${iconsHtml}
                        </div>
                        <span class="text-[9px] font-extrabold text-slate-600 dark:text-slate-300">${total} reactions</span>
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

    @auth
        @if(Auth::id() === $thread->user_id)
            function confirmDeleteThread() {
                Swal.fire({
                    title: 'Delete Discussion?',
                    text: "Are you sure you want to delete this thread? This will record the deletion inside the database logs and soft delete it from the public view.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#0f172a',
                    confirmButtonText: 'Yes, Delete It',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-thread-form').submit();
                    }
                });
            }
        @endif
    @endauth

    // Quote Reply function to copy quoted post block into Quick Reply editor
    function quotePostReply(username, postId) {
        if (!replyQuill) return;
        const postElement = document.querySelector(`#post-${postId} .ql-editor`);
        const originalContent = postElement ? postElement.innerHTML.trim() : '';
        
        // Construct the quote markup representation
        const quoteHtml = `<blockquote class="border-l-4 border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/60 pl-3 py-1 my-2 text-slate-550 text-xs italic font-sans" data-quoted-post="${postId}"><strong>Post by @${username}:</strong><br>${originalContent}</blockquote><p><br></p>`;
        
        // Append quoted HTML structure into Quick Reply editor
        const range = replyQuill.getSelection(true);
        replyQuill.clipboard.dangerouslyPasteHTML(range.index, quoteHtml);
        replyQuill.setSelection(replyQuill.getLength());
        
        // Scroll down to the reply editor
        const replyEditorContainer = document.getElementById('reply-form');
        if (replyEditorContainer) {
            replyEditorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    // Modal Edit Post controller logic
    let editPostQuill;
    function openEditPostModal(postId) {
        const modal = document.getElementById('edit-post-modal');
        const form = document.getElementById('edit-post-form');
        const postElement = document.querySelector(`#post-${postId} .ql-editor`);
        const originalContent = postElement ? postElement.innerHTML.trim() : '';

        if (!modal || !form) return;

        // Set form action route
        form.action = `/posts/${postId}`;

        // Initialize edit Quill instance if not already initialized
        if (!editPostQuill) {
            editPostQuill = new Quill('#edit-post-quill-editor', {
                theme: 'snow',
                placeholder: 'Edit your post reply message...',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link', 'clean']
                    ]
                }
            });

            form.addEventListener('submit', function(e) {
                const contentInput = document.getElementById('edit-post-content-input');
                contentInput.value = editPostQuill.root.innerHTML;
            });
        }

        editPostQuill.root.innerHTML = originalContent;

        modal.classList.remove('pointer-events-none', 'opacity-0');
    }

    function closeEditPostModal() {
        const modal = document.getElementById('edit-post-modal');
        if (modal) {
            modal.classList.add('pointer-events-none', 'opacity-0');
        }
    }

    function openFeatureModal() {
        const modal = document.getElementById('feature-thread-modal');
        if (modal) {
            modal.classList.remove('pointer-events-none', 'opacity-0');
            modal.querySelector('.relative').classList.remove('scale-95');
            modal.querySelector('.relative').classList.add('scale-100');
            
            const colorInput = document.getElementById('feature-color-input');
            const animSelect = document.getElementById('feature-anim-select');
            if (colorInput && animSelect) {
                // Ensure single listener attachment
                colorInput.removeEventListener('input', updateFeaturePreview);
                colorInput.addEventListener('input', updateFeaturePreview);
                animSelect.removeEventListener('change', updateFeaturePreview);
                animSelect.addEventListener('change', updateFeaturePreview);
            }
            updateFeaturePreview();
        }
    }

    function updateFeaturePreview() {
        const colorInput = document.getElementById('feature-color-input');
        const animSelect = document.getElementById('feature-anim-select');
        const previewSpan = document.getElementById('feature-preview-title');
        
        if (!colorInput || !animSelect || !previewSpan) return;
        
        // Update color
        previewSpan.style.color = colorInput.value;
        
        // Remove animation classes
        previewSpan.classList.remove('animate-glow', 'animate-pulse', 'animate-bolt', 'animate-shimmer');
        
        // Add selected animation
        const animVal = animSelect.value;
        if (animVal === 'glow') {
            previewSpan.classList.add('animate-glow');
        } else if (animVal === 'pulse') {
            previewSpan.classList.add('animate-pulse');
        } else if (animVal === 'crackle') {
            previewSpan.classList.add('animate-bolt');
        } else if (animVal === 'shimmer') {
            previewSpan.classList.add('animate-shimmer');
        }
    }

    function closeFeatureModal() {
        const modal = document.getElementById('feature-thread-modal');
        if (modal) {
            modal.classList.add('pointer-events-none', 'opacity-0');
            modal.querySelector('.relative').classList.remove('scale-100');
            modal.querySelector('.relative').classList.add('scale-95');
        }
    }

    function openCustomizeTitleModal() {
        const modal = document.getElementById('customize-title-modal');
        if (modal) {
            modal.classList.remove('pointer-events-none', 'opacity-0');
            modal.querySelector('.relative').classList.remove('scale-95');
            modal.querySelector('.relative').classList.add('scale-100');
            
            const colorInput = document.getElementById('cust-color-input');
            const animSelect = document.getElementById('cust-anim-select');
            const colorReset = document.getElementById('cust-color-reset');
            
            if (colorInput && animSelect && colorReset) {
                colorInput.removeEventListener('input', updateCustPreview);
                colorInput.addEventListener('input', updateCustPreview);
                animSelect.removeEventListener('change', updateCustPreview);
                animSelect.addEventListener('change', updateCustPreview);
                colorReset.removeEventListener('change', updateCustPreview);
                colorReset.addEventListener('change', updateCustPreview);
            }
            updateCustPreview();
        }
    }

    function closeCustomizeTitleModal() {
        const modal = document.getElementById('customize-title-modal');
        if (modal) {
            modal.classList.add('pointer-events-none', 'opacity-0');
            modal.querySelector('.relative').classList.remove('scale-100');
            modal.querySelector('.relative').classList.add('scale-95');
        }
    }

    function updateCustPreview() {
        const colorInput = document.getElementById('cust-color-input');
        const animSelect = document.getElementById('cust-anim-select');
        const colorReset = document.getElementById('cust-color-reset');
        const hiddenColorInput = document.getElementById('cust-color-hidden-input');
        
        const previewSpan = document.getElementById('cust-preview-title');
        
        const colorCostSpan = document.getElementById('cust-color-cost');
        const animCostSpan = document.getElementById('cust-anim-cost');
        const totalCostSpan = document.getElementById('cust-total-cost');
        const submitBtn = document.getElementById('cust-submit-btn');
        
        if (!colorInput || !animSelect || !colorReset || !hiddenColorInput || !previewSpan || !colorCostSpan || !animCostSpan || !totalCostSpan || !submitBtn) return;
        
        const threadCurrentColor = @json($thread->title_color);
        const threadCurrentAnimation = @json($thread->title_animation ?: 'none');
        const userCoins = @json(Auth::user() ? Auth::user()->coins : 0);
        const isAdmin = @json(Auth::user() ? Auth::user()->isAdmin() : false);
        
        let colorChanged = false;
        let animChanged = false;
        
        const isResetChecked = colorReset.checked;
        if (isResetChecked) {
            colorInput.disabled = true;
            colorInput.style.opacity = '0.5';
            hiddenColorInput.value = '';
            previewSpan.style.color = '';
        } else {
            colorInput.disabled = false;
            colorInput.style.opacity = '1';
            hiddenColorInput.value = colorInput.value;
            previewSpan.style.color = colorInput.value;
        }
        
        const chosenColor = hiddenColorInput.value || null;
        const chosenAnim = animSelect.value;
        
        const normalizedCurrentColor = threadCurrentColor ? threadCurrentColor.toLowerCase() : null;
        const normalizedChosenColor = chosenColor ? chosenColor.toLowerCase() : null;
        
        if (normalizedChosenColor !== normalizedCurrentColor) {
            colorChanged = true;
        }
        
        const normalizedCurrentAnim = (threadCurrentAnimation && threadCurrentAnimation !== 'none') ? threadCurrentAnimation : 'none';
        const normalizedChosenAnim = (chosenAnim && chosenAnim !== 'none') ? chosenAnim : 'none';
        
        if (normalizedChosenAnim !== normalizedCurrentAnim) {
            animChanged = true;
        }
        
        const colorCost = colorChanged ? 100 : 0;
        const animCost = animChanged ? 500 : 0;
        const totalCost = colorCost + animCost;
        
        colorCostSpan.innerText = `${colorCost} Coins`;
        animCostSpan.innerText = `${animCost} Coins`;
        totalCostSpan.innerText = `${totalCost} Coins`;
        
        // Remove animation classes
        previewSpan.classList.remove('animate-glow', 'animate-pulse', 'animate-bolt', 'animate-shimmer');
        
        // Add selected animation preview
        if (chosenAnim === 'glow') {
            previewSpan.classList.add('animate-glow');
        } else if (chosenAnim === 'pulse') {
            previewSpan.classList.add('animate-pulse');
        } else if (chosenAnim === 'crackle') {
            previewSpan.classList.add('animate-bolt');
        } else if (chosenAnim === 'shimmer') {
            previewSpan.classList.add('animate-shimmer');
        }
        
        if (totalCost > userCoins && !isAdmin) {
            submitBtn.disabled = true;
            submitBtn.classList.remove('bg-indigo-600', 'hover:bg-indigo-750');
            submitBtn.classList.add('bg-slate-400', 'cursor-not-allowed');
            submitBtn.innerText = 'Insufficient Coins';
        } else {
            submitBtn.disabled = false;
            submitBtn.classList.remove('bg-slate-400', 'cursor-not-allowed');
            submitBtn.classList.add('bg-indigo-600', 'hover:bg-indigo-750');
            submitBtn.innerText = 'Apply Customize';
        }
    }
</script>

@auth
    @if(Auth::id() === $thread->user_id)
        <!-- Feature Styling Modal -->
        <div id="feature-thread-modal" class="fixed inset-0 z-50 flex items-center justify-center pointer-events-none opacity-0 transition-opacity duration-300">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeFeatureModal()"></div>
            
            <!-- Modal content -->
            <div class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-2xl max-w-md w-full mx-4 overflow-hidden transform transition-all duration-300 scale-95 z-55">
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 dark:from-slate-950 dark:to-slate-850 px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <h3 class="font-black text-slate-850 dark:text-white text-xs flex items-center gap-2">
                        <span class="material-symbols-outlined text-amber-500 text-sm">star</span>
                        Feature & Style Thread
                    </h3>
                    <button type="button" onclick="closeFeatureModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white cursor-pointer transition-colors bg-transparent border-0 p-0 flex items-center">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </button>
                </div>
                
                <form id="feature-thread-form" action="{{ route('threads.feature', $thread->id) }}" method="POST" class="p-6 space-y-4 text-left bg-white dark:bg-slate-900">
                    @csrf
                    <p class="text-[11px] text-slate-500 dark:text-slate-450 font-semibold leading-relaxed">
                        {{ $hasFeaturedUpgrade ? "You have an active Feature upgrade! Apply it now for free." : "This promotion costs 50 DF Coins and will feature your thread on the homepage slider." }}
                    </p>

                    <!-- Color selector -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider">Choose Title Color (Optional)</label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="title_color" id="feature-color-input" value="#e11d48" class="w-10 h-10 border-0 rounded-lg cursor-pointer bg-transparent">
                            <div class="text-[10px] text-slate-400 dark:text-slate-500 font-bold">Pick a hex color or leave as default</div>
                        </div>
                    </div>

                    <!-- Animation selector -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider">Choose Title Animation (Optional)</label>
                        <select name="title_animation" id="feature-anim-select" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-850 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="none">None (Static Color)</option>
                            <option value="glow" selected>Glow (Soft neon pulse)</option>
                            <option value="pulse">Pulse (Scale and fade)</option>
                            <option value="crackle">Crackle (Lightning glow)</option>
                            <option value="shimmer">Shimmer (Metallic shine)</option>
                        </select>
                    </div>

                    <!-- Live Preview Block -->
                    <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-850 space-y-1">
                        <span class="block text-[8px] font-black uppercase text-slate-400 dark:text-slate-500 tracking-wider">Title Live Preview</span>
                        <div class="text-sm font-bold text-slate-800 dark:text-white py-1">
                            <span id="feature-preview-title" class="font-black tracking-wide">{{ $thread->title }}</span>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" onclick="closeFeatureModal()" class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-550 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 font-bold text-xs cursor-pointer transition-all bg-transparent">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-md transition-all cursor-pointer border-0">
                            Confirm & Promote
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Customize Title Modal -->
        <div id="customize-title-modal" class="fixed inset-0 z-50 flex items-center justify-center pointer-events-none opacity-0 transition-opacity duration-300">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeCustomizeTitleModal()"></div>
            
            <!-- Modal content -->
            <div class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-2xl max-w-md w-full mx-4 overflow-hidden transform transition-all duration-300 scale-95 z-55">
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-slate-950 dark:to-slate-850 px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <h3 class="font-black text-slate-850 dark:text-white text-xs flex items-center gap-2">
                        <span class="material-symbols-outlined text-indigo-500 text-sm">palette</span>
                        Customize Thread Title
                    </h3>
                    <button type="button" onclick="closeCustomizeTitleModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white cursor-pointer transition-colors bg-transparent border-0 p-0 flex items-center">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </button>
                </div>
                
                <form id="customize-title-form" action="{{ route('threads.customize-title', $thread->id) }}" method="POST" class="p-6 space-y-4 text-left bg-white dark:bg-slate-900">
                    @csrf
                    <input type="hidden" name="title_color" id="cust-color-hidden-input" value="{{ $thread->title_color }}">
                    
                    <!-- Color selector -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider">Choose Title Color (100 Coins)</label>
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="cust-color-reset" class="rounded border-slate-300 text-indigo-650 focus:ring-indigo-500" {{ !$thread->title_color ? 'checked' : '' }}>
                                <label for="cust-color-reset" class="text-[10px] text-slate-500 dark:text-slate-400 font-bold cursor-pointer">Use Default Color</label>
                            </div>
                            <input type="color" id="cust-color-input" value="{{ $thread->title_color ?: '#4f46e5' }}" class="w-10 h-10 border-0 rounded-lg cursor-pointer bg-transparent">
                            <div class="text-[10px] text-slate-400 dark:text-slate-500 font-bold">Pick a custom color</div>
                        </div>
                    </div>

                    <!-- Animation selector -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider">Choose Title Animation (500 Coins)</label>
                        <select name="title_animation" id="cust-anim-select" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-850 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="none" {{ !$thread->title_animation || $thread->title_animation === 'none' ? 'selected' : '' }}>None (Static Color)</option>
                            <option value="glow" {{ $thread->title_animation === 'glow' ? 'selected' : '' }}>Glow (Soft neon pulse)</option>
                            <option value="pulse" {{ $thread->title_animation === 'pulse' ? 'selected' : '' }}>Pulse (Scale and fade)</option>
                            <option value="crackle" {{ $thread->title_animation === 'crackle' ? 'selected' : '' }}>Crackle (Lightning glow)</option>
                            <option value="shimmer" {{ $thread->title_animation === 'shimmer' ? 'selected' : '' }}>Shimmer (Metallic shine)</option>
                        </select>
                    </div>

                    <!-- Live Preview Block -->
                    <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-850 space-y-1">
                        <span class="block text-[8px] font-black uppercase text-slate-400 dark:text-slate-500 tracking-wider">Title Live Preview</span>
                        <div class="text-sm font-bold text-slate-800 dark:text-white py-1">
                            <span id="cust-preview-title" class="font-black tracking-wide">{{ $thread->title }}</span>
                        </div>
                    </div>

                    <!-- Price & Coins Summary -->
                    <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-850 space-y-1.5 text-xs">
                        <div class="flex justify-between font-bold text-slate-600 dark:text-slate-400">
                            <span>Your Balance:</span>
                            <span class="text-indigo-650 dark:text-indigo-400 font-extrabold">{{ number_format(Auth::user()->coins) }} Coins</span>
                        </div>
                        <div class="flex justify-between font-semibold text-slate-500 dark:text-slate-400">
                            <span>Title Color Cost:</span>
                            <span id="cust-color-cost" class="font-bold">0 Coins</span>
                        </div>
                        <div class="flex justify-between font-semibold text-slate-500 dark:text-slate-400">
                            <span>Title Animation Cost:</span>
                            <span id="cust-anim-cost" class="font-bold">0 Coins</span>
                        </div>
                        <div class="border-t border-slate-200 dark:border-slate-800 my-1 pt-1 flex justify-between font-black text-slate-800 dark:text-white">
                            <span>Total Cost:</span>
                            <span id="cust-total-cost">0 Coins</span>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" onclick="closeCustomizeTitleModal()" class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-550 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 font-bold text-xs cursor-pointer transition-all bg-transparent">
                            Cancel
                        </button>
                        <button type="submit" id="cust-submit-btn" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-750 text-white font-bold text-xs shadow-md transition-all cursor-pointer border-0">
                            Apply Customize
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endauth
@endsection
