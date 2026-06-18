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

<!-- Modern Edit Post Modal -->
<div id="edit-post-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/20 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300">
    <div class="w-full max-w-2xl mui-card p-5 bg-white border border-slate-200 rounded-[2rem] shadow-2xl">
        <div class="flex justify-between items-center mb-4 border-b border-slate-100 pb-3">
            <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-widest flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-600">edit_note</span> Edit Post Reply
            </h3>
            <button type="button" onclick="closeEditPostModal()" class="w-8.5 h-8.5 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-500 cursor-pointer">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="edit-post-form" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="space-y-1.5">
                <!-- Hidden Quill value storage -->
                <textarea id="edit-post-content-input" name="content" class="hidden" readonly></textarea>
                <div class="rounded-xl border border-slate-200 overflow-hidden bg-slate-50">
                    <div id="edit-post-quill-editor" style="height: 200px; font-size: 13px;"></div>
                </div>
                <!-- ImgBB Upload Widget target container -->
                <div id="edit-post-imgbb-upload-container" class="mt-2 text-left"></div>
            </div>
            <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeEditPostModal()" class="px-5 py-2.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs cursor-pointer transition-all">Cancel</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs cursor-pointer shadow-md transition-all">Save Changes</button>
            </div>
        </form>
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
            <!-- Dynamic Status message -->
            <div id="hover-card-status-container" class="flex items-center gap-1.5 hidden mt-0.5">
                <span class="text-[9px] leading-none">💬</span>
                <p id="hover-card-status" class="text-[9px] text-slate-500 dark:text-slate-400 italic font-semibold truncate leading-normal"></p>
                <img id="hover-card-status-image" class="w-3.5 h-3.5 rounded object-cover cursor-zoom-in hidden" onclick="event.stopPropagation(); openLightbox(this.src, 'Status update image')">
            </div>
            <div class="flex flex-wrap items-center gap-1.5 mt-1">
                <span id="hover-card-badge" class="text-[7.5px] px-1.5 py-0.5 rounded font-extrabold uppercase tracking-wider text-white shadow-sm leading-none"></span>
                <span id="hover-card-rank-badge" class="text-[7.5px] px-1.5 py-0.5 rounded font-black uppercase tracking-wider text-white shadow-sm leading-none flex items-center gap-0.5"></span>
            </div>
            <p class="text-[9px] text-slate-450 font-bold">Joined: <span id="hover-card-joined" class="text-slate-700 font-bold"></span></p>
        </div>
    </div>
    <!-- Stats Row -->
    <div class="grid grid-cols-5 border-t border-slate-100 bg-slate-50/50 text-center divide-x divide-slate-100">
        <div class="py-1.5 px-0.5">
            <span class="block text-[10px] font-extrabold text-slate-850" id="hover-card-posts"></span>
            <span class="text-[6.5px] font-bold text-slate-400 uppercase tracking-widest leading-none">Posts</span>
        </div>
        <div class="py-1.5 px-0.5">
            <span class="block text-[10px] font-extrabold text-slate-850" id="hover-card-reactions"></span>
            <span class="text-[6.5px] font-bold text-slate-400 uppercase tracking-widest leading-none">Reactions</span>
        </div>
        <div class="py-1.5 px-0.5">
            <span class="block text-[10px] font-extrabold text-slate-850" id="hover-card-badges"></span>
            <span class="text-[6.5px] font-bold text-slate-400 uppercase tracking-widest leading-none">Badges</span>
        </div>
        <div class="py-1.5 px-0.5">
            <span class="block text-[10px] font-extrabold text-slate-850" id="hover-card-points"></span>
            <span class="text-[6.5px] font-bold text-slate-400 uppercase tracking-widest leading-none">Points</span>
        </div>
        <div class="py-1.5 px-0.5 min-w-0">
            <span class="block text-[10px] font-extrabold text-slate-850 truncate" id="hover-card-coins"></span>
            <span class="text-[6.5px] font-bold text-slate-400 uppercase tracking-widest leading-none">DF Coins</span>
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
<div id="lightbox-modal" onclick="closeLightbox()" class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md opacity-0 pointer-events-none transition-all duration-300 cursor-zoom-out">
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

