@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Thread Breadcrumb Path & Title Header -->
    <div>
        <div class="flex items-center gap-1.5 text-[10px] font-semibold text-slate-500 dark:text-slate-400 mb-1.5">
            <a href="{{ route('home') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">Forums</a>
            <span>/</span>
            <a href="{{ route('categories.show', $thread->category->slug) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">{{ $thread->category->name }}</a>
            <span>/</span>
            <span class="text-indigo-600 dark:text-indigo-300 truncate">{{ $thread->title }}</span>
        </div>
        <h1 class="text-2xl font-extrabold text-slate-800 dark:text-white tracking-tight flex items-center gap-2 flex-wrap">
            @if($thread->is_pinned)
                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase bg-amber-500/10 text-amber-600 dark:text-amber-300 border border-amber-500/20">📌 Pinned</span>
            @endif
            @if($thread->is_locked)
                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase bg-slate-200 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-300 dark:border-slate-700">🔒 Locked</span>
            @endif
            {{ $thread->title }}
        </h1>
        <div class="flex items-center gap-3 text-[10px] text-slate-500 dark:text-slate-400 mt-1">
            <div class="flex items-center gap-1">
                <span>By</span>
                <a href="{{ route('profile.show', $thread->user->name) }}" class="font-bold text-slate-700 dark:text-slate-355 hover:underline">{{ $thread->user->name }}</a>
            </div>
            <span>•</span>
            <span>Created {{ $thread->created_at->format('M d, Y') }}</span>
            <span>•</span>
            <span>{{ $thread->views_count }} views</span>
        </div>
    </div>

    <!-- Posts Listing Grid -->
    <div class="space-y-4">
        @foreach($posts as $post)
            <div id="post-{{ $post->id }}" class="glass-panel rounded-2xl overflow-hidden border border-slate-300/40 dark:border-slate-800/80 shadow-md flex flex-col md:flex-row">
                <!-- User Left Sidebar Info Card (XenForo Style) -->
                <div class="w-full md:w-48 bg-slate-100/60 dark:bg-slate-900/60 p-4 sm:p-5 flex flex-col items-center border-b md:border-b-0 md:border-r border-slate-300/40 dark:border-slate-800/60 text-center flex-shrink-0">
                    <!-- User Avatar -->
                    <a href="{{ route('profile.show', $post->user->name) }}" 
                       data-user-hover="true" 
                       data-user-name="{{ $post->user->name }}" 
                       data-user-badge="{{ $post->user->title_badge }}" 
                       data-user-joined="{{ $post->user->created_at->format('M d, Y') }}" 
                       data-user-threads="{{ $post->user->threads()->count() }}" 
                       data-user-posts="{{ $post->user->posts()->count() }}" 
                       data-user-uploads="{{ $post->user->attachments()->count() }}" 
                       data-user-avatar="{{ $post->user->avatar_path ? (str_starts_with($post->user->avatar_path, 'http') ? $post->user->avatar_path : asset('storage/' . $post->user->avatar_path)) : '' }}" 
                       data-user-banner="{{ $post->user->banner_color }}"
                       data-user-banner-path="{{ $post->user->banner_path }}"
                       class="relative group mb-2 block">
                        <div class="w-16 h-16 rounded-xl overflow-hidden border border-slate-300 dark:border-slate-700 group-hover:border-indigo-500 transition-all duration-300 shadow-sm">
                            @if($post->user->avatar_path)
                                <img src="{{ str_starts_with($post->user->avatar_path, 'http') ? $post->user->avatar_path : asset('storage/' . $post->user->avatar_path) }}" class="w-full h-full object-cover" alt="avatar">
                            @else
                                <div class="w-full h-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center text-lg font-bold text-slate-500 dark:text-slate-300">
                                    {{ strtoupper(substr(Auth::user() ? Auth::user()->name : $post->user->name, 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        <span class="absolute bottom-0.5 right-0.5 w-2.5 h-2.5 rounded-full bg-emerald-500 border-2 border-white dark:border-slate-950"></span>
                    </a>

                    <!-- User Name -->
                    <h3 class="font-bold text-slate-800 dark:text-slate-200 text-xs hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                        <a href="{{ route('profile.show', $post->user->name) }}"
                           data-user-hover="true" 
                           data-user-name="{{ $post->user->name }}" 
                           data-user-badge="{{ $post->user->title_badge }}" 
                           data-user-joined="{{ $post->user->created_at->format('M d, Y') }}" 
                           data-user-threads="{{ $post->user->threads()->count() }}" 
                           data-user-posts="{{ $post->user->posts()->count() }}" 
                           data-user-uploads="{{ $post->user->attachments()->count() }}" 
                           data-user-avatar="{{ $post->user->avatar_path ? (str_starts_with($post->user->avatar_path, 'http') ? $post->user->avatar_path : asset('storage/' . $post->user->avatar_path)) : '' }}" 
                           data-user-banner="{{ $post->user->banner_color }}"
                           data-user-banner-path="{{ $post->user->banner_path }}">{{ $post->user->name }}</a>
                    </h3>
                    
                    <!-- Dynamic Banner Badge -->
                    <span class="text-[8px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wider text-slate-200 mt-1 border border-slate-700/40 shadow-sm" style="background: {{ $post->user->banner_color }}">
                        {{ $post->user->title_badge }}
                    </span>

                    <!-- Join Date / Statistics Block -->
                    <div class="mt-3 w-full pt-3 border-t border-slate-300/40 dark:border-slate-800/40 text-[9px] text-slate-500 dark:text-slate-400 space-y-1 text-left">
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

                <!-- Post Body Content -->
                <div class="flex-grow p-5 sm:p-6 flex flex-col justify-between space-y-6">
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
                                                 <button onclick="openLightbox('{{ str_starts_with($attach->file_path, 'http') ? $attach->file_path : asset('storage/' . $attach->file_path) }}', '{{ $attach->file_name }}')" class="block w-full h-24 sm:h-28 overflow-hidden cursor-zoom-in text-left p-0 border-0 outline-none w-full">
                                                     <img src="{{ str_starts_with($attach->file_path, 'http') ? $attach->file_path : asset('storage/' . $attach->file_path) }}" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-200" alt="attached image">
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
                                                    <a href="{{ str_starts_with($attach->file_path, 'http') ? $attach->file_path : asset('storage/' . $attach->file_path) }}" download class="hover:text-slate-800 dark:hover:text-white transition-colors" title="Download">
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
                    <div class="mt-4 pt-3 border-t border-slate-200/50 dark:border-slate-800/40 flex flex-wrap items-center justify-between gap-3">
                        <!-- Left: Interactive Emoticon reactions buttons group -->
                        <div class="flex items-center gap-1.5">
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
                                    <button id="react-btn-{{ $post->id }}" onclick="toggleReaction('{{ $post->id }}', 'like')" class="flex items-center gap-1 px-2.5 py-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-900 text-[10px] font-bold transition-all cursor-pointer shadow-sm border border-slate-200 dark:border-slate-800 {{ $colorClass }}">
                                        <span class="material-symbols-outlined text-xs">{{ $icon }}</span>
                                        <span class="font-bold">{{ $label }}</span>
                                    </button>

                                    <!-- Floating Reactions selector tray (Spring-Elastic premium styling) -->
                                    <div class="reaction-tray">
                                        <button onclick="toggleReaction('{{ $post->id }}', 'like')" class="reaction-emoji" title="Like">👍</button>
                                        <button onclick="toggleReaction('{{ $post->id }}', 'love')" class="reaction-emoji" title="Love">❤️</button>
                                        <button onclick="toggleReaction('{{ $post->id }}', 'haha')" class="reaction-emoji" title="Haha">😆</button>
                                        <button onclick="toggleReaction('{{ $post->id }}', 'wow')" class="reaction-emoji" title="Wow">😮</button>
                                        <button onclick="toggleReaction('{{ $post->id }}', 'sad')" class="reaction-emoji" title="Sad">😢</button>
                                        <button onclick="toggleReaction('{{ $post->id }}', 'angry')" class="reaction-emoji" title="Angry">😡</button>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('login') }}" class="flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-white dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 text-[10px] text-slate-500 font-bold border border-slate-200 dark:border-slate-800 transition-all shadow-sm">
                                    <span class="material-symbols-outlined text-xs">thumb_up</span>
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
                                <div class="flex items-center gap-1 bg-slate-100 dark:bg-slate-900 px-2 py-0.5 rounded-full border border-slate-200 dark:border-slate-800 shadow-sm leading-none">
                                    <div class="flex items-center gap-0.5">
                                        @if(isset($reactStats['like'])) 👍 @endif
                                        @if(isset($reactStats['love'])) ❤️ @endif
                                        @if(isset($reactStats['haha'])) 😆 @endif
                                        @if(isset($reactStats['wow'])) 😮 @endif
                                        @if(isset($reactStats['sad'])) 😢 @endif
                                        @if(isset($reactStats['angry'])) 😡 @endif
                                    </div>
                                    <span class="text-[9px] font-extrabold text-slate-600 dark:text-slate-300">{{ $totalReactsCount }} reactions</span>
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
            <div class="mui-card rounded-2xl overflow-hidden border border-slate-200 shadow-lg mt-6 bg-white">
                <div class="bg-slate-50 px-5 py-3 border-b border-slate-200">
                    <h3 class="font-bold text-slate-800 text-xs flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-600 text-sm">reply</span>
                        Write a Quick Reply
                    </h3>
                </div>
                <form id="reply-form" action="{{ route('threads.reply', $thread->slug) }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
                    @csrf
                    <!-- Message Content -->
                    <div class="space-y-1.5">
                        <label for="reply-quill-editor" class="text-[10px] font-bold text-slate-750 uppercase tracking-wider">Reply Message</label>
                        <!-- Hidden real field -->
                        <input type="hidden" id="reply-content-input" name="content" value="{{ old('content') }}">
                        
                        <!-- Quill container -->
                        <div class="rounded-xl border border-slate-200 overflow-hidden bg-slate-50">
                            <div id="reply-quill-editor" style="height: 200px; font-size: 13px;">{!! old('content') !!}</div>
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
                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                        <button type="button" onclick="showLiveReplyPreview()" class="px-4 py-2 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs cursor-pointer transition-all flex items-center gap-1 shadow-sm">
                            <span class="material-symbols-outlined text-sm">visibility</span> Preview Reply
                        </button>
                        <button type="submit" class="xen-button text-xs font-bold text-white px-5 py-2 rounded-xl shadow-md cursor-pointer">
                            Submit Reply
                        </button>
                    </div>
                </form>
            </div>

            <!-- MODERN PROFESSIONAL LIVE PREVIEW SECTION FOR QUICK REPLY -->
            <div id="live-reply-preview-box" class="hidden space-y-3 mt-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-xs font-extrabold text-slate-500 uppercase tracking-widest flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-blue-600 text-sm">visibility</span> Live Reply Preview
                    </h2>
                    <button onclick="closeLiveReplyPreview()" class="text-xs font-semibold text-rose-600 hover:underline cursor-pointer">Hide Preview</button>
                </div>

                <div class="mui-card overflow-hidden border border-slate-200 bg-white shadow-md flex flex-col md:flex-row">
                    <!-- User Left Sidebar Mockup -->
                    <div class="w-full md:w-48 bg-slate-50 p-4 flex flex-col items-center border-b md:border-b-0 md:border-r border-slate-200 text-center flex-shrink-0">
                        <div class="w-16 h-16 rounded-xl overflow-hidden border border-slate-300 shadow-sm mb-2 bg-blue-50 flex items-center justify-center font-bold text-blue-600 text-lg">
                            @if(Auth::user()->avatar_path)
                                <img src="{{ str_starts_with(Auth::user()->avatar_path, 'http') ? Auth::user()->avatar_path : asset('storage/' . Auth::user()->avatar_path) }}" class="w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            @endif
                        </div>
                        <h3 class="font-bold text-slate-800 text-xs">{{ Auth::user()->name }}</h3>
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

            // Intercept form submit to sync Quill HTML content to the hidden content input
            const form = document.getElementById('reply-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const contentInput = document.getElementById('reply-content-input');
                    contentInput.value = replyQuill.root.innerHTML;
                    
                    // If content is empty or only whitespace HTML, fail gracefully
                    const textOnly = replyQuill.getText().trim();
                    if (textOnly.length === 0) {
                        alert('Please enter some content for your reply.');
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
                alert('Only image files (JPEG, PNG, JPG, GIF) are allowed.');
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
                alert('Failed to obtain image URL from ImgBB service.');
            }
        })
        .catch(error => {
            replyQuill.deleteText(range.index, 1);
            console.error('Reply Quill Image Upload Error:', error);
            alert('An error occurred during image upload to ImgBB. Please try again.');
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
            alert('Please write your reply message first to view a preview.');
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
            alert('Could not record reaction. Please try again.');
        });
    }
</script>
@endsection
