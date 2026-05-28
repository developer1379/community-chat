<!-- Professional Enterprise App Bar -->
<header class="w-full bg-white border-b border-slate-200 py-3 px-4 sm:px-6 lg:px-8 shadow-sm flex-shrink-0">
    <div class="max-w-7xl mx-auto flex flex-wrap sm:flex-nowrap items-center justify-between gap-3">
        <!-- Brand Logo -->
        <a href="{{ route('home') }}" class="flex items-center gap-2 group flex-shrink-0">
            <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center shadow-md shadow-blue-500/10 group-hover:scale-102 transition-transform">
                <span class="material-symbols-outlined text-white text-base">forum</span>
            </div>
            <div>
                <h1 class="text-xs font-extrabold tracking-tight text-slate-900 flex items-center gap-1">
                    XEN<span class="text-blue-600">PROFESSIONAL</span>
                </h1>
            </div>
        </a>

        <!-- Search, Notify, Create and User Profile Actions -->
        <div class="flex items-center gap-2.5 sm:gap-4 flex-wrap sm:flex-nowrap">
            <!-- Search Button (HTML Material Style) -->
            <button onclick="openSearchModal()" class="flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200/80 text-slate-700 font-bold text-[10px] transition-all cursor-pointer">
                <span class="material-symbols-outlined text-xs">search</span>
                <span class="hidden xs:inline">Search</span>
            </button>

            @auth
                <!-- Chat Quick Toggle -->
                <div class="relative flex items-center">
                    <button onclick="toggleChatDrawer()" class="w-8.5 h-8.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all flex items-center justify-center cursor-pointer">
                        <span class="material-symbols-outlined text-sm">chat</span>
                    </button>
                    <!-- Global Chat Unread Badge -->
                    <span id="global-chat-badge" class="hidden absolute -top-1 -right-1 w-3.5 h-3.5 rounded-full bg-blue-600 text-white font-extrabold text-[7px] flex items-center justify-center border border-white">0</span>
                </div>

                <!-- Notifications -->
                <div class="relative flex items-center">
                    <button onclick="toggleDropdown('notify-dropdown')" class="w-8.5 h-8.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all flex items-center justify-center cursor-pointer">
                        <span class="material-symbols-outlined text-sm">notifications</span>
                    </button>
                    <!-- Notifications Badge -->
                    <span id="global-notifications-badge" class="absolute -top-1 -right-1 w-3.5 h-3.5 rounded-full bg-red-600 text-white font-extrabold text-[7px] flex items-center justify-center border border-white">3</span>
                    <!-- Notifications Dropdown -->
                    <div id="notify-dropdown" class="dropdown-menu absolute right-0 mt-2 w-80 mui-card overflow-hidden z-50 text-left bg-white border border-slate-200 shadow-xl rounded-2xl">
                        <div class="px-4 py-3 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                            <span class="text-[9px] font-bold text-blue-600 uppercase tracking-widest">Notifications & Activity</span>
                            <button onclick="clearAllNotificationsLocal(event)" class="text-[8px] font-extrabold text-rose-600 uppercase tracking-wider hover:underline bg-transparent border-0 cursor-pointer">Clear All</button>
                        </div>
                        <div class="p-3 divide-y divide-slate-100 max-h-64 overflow-y-auto custom-scrollbar" id="notifications-dropdown-list">
                            <!-- Direct Chat Alert items inserted dynamically, fallback default forums ones -->
                            <div class="space-y-1" id="forum-notifications-static">
                                <a href="#" class="block p-2 rounded-lg hover:bg-slate-50 transition-all text-xs">
                                    <p class="font-bold text-slate-800">Welcome to XenProfessional!</p>
                                    <p class="text-[10px] text-slate-500 mt-0.5">Customize your forum signature under quick settings.</p>
                                </a>
                                <a href="#" class="block p-2 rounded-lg hover:bg-slate-50 transition-all text-xs pt-2">
                                    <p class="font-bold text-slate-800">Admin Replied</p>
                                    <p class="text-[10px] text-slate-500 mt-0.5">Founder admin replied in General Discussion.</p>
                                </a>
                            </div>
                        </div>
                        <div class="p-2.5 bg-slate-50 border-t border-slate-100 text-center">
                            <a href="#" onclick="openAllNotificationsPage(event)" class="text-[9px] font-extrabold text-blue-600 uppercase tracking-widest hover:underline block">View All Notifications</a>
                        </div>
                    </div>
                </div>

                <!-- Create Quick Thread (HTML Material Style) -->
                <div class="relative">
                    <button onclick="toggleDropdown('create-dropdown')" class="flex items-center gap-1.5 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition-all shadow-md shadow-blue-500/10 cursor-pointer">
                        <span class="material-symbols-outlined text-sm">add</span>
                        Create
                    </button>
                    <!-- Create Dropdown Menu -->
                    <div id="create-dropdown" class="dropdown-menu absolute right-0 mt-2 w-48 mui-card py-1 z-50 text-left bg-white border border-slate-200">
                        <a href="{{ route('categories.show', 'general-discussion') }}/create" class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-slate-700 hover:bg-slate-50 transition-colors font-semibold">
                            <span class="material-symbols-outlined text-sm text-slate-500">edit</span> New Thread
                        </a>
                        <a href="{{ route('categories.show', 'images-and-gifs') }}/create" class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-slate-700 hover:bg-slate-50 transition-colors font-semibold">
                            <span class="material-symbols-outlined text-sm text-slate-500">image</span> Share GIF / Image
                        </a>
                    </div>
                </div>

                <!-- User Profile Dropdown -->
                <div class="relative">
                    <button onclick="toggleDropdown('profile-dropdown')" class="flex items-center gap-2 p-0.5 rounded-full hover:bg-slate-100 transition-colors cursor-pointer">
                        <div class="w-7 h-7 rounded-full overflow-hidden border border-blue-600/30">
                            @if(Auth::user()->avatar_path)
                                <img src="{{ str_starts_with(Auth::user()->avatar_path, 'http') ? Auth::user()->avatar_path : asset('storage/' . Auth::user()->avatar_path) }}" class="w-full h-full object-cover" alt="avatar">
                            @else
                                <div class="w-full h-full bg-blue-50 flex items-center justify-center text-[10px] font-bold text-blue-750">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                            @endif
                        </div>
                    </button>
                    <!-- Profile Menu -->
                    <div id="profile-dropdown" class="dropdown-menu absolute right-0 mt-2 w-64 mui-card overflow-hidden py-1 z-50 text-left bg-white border border-slate-200">
                        <div class="p-4 border-b border-slate-100 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full overflow-hidden border border-blue-500/30">
                                @if(Auth::user()->avatar_path)
                                    <img src="{{ str_starts_with(Auth::user()->avatar_path, 'http') ? Auth::user()->avatar_path : asset('storage/' . Auth::user()->avatar_path) }}" class="w-full h-full object-cover" alt="avatar">
                                @else
                                    <div class="w-full h-full bg-blue-50 flex items-center justify-center text-xs font-bold text-blue-700">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="truncate leading-tight">
                                <h4 class="font-bold text-slate-900 truncate">{{ Auth::user()->name }}</h4>
                                <span class="text-[9px] font-bold text-blue-600 uppercase tracking-widest">{{ Auth::user()->title_badge }}</span>
                            </div>
                        </div>
                        <div class="py-1">
                            <a href="{{ route('profile.show', Auth::user()->name) }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-slate-700 hover:bg-slate-50 transition-all font-semibold">
                                <span class="material-symbols-outlined text-sm text-slate-500">person</span> View Profile
                            </a>
                            <button onclick="openSignatureModal()" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-left text-xs text-slate-700 hover:bg-slate-50 transition-all cursor-pointer font-semibold font-sans">
                                <span class="material-symbols-outlined text-sm text-slate-500">settings</span> Profile Settings
                            </button>
                        </div>
                        <div class="border-t border-slate-100 py-1">
                            <form action="{{ route('logout') }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-left text-xs text-rose-600 hover:bg-rose-50 transition-all cursor-pointer font-semibold">
                                    <span class="material-symbols-outlined text-sm">logout</span> Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <!-- Guest Options (HTML Material Style) -->
                <a href="{{ route('login') }}" class="flex items-center gap-1.5 px-4 py-2 rounded-lg border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold text-xs transition-all cursor-pointer">
                    Sign In
                </a>
                <a href="{{ route('register') }}" class="flex items-center gap-1.5 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition-all shadow-md shadow-blue-500/10 cursor-pointer">
                    Register
                </a>
            @endauth
        </div>
    </div>
</header>

<!-- Submenu Navigation -->
<div class="w-full bg-[#f8fafc] border-b border-slate-200 py-2.5 shadow-sm overflow-x-auto custom-scrollbar flex-shrink-0">
    <div class="max-w-7xl mx-auto px-4 flex gap-6 text-[10px] sm:text-xs font-semibold text-slate-600 min-w-max">
        <a href="{{ route('home') }}" class="hover:text-blue-600 flex items-center gap-1.5 transition-colors">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Discussions Home
        </a>
        <a href="{{ route('categories.show', 'images-and-gifs') }}" class="hover:text-blue-600 flex items-center gap-1.5 transition-colors">
            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> GIFs & Images Showroom
        </a>
        <a href="{{ route('members.index') }}" class="hover:text-blue-600 flex items-center gap-1.5 transition-colors">
            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> Members Directory
        </a>
        <a href="{{ route('rankings.index') }}" class="hover:text-blue-600 flex items-center gap-1.5 transition-colors">
            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Leaderboard & Rankings
        </a>
        <a href="{{ route('rules') }}" class="hover:text-blue-600 flex items-center gap-1.5 transition-colors">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Rules & Guides
        </a>
    </div>
</div>