<!-- Reusable Full-Screen Interactive Candy Crush Roadmap Modal -->
@auth
    @php
        $globalUser = Auth::user();
        $globalMilestones = \App\Models\RankMilestone::orderBy('level', 'asc')->get();
        $globalCoins = $globalUser->coins;
        
        $globalCurrentMilestone = $globalMilestones->first();
        foreach ($globalMilestones as $ms) {
            if ($globalCoins >= $ms->coins_required) {
                $globalCurrentMilestone = $ms;
            } else {
                break;
            }
        }
        
        $globalCoords = [
            1  => ['x' => 200, 'y' => 1280],
            2  => ['x' => 280, 'y' => 1215],
            3  => ['x' => 320, 'y' => 1150],
            4  => ['x' => 280, 'y' => 1085],
            5  => ['x' => 200, 'y' => 1020],
            6  => ['x' => 120, 'y' => 955],
            7  => ['x' => 80,  'y' => 890],
            8  => ['x' => 120, 'y' => 825],
            9  => ['x' => 200, 'y' => 760],
            10 => ['x' => 280, 'y' => 695],
            11 => ['x' => 320, 'y' => 630],
            12 => ['x' => 280, 'y' => 565],
            13 => ['x' => 200, 'y' => 500],
            14 => ['x' => 120, 'y' => 435],
            15 => ['x' => 80,  'y' => 370],
            16 => ['x' => 120, 'y' => 305],
            17 => ['x' => 200, 'y' => 240],
            18 => ['x' => 280, 'y' => 175],
            19 => ['x' => 320, 'y' => 110],
            20 => ['x' => 200, 'y' => 45],
        ];
    @endphp

    <div id="roadmap-fullscreen-modal" onclick="closeRoadmapModal()" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md opacity-0 pointer-events-none transition-all duration-300">
        <div class="relative w-full max-w-xl bg-white dark:bg-slate-900 rounded-[28px] shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden flex flex-col h-[90vh]" onclick="event.stopPropagation()">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-950 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-black uppercase text-slate-500 tracking-wider flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-blue-600 text-base animate-pulse">map</span> Interactive Journey Roadmap
                    </h3>
                    <p class="text-[9px] font-bold text-slate-450 dark:text-slate-500">Milestones unlocked by saving coins</p>
                </div>
                
                <div class="flex items-center gap-3">
                    <span class="text-[9px] font-black text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/20 border border-blue-150 dark:border-blue-900/30 px-2 py-0.5 rounded-lg">Level {{ $globalCurrentMilestone->level }}</span>
                    <button onclick="closeRoadmapModal()" class="w-8.5 h-8.5 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-800 flex items-center justify-center text-slate-400 hover:text-slate-655 cursor-pointer transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
            </div>

            <!-- Scrollable Winding Roadmap Map Area -->
            <div class="relative bg-slate-50 dark:bg-slate-950/40 flex-grow overflow-y-auto custom-scrollbar p-4 flex flex-col items-center justify-start" id="roadmap-modal-scroll-container">
                <!-- SVG Connector Track -->
                <svg viewBox="0 0 400 1350" class="relative z-10 flex-shrink-0" style="min-height: 1600px; min-width: 480px; height: 1600px; width: 480px;">
                    <defs>
                        <linearGradient id="modalActiveTrackGrad" x1="0" y1="1" x2="0" y2="0">
                            <stop offset="0%" stop-color="#10B981" />
                            <stop offset="50%" stop-color="#3B82F6" />
                            <stop offset="100%" stop-color="#EF4444" />
                        </linearGradient>
                    </defs>

                    <!-- Background Connection Path (Static Gray Line) -->
                    <path d="M 200,1280 C 280,1280 320,1215 320,1150 C 320,1085 280,1020 200,1020 C 120,1020 80,955 80,890 C 80,825 120,760 200,760 C 280,760 320,695 320,630 C 320,565 280,500 200,500 C 120,500 80,435 80,370 C 80,305 120,240 200,240 C 280,240 320,175 320,110 C 320,45 200,45 200,45" fill="none" stroke="#e2e8f0" stroke-width="10" stroke-linecap="round"/>
                    
                    <!-- Animated conveyor belt track overlay -->
                    <path d="M 200,1280 C 280,1280 320,1215 320,1150 C 320,1085 280,1020 200,1020 C 120,1020 80,955 80,890 C 80,825 120,760 200,760 C 280,760 320,695 320,630 C 320,565 280,500 200,500 C 120,500 80,435 80,370 C 80,305 120,240 200,240 C 280,240 320,175 320,110 C 320,45 200,45 200,45" fill="none" stroke="url(#modalActiveTrackGrad)" stroke-width="10" stroke-linecap="round" stroke-dasharray="14, 8" class="animate-conveyor-modal"/>
                    
                    <style>
                        .animate-conveyor-modal {
                            animation: conveyorDashModal 2s linear infinite;
                        }
                        @keyframes conveyorDashModal {
                            to { stroke-dashoffset: -44; }
                        }
                        .roadmap-hover-target-modal:hover .stone-ring-modal {
                            transform: scale(1.15);
                        }
                    </style>

                    <!-- Nodes -->
                    @foreach($globalMilestones as $index => $ms)
                        @php
                            $c = $globalCoords[$ms->level] ?? ['x' => 200, 'y' => 600];
                            $unlocked = $globalCoins >= $ms->coins_required;
                            $isCurrent = $globalCurrentMilestone->level === $ms->level;
                            
                            $textX = $c['x'];
                            $textY = $c['y'];
                            $anchor = 'middle';
                            
                            if ($c['x'] == 200) {
                                $textY = $c['y'] - 26;
                            } elseif ($c['x'] == 80) {
                                $textX = $c['x'] + 32;
                                $anchor = 'start';
                            } elseif ($c['x'] == 320) {
                                $textX = $c['x'] - 32;
                                $anchor = 'end';
                            } elseif ($c['x'] == 120) {
                                $textX = $c['x'] + 32;
                                $anchor = 'start';
                            } elseif ($c['x'] == 280) {
                                $textX = $c['x'] - 32;
                                $anchor = 'end';
                            }
                            
                            $mIcon = 'star';
                            if ($ms->level >= 20) { $mIcon = 'emoji_events'; }
                            elseif ($ms->level >= 16) { $mIcon = 'diamond'; }
                            elseif ($ms->level >= 12) { $mIcon = 'workspace_premium'; }
                            elseif ($ms->level >= 8) { $mIcon = 'military_tech'; }
                            elseif ($ms->level >= 4) { $mIcon = 'shield'; }
                        @endphp

                        <g class="roadmap-hover-target-modal cursor-help {{ $isCurrent ? 'active-focus-node-modal' : '' }}" data-node-level="{{ $ms->level }}" data-node-name="{{ $ms->name }}" data-node-coins="{{ number_format($ms->coins_required) }}" data-node-badge="{{ $ms->badge }}" data-node-status="{{ $unlocked ? 'Unlocked' : 'Locked' }}">
                            @if($isCurrent)
                                <circle cx="{{ $c['x'] }}" cy="{{ $c['y'] }}" r="28" fill="none" stroke="{{ $ms->color }}" stroke-width="2" opacity="0.4" class="animate-ping" style="transform-origin: {{ $c['x'] }}px {{ $c['y'] }}px;"/>
                            @endif
                            
                            <circle cx="{{ $c['x'] }}" cy="{{ $c['y'] }}" r="21" class="stone-ring-modal transition-transform" fill="{{ $unlocked ? $ms->color : '#cbd5e1' }}" opacity="0.3"/>
                            <circle cx="{{ $c['x'] }}" cy="{{ $c['y'] }}" r="18" fill="{{ $unlocked ? '#ffffff' : '#f1f5f9' }}" stroke="{{ $unlocked ? $ms->color : '#94a3b8' }}" stroke-width="2"/>
                            
                            @if($unlocked)
                                @if($mIcon === 'emoji_events')
                                    <path d="M {{ $c['x']-6 }} {{ $c['y']-7 }} H {{ $c['x']+6 }} V {{ $c['y']-2 }} Q {{ $c['x']+6 }} {{ $c['y']+3 }} {{ $c['x'] }} {{ $c['y']+3 }} Q {{ $c['x']-6 }} {{ $c['y']+3 }} {{ $c['x']-6 }} {{ $c['y']-2 }} Z M {{ $c['x'] }} {{ $c['y']+3 }} V {{ $c['y']+7 }} H {{ $c['x']-3 }} V {{ $c['y']+9 }} H {{ $c['x']+3 }} V {{ $c['y']+7 }} H {{ $c['x'] }} Z" fill="{{ $ms->color }}" />
                                @elseif($mIcon === 'diamond')
                                    <path d="M {{ $c['x'] }} {{ $c['y']-8 }} L {{ $c['x']+7 }} {{ $c['y']-2 }} L {{ $c['x'] }} {{ $c['y']+8 }} L {{ $c['x']-7 }} {{ $c['y']-2 }} Z" fill="{{ $ms->color }}" />
                                @elseif($mIcon === 'workspace_premium')
                                    <circle cx="{{ $c['x'] }}" cy="{{ $c['y']-2 }}" r="5" stroke="{{ $ms->color }}" stroke-width="2" fill="none" />
                                    <path d="M {{ $c['x']-2 }} {{ $c['y']+3 }} L {{ $c['x']-4 }} {{ $c['y']+8 }} L {{ $c['x'] }} {{ $c['y']+6 }} L {{ $c['x']+4 }} {{ $c['y']+8 }} L {{ $c['x']+2 }} {{ $c['y']+3 }}" fill="{{ $ms->color }}" />
                                @elseif($mIcon === 'military_tech')
                                    <path d="M {{ $c['x']-4 }} {{ $c['y']-7 }} L {{ $c['x']+4 }} {{ $c['y']-7 }} L {{ $c['x']+6 }} {{ $c['y']+1 }} L {{ $c['x'] }} {{ $c['y']+8 }} L {{ $c['x']-6 }} {{ $c['y']+1 }} Z" fill="{{ $ms->color }}" opacity="0.3"/>
                                    <circle cx="{{ $c['x'] }}" cy="{{ $c['y']-1 }}" r="3" fill="{{ $ms->color }}"/>
                                @elseif($mIcon === 'shield')
                                    <path d="M {{ $c['x'] }} {{ $c['y']-8 }} L {{ $c['x']-6 }} {{ $c['y']-5 }} V {{ $c['y'] }} C {{ $c['x']-6 }} {{ $c['y']+4 }} {{ $c['x'] }} {{ $c['y']+8 }} {{ $c['x'] }} {{ $c['y']+8 }} C {{ $c['x'] }} {{ $c['y']+8 }} {{ $c['x']+6 }} {{ $c['y']+4 }} {{ $c['x']+6 }} {{ $c['y'] }} V {{ $c['y']-5 }} Z" fill="{{ $ms->color }}" />
                                @else
                                    <path d="M {{ $c['x'] }} {{ $c['y']-7 }} L {{ $c['x']+2 }} {{ $c['y']-2 }} H {{ $c['x']+7 }} L {{ $c['x']+3 }} {{ $c['y']+1 }} L {{ $c['x']+5 }} {{ $c['y']+6 }} L {{ $c['x'] }} {{ $c['y']+3 }} L {{ $c['x']-5 }} {{ $c['y']+6 }} L {{ $c['x']-3 }} {{ $c['y']+1 }} L {{ $c['x']-7 }} {{ $c['y']-2 }} H {{ $c['x']-2 }} Z" fill="{{ $ms->color }}" />
                                @endif
                            @else
                                <path d="M {{ $c['x']-4 }} {{ $c['y'] }} V {{ $c['y']-3 }} C {{ $c['x']-4 }} {{ $c['y']-5.5 }} {{ $c['x']+4 }} {{ $c['y']-5.5 }} {{ $c['x']+4 }} {{ $c['y']-3 }} V {{ $c['y'] }} H {{ $c['x']-4 }} Z M {{ $c['x']-5 }} {{ $c['y'] }} H {{ $c['x']+5 }} V {{ $c['y']+6 }} H {{ $c['x']-5 }} Z" fill="#94a3b8" />
                            @endif

                            <text x="{{ $textX }}" y="{{ $textY + 3 }}" font-size="10" font-weight="900" font-family="Plus Jakarta Sans, sans-serif" text-anchor="{{ $anchor }}" fill="{{ $unlocked ? $ms->color : '#94a3b8' }}" class="uppercase tracking-wide">
                                {{ $ms->name }}
                            </text>
                        </g>
                    @endforeach
                </svg>

                <!-- Tooltip inside scroll wrapper -->
                <div id="roadmap-modal-tooltip" class="absolute hidden bg-slate-900 text-white p-3 rounded-2xl text-[10px] w-48 shadow-xl border border-white/10 z-30 leading-relaxed pointer-events-none transition-opacity duration-200">
                    <div class="flex justify-between font-extrabold items-center">
                        <span id="tooltip-modal-title" class="text-sm">Milestone</span>
                        <span id="tooltip-modal-level" class="text-[9px] uppercase px-1.5 py-0.2 bg-white/20 rounded font-black text-amber-300">Lvl</span>
                    </div>
                    <p id="tooltip-modal-badge" class="text-slate-400 mt-1 font-bold">Badge Title</p>
                    <p id="tooltip-modal-coins" class="text-slate-500 mt-0.5">0 coins required</p>
                    <p id="tooltip-modal-status" class="text-emerald-400 font-extrabold uppercase mt-1">Unlocked</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Roadmap Modal Popover Controller Scripts -->
    <script>
        function openRoadmapModal() {
            const modal = document.getElementById('roadmap-fullscreen-modal');
            const container = document.getElementById('roadmap-modal-scroll-container');
            
            if (modal) {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                modal.classList.add('opacity-100');
                
                // Auto scroll to active node center using precise client rects
                setTimeout(() => {
                    const activeNode = container ? container.querySelector('.active-focus-node-modal') : null;
                    if (container && activeNode) {
                        const containerRect = container.getBoundingClientRect();
                        const activeRect = activeNode.getBoundingClientRect();
                        const scrollOffset = activeRect.top - containerRect.top + container.scrollTop - (container.clientHeight / 2);
                        container.scrollTop = scrollOffset;
                    }
                }, 150);
            }
        }

        function closeRoadmapModal() {
            const modal = document.getElementById('roadmap-fullscreen-modal');
            if (modal) {
                modal.classList.add('opacity-0', 'pointer-events-none');
                modal.classList.remove('opacity-100');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('roadmap-modal-scroll-container');
            const tooltip = document.getElementById('roadmap-modal-tooltip');

            document.querySelectorAll('.roadmap-hover-target-modal').forEach(node => {
                node.addEventListener('mouseenter', function(e) {
                    const name = node.getAttribute('data-node-name');
                    const level = node.getAttribute('data-node-level');
                    const coins = node.getAttribute('data-node-coins');
                    const badge = node.getAttribute('data-node-badge');
                    const status = node.getAttribute('data-node-status');

                    document.getElementById('tooltip-modal-title').innerText = name;
                    document.getElementById('tooltip-modal-level').innerText = `Lvl ${level}`;
                    document.getElementById('tooltip-modal-badge').innerText = badge;
                    document.getElementById('tooltip-modal-coins').innerText = `${coins} coins required`;
                    
                    const statusEl = document.getElementById('tooltip-modal-status');
                    statusEl.innerText = status === 'Unlocked' ? 'Unlocked ✓' : 'Locked 🔒';
                    statusEl.className = status === 'Unlocked' ? 'text-emerald-400 font-extrabold uppercase mt-1' : 'text-slate-500 font-extrabold uppercase mt-1';

                    tooltip.classList.remove('hidden');
                    tooltip.style.opacity = '1';
                });

                node.addEventListener('mousemove', function(e) {
                    if (!container) return;
                    const containerRect = container.getBoundingClientRect();
                    const x = e.clientX - containerRect.left + container.scrollLeft + 15;
                    const y = e.clientY - containerRect.top + container.scrollTop - 40;
                    
                    tooltip.style.left = `${x}px`;
                    tooltip.style.top = `${y}px`;
                });

                node.addEventListener('mouseleave', function() {
                    tooltip.style.opacity = '0';
                    setTimeout(() => {
                        tooltip.classList.add('hidden');
                    }, 100);
                });
            });
        });
    </script>
