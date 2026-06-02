<!-- Professional Search Modal -->
<div id="search-modal" class="fixed inset-0 z-50 flex items-start justify-center pt-[15vh] p-4 bg-slate-950/40 backdrop-blur-md opacity-0 pointer-events-none transition-all duration-300">
    <div class="w-full max-w-2xl bg-white rounded-[2rem] shadow-2xl border border-white/20 overflow-hidden transform transition-all duration-300" id="search-modal-content">
        <!-- Input Form -->
        <form action="{{ route('search') }}" method="GET" class="relative flex items-center p-4 border-b border-slate-100">
            <span class="material-symbols-outlined absolute left-6 text-blue-600 text-2xl font-bold">search</span>
            <input type="text" name="q" id="modal-search-input" class="w-full bg-transparent pl-14 pr-12 py-4 text-lg text-slate-800 placeholder-slate-400 focus:outline-none font-medium" placeholder="Search threads, posts, or media..." autocomplete="off">
            <button type="button" onclick="closeSearchModal()" class="absolute right-4 w-10 h-10 rounded-xl hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 cursor-pointer transition-colors">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>
        </form>
        
        <!-- Suggestions Area -->
        <div class="p-6 bg-slate-50/50">
            <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-[14px]">local_fire_department</span> Trending Searches
            </h4>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('search', ['q' => 'laravel']) }}" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-600 hover:border-blue-300 hover:text-blue-600 transition-colors shadow-sm">laravel</a>
                <a href="{{ route('search', ['q' => 'tailwind css']) }}" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-600 hover:border-blue-300 hover:text-blue-600 transition-colors shadow-sm">tailwind css</a>
                <a href="{{ route('search', ['q' => 'api authentication']) }}" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-600 hover:border-blue-300 hover:text-blue-600 transition-colors shadow-sm">api authentication</a>
            </div>
        </div>
    </div>
</div>

<!-- Modern Notifications Modal -->
<div id="notifications-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/20 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300">
    <div class="w-full max-w-md mui-card p-5 bg-white border border-slate-200">
        <div class="flex justify-between items-center mb-4 border-b border-slate-100 pb-3">
            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-600">notifications</span> 
                Notifications & Activity
            </h3>
            <button onclick="closeNotificationsModal()" class="w-8.5 h-8.5 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-500 cursor-pointer">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="space-y-2.5 max-h-80 overflow-y-auto custom-scrollbar pr-1" id="notifications-dropdown-list">
            <div class="py-12 text-center text-xs text-slate-450 font-medium">
                <span class="animate-pulse">Loading notifications...</span>
            </div>
        </div>
    </div>
</div>

