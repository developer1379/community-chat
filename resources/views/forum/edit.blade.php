@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 px-3 sm:px-6">
    <!-- Header path info -->
    <div>
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
    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-xl overflow-hidden relative">
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
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-400 text-[18px]">title</span>
                        </span>
                        <input type="text" id="title" name="title" value="{{ old('title', $thread->title) }}" class="w-full bg-slate-50/50 border border-slate-200 rounded-2xl pl-11 pr-4 py-3.5 text-slate-800 text-xs sm:text-sm font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all placeholder:text-slate-400 placeholder:font-medium shadow-inner shadow-slate-100/50" placeholder="Give your thread a clean, descriptive title..." required>
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
                <input type="hidden" id="content-input" name="content" value="{{ old('content', $thread->firstPost?->content) }}">
                
                <!-- Quill container with custom HSL overrides -->
                <div class="rounded-2xl border border-slate-200 overflow-hidden bg-slate-50/50 focus-within:ring-2 focus-within:ring-blue-500/10 focus-within:border-blue-500 transition-all">
                    <div id="quill-editor" class="bg-white" style="height: 300px; font-size: 13.5px;">{!! old('content', $thread->firstPost?->content) !!}</div>
                </div>
                
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

<!-- JavaScript Integration for Tags and Quill editor -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. Quill Rich Editor Setup ---
        const quill = new Quill('#quill-editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    [{ 'color': [] }, { 'background': [] }],
                    ['clean']
                ]
            },
            placeholder: 'Draft your updated discussion details here...'
        });

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
@endsection
