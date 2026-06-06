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
                <input type="hidden" id="edit-post-content-input" name="content">
                <div class="rounded-xl border border-slate-200 overflow-hidden bg-slate-50">
                    <div id="edit-post-quill-editor" style="height: 200px; font-size: 13px;"></div>
                </div>
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
