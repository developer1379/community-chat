@extends('layouts.app')

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
                    <div class="group relative rounded-none sm:rounded-2xl overflow-hidden bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800/80 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                        <!-- Thumbnail Area -->
                        <div class="relative h-36 sm:h-52 overflow-hidden bg-slate-50 dark:bg-slate-950 flex-shrink-0">
                            <!-- Overlay Shadow -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10 pointer-events-none"></div>

                            <!-- Image -->
                            <img src="{{ $attach->url }}" class="w-full h-full object-cover group-hover:scale-103 transition-transform duration-300" alt="{{ $attach->file_name }}">

                            <!-- GIF Badge -->
                            @if(str_contains($attach->file_name, '.gif') || str_contains($attach->file_type, 'gif'))
                                <span class="absolute top-2 right-2 px-1.5 py-0.5 rounded text-[8px] font-black bg-pink-500 text-white uppercase tracking-widest z-25 shadow-sm">
                                    GIF
                                </span>
                            @endif

                            <!-- Quick Action: Zoom in Lightbox -->
                            <button onclick="openLightbox('{{ $attach->url }}', '{{ $attach->file_name }}')" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white/90 text-slate-800 hover:bg-white flex items-center justify-center opacity-0 group-hover:opacity-100 scale-90 group-hover:scale-100 transition-all duration-300 z-20 cursor-pointer shadow-md" title="Zoom Media">
                                <span class="material-symbols-outlined text-[18px] font-bold">zoom_in</span>
                            </button>
                        </div>

                        <!-- Card Footer Details -->
                        <div class="p-3 sm:p-4 flex-grow flex flex-col justify-between gap-2.5 bg-white dark:bg-slate-900">
                            <!-- File Title -->
                            <div class="space-y-0.5 min-w-0">
                                <p class="text-[11px] font-bold text-slate-800 dark:text-slate-200 truncate" title="{{ $attach->file_name }}">{{ $attach->file_name }}</p>
                                @if($attach->thread)
                                    <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider flex items-center gap-0.5">
                                        <span class="material-symbols-outlined text-[10px] font-bold text-slate-450 dark:text-slate-500">forum</span>
                                        <a href="{{ route('threads.show', $attach->thread->slug) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors truncate max-w-[120px] sm:max-w-[160px]">{{ $attach->thread->title }}</a>
                                    </p>
                                @endif
                            </div>

                            <!-- Divider -->
                            <div class="border-t border-slate-100 dark:border-slate-800/80 pt-2 flex items-center justify-between">
                                <!-- Uploader info -->
                                <div class="flex items-center gap-1.5 min-w-0">
                                    <div class="w-5 h-5 rounded-full overflow-hidden border border-slate-200 dark:border-slate-700 flex-shrink-0 bg-slate-50">
                                        <img src="{{ $attach->user->avatar_url }}" class="w-full h-full object-cover" alt="avatar">
                                    </div>
                                    <a href="{{ route('profile.show', $attach->user->name) }}" class="text-[10px] font-black text-slate-700 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors truncate" style="{{ $attach->user->username_style_css }}">{{ $attach->user->name }}</a>
                                </div>

                                <!-- Post Redirect Button -->
                                @if($attach->thread)
                                    <a href="{{ route('threads.show', $attach->thread->slug) }}" class="flex items-center justify-center w-6 h-6 rounded-lg bg-indigo-50 hover:bg-indigo-100 dark:bg-slate-800 dark:hover:bg-slate-700 text-indigo-600 dark:text-indigo-400 transition-colors" title="View associated thread">
                                        <span class="material-symbols-outlined text-[13px] font-bold">arrow_forward</span>
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
@endsection

