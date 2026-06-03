<!-- Professional Enterprise App Bar -->
<header class="w-full bg-white border-b border-slate-200 py-3 px-4 sm:px-6 lg:px-8 shadow-sm flex-shrink-0 dark:bg-slate-900 dark:border-slate-800">
    <div class="max-w-7xl mx-auto flex items-center justify-between gap-2 sm:gap-4">
        <!-- Brand Logo -->
        <a href="{{ route('home') }}" class="flex items-center gap-2 group flex-shrink-0">
            <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center shadow-md shadow-blue-500/10 group-hover:scale-105 transition-transform">
                <span class="material-symbols-outlined text-white text-base">forum</span>
            </div>
            <div>
                <h1 class="text-xs sm:text-sm font-extrabold tracking-tight text-slate-900 dark:text-slate-100 hidden sm:flex items-center gap-1">
                    XEN<span class="text-blue-600">PROFESSIONAL</span>
                </h1>
                <h1 class="text-sm font-extrabold tracking-tight text-slate-900 dark:text-slate-100 flex sm:hidden items-center gap-1">
                    XEN<span class="text-blue-600">PRO</span>
                </h1>
            </div>
        </a>

        <!-- Search, Notify, Create and User Profile Actions -->
        <div class="flex items-center gap-1.5 sm:gap-3">
            <!-- Search Button (HTML Material Style) -->
            <button onclick="openSearchModal()" class="flex items-center gap-1 px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg bg-slate-100 hover:bg-slate-200/80 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700/80 dark:text-slate-300 font-bold text-xs transition-all cursor-pointer">
                <span class="material-symbols-outlined text-[18px]">search</span>
                <span class="hidden sm:inline">Search</span>
            </button>

            <!-- Theme Toggle Button -->
            <button onclick="toggleDarkMode()" id="theme-toggle-btn" class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg border border-slate-200 dark:border-slate-800 text-slate-650 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all flex items-center justify-center cursor-pointer" title="Toggle Theme">
                <span class="material-symbols-outlined text-[18px]" id="theme-toggle-icon">dark_mode</span>
            </button>

             @auth
                <!-- Shimmering Wallet Currency Badge (Hidden on mobile) -->
                <a href="{{ route('wallet.index') }}" class="hidden sm:flex items-center gap-1 sm:gap-1.5 px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-700 dark:bg-amber-950/20 dark:hover:bg-amber-950/40 dark:text-amber-300 dark:border-amber-900/50 font-extrabold text-xs transition-all cursor-pointer border border-amber-200 shadow-sm" title="My Coins Wallet">
                    <span class="material-symbols-outlined text-[18px] text-amber-500 animate-pulse">monetization_on</span>
                    <span>{{ auth()->user()->coins }} <span class="hidden sm:inline">Coins</span></span>
                </a>

                <!-- Chat Quick Toggle -->
                <div class="relative flex items-center">
                    <button onclick="toggleChatDrawer()" class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all flex items-center justify-center cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">chat</span>
                    </button>
                    <!-- Global Chat Unread Badge -->
                    <span id="global-chat-badge" class="hidden absolute -top-1 -right-1 w-3.5 h-3.5 rounded-full bg-blue-600 text-white font-extrabold text-[7px] flex items-center justify-center border border-white">0</span>
                </div>

                <!-- Notifications -->
                <div class="relative flex items-center">
                    <button onclick="toggleDropdown('notify-dropdown')" class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all flex items-center justify-center cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">notifications</span>
                    </button>
                    <!-- Notifications Badge -->
                    <span id="global-notifications-badge" class="hidden absolute -top-1 -right-1 w-3.5 h-3.5 rounded-full bg-red-600 text-white font-extrabold text-[7px] flex items-center justify-center border border-white">0</span>
                    <!-- Notifications Dropdown -->
                    <div id="notify-dropdown" class="dropdown-menu absolute right-[-80px] sm:right-0 mt-2 w-[calc(100vw-2rem)] max-w-[320px] sm:w-80 mui-card overflow-hidden z-50 text-left bg-white border border-slate-200 shadow-xl rounded-2xl dark:bg-slate-900 dark:border-slate-800">
                        <div class="px-4 py-3 bg-slate-50 dark:bg-slate-950/50 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                            <span class="text-[9px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-widest">Notifications & Activity</span>
                            <button onclick="clearAllNotificationsLocal(event)" class="text-[8px] font-extrabold text-rose-600 uppercase tracking-wider hover:underline bg-transparent border-0 cursor-pointer">Clear All</button>
                        </div>
                        <div class="p-3 divide-y divide-slate-100 dark:divide-slate-800 max-h-64 overflow-y-auto custom-scrollbar" id="notifications-dropdown-list">
                            <!-- Direct Chat Alert items inserted dynamically, fallback default forums ones -->
                            <div class="space-y-1" id="forum-notifications-static">
                                <div class="p-4 text-center text-slate-400 text-[10px] font-bold dark:text-slate-500">
                                    No new notifications
                                </div>
                            </div>
                        </div>
                        <div class="p-2.5 bg-slate-50 dark:bg-slate-950/50 border-t border-slate-100 dark:border-slate-800 text-center">
                            <a href="#" onclick="openAllNotificationsPage(event)" class="text-[9px] font-extrabold text-blue-600 dark:text-blue-400 uppercase tracking-widest hover:underline block">View All Notifications</a>
                        </div>
                    </div>
                </div>

                <!-- Create Quick Thread (Hidden on mobile) -->
                <div class="relative hidden sm:block">
                    <button onclick="toggleDropdown('create-dropdown')" class="flex items-center gap-1 sm:gap-1.5 px-2 sm:px-4 py-1.5 sm:py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition-all shadow-md shadow-blue-500/10 cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                        <span class="hidden sm:inline">Create</span>
                    </button>
                    <!-- Create Dropdown Menu -->
                    <div id="create-dropdown" class="dropdown-menu absolute right-0 mt-2 w-48 mui-card py-1 z-50 text-left bg-white border border-slate-200 dark:bg-slate-900 dark:border-slate-800">
                        <a href="{{ route('categories.show', 'general-discussion') }}/create" class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors font-semibold">
                            <span class="material-symbols-outlined text-sm text-slate-500">edit</span> New Thread
                        </a>
                        <a href="{{ route('categories.show', 'images-and-gifs') }}/create" class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors font-semibold">
                            <span class="material-symbols-outlined text-sm text-slate-500">image</span> Share GIF / Image
                        </a>
                    </div>
                </div>

                <!-- User Profile Dropdown -->
                <div class="relative">
                    <button onclick="toggleDropdown('profile-dropdown')" class="flex items-center gap-2 p-0.5 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full overflow-hidden border border-blue-600/30">
                            <img src="{{ Auth::user()->avatar_url }}" class="w-full h-full object-cover" alt="avatar">
                        </div>
                    </button>
                    <!-- Profile Menu -->
                    <div id="profile-dropdown" class="dropdown-menu absolute right-0 mt-2 w-64 mui-card overflow-hidden py-1 z-50 text-left bg-white border border-slate-200 dark:bg-slate-900 dark:border-slate-800">
                        <div class="p-4 border-b border-slate-100 dark:border-slate-850 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full overflow-hidden border border-blue-500/30">
                                <img src="{{ Auth::user()->avatar_url }}" class="w-full h-full object-cover" alt="avatar">
                            </div>
                            <div class="truncate leading-tight">
                                <h4 class="font-bold text-slate-900 dark:text-slate-100 truncate">{{ Auth::user()->name }}</h4>
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="text-[9px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-widest">{{ Auth::user()->title_badge }}</span>
                                    <span class="text-[9px] font-extrabold text-amber-600 dark:text-amber-400 flex items-center gap-0.5"><span class="material-symbols-outlined text-[10px] text-amber-500 animate-pulse">monetization_on</span>{{ auth()->user()->coins }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="py-1">
                            <a href="{{ route('profile.show', Auth::user()->name) }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all font-semibold">
                                <span class="material-symbols-outlined text-sm text-slate-500">person</span> View Profile
                            </a>
                            <a href="{{ route('wallet.index') }}" class="flex items-center justify-between px-4 py-2.5 text-xs text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all font-semibold">
                                <span class="flex items-center gap-2.5">
                                    <span class="material-symbols-outlined text-sm text-slate-500">account_balance_wallet</span> My Wallet
                                </span>
                                <span class="text-[10px] text-amber-600 dark:text-amber-400 font-extrabold">{{ auth()->user()->coins }} Coins</span>
                            </a>
                            <button onclick="openSignatureModal()" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-left text-xs text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all cursor-pointer font-semibold font-sans">
                                <span class="material-symbols-outlined text-sm text-slate-500">settings</span> Profile Settings
                            </button>
                        </div>
                        <div class="border-t border-slate-100 dark:border-slate-850 py-1">
                            <form action="{{ route('logout') }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-left text-xs text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/20 transition-all cursor-pointer font-semibold">
                                    <span class="material-symbols-outlined text-sm">logout</span> Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <!-- Guest Options (HTML Material Style) -->
                <a href="{{ route('login') }}" class="flex items-center gap-1.5 px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs transition-all cursor-pointer">
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
<div class="w-full bg-white border-b border-slate-200 shadow-sm flex-shrink-0 relative z-30 dark:bg-slate-900 dark:border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex gap-5 sm:gap-8 overflow-x-auto hide-scrollbar whitespace-nowrap items-center w-full pb-0">
            <a href="{{ route('home') }}" class="group flex items-center py-3 border-b-2 border-transparent hover:border-blue-600 text-xs sm:text-[13px] font-semibold text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 transition-colors flex-shrink-0">
                <span class="material-symbols-outlined text-[18px] mr-1.5 text-slate-400 group-hover:text-blue-600 transition-colors">forum</span>
                Discussions
            </a>
            <a href="{{ route('media.index') }}" class="group flex items-center py-3 border-b-2 border-transparent hover:border-blue-600 text-xs sm:text-[13px] font-semibold text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 transition-colors flex-shrink-0">
                <span class="material-symbols-outlined text-[18px] mr-1.5 text-slate-400 group-hover:text-blue-600 transition-colors">image</span>
                Media Showroom
            </a>
            <a href="{{ route('members.index') }}" class="group flex items-center py-3 border-b-2 border-transparent hover:border-blue-600 text-xs sm:text-[13px] font-semibold text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 transition-colors flex-shrink-0">
                <span class="material-symbols-outlined text-[18px] mr-1.5 text-slate-400 group-hover:text-blue-600 transition-colors">groups</span>
                Members
            </a>
            <a href="{{ route('rankings.index') }}" class="group flex items-center py-3 border-b-2 border-transparent hover:border-blue-600 text-xs sm:text-[13px] font-semibold text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 transition-colors flex-shrink-0">
                <span class="material-symbols-outlined text-[18px] mr-1.5 text-slate-400 group-hover:text-blue-600 transition-colors">leaderboard</span>
                Leaderboard
            </a>
            <a href="{{ route('rules') }}" class="group flex items-center py-3 border-b-2 border-transparent hover:border-blue-600 text-xs sm:text-[13px] font-semibold text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 transition-colors flex-shrink-0">
                <span class="material-symbols-outlined text-[18px] mr-1.5 text-slate-400 group-hover:text-blue-600 transition-colors">gavel</span>
                Rules & Guides
            </a>
        </div>
    </div>
</div>
