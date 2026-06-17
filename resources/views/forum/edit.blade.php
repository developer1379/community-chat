@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 px-3 sm:px-6">
    <!-- Header path info -->
    <div class="px-4 sm:px-0">
        <div class="flex items-center gap-2 text-[10px] sm:text-xs font-semibold text-slate-500 mb-2 flex-wrap">
            <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors">Forums</a>
            <span class="text-slate-350">/</span>
            <a href="{{ route('threads.show', $thread->slug) }}" class="hover:text-blue-600 transition-colors">{{ $thread->title }}</a>
            <span class="text-slate-350">/</span>
            <span class="text-blue-600 font-bold">Edit Discussion</span>
        </div>
        <h1 class="text-xl sm:text-3xl font-black text-slate-900 tracking-tight leading-tight">Edit Your Discussion</h1>
        <p class="text-[11px] sm:text-xs text-slate-500 leading-relaxed mt-1">
            Update your insights, tags, or board category for <span class="text-blue-600 font-bold">"{{ $thread->title }}"</span>.
        </p>
    </div>

    <!-- Edit Form Card -->
    <div class="bg-white rounded-none sm:rounded-[2rem] border-y sm:border border-slate-200 shadow-xl overflow-hidden relative">
        <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500"></div>
        
        <div class="bg-slate-50/80 px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <span class="text-xs font-bold text-slate-700 uppercase tracking-widest flex items-center gap-2">
                <span class="material-symbols-outlined text-sm text-blue-600 animate-pulse">edit_note</span>
                Update Thread Details
            </span>
            <span class="text-[10px] font-black text-amber-600 bg-amber-50 border border-amber-200 px-2.5 py-0.5 rounded-full uppercase tracking-wider">Changes logged</span>
        </div>

        <form id="thread-form" action="{{ route('threads.update', $thread->id) }}" method="POST" class="p-5 sm:p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Grid container for Title and Category -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- Category Dropdown Select -->
                <div class="space-y-1.5">
                    <label for="category_id" class="text-[11px] font-black text-slate-700 uppercase tracking-widest ml-1">Board Room</label>
                    <div class="relative">
                        <select id="category_id" name="category_id" class="w-full bg-slate-50/50 hover:bg-slate-50 border border-slate-200 rounded-2xl pl-4 pr-10 py-3.5 text-slate-800 text-xs sm:text-sm font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all appearance-none cursor-pointer shadow-inner shadow-slate-100/50">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $cat->id === $thread->category_id ? 'selected' : '' }}>
                                    🚪 {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-4 top-3.5 text-slate-400 pointer-events-none text-[18px]">unfold_more</span>
                    </div>
                    @error('category_id')
                        <p class="text-xs text-rose-500 mt-1 font-bold ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Title Input -->
                <div class="md:col-span-2 space-y-1.5">
                    <label for="title" class="text-[11px] font-black text-slate-700 uppercase tracking-widest ml-1">Thread Title</label>
                    <div class="flex flex-col sm:flex-row gap-2.5">
                        <!-- Prefix Select Dropdown -->
                        <div class="relative flex-shrink-0">
                            <input type="hidden" name="prefix" id="thread_prefix" value="{{ old('prefix', $thread->prefix) }}">
                            <button type="button" id="prefix-dropdown-trigger" onclick="togglePrefixDropdown(event)"
                                class="w-full sm:w-auto h-[48px] bg-slate-900 border border-slate-750 text-slate-200 hover:bg-slate-850 hover:text-white px-4 rounded-2xl flex items-center justify-between sm:justify-start gap-2 text-xs sm:text-sm font-semibold transition-all select-none cursor-pointer">
                                <span id="selected-prefix-display" class="px-2 py-0.5 rounded-lg text-xs font-extrabold uppercase tracking-wider" style="background-color: transparent; color: inherit;">(No prefix)</span>
                                <span class="material-symbols-outlined text-[16px] text-slate-400">arrow_drop_down</span>
                            </button>
                            
                            <!-- Prefix Dropdown Options List -->
                            <div id="prefix-dropdown-options"
                                class="absolute left-0 mt-2 bg-slate-900 border border-slate-750 rounded-2xl shadow-2xl z-50 w-72 max-h-[350px] overflow-y-auto hidden p-3.5 space-y-4">
                                <style>
                                    #prefix-dropdown-options::-webkit-scrollbar {
                                        width: 6px;
                                    }
                                    #prefix-dropdown-options::-webkit-scrollbar-track {
                                        background: transparent;
                                    }
                                    #prefix-dropdown-options::-webkit-scrollbar-thumb {
                                        background-color: rgba(100, 116, 139, 0.4);
                                        border-radius: 20px;
                                    }
                                </style>
                                <div onclick="selectPrefix('', '(No prefix)', '', '')" class="text-slate-400 hover:text-white hover:bg-slate-800 px-3 py-2 rounded-xl text-xs font-semibold cursor-pointer transition-all">
                                    (No prefix)
                                </div>
                                @php
                                    $groupedPrefixes = [];
                                    foreach(\App\Models\Thread::$prefixes as $pName => $pConfig) {
                                        $groupedPrefixes[$pConfig['group']][] = array_merge(['name' => $pName], $pConfig);
                                    }
                                @endphp
                                @foreach($groupedPrefixes as $groupName => $pList)
                                    <div class="space-y-2">
                                        <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">{{ $groupName }}</div>
                                        <div class="grid grid-cols-1 gap-1.5">
                                            @foreach($pList as $p)
                                                <button type="button" 
                                                    onclick="selectPrefix('{{ addslashes($p['name']) }}', '{{ addslashes($p['name']) }}', '{{ $p['bg'] }}', '{{ $p['color'] }}')"
                                                    style="background-color: {{ $p['bg'] }}; color: {{ $p['color'] }};"
                                                    class="w-full text-left px-3 py-2 rounded-xl text-[11px] font-black uppercase tracking-wider shadow-md hover:scale-[1.02] active:scale-[0.98] transition-all cursor-pointer">
                                                    {{ $p['name'] }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Title Input -->
                        <div class="relative flex-grow">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-slate-400 text-[18px]">title</span>
                            </span>
                            <input type="text" id="title" name="title" value="{{ old('title', $thread->title) }}" class="w-full h-[48px] bg-slate-50/50 border border-slate-200 rounded-2xl pl-11 pr-4 py-3 text-slate-800 text-xs sm:text-sm font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all placeholder:text-slate-400 placeholder:font-medium shadow-inner shadow-slate-100/50" placeholder="Give your thread a clean, descriptive title..." required>
                        </div>
                    </div>
                    @error('title')
                        <p class="text-xs text-rose-500 mt-1 font-bold ml-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Tags Input & Popular Suggestions -->
            <div class="space-y-2">
                <label for="tags_input" class="text-[11px] font-black text-slate-700 uppercase tracking-widest ml-1">Discussion Tags</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-slate-400 text-[18px]">sell</span>
                    </span>
                    <input type="text" id="tags_input" class="w-full bg-slate-50/50 border border-slate-200 rounded-2xl pl-11 pr-4 py-3.5 text-slate-800 text-xs sm:text-sm font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all placeholder:text-slate-400 placeholder:font-medium shadow-inner shadow-slate-100/50" placeholder="Type a tag & press Enter or comma (e.g. laravel, css)...">
                    <input type="hidden" id="real_tags" name="tags" value="{{ old('tags', $thread->tags) }}">
                </div>
                
                <!-- Dynamic Tag Capsules Container -->
                <div id="tags-capsules-container" class="flex flex-wrap gap-1.5 pt-1">
                    <!-- Pills injected here -->
                </div>

                <!-- Preselected Popular Tag Suggestions helper -->
                <div class="pt-1.5 flex flex-wrap items-center gap-2">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider">Quick Suggestions:</span>
                    <div class="flex flex-wrap gap-1">
                        @foreach(['laravel', 'webdev', 'tailwind', 'help', 'design', 'showcase'] as $popularTag)
                            <button type="button" onclick="toggleQuickTag('{{ $popularTag }}')" id="quick-tag-{{ $popularTag }}" class="px-2.5 py-1 rounded-xl bg-slate-100 hover:bg-blue-50 text-[10px] font-bold text-slate-600 hover:text-blue-600 transition-colors shadow-sm cursor-pointer border border-transparent">
                                #{{ $popularTag }}
                            </button>
                        @endforeach
                    </div>
                </div>
                <p class="text-[10px] text-slate-400 font-medium">Add up to 5 descriptive tags to categorize your thread.</p>
            </div>

            <!-- Content Area (Quill Rich Text Editor) -->
            <div class="space-y-1.5">
                <label for="quill-editor" class="text-[11px] font-black text-slate-700 uppercase tracking-widest ml-1">Discussion Content</label>
                <!-- Hidden real field -->
                <textarea id="content-input" name="content" class="hidden" readonly>{{ old('content', $thread->firstPost?->content) }}</textarea>
                
                <!-- Quill container with custom HSL overrides -->
                <div class="rounded-2xl border border-slate-200 bg-slate-50/50 focus-within:ring-2 focus-within:ring-blue-500/10 focus-within:border-blue-500 transition-all relative z-30">
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
                            display: none !important;
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
                    <div id="quill-editor" class="bg-white rounded-b-2xl" style="height: 300px; font-size: 13.5px;">{!! old('content', $thread->firstPost?->content) !!}</div>
                </div>

                <!-- ImgBB Upload Widget target container -->
                <div id="imgbb-upload-container" class="mt-2 text-left"></div>
                
                @error('content')
                    <p class="text-xs text-rose-500 mt-1 font-bold ml-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action buttons (Highly optimized for Mobile Stacking & Touch Sizes) -->
            <div class="flex flex-col-reverse sm:flex-row items-center sm:justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('threads.show', $thread->slug) }}" class="w-full sm:w-auto text-center bg-slate-100 hover:bg-slate-200/80 text-xs sm:text-sm font-bold text-slate-750 py-3.5 px-6 rounded-2xl transition-all cursor-pointer">
                    Cancel Edits
                </a>
                <button type="submit" class="w-full sm:w-auto relative group overflow-hidden bg-slate-900 hover:bg-slate-800 text-xs sm:text-sm font-bold text-white py-3.5 px-8 rounded-2xl shadow-lg shadow-slate-900/10 cursor-pointer transition-all">
                    <span class="relative z-10 flex items-center justify-center gap-1.5">
                        Save Changes
                        <span class="material-symbols-outlined text-[18px] group-hover:translate-x-0.5 transition-transform">save</span>
                    </span>
                    <div class="absolute inset-0 h-full w-full bg-gradient-to-r from-blue-600 to-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </button>
            </div>
        </form>
    </div>
</div>

@include('partials.quill-imgbb-scripts')

<!-- JavaScript Integration for Tags and Quill editor -->
<script>


    // Initialize Quill Editor
    let quill;
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. Quill Rich Editor Setup ---
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
            placeholder: 'Draft your updated discussion details here...'
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
                                if (typeof quill !== 'undefined' && !isImageInQuill(quill, url)) {
                                    const range = quill.getSelection(true);
                                    quill.insertEmbed(range.index, 'image', url);
                                    quill.setSelection(range.index + 1);
                                }
                            });
                            // Keep the synced editor content as final value
                            descriptor.set.call(this, quill.root.innerHTML);
                        }
                    }
                }
            });
        }

        // Sync Quill changes to hidden real content field on form submit
        const form = document.getElementById('thread-form');
        form.addEventListener('submit', function(e) {
            const html = quill.root.innerHTML;
            // If empty text
            if (quill.getText().trim().length === 0) {
                document.getElementById('content-input').value = '';
            } else {
                document.getElementById('content-input').value = html;
            }
        });

        // Initialize prefix style if set
        const oldPrefixVal = document.getElementById('thread_prefix').value;
        if (oldPrefixVal) {
            // Find matching button in prefix options list to retrieve bg & color
            const btn = Array.from(document.querySelectorAll('#prefix-dropdown-options button')).find(b => b.textContent.trim() === oldPrefixVal);
            if (btn) {
                const bg = btn.style.backgroundColor;
                const color = btn.style.color;
                selectPrefix(oldPrefixVal, oldPrefixVal, bg, color);
            }
        }

        // --- 2. Dynamic Tags Manager ---
        const tagInput = document.getElementById('tags_input');
        const realTags = document.getElementById('real_tags');
        const capsulesContainer = document.getElementById('tags-capsules-container');
        
        let tagsList = [];
        
        // Initialize existing tags if they exist
        if (realTags.value.trim() !== '') {
            tagsList = realTags.value.split(',').map(t => t.trim()).filter(t => t !== '');
            renderTags();
        }

        function renderTags() {
            capsulesContainer.innerHTML = '';
            tagsList.forEach((tag, idx) => {
                const capsule = document.createElement('div');
                capsule.className = "flex items-center gap-1.5 px-3 py-1 rounded-xl bg-blue-50 border border-blue-150 text-blue-700 text-[11px] font-bold shadow-sm shadow-blue-500/5 animate-scaleUp";
                capsule.innerHTML = `
                    <span>#${tag}</span>
                    <button type="button" onclick="removeTag(${idx})" class="w-4 h-4 rounded-full bg-blue-100 hover:bg-rose-100 text-blue-800 hover:text-rose-700 flex items-center justify-center transition-colors font-extrabold text-[8px] cursor-pointer">
                        ✕
                    </button>
                `;
                capsulesContainer.appendChild(capsule);
            });
            // Update the real hidden input field
            realTags.value = tagsList.join(',');
            updateQuickTagsActiveStates();
        }

        window.removeTag = function(index) {
            tagsList.splice(index, 1);
            renderTags();
        };

        tagInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ',') {
                e.preventDefault();
                const value = tagInput.value.trim().toLowerCase().replace(/#/g, '');
                if (value && !tagsList.includes(value)) {
                    if (tagsList.length >= 5) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Tag Limit Reached',
                            text: 'You can only add up to 5 descriptive tags per discussion.',
                            confirmButtonColor: '#0f172a'
                        });
                        return;
                    }
                    tagsList.push(value);
                    renderTags();
                }
                tagInput.value = '';
            }
        });

        // Quick Tag Toggles
        window.toggleQuickTag = function(tag) {
            const index = tagsList.indexOf(tag);
            if (index > -1) {
                tagsList.splice(index, 1);
            } else {
                if (tagsList.length >= 5) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tag Limit Reached',
                        text: 'You can only add up to 5 descriptive tags per discussion.',
                        confirmButtonColor: '#0f172a'
                    });
                    return;
                }
                tagsList.push(tag);
            }
            renderTags();
        };

        function updateQuickTagsActiveStates() {
            document.querySelectorAll('[id^="quick-tag-"]').forEach(btn => {
                const tag = btn.id.replace('quick-tag-', '');
                if (tagsList.includes(tag)) {
                    btn.classList.add('bg-blue-600', 'text-white');
                    btn.classList.remove('bg-slate-100', 'text-slate-605');
                } else {
                    btn.classList.remove('bg-blue-600', 'text-white');
                    btn.classList.add('bg-slate-100', 'text-slate-600');
                }
            });
        }

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
                            <a href="https://vimeo.com" target="_blank" class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-50/50 hover:bg-blue-50/30 border border-slate-200 text-slate-700 hover:text-blue-655 font-bold transition-all text-xs">
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
                    if (videoUrl.includes('youtube.com/watch?v=')) {
                        let parts = videoUrl.split('watch?v=');
                        if (parts[1]) {
                            let id = parts[1].split('&')[0];
                            videoUrl = `https://www.youtube.com/embed/${id}`;
                        }
                    } else if (videoUrl.includes('youtu.be/')) {
                        const id = videoUrl.split('/').pop().split('?')[0];
                        videoUrl = `https://www.youtube.com/embed/${id}`;
                    } else if (videoUrl.includes('vimeo.com/') && !videoUrl.includes('player.vimeo.com')) {
                        const id = videoUrl.split('/').pop().split('?')[0];
                        videoUrl = `https://player.vimeo.com/video/${id}`;
                    } else if (videoUrl.includes('sendvid.com/') && !videoUrl.includes('/embed/')) {
                        const id = videoUrl.split('/').pop().split('?')[0];
                        videoUrl = `https://sendvid.com/embed/${id}`;
                    } else if (videoUrl.includes('streamable.com/') && !videoUrl.includes('/e/')) {
                        const id = videoUrl.split('/').pop().split('?')[0];
                        videoUrl = `https://streamable.com/e/${id}`;
                    }
                    quill.insertEmbed(range.index, 'video', videoUrl);
                    quill.setSelection(range.index + 1);
                }
            });
        }

        function selectLocalImage() {
            handleQuillImageInsertion(quill, 'imgbb-upload-container', 'quill-editor', '{{ route("media.upload") }}', '{{ csrf_token() }}');
        }

        window.togglePrefixDropdown = function (event) {
            event.stopPropagation();
            const options = document.getElementById('prefix-dropdown-options');
            options.classList.toggle('hidden');
        };

        window.selectPrefix = function (val, name, bg, color) {
            document.getElementById('thread_prefix').value = val;
            const display = document.getElementById('selected-prefix-display');
            display.innerText = name;
            if (bg && color) {
                display.style.backgroundColor = bg;
                display.style.color = color;
                display.className = "px-2.5 py-1 rounded-xl text-xs font-black uppercase tracking-wider shadow-sm";
            } else {
                display.style.backgroundColor = 'transparent';
                display.style.color = 'inherit';
                display.className = "px-2 py-0.5 rounded-lg text-xs font-extrabold uppercase tracking-wider";
            }
            document.getElementById('prefix-dropdown-options').classList.add('hidden');
        };

        // Close options list if clicked outside
        document.addEventListener('click', function (event) {
            const prefixTrigger = document.getElementById('prefix-dropdown-trigger');
            const prefixOptions = document.getElementById('prefix-dropdown-options');
            if (prefixTrigger && prefixOptions && !prefixTrigger.contains(event.target) && !prefixOptions.contains(event.target)) {
                prefixOptions.classList.add('hidden');
            }
        });
    });
</script>

<style>
    @keyframes scaleUp {
        from { transform: scale(0.9); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    .animate-scaleUp {
        animation: scaleUp 0.15s ease-out forwards;
    }
</style>

<!-- ImgBB Upload Widget Plugin -->
<script async src="https://imgbb.com/upload.js" 
        data-auto-insert="bbcode-embed-medium" 
        data-sibling-selector="#imgbb-upload-container" 
        data-sibling-position="after">
</script>
@endsection