<!-- Quick Settings Modal -->
@auth
    <div id="settings-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/20 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300">
        <div class="w-full max-w-md mui-card p-5 bg-white border border-slate-200">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest flex items-center gap-2"><span class="material-symbols-outlined text-blue-600">settings</span> Profile Settings</h3>
                <button onclick="closeSignatureModal()" class="w-8.5 h-8.5 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-500 cursor-pointer"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <!-- Avatar Upload -->
                    <div class="space-y-1">
                        <label for="modal-avatar" class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">New Avatar</label>
                        <input type="file" id="modal-avatar" name="avatar" class="block w-full text-[10px] text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all cursor-pointer">
                    </div>

                    <!-- Banner Upload -->
                    <div class="space-y-1">
                        <label for="modal-banner" class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Cover Photo Banner</label>
                        <input type="file" id="modal-banner" name="banner" class="block w-full text-[10px] text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all cursor-pointer">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Badge Title</label>
                    <input type="text" name="title_badge" value="{{ Auth::user()->title_badge }}" class="w-full bg-slate-50 border border-slate-355 rounded-xl px-4 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Theme Gradient Presets -->
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Profile Theme Gradient</label>
                    <div class="grid grid-cols-4 gap-2">
                        <label class="cursor-pointer flex items-center justify-center p-1.5 rounded-lg border border-slate-200 bg-slate-50 hover:bg-slate-100 transition-all">
                            <input type="radio" name="banner_color" value="linear-gradient(135deg, #6366f1, #a855f7)" {{ Auth::user()->banner_color === 'linear-gradient(135deg, #6366f1, #a855f7)' ? 'checked' : '' }} class="mr-1 text-blue-600 focus:ring-blue-500 scale-75">
                            <span class="w-4 h-4 rounded bg-gradient-to-r from-indigo-500 to-purple-500 inline-block shadow-inner"></span>
                        </label>

                        <label class="cursor-pointer flex items-center justify-center p-1.5 rounded-lg border border-slate-200 bg-slate-50 hover:bg-slate-100 transition-all">
                            <input type="radio" name="banner_color" value="linear-gradient(135deg, #ec4899, #8b5cf6)" {{ Auth::user()->banner_color === 'linear-gradient(135deg, #ec4899, #8b5cf6)' ? 'checked' : '' }} class="mr-1 text-pink-600 focus:ring-pink-500 scale-75">
                            <span class="w-4 h-4 rounded bg-gradient-to-r from-pink-500 to-violet-500 inline-block shadow-inner"></span>
                        </label>

                        <label class="cursor-pointer flex items-center justify-center p-1.5 rounded-lg border border-slate-200 bg-slate-50 hover:bg-slate-100 transition-all">
                            <input type="radio" name="banner_color" value="linear-gradient(135deg, #f97316, #ef4444)" {{ Auth::user()->banner_color === 'linear-gradient(135deg, #f97316, #ef4444)' ? 'checked' : '' }} class="mr-1 text-orange-600 focus:ring-orange-500 scale-75">
                            <span class="w-4 h-4 rounded bg-gradient-to-r from-orange-500 to-red-500 inline-block shadow-inner"></span>
                        </label>

                        <label class="cursor-pointer flex items-center justify-center p-1.5 rounded-lg border border-slate-200 bg-slate-50 hover:bg-slate-100 transition-all">
                            <input type="radio" name="banner_color" value="linear-gradient(135deg, #06b6d4, #3b82f6)" {{ Auth::user()->banner_color === 'linear-gradient(135deg, #06b6d4, #3b82f6)' ? 'checked' : '' }} class="mr-1 text-cyan-600 focus:ring-cyan-500 scale-75">
                            <span class="w-4 h-4 rounded bg-gradient-to-r from-cyan-500 to-blue-500 inline-block shadow-inner"></span>
                        </label>
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Forum Signature Quote</label>
                    <textarea name="signature" rows="2" class="w-full bg-slate-50 border border-slate-355 rounded-xl px-4 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ Auth::user()->signature }}</textarea>
                </div>
                <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeSignatureModal()" class="px-4 py-2 rounded-xl text-xs text-slate-500 hover:bg-slate-100 border border-transparent cursor-pointer font-semibold">Cancel</button>
                    <button type="submit" class="flex items-center gap-1 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition-all cursor-pointer">Save Settings</button>
                </div>
            </form>
        </div>
    </div>
@endauth

