@extends('layouts.app')

@section('title')
{{ $thread->title }} | XenForo Professional
@endsection
@section('meta_description')
{{ \Illuminate\Support\Str::limit(strip_tags($thread->firstPost->content ?? ($posts->first()->content ?? 'Read discussions and replies in this thread.')), 155) }}
@endsection
@section('meta_keywords')
{{ $thread->tags ? $thread->tags : 'forum, discussion, thread, community' }}
@endsection
@section('og_type')
article
@endsection
@section('og_image')
{{ $thread->attachments->first()?->url ?? ($thread->firstPost?->attachments->first()?->url ?? ($thread->user->avatar_url ?? '')) }}
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
  ]@if($posts->total() > 1 && (($posts->currentPage() === 1 && $posts->count() > 1) || $posts->currentPage() > 1)),
  "comment": [
    @foreach(($posts->currentPage() === 1 ? $posts->slice(1) : $posts) as $reply)
    {
      "@@type": "Comment",
      "text": {!! json_encode(strip_tags($reply->content)) !!},
      "dateCreated": "{{ $reply->created_at->toIso8601String() }}",
      "author": {
        "@@type": "Person",
        "name": "{{ e($reply->user->name) }}",
        "url": "{{ route('profile.show', $reply->user->name) }}"
      }
    }{{ !$loop->last ? ',' : '' }}
    @endforeach
  ]
  @endif
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
            <span class="{{ $hasTitleStyle ? 'font-black tracking-wide' : '' }} {{ $defaultClass }} {{ $animClass }}" style="{{ $colorStyle }}">{!! $thread->prefix_badge !!}{{ $thread->title }}</span>
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
         <!-- Posts Listing Grid -->
    <div id="posts-list-container" class="space-y-4">
        @include('forum.partials.post_list')
    </div>

    <!-- Dynamic Expansion Loader -->
    @if($posts->hasMorePages())
        <div id="load-more-container" class="mt-6 text-center">
            <button id="load-more-btn" onclick="loadMoreReplies()" class="px-5 py-2.5 rounded-none border border-slate-350 dark:border-slate-800 text-xs font-bold text-slate-550 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-900 transition-all cursor-pointer select-none bg-transparent">
                Show Older/More Replies...
            </button>
        </div>
    @endif

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
                        <textarea id="reply-content-input" name="content" class="hidden" readonly>{{ old('content') }}</textarea>
                        
                        <!-- Quill container -->
                        <div class="relative rounded-xl border border-slate-200 overflow-hidden bg-slate-50">
                            <div id="reply-quill-editor" style="height: 200px; font-size: 13px;">{!! old('content') !!}</div>
                            <!-- Mentions Autocomplete Dropdown -->
                            <div id="mention-dropdown" class="hidden absolute z-50 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xl max-h-48 overflow-y-auto w-56 text-left py-1 text-xs"></div>
                        </div>

                        <!-- ImgBB Upload Widget target container -->
                        <div id="reply-imgbb-upload-container" class="mt-2 text-left"></div>

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

