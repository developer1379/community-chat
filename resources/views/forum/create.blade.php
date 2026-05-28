@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header path info -->
    <div>
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-2">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Forums</a>
            <span>/</span>
            <a href="{{ route('categories.show', $category->slug) }}" class="hover:text-blue-600">{{ $category->name }}</a>
            <span>/</span>
            <span class="text-blue-600">Create New Thread</span>
        </div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Create New Thread</h1>
        <p class="text-xs text-slate-500 leading-relaxed mt-1">Share your ideas, code components, or beautiful visual media in the <span class="text-blue-650 font-semibold">{{ $category->name }}</span> community room.</p>
    </div>

    <!-- Creation Form Card -->
    <div class="mui-card overflow-hidden bg-white border border-slate-200 shadow-md">
        <div class="bg-slate-50/80 px-6 py-4 border-b border-slate-200">
            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">✍️ Thread Details</span>
        </div>
        <form id="thread-form" action="{{ route('threads.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
            @csrf
            
            <!-- Hidden Category Selector -->
            <input type="hidden" name="category_id" value="{{ $category->id }}">

            <!-- Title Input -->
            <div class="space-y-2">
                <label for="title" class="text-xs font-bold text-slate-700 uppercase tracking-wide">Thread Title</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" class="w-full bg-slate-50 border border-slate-250 rounded-xl px-4 py-3 text-slate-800 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all placeholder:text-slate-400 font-semibold" placeholder="Give your discussion a clean, descriptive title..." required>
                @error('title')
                    <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content Area (Quill Rich Text Editor) -->
            <div class="space-y-2">
                <label for="quill-editor" class="text-xs font-bold text-slate-700 uppercase tracking-wide">Discussion Content</label>
                <!-- Hidden real field -->
                <input type="hidden" id="content-input" name="content" value="{{ old('content') }}">
                
                <!-- Quill container -->
                <div class="rounded-xl border border-slate-200 overflow-hidden bg-slate-50">
                    <div id="quill-editor" style="height: 320px; font-size: 13px;">{!! old('content') !!}</div>
                </div>
                
                @error('content')
                    <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Attachments (Images & GIFs) -->
            <div class="space-y-3 bg-slate-50/60 p-5 rounded-xl border border-slate-200">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">📎 Thread Attachments (Images / GIFs)</label>
                <p class="text-[10px] text-slate-500 leading-normal">Upload visual guides, banners, custom photography, or dynamic meme GIFs to render beautifully inside your thread layout (Supported types: jpeg, png, gif; Max: 5MB per file).</p>
                
                <input type="file" id="media-input" name="attachments[]" multiple class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 file:cursor-pointer transition-all">
                @error('attachments.*')
                    <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p>
                @enderror

                <!-- GORGEOUS LIVE IMAGE PREVIEW GRID -->
                <div id="preview-container" class="hidden grid grid-cols-2 sm:grid-cols-4 gap-3 pt-3 border-t border-slate-200/60">
                    <!-- Dynamic preview items inject here -->
                </div>
            </div>

            <!-- Action buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('categories.show', $category->slug) }}" class="bg-slate-100 hover:bg-slate-200/80 text-xs font-bold text-slate-700 px-5 py-2.5 rounded-xl transition-all">
                    Cancel
                </a>
                <button type="button" onclick="showLivePreview()" class="px-5 py-2.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs cursor-pointer transition-all flex items-center gap-1.5 shadow-sm">
                    <span class="material-symbols-outlined text-sm">visibility</span> Preview Thread
                </button>
                <button type="submit" class="xen-button text-xs font-bold text-white px-6 py-2.5 rounded-xl shadow-lg cursor-pointer">
                    Create Thread
                </button>
            </div>
        </form>
    </div>

    <!-- MODERN PROFESSIONAL LIVE PREVIEW SECTION (DYNAMICALLY TOGGLED) -->
    <div id="live-preview-box" class="hidden space-y-3">
        <div class="flex items-center justify-between">
            <h2 class="text-xs font-extrabold text-slate-500 uppercase tracking-widest flex items-center gap-1.5">
                <span class="material-symbols-outlined text-blue-600 text-sm">visibility</span> Live Professional Preview
            </h2>
            <button onclick="closeLivePreview()" class="text-xs font-semibold text-rose-600 hover:underline cursor-pointer">Hide Preview</button>
        </div>

        <div class="mui-card overflow-hidden border border-slate-200 bg-white shadow-md flex flex-col md:flex-row">
            <!-- Left Info Panel Mockup -->
            <div class="w-full md:w-48 bg-slate-50 p-5 flex flex-col items-center border-b md:border-b-0 md:border-r border-slate-200 text-center flex-shrink-0">
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
                        <span>Threads:</span>
                        <span class="font-semibold text-slate-700">{{ Auth::user()->threads()->count() + 1 }}</span>
                    </div>
                </div>
            </div>

            <!-- Post Content Panel -->
            <div class="flex-grow p-6 flex flex-col justify-between">
                <div class="space-y-4">
                    <div class="border-b border-slate-100 pb-2.5 flex justify-between items-center">
                        <span class="text-[10px] text-slate-400 font-semibold">Just Now • Preview Mode</span>
                        <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">New Thread</span>
                    </div>
                    <!-- Thread Title -->
                    <h2 id="preview-title" class="text-lg font-extrabold text-slate-900 leading-tight"></h2>
                    <!-- Text content -->
                    <div id="preview-body" class="text-slate-700 text-xs leading-relaxed whitespace-pre-wrap font-sans"></div>

                    <!-- Selected Attachment Gallery Mockup inside preview -->
                    <div id="preview-gallery-container" class="hidden pt-4 border-t border-slate-100">
                        <h4 class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-2">📎 Uploaded Attachments</h4>
                        <div id="preview-gallery-grid" class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <!-- Selected images will clone inside here -->
                        </div>
                    </div>
                </div>

                @if(Auth::user()->signature)
                    <div class="mt-6 pt-3 border-t border-slate-200 border-dashed text-[10px] text-slate-500 font-medium italic">
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
            placeholder: 'Write your discussion content here...'
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
                    alert('Please enter some content for your thread discussion.');
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
                alert('Only image files (JPEG, PNG, JPG, GIF) are allowed.');
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
                alert('Failed to obtain image URL from ImgBB service.');
            }
        })
        .catch(error => {
            quill.deleteText(range.index, 1);
            console.error('Quill Image Upload Error:', error);
            alert('An error occurred during image upload to ImgBB. Please try again.');
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
            item.className = 'relative group rounded-xl overflow-hidden bg-slate-50 border border-slate-200 shadow-sm';
            
            if (isImage) {
                const objectUrl = URL.createObjectURL(file);
                item.innerHTML = `
                    <div class="w-full h-20 overflow-hidden bg-slate-100">
                        <img src="${objectUrl}" class="w-full h-full object-cover">
                    </div>
                    <div class="p-1 text-[9px] text-slate-500 truncate bg-slate-100/50 border-t border-slate-200 flex items-center justify-between">
                        <span class="truncate pr-1 font-semibold">${file.name}</span>
                    </div>
                    <button type="button" onclick="removeSelectedFile(${index})" class="absolute top-1 right-1 w-5 h-5 rounded-full bg-rose-600 text-white flex items-center justify-center shadow-lg hover:bg-rose-700 cursor-pointer transition-all border border-rose-500 text-[11px] font-bold" title="Delete">
                        ✕
                    </button>
                `;
            } else {
                item.innerHTML = `
                    <div class="w-full h-20 flex flex-col items-center justify-center p-2 bg-slate-50">
                        <span class="material-symbols-outlined text-slate-400 text-lg">description</span>
                        <p class="text-[8px] text-slate-500 truncate w-full text-center mt-1 font-semibold">${file.name}</p>
                    </div>
                    <button type="button" onclick="removeSelectedFile(${index})" class="absolute top-1 right-1 w-5 h-5 rounded-full bg-rose-600 text-white flex items-center justify-center shadow-lg hover:bg-rose-700 cursor-pointer transition-all border border-rose-500 text-[11px] font-bold" title="Delete">
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

    function showLivePreview() {
        const titleVal = document.getElementById('title').value.trim();
        const contentVal = quill.root.innerHTML.trim();

        if (!titleVal || contentVal === '<p><br></p>' || !contentVal) {
            alert('Please fill out the title and content first to view a preview.');
            return;
        }

        document.getElementById('preview-title').innerText = titleVal;
        document.getElementById('preview-body').innerHTML = contentVal;

        // Render preview media gallery
        renderPreviewGallery();

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