<!-- Reusable XenProfessional Live Hover Card (XenForo Style popover) -->
<div id="user-hover-card" class="absolute z-50 w-72 bg-white rounded-xl border border-slate-200 shadow-2xl opacity-0 pointer-events-none transition-all duration-200 scale-95" style="transform-origin: top center;">
    <div id="hover-card-header" class="h-14 rounded-t-xl" style="background: var(--color-primary, #3b82f6);"></div>
    <div class="p-4 flex gap-3 bg-white rounded-t-xl">
        <!-- Avatar Wrapper -->
        <div class="relative flex-shrink-0">
            <div class="w-14 h-14 rounded-full overflow-hidden border border-slate-200 bg-slate-100">
                <img id="hover-card-avatar" class="w-full h-full object-cover hidden">
                <div id="hover-card-avatar-placeholder" class="w-full h-full flex items-center justify-center font-bold text-slate-500 text-base"></div>
            </div>
            <!-- Dynamic Presence Dot -->
            <span id="hover-card-presence-dot" class="absolute bottom-0 right-0 w-3.5 h-3.5 rounded-full border-2 border-white bg-slate-400"></span>
        </div>
        <!-- User Core Details -->
        <div class="space-y-1.5 truncate text-left min-w-0 flex-1">
            <div class="flex items-center gap-1.5">
                <h4 class="font-extrabold text-slate-800 text-xs hover:text-blue-600 truncate leading-tight">
                    <a id="hover-card-name" href="#"></a>
                </h4>
                <span id="hover-card-presence-badge" class="text-[7.5px] px-1.5 py-0.5 rounded-full font-bold bg-slate-100 text-slate-500 flex items-center gap-1 flex-shrink-0">
                    <span class="w-1 h-1 rounded-full bg-slate-400" id="hover-card-presence-inner-dot"></span>
                    <span id="hover-card-presence-text">Offline</span>
                </span>
            </div>
            <div class="flex flex-wrap gap-1">
                <span id="hover-card-badge" class="text-[7.5px] px-1.5 py-0.5 rounded font-extrabold uppercase tracking-wider text-white shadow-sm leading-none"></span>
            </div>
            <p class="text-[9px] text-slate-450 font-bold">Joined: <span id="hover-card-joined" class="text-slate-700 font-bold"></span></p>
        </div>
    </div>
    <!-- Stats Row -->
    <div class="grid grid-cols-3 border-t border-slate-100 bg-slate-50/50 text-center divide-x divide-slate-100">
        <div class="py-1.5">
            <span class="block text-[11px] font-extrabold text-slate-850" id="hover-card-threads"></span>
            <span class="text-[7px] font-bold text-slate-400 uppercase tracking-widest leading-none">Threads</span>
        </div>
        <div class="py-1.5">
            <span class="block text-[11px] font-extrabold text-slate-850" id="hover-card-posts"></span>
            <span class="text-[7px] font-bold text-slate-400 uppercase tracking-widest leading-none">Replies</span>
        </div>
        <div class="py-1.5">
            <span class="block text-[11px] font-extrabold text-slate-850" id="hover-card-uploads"></span>
            <span class="text-[7px] font-bold text-slate-400 uppercase tracking-widest leading-none">Uploads</span>
        </div>
    </div>
    <!-- Actions Row -->
    <div id="hover-card-actions" class="flex border-t border-slate-100 bg-white rounded-b-xl overflow-hidden divide-x divide-slate-100 text-center text-[10px] font-extrabold">
        <button id="hover-card-follow-btn" onclick="handleHoverFollow()" class="flex-1 py-2 text-blue-600 hover:bg-slate-50/30 transition-colors cursor-pointer flex items-center justify-center gap-1 font-bold">
            <span class="material-symbols-outlined text-xs">person_add</span>
            <span id="hover-card-follow-text">Follow</span>
        </button>
        <button id="hover-card-message-btn" onclick="handleHoverMessage()" class="flex-1 py-2 text-slate-650 hover:bg-slate-50/30 transition-colors cursor-pointer flex items-center justify-center gap-1 font-bold">
            <span class="material-symbols-outlined text-xs">mail</span>
            <span>Message</span>
        </button>
    </div>
</div>

<!-- Reusable Premium Lightbox Modal for Images & GIFs -->
<div id="lightbox-modal" onclick="closeLightbox()" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md opacity-0 pointer-events-none transition-all duration-300 cursor-zoom-out">
    <div class="relative max-w-5xl max-h-[90vh] flex flex-col items-center justify-center select-none" onclick="event.stopPropagation()">
        <!-- Close Button -->
        <button onclick="closeLightbox()" class="absolute -top-12 right-0 w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 border border-white/20 text-white flex items-center justify-center cursor-pointer transition-all shadow-lg text-lg">
            ✕
        </button>
        <!-- Lightbox Image -->
        <div class="rounded-2xl overflow-hidden border border-white/10 shadow-2xl bg-slate-900 flex items-center justify-center">
            <img id="lightbox-image" class="max-w-full max-h-[80vh] object-contain block" src="" alt="Zoomed view">
        </div>
        <!-- Lightbox Caption -->
        <p id="lightbox-caption" class="text-xs text-white/80 font-semibold tracking-wide mt-3 text-center px-4 max-w-md truncate"></p>
    </div>
</div>