@include('partials.quill-imgbb-scripts')

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
                            ['link', 'image', 'video'],
                            ['clean']
                        ],
                        handlers: {
                            image: selectReplyLocalImage,
                            video: selectReplyVideoOption
                        }
                    }
                },
                placeholder: 'Type your reply message here...'
            });

            // ImgBB Upload Widget value listener/interceptor for reply
            const replyContentInput = document.getElementById('reply-content-input');
            if (replyContentInput) {
                const descriptor = Object.getOwnPropertyDescriptor(HTMLTextAreaElement.prototype, 'value');
                Object.defineProperty(replyContentInput, 'value', {
                    get: function() {
                        return descriptor.get.call(this);
                    },
                    set: function(val) {
                        descriptor.set.call(this, val);
                        if (val) {
                            // Extract URL from BBCode [img]URL[/img] or HTML <img> tags
                            const imgRegex = /\[img\](.*?)\[\/img\]|<img[^>]+src="([^">]+)"/gi;
                            let match;
                            let foundUrls = [];
                            while ((match = imgRegex.exec(val)) !== null) {
                                const url = match[1] || match[2];
                                if (url && !foundUrls.includes(url)) {
                                    foundUrls.push(url);
                                }
                            }
                            
                            if (foundUrls.length > 0) {
                                foundUrls.forEach(url => {
                                    if (typeof replyQuill !== 'undefined' && !isImageInQuill(replyQuill, url)) {
                                        const range = replyQuill.getSelection(true);
                                        replyQuill.insertEmbed(range.index, 'image', url);
                                        replyQuill.setSelection(range.index + 1);
                                    }
                                });
                                // Keep the synced editor content as final value
                                descriptor.set.call(this, replyQuill.root.innerHTML);
                            }
                        }
                    }
                });
            }

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

    function selectReplyVideoOption() {
        const range = replyQuill.getSelection(true);
        Swal.fire({
            title: '🎥 Insert / Upload Video',
            html: `
                <div class="text-left text-xs space-y-3">
                    <p class="text-slate-500 font-medium">To share videos, upload them to one of these <strong>5 free hosting servers</strong>, then copy and paste the video link below:</p>
                    <div class="grid grid-cols-2 gap-2.5 pt-1.5 pb-3">
                        <a href="https://sendvid.com" target="_blank" class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-50/50 hover:bg-indigo-50/30 border border-slate-200 text-slate-700 hover:text-indigo-600 font-bold transition-all text-xs">
                            <span class="material-symbols-outlined text-[18px] text-indigo-500">publish</span> Sendvid
                        </a>
                        <a href="https://streamable.com" target="_blank" class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-50/50 hover:bg-sky-50/30 border border-slate-200 text-slate-700 hover:text-sky-600 font-bold transition-all text-xs">
                            <span class="material-symbols-outlined text-[18px] text-sky-500">videocam</span> Streamable
                        </a>
                        <a href="https://youtube.com" target="_blank" class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-50/50 hover:bg-red-50/30 border border-slate-200 text-slate-700 hover:text-red-650 font-bold transition-all text-xs">
                            <span class="material-symbols-outlined text-[18px] text-red-500">play_circle</span> YouTube
                        </a>
                        <a href="https://vimeo.com" target="_blank" class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-50/50 hover:bg-blue-50/30 border border-slate-200 text-slate-700 hover:text-blue-650 font-bold transition-all text-xs">
                            <span class="material-symbols-outlined text-[18px] text-blue-500">movie</span> Vimeo
                        </a>
                        <a href="https://gofile.io" target="_blank" class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-50/50 hover:bg-emerald-50/30 border border-slate-200 text-slate-700 hover:text-emerald-600 font-bold transition-all text-xs col-span-2 justify-center">
                            <span class="material-symbols-outlined text-[18px] text-emerald-500">cloud_upload</span> GoFile Free Storage
                        </a>
                    </div>
                    <label class="block font-black text-slate-700 uppercase tracking-wider">Paste Video Embed / Share Link:</label>
                </div>
            `,
            input: 'url',
            inputPlaceholder: 'https://sendvid.com/embed/... or YouTube URL',
            showCancelButton: true,
            confirmButtonText: 'Insert Video',
            confirmButtonColor: '#1e293b',
            cancelButtonColor: '#e11d48'
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                videoUrl = getEmbedUrl(videoUrl);

                replyQuill.insertEmbed(range.index, 'video', videoUrl);
                replyQuill.setSelection(range.index + 1);
            }
        });
    }

    // Custom image handler for reply Quill instance to upload to ImgBB
    function selectReplyLocalImage() {
        handleQuillImageInsertion(replyQuill, 'reply-imgbb-upload-container', 'reply-quill-editor', '{{ route("media.upload") }}', '{{ csrf_token() }}');
    }

    if (replyMediaInput) {
        replyMediaInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            files.forEach(file => {
                if (!replySelectedFiles.some(f => f.name === file.name && f.size === file.size)) {
                    const fileObj = {
                        name: file.name,
                        size: file.size,
                        url: '',
                        isUploading: true,
                        error: false
                    };
                    replySelectedFiles.push(fileObj);

                    const formData = new FormData();
                    formData.append('image', file);
                    formData.append('_token', '{{ csrf_token() }}');

                    fetch('{{ route("media.upload") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Upload failed');
                        return response.json();
                    })
                    .then(data => {
                        if (data.url) {
                            fileObj.url = data.url;
                            fileObj.isUploading = false;
                        } else {
                            fileObj.error = true;
                            fileObj.isUploading = false;
                        }
                        updateReplyPreviewsAndInput();
                    })
                    .catch(err => {
                        console.error('Reply upload error:', err);
                        fileObj.error = true;
                        fileObj.isUploading = false;
                        updateReplyPreviewsAndInput();
                    });
                }
            });
            updateReplyPreviewsAndInput();
            replyMediaInput.value = '';
        });
    }

    function updateReplyPreviewsAndInput() {
        replyPreviewContainer.innerHTML = '';
        
        const existingInputs = document.querySelectorAll('.dynamic-reply-attachment-input');
        existingInputs.forEach(el => el.remove());

        if (replySelectedFiles.length === 0) {
            replyPreviewContainer.classList.add('hidden');
            document.getElementById('reply-preview-gallery-container').classList.add('hidden');
            return;
        }

        replyPreviewContainer.classList.remove('hidden');

        replySelectedFiles.forEach((fileObj, index) => {
            const item = document.createElement('div');
            item.className = 'relative group rounded-xl overflow-hidden bg-slate-50 border border-slate-200 shadow-sm';
            
            if (fileObj.isUploading) {
                item.innerHTML = `
                    <div class="w-full h-16 flex flex-col items-center justify-center p-2 bg-slate-50">
                        <div class="w-5 h-5 border-2 border-slate-300 border-t-slate-900 rounded-full animate-spin"></div>
                        <p class="text-[8px] text-slate-550 truncate w-full text-center mt-2 font-bold animate-pulse">Uploading...</p>
                    </div>
                    <button type="button" onclick="removeSelectedReplyFile(${index})" class="absolute top-1 right-1 w-4.5 h-4.5 rounded-full bg-rose-600 text-white flex items-center justify-center shadow hover:bg-rose-700 cursor-pointer transition-all border border-rose-500 text-[10px] font-bold" title="Cancel">
                        ✕
                    </button>
                `;
            } else if (fileObj.error) {
                item.innerHTML = `
                    <div class="w-full h-16 flex flex-col items-center justify-center p-2 bg-rose-50 border border-rose-100 text-rose-600">
                        <span class="material-symbols-outlined text-sm">error</span>
                        <p class="text-[8px] truncate w-full text-center mt-1 font-bold">Failed</p>
                    </div>
                    <button type="button" onclick="removeSelectedReplyFile(${index})" class="absolute top-1 right-1 w-4.5 h-4.5 rounded-full bg-rose-600 text-white flex items-center justify-center shadow hover:bg-rose-700 cursor-pointer transition-all border border-rose-500 text-[10px] font-bold" title="Delete">
                        ✕
                    </button>
                `;
            } else {
                item.innerHTML = `
                    <div class="w-full h-16 overflow-hidden bg-slate-100">
                        <img src="${fileObj.url}" class="w-full h-full object-cover">
                    </div>
                    <div class="p-1 text-[8px] text-slate-550 truncate bg-slate-100/50 border-t border-slate-200 flex items-center justify-between">
                        <span class="truncate pr-1 font-semibold">${fileObj.name}</span>
                    </div>
                    <button type="button" onclick="removeSelectedReplyFile(${index})" class="absolute top-1 right-1 w-4.5 h-4.5 rounded-full bg-rose-600 text-white flex items-center justify-center shadow hover:bg-rose-700 cursor-pointer transition-all border border-rose-500 text-[10px] font-bold" title="Delete">
                        ✕
                    </button>
                `;

                const urlInput = document.createElement('input');
                urlInput.type = 'hidden';
                urlInput.className = 'dynamic-reply-attachment-input';
                urlInput.name = 'attachment_urls[]';
                urlInput.value = fileObj.url;

                const nameInput = document.createElement('input');
                nameInput.type = 'hidden';
                nameInput.className = 'dynamic-reply-attachment-input';
                nameInput.name = 'attachment_names[]';
                nameInput.value = fileObj.name;

                replyPreviewContainer.appendChild(urlInput);
                replyPreviewContainer.appendChild(nameInput);
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
        const images = replySelectedFiles.filter(f => !f.isUploading && !f.error && f.url);
        
        if (images.length === 0) {
            galleryContainer.classList.add('hidden');
            return;
        }

        galleryContainer.classList.remove('hidden');

        images.forEach(fileObj => {
            const card = document.createElement('div');
            card.className = 'relative group rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shadow-sm';
            card.innerHTML = `
                <div class="block w-full h-20 overflow-hidden">
                    <img src="${fileObj.url}" class="w-full h-full object-cover">
                </div>
                <div class="bg-slate-100/85 p-1 text-[8px] text-slate-550 border-t border-slate-200 flex items-center justify-between">
                    <span class="truncate pr-2 font-medium">${fileObj.name}</span>
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
            let labelText = 'Like';
            let activeColorClass = 'text-slate-550 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200';

            if (data.active_type === 'like') { labelText = 'Like'; activeColorClass = 'text-blue-600 dark:text-blue-400 font-extrabold'; }
            else if (data.active_type === 'love') { labelText = 'Love'; activeColorClass = 'text-pink-600 dark:text-pink-500 font-extrabold'; iconText = 'favorite'; }
            else if (data.active_type === 'haha') { labelText = 'Haha'; activeColorClass = 'text-amber-500 font-extrabold'; iconText = 'sentiment_very_satisfied'; }
            else if (data.active_type === 'wow') { labelText = 'Wow'; activeColorClass = 'text-indigo-500 dark:text-indigo-400 font-extrabold'; iconText = 'sentiment_satisfied'; }
            else if (data.active_type === 'sad') { labelText = 'Sad'; activeColorClass = 'text-sky-500 font-extrabold'; iconText = 'sentiment_dissatisfied'; }
            else if (data.active_type === 'angry') { labelText = 'Angry'; activeColorClass = 'text-rose-600 font-extrabold'; iconText = 'sentiment_extremely_dissatisfied'; }

            btn.className = `flex items-center gap-0.5 text-[11px] font-bold transition-all cursor-pointer bg-transparent border-0 select-none ${activeColorClass}`;
            btn.innerHTML = `
                <span class="material-symbols-outlined text-sm">${iconText}</span>
                <span>${labelText}</span>
            `;

            // 2. Re-render Reactions Summary Bar
            const summaryBox = document.getElementById(`reactions-summary-${postId}`);
            if (summaryBox) {
                const total = data.total_count || 0;
                if (total === 0) {
                    summaryBox.classList.add('hidden');
                } else {
                    summaryBox.classList.remove('hidden');
                    
                    let iconsHtml = '';
                    if (data.stats.like) iconsHtml += '<span>👍</span>';
                    if (data.stats.love) iconsHtml += '<span>❤️</span>';
                    if (data.stats.haha) iconsHtml += '<span>😆</span>';
                    if (data.stats.wow) iconsHtml += '<span>😮</span>';
                    if (data.stats.sad) iconsHtml += '<span>😢</span>';
                    if (data.stats.angry) iconsHtml += '<span>😡</span>';
                    
                    const iconsContainer = summaryBox.querySelector('.flex.items-center.-space-x-1\\.5');
                    if (iconsContainer) {
                        iconsContainer.innerHTML = iconsHtml;
                    }
                    const textSpan = document.getElementById(`reactions-text-${postId}`);
                    if (textSpan) {
                        textSpan.innerText = data.reacts_sentence || '';
                    }
                }
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
                    toolbar: {
                        container: [
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            ['link', 'image', 'video'],
                            ['clean']
                        ],
                        handlers: {
                            image: selectEditPostLocalImage,
                            video: selectEditPostVideoOption
                        }
                    }
                }
            });

            // ImgBB Upload Widget value listener/interceptor for edit post
            const editPostContentInput = document.getElementById('edit-post-content-input');
            if (editPostContentInput) {
                const descriptor = Object.getOwnPropertyDescriptor(HTMLTextAreaElement.prototype, 'value');
                Object.defineProperty(editPostContentInput, 'value', {
                    get: function() {
                        return descriptor.get.call(this);
                    },
                    set: function(val) {
                        descriptor.set.call(this, val);
                        if (val) {
                            // Extract URL from BBCode [img]URL[/img] or HTML <img> tags
                            const imgRegex = /\[img\](.*?)\[\/img\]|<img[^>]+src="([^">]+)"/gi;
                            let match;
                            let foundUrls = [];
                            while ((match = imgRegex.exec(val)) !== null) {
                                const url = match[1] || match[2];
                                if (url && !foundUrls.includes(url)) {
                                    foundUrls.push(url);
                                }
                            }
                            
                            if (foundUrls.length > 0) {
                                foundUrls.forEach(url => {
                                    if (typeof editPostQuill !== 'undefined' && !isImageInQuill(editPostQuill, url)) {
                                        const range = editPostQuill.getSelection(true);
                                        editPostQuill.insertEmbed(range.index, 'image', url);
                                        editPostQuill.setSelection(range.index + 1);
                                    }
                                });
                                // Keep the synced editor content as final value
                                descriptor.set.call(this, editPostQuill.root.innerHTML);
                            }
                        }
                    }
                });
            }

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


    function selectEditPostVideoOption() {
        const range = editPostQuill.getSelection(true);
        Swal.fire({
            title: '🎥 Insert / Upload Video',
            html: `
                <div class="text-left text-xs space-y-3 text-slate-800 dark:text-slate-200">
                    <p class="text-slate-500 font-medium">To share videos, upload them to one of these <strong>5 free hosting servers</strong>, then copy and paste the video link below:</p>
                    <div class="grid grid-cols-2 gap-2.5 pt-1.5 pb-3">
                        <a href="https://sendvid.com" target="_blank" class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-50 dark:bg-slate-950/50 hover:bg-indigo-50/30 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-350 hover:text-indigo-600 font-bold transition-all text-xs">
                            <span class="material-symbols-outlined text-[18px] text-indigo-500">publish</span> Sendvid
                        </a>
                        <a href="https://streamable.com" target="_blank" class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-50 dark:bg-slate-950/50 hover:bg-sky-50/30 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-350 hover:text-sky-600 font-bold transition-all text-xs">
                            <span class="material-symbols-outlined text-[18px] text-sky-500">videocam</span> Streamable
                        </a>
                        <a href="https://youtube.com" target="_blank" class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-50 dark:bg-slate-950/50 hover:bg-red-50/30 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-350 hover:text-red-650 font-bold transition-all text-xs">
                            <span class="material-symbols-outlined text-[18px] text-red-500">play_circle</span> YouTube
                        </a>
                        <a href="https://vimeo.com" target="_blank" class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-50 dark:bg-slate-950/50 hover:bg-blue-50/30 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-350 hover:text-blue-650 font-bold transition-all text-xs">
                            <span class="material-symbols-outlined text-[18px] text-blue-500">movie</span> Vimeo
                        </a>
                        <a href="https://gofile.io" target="_blank" class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-50 dark:bg-slate-950/50 hover:bg-emerald-50/30 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-350 hover:text-emerald-600 font-bold transition-all text-xs col-span-2 justify-center">
                            <span class="material-symbols-outlined text-[18px] text-emerald-500">cloud_upload</span> GoFile Free Storage
                        </a>
                    </div>
                    <label class="block font-black text-slate-700 dark:text-slate-300 uppercase tracking-wider">Paste Video Embed / Share Link:</label>
                </div>
            `,
            input: 'url',
            inputPlaceholder: 'https://sendvid.com/embed/... or YouTube URL',
            showCancelButton: true,
            confirmButtonText: 'Insert Video',
            confirmButtonColor: '#0f172a',
            cancelButtonColor: '#e11d48'
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                videoUrl = getEmbedUrl(videoUrl);

                editPostQuill.insertEmbed(range.index, 'video', videoUrl);
                editPostQuill.setSelection(range.index + 1);
            }
        });
    }

    function selectEditPostLocalImage() {
        handleQuillImageInsertion(editPostQuill, 'edit-post-imgbb-upload-container', 'edit-post-quill-editor', '{{ route("media.upload") }}', '{{ csrf_token() }}');
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

    // Dynamic Expansion, Reactors, and Quotes Controller Functions
    let nextPageUrl = '{{ $posts->nextPageUrl() }}';
    let loadingReplies = false;

    function loadMoreReplies() {
        if (loadingReplies || !nextPageUrl) return;
        loadingReplies = true;
        
        const loadBtn = document.getElementById('load-more-btn');
        if (loadBtn) {
            loadBtn.disabled = true;
            loadBtn.innerHTML = '<span class="animate-spin inline-block mr-2">⏳</span> Loading more replies...';
        }

        fetch(nextPageUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('posts-list-container');
            if (container) {
                container.insertAdjacentHTML('beforeend', data.html);
            }

            if (window.setupHoverCardListeners) {
                window.setupHoverCardListeners();
            }
            setupCollapsibleQuotes();

            if (data.has_more) {
                nextPageUrl = nextPageUrl.split('?')[0] + '?page=' + data.next_page;
                if (loadBtn) {
                    loadBtn.disabled = false;
                    loadBtn.innerHTML = 'Show Older/More Replies...';
                }
            } else {
                nextPageUrl = null;
                const loadContainer = document.getElementById('load-more-container');
                if (loadContainer) {
                    loadContainer.remove();
                }
            }
            loadingReplies = false;
        })
        .catch(err => {
            console.error('Error loading more replies:', err);
            if (loadBtn) {
                loadBtn.disabled = false;
                loadBtn.innerHTML = 'Error loading. Click to retry...';
            }
            loadingReplies = false;
        });
    }

    let allReactorsData = [];
    let activeReactorsTab = 'all';

    function openReactorsModal(postId) {
        const modal = document.getElementById('reactors-modal');
        if (!modal) return;

        const listContainer = document.getElementById('reactors-modal-list');
        const tabsContainer = document.getElementById('reactors-modal-tabs');
        const titleHeader = document.getElementById('reactors-modal-title');
        
        titleHeader.innerText = `Members who reacted`;
        tabsContainer.innerHTML = '';
        listContainer.innerHTML = `
            <div class="flex items-center justify-center py-12 text-slate-400">
                <span class="animate-spin mr-2">⏳</span> Loading reactors...
            </div>
        `;

        modal.classList.remove('pointer-events-none');
        modal.classList.add('opacity-100');
        modal.firstElementChild.nextElementSibling.classList.remove('scale-95');
        modal.firstElementChild.nextElementSibling.classList.add('scale-100');

        fetch(`/posts/${postId}/reacts`)
            .then(res => res.json())
            .then(data => {
                allReactorsData = data;
                activeReactorsTab = 'all';
                renderReactorsTabs();
                renderReactorsList();
            })
            .catch(err => {
                listContainer.innerHTML = `
                    <div class="flex items-center justify-center py-12 text-rose-500 text-xs font-bold">
                        Failed to load reactors. Please try again.
                    </div>
                `;
                console.error(err);
            });
    }

    function closeReactorsModal() {
        const modal = document.getElementById('reactors-modal');
        if (!modal) return;

        modal.classList.add('pointer-events-none');
        modal.classList.remove('opacity-100');
        modal.firstElementChild.nextElementSibling.classList.add('scale-95');
        modal.firstElementChild.nextElementSibling.classList.remove('scale-100');
    }

    function renderReactorsTabs() {
        const tabsContainer = document.getElementById('reactors-modal-tabs');
        if (!tabsContainer) return;

        const counts = { all: allReactorsData.length };
        allReactorsData.forEach(r => {
            counts[r.type] = (counts[r.type] || 0) + 1;
        });

        const emojiMap = {
            like: '👍 Like',
            love: '❤️ Love',
            haha: '😆 Haha',
            wow: '😮 Wow',
            sad: '😢 Sad',
            angry: '😡 Angry'
        };

        let html = `
            <button onclick="switchReactorsTab('all')" class="pb-1 border-b-2 transition-all bg-transparent border-0 cursor-pointer ${activeReactorsTab === 'all' ? 'border-blue-500 text-blue-600 dark:text-blue-400 font-extrabold' : 'border-transparent text-slate-400 hover:text-slate-600 dark:hover:text-slate-200'}">
                All (${counts.all})
            </button>
        `;

        Object.keys(emojiMap).forEach(type => {
            if (counts[type]) {
                html += `
                    <button onclick="switchReactorsTab('${type}')" class="pb-1 border-b-2 transition-all bg-transparent border-0 cursor-pointer flex items-center gap-1 ${activeReactorsTab === type ? 'border-blue-500 text-blue-600 dark:text-blue-400 font-extrabold' : 'border-transparent text-slate-400 hover:text-slate-600 dark:hover:text-slate-200'}">
                        ${emojiMap[type]} (${counts[type]})
                    </button>
                `;
            }
        });

        tabsContainer.innerHTML = html;
    }

    function switchReactorsTab(tab) {
        activeReactorsTab = tab;
        renderReactorsTabs();
        renderReactorsList();
    }

    function renderReactorsList() {
        const listContainer = document.getElementById('reactors-modal-list');
        if (!listContainer) return;

        const filtered = activeReactorsTab === 'all'
            ? allReactorsData
            : allReactorsData.filter(r => r.type === activeReactorsTab);

        if (filtered.length === 0) {
            listContainer.innerHTML = `
                <div class="flex items-center justify-center py-12 text-slate-400 text-xs">
                    No reactions to show.
                </div>
            `;
            return;
        }

        const emojiCharMap = {
            like: '👍',
            love: '❤️',
            haha: '😆',
            wow: '😮',
            sad: '😢',
            angry: '😡'
        };

        let html = '';
        filtered.forEach(r => {
            const avatar = r.avatar_url;
            const emoji = emojiCharMap[r.type] || '👍';
            
            html += `
                <div class="flex items-center justify-between py-3">
                    <div class="flex items-center gap-3">
                        <a href="/profile/${encodeURIComponent(r.name)}" class="w-10 h-10 border border-slate-200/50 dark:border-slate-800/50 overflow-hidden flex items-center justify-center select-none bg-slate-100 dark:bg-slate-900 rounded-none shrink-0">
                            <img src="${avatar}" alt="${r.name}" class="w-full h-full object-cover">
                        </a>
                        <div class="flex flex-col text-left">
                            <a href="/profile/${encodeURIComponent(r.name)}" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">
                                ${r.name}
                            </a>
                            <span class="text-[10px] text-slate-450 dark:text-slate-500 font-bold mt-0.5">
                                ${r.title_badge}
                            </span>
                            <div class="text-[9px] text-slate-400 dark:text-slate-500 font-medium mt-0.5 flex items-center gap-1">
                                <span>Posts: ${r.posts_count}</span>
                                <span>·</span>
                                <span>Reactions: ${r.reactions_count}</span>
                                <span>·</span>
                                <span>Points: ${r.activity_points}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-1 shrink-0">
                        <span class="text-base">${emoji}</span>
                        <span class="text-[9px] text-slate-400 dark:text-slate-500 font-semibold">${r.reacted_at}</span>
                    </div>
                </div>
            `;
        });

        listContainer.innerHTML = html;
    }

    function setupCollapsibleQuotes() {
        document.querySelectorAll('.ql-editor blockquote').forEach(bq => {
            if (bq.dataset.quoteInitialized) return;
            bq.dataset.quoteInitialized = "true";

            // If blockquote is long, collapse it and add expand button
            if (bq.scrollHeight > 110) {
                bq.classList.add('quote-collapsed');
                
                const btn = document.createElement('button');
                btn.className = 'quote-expand-btn';
                btn.type = 'button';
                btn.innerText = 'Click to expand...';
                
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    bq.classList.remove('quote-collapsed');
                    btn.remove();
                });
                
                bq.appendChild(btn);
            }
        });
    }

    function addMultiQuote(username, postId) {
        if (!replyQuill) return;
        const postElement = document.querySelector(`#post-${postId} .ql-editor`);
        const originalContent = postElement ? postElement.innerHTML.trim() : '';
        
        const quoteHtml = `<blockquote class="border-l-4 border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/60 pl-3 py-1 my-2 text-slate-550 text-xs italic font-sans" data-quoted-post="${postId}"><strong>Post by @${username}:</strong><br>${originalContent}</blockquote><p><br></p>`;
        
        const range = replyQuill.getSelection(true);
        replyQuill.clipboard.dangerouslyPasteHTML(range.index, quoteHtml);
        replyQuill.setSelection(replyQuill.getLength());
        
        if (typeof Swal !== 'undefined') {
            const toast = Swal.mixin({
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            toast.fire({
                icon: 'success',
                title: `Quote from @${username} added to reply.`
            });
        }
    }

    document.addEventListener('DOMContentLoaded', setupCollapsibleQuotes);
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

<!-- ImgBB Upload Widget Plugins -->
<script async src="https://imgbb.com/upload.js" 
        data-auto-insert="bbcode-embed-medium" 
        data-sibling-selector="#reply-imgbb-upload-container" 
        data-sibling-position="after">
</script>
<script async src="https://imgbb.com/upload.js" 
        data-auto-insert="bbcode-embed-medium" 
        data-sibling-selector="#edit-post-imgbb-upload-container" 
        data-sibling-position="after">
<!-- High-End Real-Time Reaction Details Modal -->
<div id="reactors-modal" class="fixed inset-0 z-50 flex items-center justify-center pointer-events-none opacity-0 transition-all duration-300">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-slate-950/40 backdrop-blur-[3px]" onclick="closeReactorsModal()"></div>
    
    <!-- Modal content -->
    <div class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-none shadow-2xl max-w-lg w-full mx-4 overflow-hidden transform transition-all duration-300 scale-95 z-55 flex flex-col max-h-[85vh]">
        <!-- Header -->
        <div class="bg-slate-50 dark:bg-slate-950/60 px-5 py-4 border-b border-slate-200 dark:border-slate-850 flex items-center justify-between shrink-0">
            <h3 id="reactors-modal-title" class="font-extrabold text-slate-800 dark:text-white text-xs">
                Members who reacted
            </h3>
            <button type="button" onclick="closeReactorsModal()" class="text-slate-400 hover:text-slate-650 dark:hover:text-white cursor-pointer transition-colors bg-transparent border-0 p-0 flex items-center">
                <span class="material-symbols-outlined text-sm">close</span>
            </button>
        </div>
        
        <!-- Tab Bar -->
        <div id="reactors-modal-tabs" class="flex gap-4 px-5 py-3 border-b border-slate-200/60 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-900/50 text-[11px] font-bold overflow-x-auto whitespace-nowrap shrink-0">
            <!-- Dynamically populated -->
        </div>

        <!-- Reactor List (Scrollable) -->
        <div id="reactors-modal-list" class="p-5 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800/50 max-h-[50vh] scrollbar-thin">
            <!-- Dynamically populated -->
        </div>
    </div>
</div>

<style>
/* Custom blockquote collapse & expand */
.ql-editor blockquote {
    background: rgba(241, 245, 249, 0.4) !important;
    border-left: 3px solid #cbd5e1 !important;
    padding: 10px 14px !important;
    margin: 8px 0 !important;
    font-size: 11px !important;
    color: #475569 !important;
    position: relative;
    overflow: hidden;
    transition: max-height 0.3s ease-out;
}
.dark .ql-editor blockquote {
    background: rgba(15, 23, 42, 0.35) !important;
    border-left: 3px solid #334155 !important;
    color: #94a3b8 !important;
}
.quote-collapsed {
    max-height: 95px !important;
    padding-bottom: 24px !important;
}
.quote-expand-btn {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 24px;
    background: linear-gradient(to top, rgba(241, 245, 249, 0.98), rgba(241, 245, 249, 0));
    color: #3b82f6;
    font-size: 10px;
    font-weight: 800;
    text-align: center;
    line-height: 24px;
    cursor: pointer;
    border: 0;
    width: 100%;
}
.dark .quote-expand-btn {
    background: linear-gradient(to top, rgba(15, 23, 42, 0.98), rgba(15, 23, 42, 0));
    color: #60a5fa;
}

/* Custom Scrollbar for Modal list */
.scrollbar-thin::-webkit-scrollbar {
    width: 4px;
    height: 4px;
}
.scrollbar-thin::-webkit-scrollbar-track {
    background: transparent;
}
.scrollbar-thin::-webkit-scrollbar-thumb {
    background: #cbd5e1;
}
.dark .scrollbar-thin::-webkit-scrollbar-thumb {
    background: #334155;
}
</style>
@endsection