@endauth

<!-- Reusable Login & Register Modal -->
<div id="login-auth-modal" onclick="closeAuthModal()" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-md opacity-0 pointer-events-none transition-all duration-300">
    <div class="w-full max-w-md bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden relative transform scale-95 transition-all duration-300" id="login-auth-modal-content" onclick="event.stopPropagation()">
        <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500"></div>
        
        <!-- Modal Header -->
        <div class="px-6 pt-6 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center shadow-md shadow-blue-500/10">
                    <span class="material-symbols-outlined text-white text-base">forum</span>
                </div>
                <h3 class="text-xs font-black uppercase text-slate-500 tracking-wider">XenProfessional</h3>
            </div>
            <button type="button" onclick="closeAuthModal()" class="w-8 h-8 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-850 flex items-center justify-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 cursor-pointer transition-colors bg-transparent border-0">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
        </div>

        <!-- Tabs Container -->
        <div class="px-8 pt-4 pb-6">
            <!-- Tabs Navigation -->
            <div class="flex border-b border-slate-150 dark:border-slate-800 mb-6">
                <button onclick="switchAuthTab('login')" id="tab-btn-login" class="flex-1 pb-3 text-sm font-bold text-blue-600 border-b-2 border-blue-600 focus:outline-none transition-all cursor-pointer bg-transparent">Sign In</button>
                <button onclick="switchAuthTab('register')" id="tab-btn-register" class="flex-1 pb-3 text-sm font-bold text-slate-400 border-b-2 border-transparent hover:text-slate-600 dark:hover:text-slate-300 focus:outline-none transition-all cursor-pointer bg-transparent">Register</button>
            </div>

            <!-- Login View -->
            <div id="auth-view-login" class="space-y-4">
                <div class="text-center mb-4">
                    <h4 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Welcome Back</h4>
                    <p class="text-[11px] font-medium text-slate-500 dark:text-slate-400 mt-1">Please sign in to proceed with XenProfessional</p>
                </div>

                @if ($errors->has('email') || $errors->has('password'))
                    <div class="p-3 rounded-xl bg-rose-50 dark:bg-rose-950/20 border border-rose-500/20 text-rose-600 dark:text-rose-400 text-xs font-bold leading-normal">
                        {{ $errors->first('email') ?: $errors->first('password') }}
                    </div>
                @endif

                <!-- Google Button -->
                <a href="{{ route('auth.google.redirect') }}" class="w-full flex items-center justify-center gap-3 px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 hover:border-slate-350 dark:hover:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-900 rounded-2xl text-xs font-bold text-slate-700 dark:text-slate-300 transition-all focus:outline-none shadow-sm no-underline">
                    <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    Continue with Google
                </a>

                <!-- Divider -->
                <div class="relative flex items-center py-1">
                    <div class="flex-grow border-t border-slate-200 dark:border-slate-800"></div>
                    <span class="flex-shrink-0 mx-3 text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Or</span>
                    <div class="flex-grow border-t border-slate-200 dark:border-slate-800"></div>
                </div>

                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf
                    <!-- Email -->
                    <div class="space-y-1.5 text-left">
                        <label for="modal-login-email" class="text-[10px] font-black text-slate-700 dark:text-slate-350 uppercase tracking-widest">Email Address</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-slate-400 text-[16px]">mail</span>
                            </span>
                            <input type="email" id="modal-login-email" name="email" class="w-full bg-slate-50/50 dark:bg-slate-950/20 border border-slate-200 dark:border-slate-800 rounded-xl pl-10 pr-4 py-2.5 text-slate-850 dark:text-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="name@domain.com" required>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="space-y-1.5 text-left">
                        <label for="modal-login-password" class="text-[10px] font-black text-slate-700 dark:text-slate-350 uppercase tracking-widest">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-slate-400 text-[16px]">lock</span>
                            </span>
                            <input type="password" id="modal-login-password" name="password" class="w-full bg-slate-50/50 dark:bg-slate-950/20 border border-slate-200 dark:border-slate-800 rounded-xl pl-10 pr-4 py-2.5 text-slate-850 dark:text-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="••••••••" required>
                        </div>
                    </div>

                    <!-- Remember -->
                    <div class="flex items-center">
                        <input type="checkbox" id="modal-login-remember" name="remember" class="w-4 h-4 rounded bg-slate-50 dark:bg-slate-950 border-slate-300 dark:border-slate-800 text-blue-600 focus:ring-blue-500 cursor-pointer">
                        <label for="modal-login-remember" class="text-[10px] text-slate-500 dark:text-slate-400 ml-2 font-bold cursor-pointer">Remember me next time</label>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs py-3 rounded-xl shadow-md transition-all cursor-pointer border-0">
                        Sign In to Account
                    </button>
                </form>
            </div>

            <!-- Register View -->
            <div id="auth-view-register" class="space-y-4 hidden">
                <!-- Step Indicator -->
                <div class="flex items-center justify-between">
                    <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">
                        Setup Progress
                    </span>
                    <div class="flex items-center gap-1.5">
                        <span id="modal-step-dot-1" class="w-2 h-2 rounded-full transition-all duration-300 bg-blue-600 shadow-sm shadow-blue-500/50"></span>
                        <span class="h-0.5 w-3 bg-slate-200 dark:bg-slate-800"></span>
                        <span id="modal-step-dot-2" class="w-2 h-2 rounded-full transition-all duration-300 bg-slate-200 dark:bg-slate-850"></span>
                    </div>
                </div>

                @if ($errors->has('name') || $errors->has('email') || $errors->has('password'))
                    <div class="p-3 rounded-xl bg-rose-50 dark:bg-rose-950/20 border border-rose-500/20 text-rose-600 dark:text-rose-400 text-xs font-bold leading-normal">
                        {{ $errors->first('name') ?: ($errors->first('email') ?: $errors->first('password')) }}
                    </div>
                @endif

                <form id="modal-registration-form" action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <!-- STEP 1: Username & Profile Setup -->
                    <div id="modal-step-panel-1" class="space-y-4 transition-all duration-300">
                        <!-- Username -->
                        <div class="space-y-1.5 text-left">
                            <label for="modal-name" class="text-[10px] font-black text-slate-700 dark:text-slate-350 uppercase tracking-widest ml-1">Username</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="material-symbols-outlined text-slate-400 text-[16px]">person</span>
                                </span>
                                <input type="text" id="modal-name" name="name" value="{{ old('name') }}" class="w-full bg-slate-50/50 dark:bg-slate-950/20 border border-slate-200 dark:border-slate-800 rounded-xl pl-9 pr-10 py-2.5 text-slate-850 dark:text-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Choose a display name" required>
                                
                                <span class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <span id="modal-username-spinner" class="hidden animate-spin h-3.5 w-3.5 border-2 border-blue-500 border-t-transparent rounded-full"></span>
                                    <span id="modal-username-ok-badge" class="hidden material-symbols-outlined text-emerald-500 text-[18px] font-bold">check_circle</span>
                                    <span id="modal-username-err-badge" class="hidden material-symbols-outlined text-rose-500 text-[18px] font-bold">cancel</span>
                                </span>
                            </div>
                            
                            <p id="modal-username-feedback" class="text-[10px] font-bold mt-1 ml-1 text-slate-400 dark:text-slate-500">
                                Usernames must be unique and contain no special characters.
                            </p>
                        </div>

                        <!-- Profile Avatar Selection -->
                        <div class="space-y-3 text-left border-t border-slate-100 dark:border-slate-850 pt-3">
                            <label class="text-[10px] font-black text-slate-700 dark:text-slate-350 uppercase tracking-widest ml-1">Profile Avatar</label>
                            
                            <!-- Active preview of avatar -->
                            <div class="flex items-center gap-3 p-3 border border-slate-150 dark:border-slate-800 rounded-xl bg-slate-50/30 dark:bg-slate-950/20">
                                <div class="relative w-10 h-10 rounded-full overflow-hidden border border-slate-200 dark:border-slate-850 bg-white dark:bg-slate-900 flex-shrink-0">
                                    <img id="modal-avatar-preview" src="https://api.dicebear.com/7.x/bottts/svg?seed=Felix" class="w-full h-full object-cover" alt="Avatar preview">
                                </div>
                                <div>
                                    <h4 class="text-[11px] font-bold text-slate-800 dark:text-slate-250">Avatar Preview</h4>
                                    <p class="text-[9px] text-slate-550 dark:text-slate-400 mt-0.5">Choose a preset or upload an image file.</p>
                                </div>
                            </div>

                            <!-- Option A: File Upload -->
                            <div class="space-y-1">
                                <span class="text-[9px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">Option A: Upload custom image</span>
                                <div class="relative">
                                    <input type="file" id="modal-avatar_file" name="avatar_file" accept="image/*" class="hidden" onchange="previewModalUploadedFile(this)">
                                    <label for="modal-avatar_file" class="flex items-center justify-center gap-1.5 w-full px-3 py-2 border border-dashed border-slate-300 dark:border-slate-700 rounded-xl cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-850 hover:border-blue-400 transition-all font-bold text-[10px] text-slate-600 dark:text-slate-350">
                                        <span class="material-symbols-outlined text-[14px]">cloud_upload</span>
                                        Choose custom file...
                                    </label>
                                </div>
                            </div>

                            <!-- Option B: Presets -->
                            <div class="space-y-1.5">
                                <span class="text-[9px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">Option B: Select preset</span>
                                <input type="hidden" id="modal-selected-preset" name="avatar_preset" value="https://api.dicebear.com/7.x/bottts/svg?seed=Felix">
                                
                                <div class="grid grid-cols-4 gap-1.5">
                                    @php
                                        $presets = [
                                            'https://api.dicebear.com/7.x/bottts/svg?seed=Felix',
                                            'https://api.dicebear.com/7.x/bottts/svg?seed=Aneka',
                                            'https://api.dicebear.com/7.x/adventurer/svg?seed=Nala',
                                            'https://api.dicebear.com/7.x/adventurer/svg?seed=Buster',
                                            'https://api.dicebear.com/7.x/fun-emoji/svg?seed=Gizmo',
                                            'https://api.dicebear.com/7.x/fun-emoji/svg?seed=Maggie',
                                            'https://api.dicebear.com/7.x/pixel-art/svg?seed=Luna',
                                            'https://api.dicebear.com/7.x/pixel-art/svg?seed=Cooper'
                                        ];
                                    @endphp
                                    @foreach($presets as $index => $preset)
                                        <div onclick="selectModalPreset('{{ $preset }}', this)" class="modal-avatar-option-item relative aspect-square rounded-full overflow-hidden border {{ $index === 0 ? 'border-blue-500 ring-2 ring-blue-500/20' : 'border-slate-200 dark:border-slate-850' }} hover:border-blue-400 cursor-pointer hover:scale-105 transition-all select-none bg-slate-50 dark:bg-slate-950">
                                            <img src="{{ $preset }}" class="w-full h-full object-cover" alt="Preset">
                                            <div class="modal-checkmark-overlay {{ $index === 0 ? '' : 'hidden' }} absolute inset-0 bg-blue-500/10 flex items-center justify-center">
                                                <span class="material-symbols-outlined text-white text-[10px] bg-blue-500 rounded-full p-0.5 font-bold">done</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Step 1 Button -->
                        <div class="pt-1">
                            <button type="button" id="modal-btn-next-step" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs py-3 rounded-xl shadow-md transition-all cursor-pointer border-0">
                                Continue to Account Details
                            </button>
                        </div>
                    </div>

                    <!-- STEP 2: Credentials (Email / Passwords) -->
                    <div id="modal-step-panel-2" class="hidden space-y-4 transition-all duration-300">
                        <!-- Email Field -->
                        <div class="space-y-1.5 text-left">
                            <label for="modal-email" class="text-[10px] font-black text-slate-700 dark:text-slate-350 uppercase tracking-widest ml-1">Email Address</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="material-symbols-outlined text-slate-400 text-[16px]">mail</span>
                                </span>
                                <input type="email" id="modal-email" name="email" class="w-full bg-slate-50/50 dark:bg-slate-950/20 border border-slate-200 dark:border-slate-800 rounded-xl pl-9 pr-4 py-2.5 text-slate-850 dark:text-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="name@domain.com">
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div class="space-y-1.5 text-left">
                            <label for="modal-password" class="text-[10px] font-black text-slate-700 dark:text-slate-350 uppercase tracking-widest ml-1">Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="material-symbols-outlined text-slate-400 text-[16px]">lock</span>
                                </span>
                                <input type="password" id="modal-password" name="password" class="w-full bg-slate-50/50 dark:bg-slate-950/20 border border-slate-200 dark:border-slate-800 rounded-xl pl-9 pr-4 py-2.5 text-slate-850 dark:text-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="••••••••">
                            </div>
                        </div>

                        <!-- Password Confirmation Field -->
                        <div class="space-y-1.5 text-left">
                            <label for="modal-password-confirm" class="text-[10px] font-black text-slate-700 dark:text-slate-350 uppercase tracking-widest ml-1">Confirm Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="material-symbols-outlined text-slate-400 text-[16px]">lock_reset</span>
                                </span>
                                <input type="password" id="modal-password-confirm" name="password_confirmation" class="w-full bg-slate-50/50 dark:bg-slate-950/20 border border-slate-200 dark:border-slate-800 rounded-xl pl-9 pr-4 py-2.5 text-slate-850 dark:text-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="••••••••">
                            </div>
                        </div>

                        <!-- Step 2 Buttons -->
                        <div class="grid grid-cols-3 gap-2.5 pt-1">
                            <button type="button" id="modal-btn-prev-step" class="col-span-1 border border-slate-250 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-850 text-[10px] font-bold text-slate-600 dark:text-slate-300 py-3 rounded-xl cursor-pointer bg-transparent">
                                Back
                            </button>
                            
                            <button type="submit" class="col-span-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs py-3 rounded-xl shadow-md transition-all cursor-pointer border-0">
                                Register Account
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-slate-50 dark:bg-slate-950/45 p-4 text-center border-t border-slate-100 dark:border-slate-850">
            <span class="text-[9px] font-bold text-slate-450 uppercase tracking-widest">By logging in, you agree to our <a href="{{ route('rules') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Community Rules</a></span>
        </div>
    </div>
