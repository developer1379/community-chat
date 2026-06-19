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
                <div class="w-10 h-10 md:w-16 md:h-16 rounded-none overflow-hidden border border-slate-300 dark:border-slate-700 group-hover:border-indigo-500 transition-all duration-300 shadow-sm">
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
                <span class="text-[8px] px-2 py-0.5 rounded-none font-bold uppercase tracking-wider mt-1 border border-slate-700/40 shadow-sm w-max md:w-auto" style="color: {{ $post->user->title_color ?: '#e2e8f0' }}; background: {{ $post->user->banner_color }}">
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
        <div class="flex-grow p-3.5 sm:p-5 flex flex-col justify-between space-y-3 sm:space-y-4">
            <div class="space-y-2.5">
                <div class="flex justify-between items-center text-[10px] text-slate-400 dark:text-slate-500 border-b border-slate-200/50 dark:border-slate-800/40 pb-1.5">
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
                @php
                    $isFirstPost = ($post->id === $thread->firstPost?->id);
                    $postAttachments = $post->attachments;
                    if ($isFirstPost) {
                        $threadOnlyAttachments = \App\Models\Attachment::where('thread_id', $thread->id)->whereNull('post_id')->get();
                        if ($threadOnlyAttachments->count() > 0) {
                            $postAttachments = $postAttachments->merge($threadOnlyAttachments);
                        }
                    }
                    $filteredAttachments = $postAttachments->filter(function($attach) use ($post) {
                        return !str_contains($post->content, $attach->file_path) && !str_contains($post->content, $attach->url);
                    });
                @endphp
                @if($filteredAttachments->count() > 0)
                    <div class="mt-4 pt-4 border-t border-slate-200/50 dark:border-slate-800/40 clear-both">
                        <h4 class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-2">📎 Uploaded Attachments</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($filteredAttachments as $attach)
                                <div class="relative group rounded-none overflow-hidden bg-slate-100 dark:bg-slate-900 border border-slate-300/40 dark:border-slate-800/60 shadow-sm">
                                    @if(str_starts_with($attach->file_type, 'image/') || preg_match('/\.(jpe?g|png|gif|webp|bmp)/i', $attach->file_path) || str_contains($attach->file_path, 'ibb.co') || str_contains($attach->file_path, 'imgbb'))
                                         <button onclick="openLightbox('{{ $attach->url }}', '{{ $attach->file_name }}')" class="block w-full h-24 sm:h-28 overflow-hidden cursor-zoom-in text-left p-0 border-0 outline-none w-full">
                                             <img src="{{ $attach->url }}" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-200" alt="attached image">
                                         </button>
                                        <!-- Media Tag Badge (e.g. GIF) -->
                                        @if(str_contains($attach->file_name, '.gif') || str_contains($attach->file_type, 'gif'))
                                            <span class="absolute top-1.5 right-1.5 px-1 py-0.5 rounded-none text-[7px] font-bold bg-pink-500 text-white uppercase tracking-widest shadow">
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

            <!-- User signature display -->
            @if($post->user->signature)
                <div class="mt-2 pt-2 border-t border-slate-200 dark:border-slate-800/40 border-dashed text-[10px] text-slate-500 dark:text-slate-400 font-medium italic">
                    {{ $post->user->signature }}
                </div>
            @endif

            <!-- Compact Actions Bar (Report on Left, Reactions & Reply on Right) -->
            <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-200/50 dark:border-slate-800/40">
                <div>
                    <button class="text-[11px] font-bold text-slate-400 hover:text-rose-500 dark:text-slate-500 dark:hover:text-rose-450 transition-colors bg-transparent border-0 cursor-pointer select-none">Report</button>
                </div>
                <div class="flex items-center gap-3.5">
                    @auth
                        <!-- Like/Reaction button and floating tray -->
                        <div class="relative group/react flex items-center" onclick="handleReactContainerClick(event, this)">
                            @php
                                $userReact = $post->reacts->where('user_id', Auth::id())->first();
                                $activeType = $userReact ? $userReact->type : null;
                                
                                $label = 'Like';
                                $colorClass = 'text-slate-550 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200';
                                $icon = 'thumb_up';

                                if ($activeType === 'like') { $label = 'Like'; $colorClass = 'text-blue-600 dark:text-blue-400 font-extrabold'; }
                                elseif ($activeType === 'love') { $label = 'Love'; $colorClass = 'text-pink-600 dark:text-pink-500 font-extrabold'; $icon = 'favorite'; }
                                elseif ($activeType === 'haha') { $label = 'Haha'; $colorClass = 'text-amber-500 font-extrabold'; $icon = 'sentiment_very_satisfied'; }
                                elseif ($activeType === 'wow') { $label = 'Wow'; $colorClass = 'text-indigo-500 dark:text-indigo-400 font-extrabold'; $icon = 'sentiment_satisfied'; }
                                elseif ($activeType === 'sad') { $label = 'Sad'; $colorClass = 'text-sky-500 font-extrabold'; $icon = 'sentiment_dissatisfied'; }
                                elseif ($activeType === 'angry') { $label = 'Angry'; $colorClass = 'text-rose-600 font-extrabold'; $icon = 'sentiment_extremely_dissatisfied'; }
                            @endphp
                            
                            <button id="react-btn-{{ $post->id }}" onclick="toggleReaction('{{ $post->id }}', 'like')" class="flex items-center gap-0.5 text-[11px] font-bold transition-all cursor-pointer bg-transparent border-0 select-none {{ $colorClass }}">
                                <span class="material-symbols-outlined text-sm">{{ $icon }}</span>
                                <span>{{ $label }}</span>
                            </button>

                            <div class="reaction-tray">
                                <button onclick="toggleReaction('{{ $post->id }}', 'like')" class="reaction-emoji" title="Like">👍</button>
                                <button onclick="toggleReaction('{{ $post->id }}', 'love')" class="reaction-emoji" title="Love">❤️</button>
                                <button onclick="toggleReaction('{{ $post->id }}', 'haha')" class="reaction-emoji" title="Haha">😆</button>
                                <button onclick="toggleReaction('{{ $post->id }}', 'wow')" class="reaction-emoji" title="Wow">😮</button>
                                <button onclick="toggleReaction('{{ $post->id }}', 'sad')" class="reaction-emoji" title="Sad">😢</button>
                                <button onclick="toggleReaction('{{ $post->id }}', 'angry')" class="reaction-emoji" title="Angry">😡</button>
                            </div>
                        </div>

                        <!-- Quote/Multi-quote Link -->
                        <button onclick="addMultiQuote('{{ $post->user->name }}', '{{ $post->id }}')" class="flex items-center gap-0.5 text-[11px] font-bold text-slate-550 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 bg-transparent border-0 cursor-pointer select-none">
                            <span class="material-symbols-outlined text-sm">add</span>
                            <span>Quote</span>
                        </button>

                        <!-- Edit Link -->
                        @if(Auth::id() === $post->user_id)
                            <button onclick="openEditPostModal('{{ $post->id }}')" class="flex items-center gap-0.5 text-[11px] font-bold text-slate-555 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 bg-transparent border-0 cursor-pointer select-none">
                                <span class="material-symbols-outlined text-sm">edit</span>
                                <span>Edit</span>
                            </button>
                        @endif

                        <!-- Reply Link -->
                        <button onclick="quotePostReply('{{ $post->user->name }}', '{{ $post->id }}')" class="flex items-center gap-0.5 text-[11px] font-bold text-slate-550 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 bg-transparent border-0 cursor-pointer select-none">
                            <span class="material-symbols-outlined text-sm">reply</span>
                            <span>Reply</span>
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center gap-0.5 text-[11px] font-bold text-slate-550 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 select-none">
                            <span class="material-symbols-outlined text-sm">thumb_up</span>
                            <span>React</span>
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Reactions Summary Bar -->
            @php
                $reactStats = $post->reacts
                    ->groupBy('type')
                    ->map(fn($group) => $group->count())
                    ->toArray();
                $totalReactsCount = array_sum($reactStats);
            @endphp
            <div id="reactions-summary-{{ $post->id }}" class="mt-2 {{ $totalReactsCount > 0 ? '' : 'hidden' }}">
                <div onclick="openReactorsModal('{{ $post->id }}')" class="flex items-center gap-1.5 bg-slate-50 dark:bg-slate-900/60 hover:bg-slate-100 dark:hover:bg-slate-800/80 px-2.5 py-1.5 border border-slate-200/60 dark:border-slate-800/60 shadow-sm cursor-pointer select-none transition-colors text-[11px] font-semibold text-slate-500 dark:text-slate-400">
                    <div class="flex items-center -space-x-1.5 text-xs mr-1 select-none">
                        @if(isset($reactStats['like'])) <span>👍</span> @endif
                        @if(isset($reactStats['love'])) <span>❤️</span> @endif
                        @if(isset($reactStats['haha'])) <span>😆</span> @endif
                        @if(isset($reactStats['wow'])) <span>😮</span> @endif
                        @if(isset($reactStats['sad'])) <span>😢</span> @endif
                        @if(isset($reactStats['angry'])) <span>😡</span> @endif
                    </div>
                    <span id="reactions-text-{{ $post->id }}" class="hover:underline">
                        {{ $post->reacts_sentence }}
                    </span>
                </div>
            </div>

        </div>
    </div>
@endforeach
