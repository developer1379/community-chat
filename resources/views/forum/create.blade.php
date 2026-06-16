@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Header path info -->
        <div class="px-4 sm:px-0">
            <div class="flex items-center gap-2 text-[10px] sm:text-xs font-semibold text-slate-500 mb-2 flex-wrap">
                <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors">Forums</a>
                <span class="text-slate-355">/</span>
                <a href="{{ route('categories.show', $category->slug) }}" id="breadcrumb-category"
                    class="hover:text-blue-600 transition-colors">{{ $category->name }}</a>
                <span class="text-slate-355">/</span>
                <span class="text-blue-600 font-bold">Create New Thread</span>
            </div>
            <h1 class="text-xl sm:text-3xl font-black text-slate-900 tracking-tight leading-tight">Post a New Discussion
            </h1>
            <p class="text-[11px] sm:text-xs text-slate-555 leading-relaxed mt-1">
                Share your insights, showcase media, or ask questions in the <span id="description-category"
                    class="text-blue-600 font-bold">{{ $category->name }}</span> room.
            </p>
        </div>

        <!-- Creation Form Card -->
        <div
            class="bg-white rounded-none sm:rounded-[2rem] border-y sm:border border-slate-200 shadow-xl overflow-hidden relative">
            <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500"></div>

            <div class="bg-slate-50/80 px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <span class="text-xs font-bold text-slate-700 uppercase tracking-widest flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm text-blue-600 animate-pulse">edit_note</span>
                    Draft Thread Details
                </span>
                <span
                    class="text-[10px] font-bold text-slate-400 bg-white border border-slate-200 px-2 py-0.5 rounded-full hidden sm:inline-block">Draft
                    autosaved</span>
            </div>

            <form id="thread-form" action="{{ route('threads.store') }}" method="POST" enctype="multipart/form-data"
                class="p-5 sm:p-8 space-y-6">
                @csrf

                <!-- Grid container for Title and Category -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <!-- Category Dropdown Select -->
                    <div class="space-y-1.5">
                        <label for="category_id"
                            class="text-[11px] font-black text-slate-700 uppercase tracking-widest ml-1">Select Board
                            Room</label>
                        <div class="relative">
                            <!-- Hidden Input to submit the category ID -->
                            <input type="hidden" id="category_id" name="category_id" value="{{ $category->id }}">

                            <!-- Trigger Box -->
                            <div id="category-dropdown-trigger" onclick="toggleCategoryDropdown()"
                                class="w-full bg-slate-50/50 hover:bg-slate-50 border border-slate-200 rounded-2xl px-4 py-[11px] flex items-center justify-between text-slate-800 text-xs sm:text-sm font-semibold focus-within:ring-2 focus-within:ring-blue-550 transition-all cursor-pointer shadow-inner shadow-slate-100/50 select-none">
                                <div class="flex items-center gap-2.5 min-w-0" id="selected-category-display">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 border border-slate-150 shadow-sm flex-shrink-0 overflow-hidden"
                                        id="selected-category-icon">
                                        @if(\Illuminate\Support\Str::startsWith($category->icon, ['http://', 'https://']) || \Illuminate\Support\Str::contains($category->icon, '/'))
                                            <img src="{{ $category->icon }}" alt="" class="w-full h-full object-cover">
                                        @elseif($category->icon == 'chat-bubble-left-right')
                                            <span class="material-symbols-outlined text-base">forum</span>
                                        @elseif($category->icon == 'photo')
                                            <span class="material-symbols-outlined text-base">photo_library</span>
                                        @elseif($category->icon == 'sparkles')
                                            <span class="material-symbols-outlined text-base">auto_awesome</span>
                                        @elseif(\Illuminate\Support\Str::startsWith($category->icon, 'fa'))
                                            <i class="{{ $category->icon }} text-xs"></i>
                                        @else
                                            <span
                                                class="material-symbols-outlined text-base">{{ $category->icon ?: 'tag' }}</span>
                                        @endif
                                    </div>
                                    <span class="truncate" id="selected-category-name">{{ $category->name }}</span>
                                </div>
                                <span class="material-symbols-outlined text-slate-400 text-[18px]">unfold_more</span>
                            </div>

                            <!-- Dropdown Options List -->
                            <div id="category-dropdown-options"
                                class="absolute left-0 right-0 mt-2 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 max-h-64 overflow-y-auto hidden">
                                <div class="p-1.5 space-y-1">
                                    @foreach($categories as $cat)
                                        <div onclick="selectCategory('{{ $cat->id }}', '{{ addslashes($cat->name) }}', '{{ $cat->slug }}', '{{ $cat->icon }}')"
                                            class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 cursor-pointer transition-colors">
                                            <div
                                                class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 border border-slate-150 shadow-sm flex-shrink-0 overflow-hidden">
                                                @if(\Illuminate\Support\Str::startsWith($cat->icon, ['http://', 'https://']) || \Illuminate\Support\Str::contains($cat->icon, '/'))
                                                    <img src="{{ $cat->icon }}" alt="" class="w-full h-full object-cover">
                                                @elseif($cat->icon == 'chat-bubble-left-right')
                                                    <span class="material-symbols-outlined text-base">forum</span>
                                                @elseif($cat->icon == 'photo')
                                                    <span class="material-symbols-outlined text-base">photo_library</span>
                                                @elseif($cat->icon == 'sparkles')
                                                    <span class="material-symbols-outlined text-base">auto_awesome</span>
                                                @elseif(\Illuminate\Support\Str::startsWith($cat->icon, 'fa'))
                                                    <i class="{{ $cat->icon }} text-xs"></i>
                                                @else
                                                    <span
                                                        class="material-symbols-outlined text-base">{{ $cat->icon ?: 'tag' }}</span>
                                                @endif
                                            </div>
                                            <div class="text-left min-w-0">
                                                <div class="font-bold text-slate-800 text-xs sm:text-sm truncate">
                                                    {{ $cat->name }}</div>
                                                @if($cat->description)
                                                    <div class="text-[10px] text-slate-400 font-medium truncate max-w-xs">
                                                        {{ $cat->description }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @error('category_id')
                            <p class="text-xs text-rose-500 mt-1 font-bold ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Title Input -->
                    <div class="md:col-span-2 space-y-1.5">
                        <label for="title"
                            class="text-[11px] font-black text-slate-700 uppercase tracking-widest ml-1">Thread
                            Title</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-slate-400 text-[18px]">title</span>
                            </span>
                            <input type="text" id="title" name="title" value="{{ old('title') }}"
                                class="w-full bg-slate-50/50 border border-slate-200 rounded-2xl pl-11 pr-4 py-3.5 text-slate-800 text-xs sm:text-sm font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all placeholder:text-slate-400 placeholder:font-medium shadow-inner shadow-slate-100/50"
                                placeholder="Give your thread a clean, descriptive title..." required>
                        </div>
                        @error('title')
                            <p class="text-xs text-rose-500 mt-1 font-bold ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Tags Input & Popular Suggestions -->
                <div class="space-y-2">
                    <label for="tags_input"
                        class="text-[11px] font-black text-slate-700 uppercase tracking-widest ml-1">Discussion Tags</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-400 text-[18px]">sell</span>
                        </span>
                        <input type="text" id="tags_input"
                            class="w-full bg-slate-50/50 border border-slate-200 rounded-2xl pl-11 pr-4 py-3.5 text-slate-800 text-xs sm:text-sm font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all placeholder:text-slate-400 placeholder:font-medium shadow-inner shadow-slate-100/50"
                            placeholder="Type a tag & press Enter or comma (e.g. laravel, css)...">
                        <input type="hidden" id="real_tags" name="tags" value="{{ old('tags') }}">
                    </div>

                    <!-- Dynamic Tag Capsules Container -->
                    <div id="tags-capsules-container" class="flex flex-wrap gap-1.5 pt-1">
                        <!-- Pills injected here -->
                    </div>

                    <!-- Preselected Popular Tag Suggestions helper -->
                    <div class="pt-1.5 flex flex-wrap items-center gap-2">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider">Quick
                            Suggestions:</span>
                        <div class="flex flex-wrap gap-1">
                            @foreach(['laravel', 'webdev', 'tailwind', 'help', 'design', 'showcase'] as $popularTag)
                                <button type="button" onclick="toggleQuickTag('{{ $popularTag }}')"
                                    id="quick-tag-{{ $popularTag }}"
                                    class="px-2.5 py-1 rounded-xl bg-slate-100 hover:bg-blue-50 text-[10px] font-bold text-slate-600 hover:text-blue-600 transition-colors shadow-sm cursor-pointer border border-transparent">
                                    #{{ $popularTag }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400 font-medium">Add up to 5 descriptive tags to categorize your
                        thread.</p>
                </div>

                <!-- Content Area (Quill Rich Text Editor) -->
                <div class="space-y-1.5">
                    <label for="quill-editor"
                        class="text-[11px] font-black text-slate-700 uppercase tracking-widest ml-1">Discussion
                        Content</label>
                    <!-- Hidden real field -->
                    <textarea id="content-input" name="content" class="hidden" readonly>{{ old('content') }}</textarea>

                    <!-- Quill container with custom HSL overrides -->
                    <div
                        class="rounded-2xl border border-slate-200 bg-slate-50/50 focus-within:ring-2 focus-within:ring-blue-500/10 focus-within:border-blue-500 transition-all relative z-30">
                        <style>
                            .ql-toolbar.ql-snow {
                                position: relative;
                                z-index: 10;
                                border-top-left-radius: 1rem !important;
                                border-top-right-radius: 1rem !important;
                            }

                            .ql-container.ql-snow {
                                border-bottom-left-radius: 1rem !important;
                                border-bottom-right-radius: 1rem !important;
                            }

                            /* Custom ImgBB Upload Button Styling to match premium theme */
                            .imgbb-container {
                                display: inline-block !important;
                                margin-top: 8px !important;
                                margin-bottom: 8px !important;
                            }

                            .imgbb-button {
                                display: inline-flex !important;
                                align-items: center !important;
                                gap: 6px !important;
                                background-color: #f1f5f9 !important; /* light slate */
                                color: #334155 !important;
                                border: 1px solid #cbd5e1 !important;
                                border-radius: 0.75rem !important; /* rounded-xl */
                                padding: 0.5rem 1rem !important;
                                font-size: 11px !important;
                                font-weight: 700 !important;
                                font-family: inherit !important;
                                cursor: pointer !important;
                                transition: all 0.2s !important;
                                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
                            }

                            .imgbb-button:hover {
                                background-color: #e2e8f0 !important;
                                color: #0f172a !important;
                                border-color: #94a3b8 !important;
                            }

                            .dark .imgbb-button {
                                background-color: #1e293b !important; /* slate-800 */
                                color: #cbd5e1 !important;
                                border-color: #334155 !important;
                            }

                            .dark .imgbb-button:hover {
                                background-color: #334155 !important; /* slate-700 */
                                color: #f8fafc !important;
                                border-color: #475569 !important;
                            }
                        </style>
                        <div id="quill-editor" class="bg-white rounded-b-2xl" style="height: 300px; font-size: 13.5px;">
                            {!! old('content') !!}</div>
                    </div>

                    <!-- ImgBB Upload Widget target container -->
                    <div id="imgbb-upload-container" class="mt-2 text-left"></div>

                    @error('content')
                        <p class="text-xs text-rose-500 mt-1 font-bold ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Custom Drag and Drop Uploader Block -->
                <div class="space-y-3 bg-slate-50/50 p-5 rounded-3xl border border-slate-200/80">
                    <label class="block text-[11px] font-black text-slate-700 uppercase tracking-widest">📎 Media Showroom
                        Uploads (Images / GIFs)</label>

                    <!-- Interactive Dropzone card -->
                    <div onclick="document.getElementById('media-input').click()"
                        class="border-2 border-dashed border-slate-250 hover:border-blue-500 rounded-2xl p-6 sm:p-8 flex flex-col items-center justify-center text-center cursor-pointer bg-white/60 hover:bg-blue-50/10 transition-all group shadow-sm">
                        <div
                            class="w-12 h-12 rounded-xl bg-slate-100 group-hover:bg-blue-100 flex items-center justify-center text-slate-500 group-hover:text-blue-600 transition-colors shadow-inner mb-3">
                            <span class="material-symbols-outlined text-2xl">cloud_upload</span>
                        </div>
                        <h4 class="font-bold text-slate-800 text-xs sm:text-sm">Click to Select Files</h4>
                        <p class="text-[10px] text-slate-400 mt-1 leading-normal max-w-sm">Drag & drop or browse from
                            storage. High-resolution JPEG, PNG, or dynamic GIF animations supported (Max: 5MB per file).</p>

                        <!-- Real hidden uploader input -->
                        <input type="file" id="media-input" name="attachments[]" multiple class="hidden" accept="image/*">
                    </div>

                    @error('attachments.*')
                        <p class="text-xs text-rose-500 mt-1 font-bold ml-1">{{ $message }}</p>
                    @enderror

                    <!-- Dynamic Previews Grid -->
                    <div id="preview-container"
                        class="hidden grid grid-cols-2 sm:grid-cols-4 gap-3 pt-4 border-t border-slate-200/60">
                        <!-- Selected previews rendered dynamically by JS -->
                    </div>
                </div>

                <!-- Action buttons (Highly optimized for Mobile Stacking & Touch Sizes) -->
                <div
                    class="flex flex-col-reverse sm:flex-row items-center sm:justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('categories.show', $category->slug) }}"
                        class="w-full sm:w-auto text-center bg-slate-100 hover:bg-slate-200/80 text-xs sm:text-sm font-bold text-slate-750 py-3.5 px-6 rounded-2xl transition-all cursor-pointer">
                        Cancel Drafting
                    </a>
                    <button type="button" onclick="showLivePreview()"
                        class="w-full sm:w-auto py-3.5 px-6 rounded-2xl border border-slate-350 bg-white hover:bg-slate-50 text-slate-755 font-bold text-xs sm:text-sm cursor-pointer transition-all flex items-center justify-center gap-2 shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">visibility</span> Preview Content
                    </button>
                    <button type="submit"
                        class="w-full sm:w-auto relative group overflow-hidden bg-slate-900 hover:bg-slate-800 text-xs sm:text-sm font-bold text-white py-3.5 px-8 rounded-2xl shadow-lg shadow-slate-900/10 cursor-pointer transition-all">
                        <span class="relative z-10 flex items-center justify-center gap-1.5">
                            Publish Thread
                            <span
                                class="material-symbols-outlined text-[18px] group-hover:translate-x-0.5 transition-transform">send</span>
                        </span>
                        <div
                            class="absolute inset-0 h-full w-full bg-gradient-to-r from-blue-600 to-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                    </button>
                </div>
            </form>
        </div>

        <!-- MOBILE-FRIENDLY ACCORDION LIVE PREVIEW BLOCK -->
        <div id="live-preview-box" class="hidden space-y-3.5 transition-all">
            <div class="flex items-center justify-between px-1">
                <h2 class="text-xs font-black text-slate-500 uppercase tracking-widest flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-blue-600 text-[18px]">analytics</span> Professional Thread
                    Mockup
                </h2>
                <button onclick="closeLivePreview()"
                    class="text-xs font-bold text-rose-600 hover:underline cursor-pointer flex items-center gap-1">
                    <span class="material-symbols-outlined text-base">visibility_off</span> Hide Preview
                </button>
            </div>

            <div
                class="bg-white rounded-none sm:rounded-[2rem] border-y sm:border border-slate-200 shadow-xl overflow-hidden flex flex-col md:flex-row relative">
                <!-- Left Info Panel Mockup -->
                <div
                    class="w-full md:w-48 bg-slate-50 p-6 flex flex-col items-center border-b md:border-b-0 md:border-r border-slate-200 text-center flex-shrink-0">
                    <div
                        class="w-16 h-16 rounded-2xl overflow-hidden border border-slate-300 shadow-inner mb-3 bg-blue-50 flex items-center justify-center font-bold text-blue-600 text-lg">
                        <img src="{{ Auth::user()->avatar_url }}" class="w-full h-full object-cover" alt="avatar">
                    </div>
                    <h3 class="font-extrabold text-slate-800 text-xs truncate max-w-full">{{ Auth::user()->name }}</h3>
                    <span
                        class="text-[8px] px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider text-white mt-1.5 border border-slate-350 shadow-sm"
                        style="background: {{ Auth::user()->banner_color ?? '#2563eb' }}">
                        {{ Auth::user()->title_badge ?? 'Member' }}
                    </span>
                    <div class="mt-4 w-full pt-4 border-t border-slate-200 text-[9px] text-slate-450 space-y-1.5 text-left">
                        <div class="flex justify-between font-bold">
                            <span>Joined:</span>
                            <span class="text-slate-600">{{ Auth::user()->created_at->format('M Y') }}</span>
                        </div>
                        <div class="flex justify-between font-bold">
                            <span>Threads:</span>
                            <span class="text-slate-600">{{ Auth::user()->threads()->count() + 1 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Post Content Panel -->
                <div class="flex-grow p-6 sm:p-8 flex flex-col justify-between min-w-0">
                    <div class="space-y-4">
                        <div class="border-b border-slate-100 pb-3 flex justify-between items-center">
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Just Now • Live
                                Preview</span>
                            <span id="preview-category"
                                class="text-[10px] font-black text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded uppercase tracking-wider">{{ $category->name }}</span>
                        </div>

                        <!-- Thread Title -->
                        <h2 id="preview-title"
                            class="text-base sm:text-xl font-black text-slate-900 leading-tight break-words"></h2>

                        <!-- Previews tags capsules -->
                        <div id="preview-tags" class="flex flex-wrap gap-1 pt-1"></div>

                        <!-- Text content -->
                        <div id="preview-body"
                            class="text-slate-700 text-xs sm:text-sm leading-relaxed whitespace-pre-wrap font-sans ql-snow ql-editor !p-0">
                        </div>

                        <!-- Selected Attachment Gallery Mockup inside preview -->
                        <div id="preview-gallery-container" class="hidden pt-4 border-t border-slate-100">
                            <h4 class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-2">📎 Uploaded
                                Attachments</h4>
                            <div id="preview-gallery-grid" class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                <!-- Selected images will clone inside here -->
                            </div>
                        </div>
                    </div>

                    @if(Auth::user()->signature)
                        <div
                            class="mt-6 pt-4 border-t border-slate-200 border-dashed text-[10px] text-slate-500 font-medium italic">
                            {{ Auth::user()->signature }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- JS Controller for Live Selection and Removal + Dynamic Document Previews + Quill Editor -->
    <script>
        // Trigger programmatic click on ImgBB upload widget button
        function triggerImgBBWidget(editorId, containerId) {
            const container = document.getElementById(containerId);
            let widgetBtn = null;
            if (container) {
                let next = container.nextElementSibling;
                while (next) {
                    if (next.classList.contains('imgbb-container')) {
                        widgetBtn = next.querySelector('button');
                        break;
                    }
                    const btn = next.querySelector('button[data-imgbb-trigger]');
                    if (btn) {
                        widgetBtn = btn;
                        break;
                    }
                    next = next.nextElementSibling;
                }
            }
            if (!widgetBtn) {
                const quillContainer = document.getElementById(editorId);
                if (quillContainer) {
                    let targetId = quillContainer.getAttribute('data-imgbb-target');
                    if (!targetId) {
                        const qlEditor = quillContainer.querySelector('.ql-editor');
                        if (qlEditor) {
                            targetId = qlEditor.getAttribute('data-imgbb-target');
                        }
                    }
                    if (targetId) {
                        widgetBtn = document.querySelector(`[data-imgbb-id="${targetId}"]`);
                    }
                }
            }
            if (!widgetBtn) {
                widgetBtn = document.querySelector(`[data-sibling-selector="#${containerId}"]`) || document.querySelector('button[data-imgbb-trigger]');
            }
            if (widgetBtn) {
                widgetBtn.click();
            } else {
                window.open('https://imgbb.com/upload', '_blank');
            }
        }

        // Global message interceptor for ImgBB manual uploads
        window.addEventListener("message", function(event) {
            if (event.data && typeof event.data === 'object' && event.data.id && event.data.message) {
                const msg = event.data.message;
                const imgRegex = /\[img\](.*?)\[\/img\]|<img[^>]+src="([^">]+)"|https?:\/\/[^\s]+(?:\.png|\.jpg|\.jpeg|\.gif)/gi;
                let match;
                let foundUrls = [];
                while ((match = imgRegex.exec(msg)) !== null) {
                    const url = match[1] || match[2] || match[0];
                    if (url && !foundUrls.includes(url)) {
                        foundUrls.push(url);
                    }
                }
                
                if (foundUrls.length > 0) {
                    const id = event.data.id;
                    let targetEditor = null;
                    
                    const mainEditor = document.getElementById('quill-editor');
                    const mainInput = document.getElementById('content-input');
                    if ((mainEditor && (mainEditor.getAttribute('data-imgbb-target') === id || mainEditor.querySelector('.ql-editor')?.getAttribute('data-imgbb-target') === id)) ||
                        (mainInput && mainInput.getAttribute('data-imgbb-target') === id)) {
                        targetEditor = typeof quill !== 'undefined' ? quill : null;
                    }
                    
                    const replyEditor = document.getElementById('reply-quill-editor');
                    const replyInput = document.getElementById('reply-content-input');
                    if ((replyEditor && (replyEditor.getAttribute('data-imgbb-target') === id || replyEditor.querySelector('.ql-editor')?.getAttribute('data-imgbb-target') === id)) ||
                        (replyInput && replyInput.getAttribute('data-imgbb-target') === id)) {
                        targetEditor = typeof replyQuill !== 'undefined' ? replyQuill : null;
                    }
                    
                    const editEditor = document.getElementById('edit-post-quill-editor');
                    const editInput = document.getElementById('edit-post-content-input');
                    if ((editEditor && (editEditor.getAttribute('data-imgbb-target') === id || editEditor.querySelector('.ql-editor')?.getAttribute('data-imgbb-target') === id)) ||
                        (editInput && editInput.getAttribute('data-imgbb-target') === id)) {
                        targetEditor = typeof editPostQuill !== 'undefined' ? editPostQuill : null;
                    }
                    
                    if (!targetEditor) {
                        if (typeof quill !== 'undefined') targetEditor = quill;
                        else if (typeof replyQuill !== 'undefined') targetEditor = replyQuill;
                        else if (typeof editPostQuill !== 'undefined') targetEditor = editPostQuill;
                    }
                    
                    if (targetEditor) {
                        foundUrls.forEach(url => {
                            const range = targetEditor.getSelection(true);
                            targetEditor.insertEmbed(range.index, 'image', url);
                            targetEditor.setSelection(range.index + 1);
                        });
                        
                        // Clean up any raw BBCode appended to the editor's innerHTML
                        setTimeout(() => {
                            const editorEl = targetEditor.root;
                            if (editorEl) {
                                let html = editorEl.innerHTML;
                                if (html.includes('[img]') || html.includes('[/img]')) {
                                    html = html.replace(/\[url=.*?\]\[img\].*?\[\/img\]\[\/url\]|\[img\].*?\[\/img\]/gi, '');
                                    editorEl.innerHTML = html;
                                }
                            }
                        }, 50);
                    }
                }
            }
        });

        let selectedFiles = [];
        const mediaInput = document.getElementById('media-input');
        const previewContainer = document.getElementById('preview-container');

        // Initialize Quill Editor
        let quill;
        document.addEventListener('DOMContentLoaded', function () {
            quill = new Quill('#quill-editor', {
                theme: 'snow',
                modules: {
                    toolbar: {
                        container: [
                            [{ 'font': [] }],
                            [{ 'header': [1, 2, 3, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'color': [] }, { 'background': [] }],
                            ['blockquote', 'code-block'],
                            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                            ['link', 'image', 'video'],
                            ['clean']
                        ],
                        handlers: {
                            image: selectLocalImage,
                            video: selectVideoOption
                        }
                    }
                },
                placeholder: 'Write your thread discussion, code blocks, or markdown content here...'
            });

            // ImgBB Upload Widget value listener/interceptor
            const contentInput = document.getElementById('content-input');
            if (contentInput) {
                const descriptor = Object.getOwnPropertyDescriptor(HTMLTextAreaElement.prototype, 'value');
                Object.defineProperty(contentInput, 'value', {
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
                                    const range = quill.getSelection(true);
                                    quill.insertEmbed(range.index, 'image', url);
                                    quill.setSelection(range.index + 1);
                                });
                                // Keep the synced editor content as final value
                                descriptor.set.call(this, quill.root.innerHTML);
                            }
                        }
                    }
                });
            }

            // Intercept form submit to sync Quill HTML content to the hidden content input
            const form = document.getElementById('thread-form');
            if (form) {
                form.addEventListener('submit', function (e) {
                    const contentInput = document.getElementById('content-input');
                    // Use html content from Quill
                    contentInput.value = quill.root.innerHTML;

                    // If content is empty or only whitespace HTML, fail gracefully
                    const textOnly = quill.getText().trim();
                    if (textOnly.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Empty Content',
                            text: 'Please enter some content for your thread discussion.',
                            confirmButtonColor: '#0f172a'
                        });
                        e.preventDefault();
                    }
                });
            }
        });

        function selectVideoOption() {
            const range = quill.getSelection(true);
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
                confirmButtonColor: '#0f172a',
                cancelButtonColor: '#e11d48'
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    let videoUrl = result.value.trim();
                    
                    // YouTube watch link
                    if (videoUrl.includes('youtube.com/watch?v=')) {
                        let parts = videoUrl.split('watch?v=');
                        if (parts[1]) {
                            let id = parts[1].split('&')[0];
                            videoUrl = `https://www.youtube.com/embed/${id}`;
                        }
                    }
                    // YouTube shortened link
                    else if (videoUrl.includes('youtu.be/')) {
                        const id = videoUrl.split('/').pop().split('?')[0];
                        videoUrl = `https://www.youtube.com/embed/${id}`;
                    }
                    // Vimeo watch link
                    else if (videoUrl.includes('vimeo.com/') && !videoUrl.includes('player.vimeo.com')) {
                        const id = videoUrl.split('/').pop().split('?')[0];
                        videoUrl = `https://player.vimeo.com/video/${id}`;
                    }
                    // Sendvid watch link
                    else if (videoUrl.includes('sendvid.com/') && !videoUrl.includes('/embed/')) {
                        const id = videoUrl.split('/').pop().split('?')[0];
                        videoUrl = `https://sendvid.com/embed/${id}`;
                    }
                    // Streamable watch link
                    else if (videoUrl.includes('streamable.com/') && !videoUrl.includes('/e/')) {
                        const id = videoUrl.split('/').pop().split('?')[0];
                        videoUrl = `https://streamable.com/e/${id}`;
                    }

                    quill.insertEmbed(range.index, 'video', videoUrl);
                    quill.setSelection(range.index + 1);
                }
            });
        }

        // Custom image handler for Quill to upload to ImgBB or insert URL
        function selectLocalImage() {
            Swal.fire({
                title: '🖼️ Insert Image',
                text: 'Select how you want to add an image to your post:',
                icon: 'question',
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: '📤 Upload File',
                denyButtonText: '🔗 Paste Image URL',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#0f172a',
                denyButtonColor: '#4f46e5',
                cancelButtonColor: '#e11d48',
            }).then((result) => {
                if (result.isConfirmed) {
                    const input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/jpeg, image/png, image/jpg, image/gif');
                    input.click();

                    input.onchange = () => {
                        const file = input.files[0];
                        if (/^image\//.test(file.type)) {
                            uploadImageToImgBB(file);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Invalid File',
                                text: 'Only image files (JPEG, PNG, JPG, GIF) are allowed.',
                                confirmButtonColor: '#0f172a'
                            });
                        }
                    };
                } else if (result.isDenied) {
                    Swal.fire({
                        title: '🔗 Paste Image URL',
                        input: 'url',
                        inputPlaceholder: 'https://example.com/image.jpg',
                        showCancelButton: true,
                        confirmButtonText: 'Insert Image',
                        confirmButtonColor: '#0f172a',
                        cancelButtonColor: '#e11d48',
                        inputValidator: (value) => {
                            if (!value) {
                                return 'You need to write something!';
                            }
                            if (!value.match(/^https?:\/\/.+/)) {
                                return 'Please enter a valid URL!';
                            }
                        }
                    }).then((urlResult) => {
                        if (urlResult.isConfirmed && urlResult.value) {
                            const range = quill.getSelection(true);
                            quill.insertEmbed(range.index, 'image', urlResult.value.trim());
                            quill.setSelection(range.index + 1);
                        }
                    });
                }
            });
        }

        function uploadImageToImgBB(file) {
            const formData = new FormData();
            formData.append('image', file);
            formData.append('_token', '{{ csrf_token() }}');

            // Show a loader or disable editor temporarily if needed
            const range = quill.getSelection(true);
            quill.insertEmbed(range.index, 'image', 'https://media.giphy.com/media/3oEjI6SIIHBdRxXI40/giphy.gif'); // Temp spinner

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
                    // Remove the temp spinner
                    quill.deleteText(range.index, 1);
                    if (data.url) {
                        // Insert uploaded ImgBB URL
                        quill.insertEmbed(range.index, 'image', data.url);
                        quill.setSelection(range.index + 1);
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Upload Failed',
                            html: `
                                <div class="text-center text-sm">
                                    <p class="mb-4 text-slate-600 dark:text-slate-400">Background upload failed. Please use the manual upload widget to upload your image.</p>
                                    <button id="swal-manual-upload-btn" class="px-4 py-2 rounded-xl bg-slate-900 text-white font-bold hover:bg-slate-805 transition-all text-xs">
                                        Open Upload Widget
                                    </button>
                                </div>
                            `,
                            showConfirmButton: false,
                            showCancelButton: true,
                            cancelButtonText: 'Cancel',
                            cancelButtonColor: '#e11d48',
                            didOpen: () => {
                                const swalBtn = document.getElementById('swal-manual-upload-btn');
                                if (swalBtn) {
                                    swalBtn.addEventListener('click', () => {
                                        triggerImgBBWidget('quill-editor', 'imgbb-upload-container');
                                        Swal.close();
                                    });
                                }
                            }
                        });
                    }
                })
                .catch(error => {
                    quill.deleteText(range.index, 1);
                    console.error('Quill Image Upload Error:', error);
                    Swal.fire({
                        icon: 'warning',
                        title: 'Upload Failed',
                        html: `
                            <div class="text-center text-sm">
                                <p class="mb-4 text-slate-600 dark:text-slate-400">An error occurred during upload. Please use the manual upload widget to upload your image.</p>
                                <button id="swal-manual-upload-btn" class="px-4 py-2 rounded-xl bg-slate-900 text-white font-bold hover:bg-slate-805 transition-all text-xs">
                                    Open Upload Widget
                                </button>
                            </div>
                        `,
                        showConfirmButton: false,
                        showCancelButton: true,
                        cancelButtonText: 'Cancel',
                        cancelButtonColor: '#e11d48',
                        didOpen: () => {
                            const swalBtn = document.getElementById('swal-manual-upload-btn');
                            if (swalBtn) {
                                swalBtn.addEventListener('click', () => {
                                    triggerImgBBWidget('quill-editor', 'imgbb-upload-container');
                                    Swal.close();
                                });
                            }
                        }
                    });
                });
        }

        mediaInput.addEventListener('change', function (e) {
            // Append newly selected files to our tracking list
            const files = Array.from(e.target.files);

            files.forEach(file => {
                // Keep unique items
                if (!selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
                    selectedFiles.push(file);
                }
            });

            updatePreviewsAndInput();
        });

        function updatePreviewsAndInput() {
            // Sync our local tracking list to the real input element files list!
            const dt = new DataTransfer();
            selectedFiles.forEach(file => dt.items.add(file));
            mediaInput.files = dt.files;

            // Render preview thumbnails
            previewContainer.innerHTML = '';

            if (selectedFiles.length === 0) {
                previewContainer.classList.add('hidden');
                document.getElementById('preview-gallery-container').classList.add('hidden');
                return;
            }

            previewContainer.classList.remove('hidden');

            selectedFiles.forEach((file, index) => {
                const isImage = file.type.startsWith('image/');
                const item = document.createElement('div');
                item.className = 'relative group rounded-2xl overflow-hidden bg-slate-50 border border-slate-200 shadow-sm transition-transform hover:scale-102';

                if (isImage) {
                    const objectUrl = URL.createObjectURL(file);
                    item.innerHTML = `
                        <div class="w-full h-20 overflow-hidden bg-slate-100">
                            <img src="${objectUrl}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-1 text-[9px] text-slate-500 truncate bg-slate-100/50 border-t border-slate-200 flex items-center justify-between">
                            <span class="truncate pr-1 font-semibold">${file.name}</span>
                        </div>
                        <button type="button" onclick="removeSelectedFile(${index})" class="absolute top-1.5 right-1.5 w-5 h-5 rounded-full bg-rose-600 text-white flex items-center justify-center shadow-lg hover:bg-rose-700 cursor-pointer transition-all border border-rose-500 text-[10px] font-black" title="Delete">
                            ✕
                        </button>
                    `;
                } else {
                    item.innerHTML = `
                        <div class="w-full h-20 flex flex-col items-center justify-center p-2 bg-slate-50">
                            <span class="material-symbols-outlined text-slate-400 text-lg">description</span>
                            <p class="text-[8px] text-slate-550 truncate w-full text-center mt-1 font-semibold">${file.name}</p>
                        </div>
                        <button type="button" onclick="removeSelectedFile(${index})" class="absolute top-1.5 right-1.5 w-5 h-5 rounded-full bg-rose-600 text-white flex items-center justify-center shadow-lg hover:bg-rose-700 cursor-pointer transition-all border border-rose-500 text-[10px] font-black" title="Delete">
                            ✕
                        </button>
                    `;
                }
                previewContainer.appendChild(item);
            });

            // Also update any live active preview gallery
            renderPreviewGallery();
        }

        function removeSelectedFile(index) {
            selectedFiles.splice(index, 1);
            updatePreviewsAndInput();
        }

        function renderPreviewGallery() {
            const galleryGrid = document.getElementById('preview-gallery-grid');
            const galleryContainer = document.getElementById('preview-gallery-container');
            if (!galleryGrid || !galleryContainer) return;
            galleryGrid.innerHTML = '';

            const images = selectedFiles.filter(f => f.type.startsWith('image/'));

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
                    <div class="block w-full h-24 overflow-hidden">
                        <img src="${objectUrl}" class="w-full h-full object-cover">
                    </div>
                    <div class="bg-slate-100/85 p-1.5 text-[8px] text-slate-500 border-t border-slate-200 flex items-center justify-between">
                        <span class="truncate pr-2 font-medium">${file.name}</span>
                    </div>
                `;
                galleryGrid.appendChild(card);
            });
        }

        // Tags Input Logic
        let tagsList = [];
        const tagsInput = document.getElementById('tags_input');
        const tagsContainer = document.getElementById('tags-capsules-container');
        const realTagsInput = document.getElementById('real_tags');

        if (tagsInput) {
            tagsInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ',') {
                    e.preventDefault();
                    let tag = tagsInput.value.trim().toLowerCase().replace(/[^a-z0-9-_]/g, '');
                    if (tag) {
                        addTag(tag);
                    }
                    tagsInput.value = '';
                }
            });
        }

        function addTag(tag) {
            if (!tagsList.includes(tag)) {
                if (tagsList.length >= 5) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tag Limit',
                        text: 'You can only add up to 5 tags.',
                        confirmButtonColor: '#0f172a'
                    });
                    return;
                }
                tagsList.push(tag);
                renderTagCapsules();
                updateQuickTagButtons();
            }
        }

        function renderTagCapsules() {
            tagsContainer.innerHTML = '';
            tagsList.forEach((tag, index) => {
                const pill = document.createElement('span');
                pill.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-[10px] sm:text-xs font-bold bg-blue-50 text-blue-700 border border-blue-150 shadow-sm transition-all hover:scale-102';
                pill.innerHTML = `
                    #${tag}
                    <button type="button" onclick="removeTag(${index})" class="text-[10px] hover:text-rose-600 cursor-pointer font-black ml-0.5 select-none">✕</button>
                `;
                tagsContainer.appendChild(pill);
            });
            realTagsInput.value = tagsList.join(',');
        }

        window.removeTag = function (index) {
            tagsList.splice(index, 1);
            renderTagCapsules();
            updateQuickTagButtons();
        };

        // Quick tag suggestions helper
        window.toggleQuickTag = function (tag) {
            if (tagsList.includes(tag)) {
                const idx = tagsList.indexOf(tag);
                if (idx > -1) {
                    tagsList.splice(idx, 1);
                    renderTagCapsules();
                }
            } else {
                addTag(tag);
            }
            updateQuickTagButtons();
        };

        function updateQuickTagButtons() {
            const quickTags = ['laravel', 'webdev', 'tailwind', 'help', 'design', 'showcase'];
            quickTags.forEach(tag => {
                const btn = document.getElementById(`quick-tag-${tag}`);
                if (btn) {
                    if (tagsList.includes(tag)) {
                        btn.className = 'px-2.5 py-1 rounded-xl bg-blue-600 text-[10px] font-bold text-white shadow-md shadow-blue-500/10 cursor-pointer border border-transparent scale-102';
                    } else {
                        btn.className = 'px-2.5 py-1 rounded-xl bg-slate-100 hover:bg-blue-50 text-[10px] font-bold text-slate-600 hover:text-blue-600 transition-colors shadow-sm cursor-pointer border border-transparent';
                    }
                }
            });
        }

        // Custom Category Dropdown Toggle & Selector
        window.toggleCategoryDropdown = function () {
            const options = document.getElementById('category-dropdown-options');
            options.classList.toggle('hidden');
        };

        window.selectCategory = function (id, name, slug, icon) {
            // Set value
            document.getElementById('category_id').value = id;

            // Set name
            document.getElementById('selected-category-name').innerText = name;

            // Set icon HTML
            const iconDiv = document.getElementById('selected-category-icon');
            let iconHtml = '';
            if (icon.startsWith('http://') || icon.startsWith('https://') || icon.includes('/')) {
                iconHtml = `<img src="${icon}" alt="" class="w-full h-full object-cover">`;
            } else if (icon === 'chat-bubble-left-right') {
                iconHtml = `<span class="material-symbols-outlined text-base">forum</span>`;
            } else if (icon === 'photo') {
                iconHtml = `<span class="material-symbols-outlined text-base">photo_library</span>`;
            } else if (icon === 'sparkles') {
                iconHtml = `<span class="material-symbols-outlined text-base">auto_awesome</span>`;
            } else if (icon.startsWith('fa')) {
                iconHtml = `<i class="${icon} text-xs"></i>`;
            } else {
                iconHtml = `<span class="material-symbols-outlined text-base">${icon || 'tag'}</span>`;
            }
            iconDiv.innerHTML = iconHtml;

            // Sync preview text & breadcrumb
            window.updatePreviewCategoryManual(name, slug);

            // Hide options
            document.getElementById('category-dropdown-options').classList.add('hidden');
        };

        window.updatePreviewCategoryManual = function (name, slug) {
            const url = `/categories/${slug}`;

            const breadcrumb = document.getElementById('breadcrumb-category');
            const desc = document.getElementById('description-category');
            const preview = document.getElementById('preview-category');

            if (breadcrumb) {
                breadcrumb.innerText = name;
                breadcrumb.href = url;
            }
            if (desc) {
                desc.innerText = name;
            }
            if (preview) {
                preview.innerText = name;
            }
        };

        // Close options list if clicked outside
        document.addEventListener('click', function (event) {
            const trigger = document.getElementById('category-dropdown-trigger');
            const options = document.getElementById('category-dropdown-options');
            if (trigger && options && !trigger.contains(event.target) && !options.contains(event.target)) {
                options.classList.add('hidden');
            }
        });

        function renderPreviewTags() {
            const previewTagsContainer = document.getElementById('preview-tags');
            if (!previewTagsContainer) return;
            previewTagsContainer.innerHTML = '';

            tagsList.forEach(tag => {
                const span = document.createElement('span');
                span.className = 'inline-block px-2.5 py-0.5 rounded-lg text-[9px] font-extrabold uppercase tracking-wider bg-slate-100 border border-slate-200 text-slate-500 shadow-sm';
                span.innerText = '#' + tag;
                previewTagsContainer.appendChild(span);
            });
        }

        function showLivePreview() {
            const titleVal = document.getElementById('title').value.trim();
            const contentVal = quill.root.innerHTML.trim();

            if (!titleVal || contentVal === '<p><br></p>' || !contentVal) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Preview Incomplete',
                    text: 'Please fill out the title and content first to view a preview.',
                    confirmButtonColor: '#0f172a'
                });
                return;
            }

            document.getElementById('preview-title').innerText = titleVal;
            document.getElementById('preview-body').innerHTML = contentVal;

            // Render preview media gallery
            renderPreviewGallery();

            // Render preview tags
            renderPreviewTags();

            // Reveal standard interactive preview block
            const previewBox = document.getElementById('live-preview-box');
            previewBox.classList.remove('hidden');
            previewBox.scrollIntoView({ behavior: 'smooth', block: 'end' });
        }

        function closeLivePreview() {
            document.getElementById('live-preview-box').classList.add('hidden');
        }
    </script>

    <!-- ImgBB Upload Widget Plugin -->
    <script async src="https://imgbb.com/upload.js" 
            data-auto-insert="bbcode-embed-medium" 
            data-sibling-selector="#imgbb-upload-container" 
            data-sibling-position="after">
    </script>
@endsection