</div>

<script>
function selectModalPreset(url, element) {
    document.getElementById('modal-selected-preset').value = url;
    document.getElementById('modal-avatar_file').value = '';
    
    document.querySelectorAll('.modal-avatar-option-item').forEach(item => {
        item.classList.remove('border-blue-500', 'ring-2', 'ring-blue-500/20');
        item.classList.add('border-slate-200', 'dark:border-slate-850');
        const overlay = item.querySelector('.modal-checkmark-overlay');
        if (overlay) overlay.classList.add('hidden');
    });

    element.classList.add('border-blue-500', 'ring-2', 'ring-blue-500/20');
    element.classList.remove('border-slate-200', 'dark:border-slate-850');
    const overlay = element.querySelector('.modal-checkmark-overlay');
    if (overlay) overlay.classList.remove('hidden');

    document.getElementById('modal-avatar-preview').src = url;
}

function previewModalUploadedFile(input) {
    if (input.files && input.files[0]) {
        document.getElementById('modal-selected-preset').value = '';
        document.querySelectorAll('.modal-avatar-option-item').forEach(item => {
            item.classList.remove('border-blue-500', 'ring-2', 'ring-blue-500/20');
            item.classList.add('border-slate-200', 'dark:border-slate-850');
            const overlay = item.querySelector('.modal-checkmark-overlay');
            if (overlay) overlay.classList.add('hidden');
        });

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('modal-avatar-preview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const modalNameInput = document.getElementById('modal-name');
    const modalFeedbackText = document.getElementById('modal-username-feedback');
    const modalSpinner = document.getElementById('modal-username-spinner');
    const modalBadgeOk = document.getElementById('modal-username-ok-badge');
    const modalBadgeErr = document.getElementById('modal-username-err-badge');
    
    const modalBtnNext = document.getElementById('modal-btn-next-step');
    const modalBtnPrev = document.getElementById('modal-btn-prev-step');
    const modalStepPanel1 = document.getElementById('modal-step-panel-1');
    const modalStepPanel2 = document.getElementById('modal-step-panel-2');
    const modalStepDot1 = document.getElementById('modal-step-dot-1');
    const modalStepDot2 = document.getElementById('modal-step-dot-2');
    
    let isModalUsernameAvailable = false;
    let modalCheckTimeout = null;

    let activeModalStep = 1;
    @if(old('name') && ($errors->has('email') || $errors->has('password')))
        activeModalStep = 2;
    @endif

    function goToModalStep(step) {
        if (step === 2) {
            modalStepPanel1.classList.add('hidden');
            modalStepPanel2.classList.remove('hidden');
            
            modalStepDot1.classList.remove('bg-blue-600', 'shadow-blue-500/50');
            modalStepDot1.classList.add('bg-slate-200', 'dark:bg-slate-800');
            modalStepDot2.classList.add('bg-blue-600', 'shadow-blue-500/50');
            modalStepDot2.classList.remove('bg-slate-200', 'dark:bg-slate-800');
            
            document.getElementById('modal-email').required = true;
            document.getElementById('modal-password').required = true;
            document.getElementById('modal-password-confirm').required = true;
            
            activeModalStep = 2;
        } else {
            modalStepPanel1.classList.remove('hidden');
            modalStepPanel2.classList.add('hidden');
            
            modalStepDot1.classList.add('bg-blue-600', 'shadow-blue-500/50');
            modalStepDot1.classList.remove('bg-slate-200', 'dark:bg-slate-800');
            modalStepDot2.classList.remove('bg-blue-600', 'shadow-blue-500/50');
            modalStepDot2.classList.add('bg-slate-200', 'dark:bg-slate-800');
            
            document.getElementById('modal-email').required = false;
            document.getElementById('modal-password').required = false;
            document.getElementById('modal-password-confirm').required = false;
            
            activeModalStep = 1;
        }
    }

    if (activeModalStep === 2) {
        goToModalStep(2);
    }

    function checkModalUsername(username) {
        if (!username || username.trim().length < 3) {
            if (modalFeedbackText) {
                modalFeedbackText.innerText = "Username must be at least 3 characters.";
                modalFeedbackText.className = "text-[10px] font-bold mt-1 ml-1 text-rose-500";
            }
            if (modalBadgeOk) modalBadgeOk.classList.add('hidden');
            if (modalBadgeErr) modalBadgeErr.classList.remove('hidden');
            if (modalSpinner) modalSpinner.classList.add('hidden');
            isModalUsernameAvailable = false;
            return;
        }

        const cleanRegex = /^[A-Za-z0-9\s-_]+$/;
        if (!cleanRegex.test(username)) {
            if (modalFeedbackText) {
                modalFeedbackText.innerText = "No special characters allowed (letters, numbers, space, dash, underscore only).";
                modalFeedbackText.className = "text-[10px] font-bold mt-1 ml-1 text-rose-500";
            }
            if (modalBadgeOk) modalBadgeOk.classList.add('hidden');
            if (modalBadgeErr) modalBadgeErr.classList.remove('hidden');
            if (modalSpinner) modalSpinner.classList.add('hidden');
            isModalUsernameAvailable = false;
            return;
        }

        if (modalSpinner) modalSpinner.classList.remove('hidden');
        if (modalBadgeOk) modalBadgeOk.classList.add('hidden');
        if (modalBadgeErr) modalBadgeErr.classList.add('hidden');

        fetch("{{ route('register.check-username') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name: username })
        })
        .then(r => r.json())
        .then(data => {
            if (modalSpinner) modalSpinner.classList.add('hidden');
            if (data.available) {
                if (modalFeedbackText) {
                    modalFeedbackText.innerText = "Awesome, that username is available!";
                    modalFeedbackText.className = "text-[10px] font-bold mt-1 ml-1 text-emerald-500";
                }
                if (modalBadgeOk) modalBadgeOk.classList.remove('hidden');
                if (modalBadgeErr) modalBadgeErr.classList.add('hidden');
                isModalUsernameAvailable = true;
            } else {
                if (modalFeedbackText) {
                    modalFeedbackText.innerText = "Sorry, that username is already taken.";
                    modalFeedbackText.className = "text-[10px] font-bold mt-1 ml-1 text-rose-500";
                }
                if (modalBadgeOk) modalBadgeOk.classList.add('hidden');
                if (modalBadgeErr) modalBadgeErr.classList.remove('hidden');
                isModalUsernameAvailable = false;
            }
        })
        .catch(err => {
            console.error(err);
            if (modalSpinner) modalSpinner.classList.add('hidden');
        });
    }

    if (modalNameInput) {
        modalNameInput.addEventListener('input', (e) => {
            clearTimeout(modalCheckTimeout);
            const val = e.target.value;
            modalCheckTimeout = setTimeout(() => {
                checkModalUsername(val);
            }, 400);
        });

        // Initial check if there's an old value loaded
        if (modalNameInput.value.trim().length > 0) {
            checkModalUsername(modalNameInput.value);
        }
    }

    if (modalBtnNext) {
        modalBtnNext.addEventListener('click', () => {
            const val = modalNameInput.value.trim();
            
            if (!val) {
                modalNameInput.reportValidity();
                return;
            }

            if (isModalUsernameAvailable) {
                goToModalStep(2);
            } else {
                checkModalUsername(val);
                modalNameInput.focus();
            }
        });
    }

    if (modalBtnPrev) {
        modalBtnPrev.addEventListener('click', () => {
            goToModalStep(1);
        });
    }
});
</script>
