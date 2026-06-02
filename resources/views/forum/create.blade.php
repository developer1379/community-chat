@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 px-3 sm:px-6">
    <!-- Header path info -->
    <div>
        <div class="flex items-center gap-2 text-[10px] sm:text-xs font-semibold text-slate-500 mb-2 flex-wrap">
            <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors">Forums</a>
            <span class="text-slate-350">/</span>
            <a href="{{ route('categories.show', $category->slug) }}" id="breadcrumb-category" class="hover:text-blue-600 transition-colors">{{ $category->name }}</a>
            <span class="text-slate-350">/</span>
            <span class="text-blue-600 font-bold">Create New Thread</span>
        </div>
        <h1 class="text-xl sm:text-3xl font-black text-slate-900 tracking-tight leading-tight">Post a New Discussion</h1>
        <p class="text-[11px] sm:text-xs text-slate-500 leading-relaxed mt-1">
            Share your insights, showcase media, or ask questions in the <span id="description-category" class="text-blue-600 font-bold">{{ $category->name }}</span> room.
        </p>
    </div>

    <!-- Creation Form Card -->
    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-xl overflow-hidden relative">
        <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500"></div>
        
        <div class="bg-slate-50/80 px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <span class="text-xs font-bold text-slate-700 uppercase tracking-widest flex items-center gap-2">
                <span class="material-symbols-outlined text-sm text-blue-600 animate-pulse">edit_note</span>
                Draft Thread Details
            </span>
            <span class="text-[10px] font-bold text-slate-400 bg-white border border-slate-200 px-2 py-0.5 rounded-full hidden sm:inline-block">Draft autosaved</span>
        </div>

        <form id="thread-form" action="{{ route('threads.store') }}" method="POST" enctype="multipart/form-data" class="p-5 sm:p-8 space-y-6">
            @csrf

            <!-- Grid container for Title and Category -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- Category Dropdown Select -->
                <div class="space-y-1.5">
                    <label for="category_id" class="text-[11px] font-black text-slate-700 uppercase tracking-widest ml-1">Select Board Room</label>
                    <div class="relative">
                        <select id="category_id" name="category_id" onchange="updatePreviewCategory(this)" class="w-full bg-slate-50/50 hover:bg-slate-50 border border-slate-200 rounded-2xl pl-4 pr-10 py-3.5 text-slate-800 text-xs sm:text-sm font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all appearance-none cursor-pointer shadow-inner shadow-slate-100/50">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" data-slug="{{ $cat->slug }}" {{ $cat->id === $category->id ? 'selected' : '' }}>
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
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-400 text-[18px]">title</span>
                        </span>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" class="w-full bg-slate-50/50 border border-slate-200 rounded-2xl pl-11 pr-4 py-3.5 text-slate-800 text-xs sm:text-sm font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all placeholder:text-slate-400 placeholder:font-medium shadow-inner shadow-slate-100/50" placeholder="Give your thread a clean, descriptive title..." required>
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
                    <input type="hidden" id="real_tags" name="tags" value="{{ old('tags') }}">
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
                <input type="hidden" id="content-input" name="content" value="{{ old('content') }}">
                
                <!-- Quill container with custom HSL overrides -->
                <div class="rounded-2xl border border-slate-200 overflow-hidden bg-slate-50/50 focus-within:ring-2 focus-within:ring-blue-500/10 focus-within:border-blue-500 transition-all">
                    <div id="quill-editor" class="bg-white" style="height: 300px; font-size: 13.5px;">{!! old('content') !!}</div>
                </div>
                
                @error('content')
                    <p class="text-xs text-rose-500 mt-1 font-bold ml-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Custom Drag and Drop Uploader Block -->
            <div class="space-y-3 bg-slate-50/50 p-5 rounded-3xl border border-slate-200/80">
                <label class="block text-[11px] font-black text-slate-700 uppercase tracking-widest">📎 Media Showroom Uploads (Images / GIFs)</label>
                
                <!-- Interactive Dropzone card -->
                <div onclick="document.getElementById('media-input').click()" class="border-2 border-dashed border-slate-250 hover:border-blue-500 rounded-2xl p-6 sm:p-8 flex flex-col items-center justify-center text-center cursor-pointer bg-white/60 hover:bg-blue-50/10 transition-all group shadow-sm">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 group-hover:bg-blue-100 flex items-center justify-center text-slate-500 group-hover:text-blue-600 transition-colors shadow-inner mb-3">
                        <span class="material-symbols-outlined text-2xl">cloud_upload</span>
                    </div>
                    <h4 class="font-bold text-slate-800 text-xs sm:text-sm">Click to Select Files</h4>
                    <p class="text-[10px] text-slate-400 mt-1 leading-normal max-w-sm">Drag & drop or browse from storage. High-resolution JPEG, PNG, or dynamic GIF animations supported (Max: 5MB per file).</p>
                    
                    <!-- Real hidden uploader input -->
                    <input type="file" id="media-input" name="attachments[]" multiple class="hidden" accept="image/*">
                </div>
                
                @error('attachments.*')
                    <p class="text-xs text-rose-500 mt-1 font-bold ml-1">{{ $message }}</p>
                @enderror

                <!-- Dynamic Previews Grid -->
                <div id="preview-container" class="hidden grid grid-cols-2 sm:grid-cols-4 gap-3 pt-4 border-t border-slate-200/60">
                    <!-- Selected previews rendered dynamically by JS -->
                </div>
            </div>

            <!-- Action buttons (Highly optimized for Mobile Stacking & Touch Sizes) -->
            <div class="flex flex-col-reverse sm:flex-row items-center sm:justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('categories.show', $category->slug) }}" class="w-full sm:w-auto text-center bg-slate-100 hover:bg-slate-200/80 text-xs sm:text-sm font-bold text-slate-750 py-3.5 px-6 rounded-2xl transition-all cursor-pointer">
                    Cancel Drafting
                </a>
                <button type="button" onclick="showLivePreview()" class="w-full sm:w-auto py-3.5 px-6 rounded-2xl border border-slate-350 bg-white hover:bg-slate-50 text-slate-755 font-bold text-xs sm:text-sm cursor-pointer transition-all flex items-center justify-center gap-2 shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">visibility</span> Preview Content
                </button>
                <button type="submit" class="w-full sm:w-auto relative group overflow-hidden bg-slate-900 hover:bg-slate-800 text-xs sm:text-sm font-bold text-white py-3.5 px-8 rounded-2xl shadow-lg shadow-slate-900/10 cursor-pointer transition-all">
                    <span class="relative z-10 flex items-center justify-center gap-1.5">
                        Publish Thread
                        <span class="material-symbols-outlined text-[18px] group-hover:translate-x-0.5 transition-transform">send</span>
                    </span>
                    <div class="absolute inset-0 h-full w-full bg-gradient-to-r from-blue-600 to-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </button>
            </div>
        </form>
    </div>

    <!-- MOBILE-FRIENDLY ACCORDION LIVE PREVIEW BLOCK -->
    <div id="live-preview-box" class="hidden space-y-3.5 transition-all">
        <div class="flex items-center justify-between px-1">
            <h2 class="text-xs font-black text-slate-500 uppercase tracking-widest flex items-center gap-1.5">
                <span class="material-symbols-outlined text-blue-600 text-[18px]">analytics</span> Professional Thread Mockup
            </h2>
            <button onclick="closeLivePreview()" class="text-xs font-bold text-rose-600 hover:underline cursor-pointer flex items-center gap-1">
                <span class="material-symbols-outlined text-base">visibility_off</span> Hide Preview
            </button>
        </div>

        <div class="bg-white rounded-[2rem] border border-slate-200 shadow-xl overflow-hidden flex flex-col md:flex-row relative">
            <!-- Left Info Panel Mockup -->
            <div class="w-full md:w-48 bg-slate-50 p-6 flex flex-col items-center border-b md:border-b-0 md:border-r border-slate-200 text-center flex-shrink-0">
                <div class="w-16 h-16 rounded-2xl overflow-hidden border border-slate-300 shadow-inner mb-3 bg-blue-50 flex items-center justify-center font-bold text-blue-600 text-lg">
                    @if(Auth::user()->avatar_path)
                        <img src="{{ str_starts_with(Auth::user()->avatar_path, 'http') ? Auth::user()->avatar_path : asset('storage/' . Auth::user()->avatar_path) }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    @endif
                </div>
                <h3 class="font-extrabold text-slate-800 text-xs truncate max-w-full">{{ Auth::user()->name }}</h3>
                <span class="text-[8px] px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider text-white mt-1.5 border border-slate-350 shadow-sm" style="background: {{ Auth::user()->banner_color ?? '#2563eb' }}">
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
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Just Now • Live Preview</span>
                        <span id="preview-category" class="text-[10px] font-black text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded uppercase tracking-wider">{{ $category->name }}</span>
                    </div>
                    
                    <!-- Thread Title -->
                    <h2 id="preview-title" class="text-base sm:text-xl font-black text-slate-900 leading-tight break-words"></h2>
                    
                    <!-- Previews tags capsules -->
                    <div id="preview-tags" class="flex flex-wrap gap-1 pt-1"></div>

                    <!-- Text content -->
                    <div id="preview-body" class="text-slate-700 text-xs sm:text-sm leading-relaxed whitespace-pre-wrap font-sans ql-snow ql-editor !p-0"></div>

                    <!-- Selected Attachment Gallery Mockup inside preview -->
                    <div id="preview-gallery-container" class="hidden pt-4 border-t border-slate-100">
                        <h4 class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-2">📎 Uploaded Attachments</h4>
                        <div id="preview-gallery-grid" class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <!-- Selected images will clone inside here -->
                        </div>
                    </div>
                </div>

                @if(Auth::user()->signature)
                    <div class="mt-6 pt-4 border-t border-slate-200 border-dashed text-[10px] text-slate-500 font-medium italic">
                        {{ Auth::user()->signature }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- JS Controller for Live Selection and Removal + Dynamic Document Previews + Quill Editor -->
<script>
    let selectedFiles = [];
    const mediaInput = document.getElementById('media-input');
    const previewContainer = document.getElementById('preview-container');

    // Initialize Quill Editor
    let quill;
    document.addEventListener('DOMContentLoaded', function() {
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
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link', 'image'],
                        ['clean']
                    ],
                    handlers: {
                        image: selectLocalImage
                    }
                }
            },
            placeholder: 'Write your thread discussion, code blocks, or markdown content here...'
        });

        // Intercept form submit to sync Quill HTML content to the hidden content input
        const form = document.getElementById('thread-form');
        if (form) {
            form.addEventListener('submit', function(e) {
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

    // Custom image handler for Quill to upload to ImgBB
    function selectLocalImage() {
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
                    icon: 'error',
                    title: 'Upload Failed',
                    text: 'Failed to obtain image URL from ImgBB service.',
                    confirmButtonColor: '#0f172a'
                });
            }
        })
        .catch(error => {
            quill.deleteText(range.index, 1);
            console.error('Quill Image Upload Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Upload Error',
                text: 'An error occurred during image upload to ImgBB. Please try again.',
                confirmButtonColor: '#0f172a'
            });
        });
    }

    mediaInput.addEventListener('change', function(e) {
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
        tagsInput.addEventListener('keydown', function(e) {
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

    window.removeTag = function(index) {
        tagsList.splice(index, 1);
        renderTagCapsules();
        updateQuickTagButtons();
    };

    // Quick tag suggestions helper
    window.toggleQuickTag = function(tag) {
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

    // Category Change Preview Dynamic Sync
    window.updatePreviewCategory = function(select) {
        const option = select.options[select.selectedIndex];
        const name = option.text.replace(/^[^\w]*/, '').trim(); // strip door emoji for breadcrumb
        const slug = option.getAttribute('data-slug');
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
@endsection
