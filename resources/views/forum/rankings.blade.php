@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Premium Material Welcome Banner with Glowing vector illustration -->
    <div class="relative rounded-[28px] overflow-hidden border border-slate-200 dark:border-slate-800 bg-slate-900 dark:bg-slate-950 p-8 sm:p-10 text-white shadow-xl">
        <!-- Background absolute decorative shapes -->
        <div class="absolute right-0 top-0 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute left-1/4 bottom-0 w-64 h-64 bg-purple-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="space-y-4 text-left max-w-2xl">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-500/20 text-blue-300 border border-blue-500/30 uppercase tracking-widest leading-none">
                    Leaderboard & Ranks
                </span>
                <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-white font-sans">
                    XenProfessional Rankings
                </h1>
                <p class="text-base text-slate-300 font-medium leading-relaxed">
                    Level up your professional profile by starting discussions, posting replies, and earning coins. Filter the ranking charts by special roles like creators, analysts, and moderators.
                </p>
            </div>
            
            <!-- Open Source Material Vector Graphic/Image -->
            <div class="hidden md:block flex-shrink-0">
                <svg class="w-48 h-48 drop-shadow-[0_8px_24px_rgba(59,130,246,0.3)]" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Star elements -->
                    <path d="M100 20L104 32H116L106 40L110 52L100 44L90 52L94 40L84 32H96L100 20Z" fill="#FBBF24" opacity="0.8"/>
                    <path d="M40 60L42 66H48L43 70L45 76L40 72L35 76L37 70L32 66H38L40 60Z" fill="#FBBF24" opacity="0.6"/>
                    <path d="M160 70L162 76H168L163 80L165 86L160 82L155 86L157 80L152 76H158L160 70Z" fill="#FBBF24" opacity="0.6"/>
                    <!-- Trophy Cup -->
                    <path d="M70 70H130V105C130 121.57 116.57 135 100 135C83.43 135 70 121.57 70 105V70Z" fill="url(#bannerTrophyGold)" />
                    <!-- Trophy Handles -->
                    <path d="M70 80H58C52.48 80 48 84.48 48 90V96C48 101.52 52.48 106 58 106H70V80Z" fill="url(#bannerTrophyAccent)" />
                    <path d="M130 80H142C147.52 80 152 84.48 152 90V96C152 101.52 147.52 106 142 106H130V80Z" fill="url(#bannerTrophyAccent)" />
                    <!-- Stem and Base -->
                    <path d="M100 135V165H85V175H115V165H100V135Z" fill="url(#bannerTrophyAccent)" />
                    <rect x="70" y="175" width="60" height="12" rx="4" fill="#1E293B" />
                    <rect x="75" y="178" width="50" height="6" rx="2" fill="#475569" />
                    <!-- Glowing highlights -->
                    <circle cx="100" cy="100" r="45" stroke="#FFFFFF" stroke-opacity="0.15" stroke-width="2" />
                    <defs>
                        <linearGradient id="bannerTrophyGold" x1="70" y1="70" x2="130" y2="135" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#FDE047" />
                            <stop offset="40%" stop-color="#EAB308" />
                            <stop offset="100%" stop-color="#CA8A04" />
                        </linearGradient>
                        <linearGradient id="bannerTrophyAccent" x1="48" y1="80" x2="152" y2="175" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#FEF08A" />
                            <stop offset="50%" stop-color="#EAB308" />
                            <stop offset="100%" stop-color="#854D0E" />
                        </linearGradient>
                    </defs>
                </svg>
            </div>
        </div>
    </div>

    <!-- Segmented Navigation Control (MD3 Standard with custom SVG Images) -->
    <div class="flex border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-1.5 rounded-2xl shadow-sm overflow-x-auto hide-scrollbar justify-start items-center gap-2">
        <div class="flex items-center gap-1.5 flex-nowrap w-full">
            <a href="{{ route('rankings.index', ['tab' => 'all']) }}" class="px-5 py-3 text-sm font-semibold transition-all duration-200 rounded-xl flex items-center gap-2.5 flex-shrink-0 {{ $currentTab === 'all' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2Z" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M12 15C14.2091 15 16 13.2091 16 11C16 8.79086 14.2091 7 12 7C9.79086 7 8 8.79086 8 11C8 13.2091 9.79086 15 12 15Z" fill="currentColor" fill-opacity="0.3" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M6 19.5C6.88383 17.9355 8.57218 17 10.5 17H13.5C15.4278 17 17.1162 17.9355 18 19.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                All Community
            </a>
            <a href="{{ route('rankings.index', ['tab' => 'creatives']) }}" class="px-5 py-3 text-sm font-semibold transition-all duration-200 rounded-xl flex items-center gap-2.5 flex-shrink-0 {{ $currentTab === 'creatives' ? 'bg-pink-600 text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 14.7255 3.09032 17.1962 4.85857 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    <circle cx="7.5" cy="10.5" r="1.5" fill="currentColor"/>
                    <circle cx="11.5" cy="7.5" r="1.5" fill="currentColor"/>
                    <circle cx="16.5" cy="9.5" r="1.5" fill="currentColor"/>
                    <circle cx="15.5" cy="14.5" r="1.5" fill="currentColor"/>
                    <path d="M6 17C7.5 15.5 9.5 16 10.5 17.5C11.5 19 13.5 19.5 15 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                Creatives & Artists
            </a>
            <a href="{{ route('rankings.index', ['tab' => 'critics']) }}" class="px-5 py-3 text-sm font-semibold transition-all duration-200 rounded-xl flex items-center gap-2.5 flex-shrink-0 {{ $currentTab === 'critics' ? 'bg-purple-600 text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 22H15C19 22 21 20 21 16V8C21 4 19 2 15 2H9C5 2 3 4 3 8V16C3 20 5 22 9 22Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M8 7H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M8 12H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M8 17H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                Critics & Analysts
            </a>
            <a href="{{ route('rankings.index', ['tab' => 'guild']) }}" class="px-5 py-3 text-sm font-semibold transition-all duration-200 rounded-xl flex items-center gap-2.5 flex-shrink-0 {{ $currentTab === 'guild' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L3 6V12C3 17.52 7.02 20.74 12 22C16.98 20.74 21 17.52 21 12V6L12 2Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                    <path d="M12 7V17M9 10H15M8 14H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                Guild & Admins
            </a>
        </div>
    </div>

    @if(count($users) >= 3)
        <!-- Beautiful MD3 Container Card for podium -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end justify-center py-8 px-6 bg-white dark:bg-slate-900 rounded-[28px] border border-slate-200 dark:border-slate-800 shadow-sm">
            <!-- 2nd Place (Silver) Podium -->
            @if(isset($users[1]))
                @php $secondUser = $users[1]; @endphp
                <div class="flex flex-col items-center group">
                    <a href="{{ route('profile.show', $secondUser->name) }}" class="relative block shrink-0" data-user-hover="true" data-user-name="{{ $secondUser->name }}" data-user-badge="{{ $secondUser->title_badge }}" data-user-joined="{{ $secondUser->created_at->format('M d, Y') }}" data-user-threads="{{ $secondUser->threads()->count() }}" data-user-posts="{{ $secondUser->posts()->count() }}" data-user-uploads="{{ $secondUser->attachments()->count() }}" data-user-avatar="{{ $secondUser->avatar_url }}" data-user-banner="{{ $secondUser->banner_color }}" data-user-banner-path="{{ $secondUser->banner_path }}">
                        <div class="absolute inset-0 rounded-full bg-slate-400 opacity-20 blur-md animate-pulse"></div>
                        <div class="w-20 h-20 rounded-full overflow-hidden border-4 border-slate-300 shadow-md relative z-10 transition-transform group-hover:scale-105">
                            <img src="{{ $secondUser->avatar_url }}" class="w-full h-full object-cover" alt="avatar">
                        </div>
                    </a>
                    <h3 class="font-extrabold text-slate-850 dark:text-slate-200 text-sm mt-4 truncate max-w-[150px]">{{ $secondUser->name }}</h3>
                    <span class="text-[9px] font-black uppercase tracking-wider text-slate-500 bg-slate-100 dark:bg-slate-800 dark:text-slate-400 px-2.5 py-1 rounded-full border border-slate-200 dark:border-slate-700 mt-1">{{ $secondUser->title_badge }}</span>
                    <!-- Silver Podium Base -->
                    <div class="w-48 bg-gradient-to-t from-slate-100 to-slate-200/50 dark:from-slate-800 dark:to-slate-900 border-t border-slate-300 dark:border-slate-700 h-24 mt-4 rounded-t-2xl shadow-inner flex flex-col items-center justify-center gap-1.5">
                        <svg class="w-10 h-10 drop-shadow-[0_2px_4px_rgba(148,163,184,0.3)]" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="32" cy="32" r="26" fill="url(#silverMetallic)" stroke="#94a3b8" stroke-width="2"/>
                            <circle cx="32" cy="32" r="20" fill="url(#silverMetallicAccent)"/>
                            <path d="M26 44L32 20L38 44L26 44Z" fill="#ffffff" fill-opacity="0.3"/>
                            <text x="32" y="38" font-size="18" font-weight="900" fill="#475569" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif">2</text>
                            <defs>
                                <linearGradient id="silverMetallic" x1="6" y1="6" x2="58" y2="58" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#f1f5f9" />
                                    <stop offset="50%" stop-color="#cbd5e1" />
                                    <stop offset="100%" stop-color="#64748b" />
                                </linearGradient>
                                <linearGradient id="silverMetallicAccent" x1="12" y1="12" x2="52" y2="52" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#ffffff" />
                                    <stop offset="50%" stop-color="#e2e8f0" />
                                    <stop offset="100%" stop-color="#94a3b8" />
                                </linearGradient>
                            </defs>
                        </svg>
                        <span class="text-[10px] font-black text-slate-500 dark:text-slate-400">{{ number_format($secondUser->calculated_points) }} pts</span>
                    </div>
                </div>
            @endif

            <!-- 1st Place (Gold) Podium -->
            @if(isset($users[0]))
                @php $firstUser = $users[0]; @endphp
                <div class="flex flex-col items-center group -mt-6 z-20">
                    <a href="{{ route('profile.show', $firstUser->name) }}" class="relative block shrink-0" data-user-hover="true" data-user-name="{{ $firstUser->name }}" data-user-badge="{{ $firstUser->title_badge }}" data-user-joined="{{ $firstUser->created_at->format('M d, Y') }}" data-user-threads="{{ $firstUser->threads()->count() }}" data-user-posts="{{ $firstUser->posts()->count() }}" data-user-uploads="{{ $firstUser->attachments()->count() }}" data-user-avatar="{{ $firstUser->avatar_url }}" data-user-banner="{{ $firstUser->banner_color }}" data-user-banner-path="{{ $firstUser->banner_path }}">
                        <div class="absolute inset-0 rounded-full bg-amber-500 opacity-30 blur-lg animate-pulse"></div>
                        <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-amber-400 shadow-xl relative z-10 transition-transform group-hover:scale-105">
                            <img src="{{ $firstUser->avatar_url }}" class="w-full h-full object-cover" alt="avatar">
                        </div>
                    </a>
                    <h3 class="font-extrabold text-slate-900 dark:text-slate-100 text-base mt-4 truncate max-w-[150px]">{{ $firstUser->name }}</h3>
                    <span class="text-[9px] font-black uppercase tracking-wider text-amber-600 bg-amber-50 dark:bg-amber-950/40 dark:text-amber-400 px-2.5 py-1 rounded-full border border-amber-200 dark:border-amber-900/30 mt-1">{{ $firstUser->title_badge }}</span>
                    <!-- Gold Podium Base -->
                    <div class="w-52 bg-gradient-to-t from-amber-50 to-amber-100/50 dark:from-slate-800 dark:to-slate-900 border-t border-amber-300 dark:border-amber-700 h-32 mt-4 rounded-t-3xl shadow-inner flex flex-col items-center justify-center gap-1.5">
                        <svg class="w-14 h-14 drop-shadow-[0_4px_10px_rgba(245,158,11,0.4)] animate-bounce" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16 18H12C9.79 18 8 19.79 8 22V26C8 30.42 11.58 34 16 34H18V18H16Z" fill="url(#goldMetallic)"/>
                            <path d="M48 18H46V34H48C52.42 34 56 30.42 56 26V22C56 19.79 54.21 18 52 18H48Z" fill="url(#goldMetallic)"/>
                            <path d="M44 10H20V34C20 40.63 25.37 46 32 46C38.63 46 44 40.63 44 34V10Z" fill="url(#goldMetallicAccent)"/>
                            <path d="M32 46V54H22V58H42V54H32V46Z" fill="url(#goldMetallic)"/>
                            <circle cx="32" cy="24" r="8" fill="#ffffff" fill-opacity="0.3"/>
                            <path d="M32 18L33.8 21.6L37.8 22.2L34.9 25L35.6 29L32 27.1L28.4 29L29.1 25L26.2 22.2L30.2 21.6L32 18Z" fill="#fff"/>
                            <defs>
                                <linearGradient id="goldMetallic" x1="8" y1="10" x2="56" y2="58" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#fef08a" />
                                    <stop offset="35%" stop-color="#f59e0b" />
                                    <stop offset="70%" stop-color="#b45309" />
                                    <stop offset="100%" stop-color="#78350f" />
                                </linearGradient>
                                <linearGradient id="goldMetallicAccent" x1="20" y1="10" x2="44" y2="46" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#fef08a" />
                                    <stop offset="30%" stop-color="#fbbf24" />
                                    <stop offset="70%" stop-color="#d97706" />
                                    <stop offset="100%" stop-color="#92400e" />
                                </linearGradient>
                            </defs>
                        </svg>
                        <span class="text-xs font-black text-amber-700 dark:text-amber-500">{{ number_format($firstUser->calculated_points) }} pts</span>
                    </div>
                </div>
            @endif

            <!-- 3rd Place (Bronze) Podium -->
            @if(isset($users[2]))
                @php $thirdUser = $users[2]; @endphp
                <div class="flex flex-col items-center group">
                    <a href="{{ route('profile.show', $thirdUser->name) }}" class="relative block shrink-0" data-user-hover="true" data-user-name="{{ $thirdUser->name }}" data-user-badge="{{ $thirdUser->title_badge }}" data-user-joined="{{ $thirdUser->created_at->format('M d, Y') }}" data-user-threads="{{ $thirdUser->threads()->count() }}" data-user-posts="{{ $thirdUser->posts()->count() }}" data-user-uploads="{{ $thirdUser->attachments()->count() }}" data-user-avatar="{{ $thirdUser->avatar_url }}" data-user-banner="{{ $thirdUser->banner_color }}" data-user-banner-path="{{ $thirdUser->banner_path }}">
                        <div class="absolute inset-0 rounded-full bg-amber-700 opacity-20 blur-md animate-pulse"></div>
                        <div class="w-20 h-20 rounded-full overflow-hidden border-4 border-amber-600 shadow-md relative z-10 transition-transform group-hover:scale-105">
                            <img src="{{ $thirdUser->avatar_url }}" class="w-full h-full object-cover" alt="avatar">
                        </div>
                    </a>
                    <h3 class="font-extrabold text-slate-800 dark:text-slate-200 text-sm mt-4 truncate max-w-[150px]">{{ $thirdUser->name }}</h3>
                    <span class="text-[9px] font-black uppercase tracking-wider text-amber-700 bg-amber-50 dark:bg-amber-950/40 dark:text-amber-400 px-2.5 py-1 rounded-full border border-amber-250 dark:border-amber-900/30 mt-1">{{ $thirdUser->title_badge }}</span>
                    <!-- Bronze Podium Base -->
                    <div class="w-48 bg-gradient-to-t from-amber-50 to-amber-100/30 dark:from-slate-800 dark:to-slate-900 border-t border-amber-200 dark:border-amber-700 h-20 mt-4 rounded-t-2xl shadow-inner flex flex-col items-center justify-center gap-1.5">
                        <svg class="w-10 h-10 drop-shadow-[0_2px_4px_rgba(180,83,9,0.3)]" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="32" cy="32" r="26" fill="url(#bronzeMetallic)" stroke="#b45309" stroke-width="2"/>
                            <circle cx="32" cy="32" r="20" fill="url(#bronzeMetallicAccent)"/>
                            <text x="32" y="38" font-size="18" font-weight="900" fill="#78350f" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif">3</text>
                            <defs>
                                <linearGradient id="bronzeMetallic" x1="6" y1="6" x2="58" y2="58" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#ffedd5" />
                                    <stop offset="50%" stop-color="#d97706" />
                                    <stop offset="100%" stop-color="#78350f" />
                                </linearGradient>
                                <linearGradient id="bronzeMetallicAccent" x1="12" y1="12" x2="52" y2="52" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#ffedd5" />
                                    <stop offset="50%" stop-color="#f59e0b" />
                                    <stop offset="100%" stop-color="#b45309" />
                                </linearGradient>
                            </defs>
                        </svg>
                        <span class="text-[10px] font-black text-amber-700 dark:text-amber-500">{{ number_format($thirdUser->calculated_points) }} pts</span>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Desktop List Layout (MD3 List Items in a neat Card surface) -->
    <div class="hidden md:block space-y-3">
        @forelse($users as $user)
            <div class="border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-4 rounded-2xl flex items-center justify-between gap-4 shadow-sm hover:shadow-md hover:border-blue-300 dark:hover:border-blue-800 transition-all duration-200">
                <!-- Left Section: Rank + Member info -->
                <div class="flex items-center gap-4 min-w-0">
                    <!-- Rank Position Medal/Badge (Vector images, not icon font) -->
                    <div class="w-12 flex-shrink-0 text-center font-extrabold text-slate-800 dark:text-slate-200">
                        @if($loop->iteration === 1)
                            <span class="inline-flex w-10 h-10 items-center justify-center cursor-help" title="1st Place Gold Champion">
                                <svg class="w-9 h-9 drop-shadow-[0_2px_4px_rgba(245,158,11,0.3)]" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16 18H12C9.79 18 8 19.79 8 22V26C8 30.42 11.58 34 16 34H18V18H16Z" fill="url(#goldRowMetallic)"/>
                                    <path d="M48 18H46V34H48C52.42 34 56 30.42 56 26V22C56 19.79 54.21 18 52 18H48Z" fill="url(#goldRowMetallic)"/>
                                    <path d="M44 10H20V34C20 40.63 25.37 46 32 46C38.63 46 44 40.63 44 34V10Z" fill="url(#goldRowMetallicAccent)"/>
                                    <path d="M32 46V54H22V58H42V54H32V46Z" fill="url(#goldRowMetallic)"/>
                                    <circle cx="32" cy="24" r="8" fill="#ffffff" fill-opacity="0.3"/>
                                    <path d="M32 18L33.8 21.6L37.8 22.2L34.9 25L35.6 29L32 27.1L28.4 29L29.1 25L26.2 22.2L30.2 21.6L32 18Z" fill="#fff"/>
                                    <defs>
                                        <linearGradient id="goldRowMetallic" x1="8" y1="10" x2="56" y2="58" gradientUnits="userSpaceOnUse">
                                            <stop offset="0%" stop-color="#fef08a" />
                                            <stop offset="35%" stop-color="#f59e0b" />
                                            <stop offset="70%" stop-color="#b45309" />
                                            <stop offset="100%" stop-color="#78350f" />
                                        </linearGradient>
                                        <linearGradient id="goldRowMetallicAccent" x1="20" y1="10" x2="44" y2="46" gradientUnits="userSpaceOnUse">
                                            <stop offset="0%" stop-color="#fef08a" />
                                            <stop offset="30%" stop-color="#fbbf24" />
                                            <stop offset="70%" stop-color="#d97706" />
                                            <stop offset="100%" stop-color="#92400e" />
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </span>
                        @elseif($loop->iteration === 2)
                            <span class="inline-flex w-10 h-10 items-center justify-center cursor-help" title="2nd Place Silver Finalist">
                                <svg class="w-8 h-8 drop-shadow-[0_2px_4px_rgba(148,163,184,0.25)]" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="32" cy="32" r="26" fill="url(#silverRowMetallic)" stroke="#94a3b8" stroke-width="2"/>
                                    <circle cx="32" cy="32" r="20" fill="url(#silverRowMetallicAccent)"/>
                                    <path d="M26 44L32 20L38 44L26 44Z" fill="#ffffff" fill-opacity="0.3"/>
                                    <text x="32" y="38" font-size="18" font-weight="900" fill="#475569" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif">2</text>
                                    <defs>
                                        <linearGradient id="silverRowMetallic" x1="6" y1="6" x2="58" y2="58" gradientUnits="userSpaceOnUse">
                                            <stop offset="0%" stop-color="#f1f5f9" />
                                            <stop offset="50%" stop-color="#cbd5e1" />
                                            <stop offset="100%" stop-color="#64748b" />
                                        </linearGradient>
                                        <linearGradient id="silverRowMetallicAccent" x1="12" y1="12" x2="52" y2="52" gradientUnits="userSpaceOnUse">
                                            <stop offset="0%" stop-color="#ffffff" />
                                            <stop offset="50%" stop-color="#e2e8f0" />
                                            <stop offset="100%" stop-color="#94a3b8" />
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </span>
                        @elseif($loop->iteration === 3)
                            <span class="inline-flex w-10 h-10 items-center justify-center cursor-help" title="3rd Place Bronze Medalist">
                                <svg class="w-8 h-8 drop-shadow-[0_2px_4px_rgba(180,83,9,0.25)]" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="32" cy="32" r="26" fill="url(#bronzeRowMetallic)" stroke="#b45309" stroke-width="2"/>
                                    <circle cx="32" cy="32" r="20" fill="url(#bronzeRowMetallicAccent)"/>
                                    <text x="32" y="38" font-size="18" font-weight="900" fill="#78350f" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif">3</text>
                                    <defs>
                                        <linearGradient id="bronzeRowMetallic" x1="6" y1="6" x2="58" y2="58" gradientUnits="userSpaceOnUse">
                                            <stop offset="0%" stop-color="#ffedd5" />
                                            <stop offset="50%" stop-color="#d97706" />
                                            <stop offset="100%" stop-color="#78350f" />
                                        </linearGradient>
                                        <linearGradient id="bronzeRowMetallicAccent" x1="12" y1="12" x2="52" y2="52" gradientUnits="userSpaceOnUse">
                                            <stop offset="0%" stop-color="#ffedd5" />
                                            <stop offset="50%" stop-color="#f59e0b" />
                                            <stop offset="100%" stop-color="#b45309" />
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </span>
                        @else
                            <span class="text-xs text-slate-600 dark:text-slate-350 font-black bg-slate-100 dark:bg-slate-800 w-8 h-8 inline-flex items-center justify-center rounded-full border border-slate-200 dark:border-slate-700 shadow-inner">#{{ $loop->iteration }}</span>
                        @endif
                    </div>

                    <!-- Member Avatar -->
                    <a href="{{ route('profile.show', $user->name) }}"
                       data-user-hover="true"
                       data-user-name="{{ $user->name }}"
                       data-user-badge="{{ $user->title_badge }}"
                       data-user-joined="{{ $user->created_at->format('M d, Y') }}"
                       data-user-threads="{{ $user->threads()->count() }}"
                       data-user-posts="{{ $user->posts()->count() }}"
                       data-user-uploads="{{ $user->attachments()->count() }}"
                       data-user-avatar="{{ $user->avatar_url }}"
                       data-user-banner="{{ $user->banner_color }}"
                       data-user-banner-path="{{ $user->banner_path }}"
                       class="w-11 h-11 rounded-full overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm flex-shrink-0 block hover:ring-2 hover:ring-blue-500 transition-all bg-slate-100">
                        <img src="{{ $user->avatar_url }}" class="w-full h-full object-cover" alt="avatar">
                    </a>

                    <!-- Username -->
                    <div class="min-w-0 leading-tight text-left">
                        <a href="{{ route('profile.show', $user->name) }}"
                           data-user-hover="true"
                           data-user-name="{{ $user->name }}"
                           data-user-badge="{{ $user->title_badge }}"
                           data-user-joined="{{ $user->created_at->format('M d, Y') }}"
                           data-user-threads="{{ $user->threads()->count() }}"
                           data-user-posts="{{ $user->posts()->count() }}"
                           data-user-uploads="{{ $user->attachments()->count() }}"
                           data-user-avatar="{{ $user->avatar_url }}"
                           data-user-banner="{{ $user->banner_color }}"
                           data-user-banner-path="{{ $user->banner_path }}"
                           class="font-bold text-slate-900 dark:text-white hover:text-blue-600 text-sm truncate block transition-colors">{{ $user->name }}</a>
                        <p class="text-[11px] text-slate-500 font-medium mt-1">Joined {{ $user->created_at->format('M Y') }}</p>
                    </div>
                </div>

                <!-- Middle Section: Ranks and specialties -->
                <div class="flex items-center gap-3 flex-shrink-0">
                    <!-- Otaku Tier -->
                    @php
                        $tier = $user->anime_tier;
                        $tierName = $tier['name'];
                        $tierColor = $tier['color'];
                    @endphp
                    <span class="inline-flex items-center gap-2 px-3.5 py-1.5 text-[11px] font-bold uppercase shadow-sm text-white rounded-full" style="background-color: {{ $tierColor }}">
                        <span>Level {{ $tier['level'] ?? 1 }}</span>
                        <span class="opacity-60">•</span>
                        <span>{{ $tierName }}</span>
                    </span>

                    <!-- Specialty badge -->
                    <span class="inline-flex px-3 py-1.5 text-[11px] font-bold uppercase bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 rounded-lg shadow-sm" style="border-left: 3px solid {{ $user->banner_color ?? '#3b82f6' }}">
                        {{ $user->title_badge ?? 'Otaku Member' }}
                    </span>
                </div>

                <!-- Right Section: Stats counters + Points -->
                <div class="flex items-center gap-6 flex-shrink-0">
                    <!-- Stats (Custom vector SVG illustrations instead of emojis) -->
                    <div class="flex items-center gap-2.5 text-xs font-bold text-slate-500">
                        <span class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-2.5 py-1.5 rounded-lg shadow-sm flex items-center gap-1" title="Threads started">
                            <svg class="w-3.5 h-3.5 text-blue-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 19.5C4 18.837 4.26339 18.2011 4.73223 17.7322C5.20107 17.2634 5.83696 17 6.5 17H20V3H6.5C5.83696 3 5.20107 3.26339 4.73223 3.73223C4.26339 4.20107 4 4.83696 4 5.5V19.5ZM4 19.5C4 20.163 4.26339 20.7989 4.73223 21.2678C5.20107 21.7366 5.83696 22 6.5 22H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            {{ $user->threads()->count() }}
                        </span>
                        <span class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-2.5 py-1.5 rounded-lg shadow-sm flex items-center gap-1" title="Replies posted">
                            <svg class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21 15C21 15.5304 20.7893 16.0391 20.4142 16.4142C20.0391 16.7893 19.5304 17 19 17H7L3 21V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H19C19.5304 3 20.0391 3.21071 20.4142 3.58579C20.7893 3.96086 21 4.46957 21 5V15Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            {{ $user->posts()->count() }}
                        </span>
                    </div>

                    <!-- Points pill -->
                    <span class="inline-block font-extrabold text-blue-700 dark:text-blue-400 text-xs px-3.5 py-1.5 rounded-xl bg-blue-50 dark:bg-blue-950/20 border border-blue-150 dark:border-blue-900/30 shadow-sm w-24 text-center hover:scale-105 transition-transform duration-200">
                        {{ number_format($user->calculated_points) }} pts
                    </span>
                </div>
            </div>
        @empty
            <!-- Open source style empty state vector graphic/image -->
            <div class="border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-12 text-center rounded-2xl shadow-sm">
                <svg class="w-32 h-32 mx-auto mb-4 opacity-75" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="60" cy="60" r="50" fill="url(#emptyStateGrad)" stroke="#E2E8F0" stroke-width="2" stroke-dasharray="4 4" />
                    <path d="M50 50C55.5228 50 60 45.5228 60 40C60 34.4772 55.5228 30 50 30C44.4772 30 40 34.4772 40 40C40 45.5228 44.4772 50 50 50Z" stroke="#94A3B8" stroke-width="3" />
                    <path d="M57 47L72 62" stroke="#94A3B8" stroke-width="3" stroke-linecap="round" />
                    <defs>
                        <linearGradient id="emptyStateGrad" x1="10" y1="10" x2="110" y2="110" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#F8FAFC" />
                            <stop offset="100%" stop-color="#F1F5F9" />
                        </linearGradient>
                    </defs>
                </svg>
                <p class="text-sm text-slate-500 font-medium">No community members matched this rankings filter.</p>
            </div>
        @endforelse
    </div>

    <!-- Mobile Card Layout (MD3 Card List) -->
    <div class="block md:hidden space-y-4">
        @forelse($users as $user)
            <div class="border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-4 rounded-2xl shadow-sm hover:shadow transition-all duration-200 flex flex-col gap-4">
                <!-- Top Row: Rank + User profile info -->
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <!-- Rank Medal -->
                        <div class="w-8 flex-shrink-0 text-center font-extrabold text-slate-850 dark:text-slate-200">
                            @if($loop->iteration === 1)
                                <span class="inline-flex w-8 h-8 items-center justify-center">
                                    <svg class="w-7 h-7 drop-shadow-[0_2px_4px_rgba(245,158,11,0.3)]" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M16 18H12C9.79 18 8 19.79 8 22V26C8 30.42 11.58 34 16 34H18V18H16Z" fill="url(#goldMobMetallic)"/>
                                        <path d="M48 18H46V34H48C52.42 34 56 30.42 56 26V22C56 19.79 54.21 18 52 18H48Z" fill="url(#goldMobMetallic)"/>
                                        <path d="M44 10H20V34C20 40.63 25.37 46 32 46C38.63 46 44 40.63 44 34V10Z" fill="url(#goldMobMetallicAccent)"/>
                                        <path d="M32 46V54H22V58H42V54H32V46Z" fill="url(#goldMobMetallic)"/>
                                        <circle cx="32" cy="24" r="8" fill="#ffffff" fill-opacity="0.3"/>
                                        <path d="M32 18L33.8 21.6L37.8 22.2L34.9 25L35.6 29L32 27.1L28.4 29L29.1 25L26.2 22.2L30.2 21.6L32 18Z" fill="#fff"/>
                                        <defs>
                                            <linearGradient id="goldMobMetallic" x1="8" y1="10" x2="56" y2="58" gradientUnits="userSpaceOnUse">
                                                <stop offset="0%" stop-color="#fef08a" />
                                                <stop offset="35%" stop-color="#f59e0b" />
                                                <stop offset="70%" stop-color="#b45309" />
                                                <stop offset="100%" stop-color="#78350f" />
                                            </linearGradient>
                                            <linearGradient id="goldMobMetallicAccent" x1="20" y1="10" x2="44" y2="46" gradientUnits="userSpaceOnUse">
                                                <stop offset="0%" stop-color="#fef08a" />
                                                <stop offset="30%" stop-color="#fbbf24" />
                                                <stop offset="70%" stop-color="#d97706" />
                                                <stop offset="100%" stop-color="#92400e" />
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                </span>
                            @elseif($loop->iteration === 2)
                                <span class="inline-flex w-8 h-8 items-center justify-center">
                                    <svg class="w-7 h-7 drop-shadow-[0_2px_4px_rgba(148,163,184,0.25)]" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="32" cy="32" r="26" fill="url(#silverMobMetallic)" stroke="#94a3b8" stroke-width="2"/>
                                        <circle cx="32" cy="32" r="20" fill="url(#silverMobMetallicAccent)"/>
                                        <path d="M26 44L32 20L38 44L26 44Z" fill="#ffffff" fill-opacity="0.3"/>
                                        <text x="32" y="38" font-size="18" font-weight="900" fill="#475569" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif">2</text>
                                        <defs>
                                            <linearGradient id="silverMobMetallic" x1="6" y1="6" x2="58" y2="58" gradientUnits="userSpaceOnUse">
                                                <stop offset="0%" stop-color="#f1f5f9" />
                                                <stop offset="50%" stop-color="#cbd5e1" />
                                                <stop offset="100%" stop-color="#64748b" />
                                            </linearGradient>
                                            <linearGradient id="silverMobMetallicAccent" x1="12" y1="12" x2="52" y2="52" gradientUnits="userSpaceOnUse">
                                                <stop offset="0%" stop-color="#ffffff" />
                                                <stop offset="50%" stop-color="#e2e8f0" />
                                                <stop offset="100%" stop-color="#94a3b8" />
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                </span>
                            @elseif($loop->iteration === 3)
                                <span class="inline-flex w-8 h-8 items-center justify-center">
                                    <svg class="w-7 h-7 drop-shadow-[0_2px_4px_rgba(180,83,9,0.25)]" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="32" cy="32" r="26" fill="url(#bronzeMobMetallic)" stroke="#b45309" stroke-width="2"/>
                                        <circle cx="32" cy="32" r="20" fill="url(#bronzeMobMetallicAccent)"/>
                                        <text x="32" y="38" font-size="18" font-weight="900" fill="#78350f" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif">3</text>
                                        <defs>
                                            <linearGradient id="bronzeMobMetallic" x1="6" y1="6" x2="58" y2="58" gradientUnits="userSpaceOnUse">
                                                <stop offset="0%" stop-color="#ffedd5" />
                                                <stop offset="50%" stop-color="#d97706" />
                                                <stop offset="100%" stop-color="#78350f" />
                                            </linearGradient>
                                            <linearGradient id="bronzeMobMetallicAccent" x1="12" y1="12" x2="52" y2="52" gradientUnits="userSpaceOnUse">
                                                <stop offset="0%" stop-color="#ffedd5" />
                                                <stop offset="50%" stop-color="#f59e0b" />
                                                <stop offset="100%" stop-color="#b45309" />
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                </span>
                            @else
                                <span class="text-[10px] text-slate-500 font-bold bg-slate-50 dark:bg-slate-800 w-7 h-7 inline-flex items-center justify-center rounded-full border border-slate-200 dark:border-slate-700 shadow-sm">#{{ $loop->iteration }}</span>
                            @endif
                        </div>

                        <!-- Avatar -->
                        <a href="{{ route('profile.show', $user->name) }}" class="w-10 h-10 rounded-full overflow-hidden border border-slate-200 dark:border-slate-700 shadow flex-shrink-0 block bg-slate-100">
                            <img src="{{ $user->avatar_url }}" class="w-full h-full object-cover" alt="avatar">
                        </a>

                        <!-- Username -->
                        <div class="min-w-0 text-left">
                            <a href="{{ route('profile.show', $user->name) }}" class="font-bold text-slate-900 dark:text-white hover:text-blue-600 text-sm truncate block">{{ $user->name }}</a>
                            <p class="text-[10px] text-slate-500 font-medium leading-none mt-1">Joined {{ $user->created_at->format('M Y') }}</p>
                        </div>
                    </div>

                    <!-- Points pill -->
                    <span class="inline-block font-extrabold text-blue-700 dark:text-blue-400 text-[10px] px-2.5 py-1.5 rounded-lg bg-blue-50 dark:bg-blue-950/20 border border-blue-150 dark:border-blue-900/30 shadow-sm">
                        {{ number_format($user->calculated_points) }} pts
                    </span>
                </div>

                <!-- Mid Row: Badges side by side -->
                <div class="flex flex-wrap items-center gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                    <!-- Otaku Tier -->
                    @php
                        $tier = $user->anime_tier;
                        $tierName = $tier['name'];
                        $tierColor = $tier['color'];
                    @endphp
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-bold uppercase shadow-sm text-white rounded-full" style="background-color: {{ $tierColor }}">
                        <span>Level {{ $tier['level'] ?? 1 }}</span>
                        <span class="opacity-60">•</span>
                        <span>{{ $tierName }}</span>
                    </span>

                    <!-- Specialty badge -->
                    <span class="inline-flex px-2.5 py-1 text-[10px] font-bold uppercase bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 rounded-lg shadow-sm" style="border-left: 2px solid {{ $user->banner_color ?? '#3b82f6' }}">
                        {{ $user->title_badge ?? 'Otaku Member' }}
                    </span>
                </div>

                <!-- Bottom Row: Vector Stats Counts -->
                <div class="flex items-center justify-between border-t border-slate-100/60 dark:border-slate-800/60 pt-3 text-[10px] text-slate-500 font-bold bg-slate-50/20 dark:bg-slate-800/10 px-3 py-2 rounded-xl">
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-blue-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 19.5C4 18.837 4.26339 18.2011 4.73223 17.7322C5.20107 17.2634 5.83696 17 6.5 17H20V3H6.5C5.83696 3 5.20107 3.26339 4.73223 3.73223C4.26339 4.20107 4 4.83696 4 5.5V19.5ZM4 19.5C4 20.163 4.26339 20.7989 4.73223 21.2678C5.20107 21.7366 5.83696 22 6.5 22H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        {{ $user->threads()->count() }} Threads
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 15C21 15.5304 20.7893 16.0391 20.4142 16.4142C20.0391 16.7893 19.5304 17 19 17H7L3 21V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H19C19.5304 3 20.0391 3.21071 20.4142 3.58579C20.7893 3.96086 21 4.46957 21 5V15Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        {{ $user->posts()->count() }} Replies
                    </span>
                </div>
            </div>
        @empty
            <div class="border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-8 text-center rounded-2xl shadow-sm flex flex-col items-center">
                <svg class="w-24 h-24 opacity-60 mb-2" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="60" cy="60" r="50" fill="url(#emptyStateGradMob)" stroke="#E2E8F0" stroke-width="2" stroke-dasharray="4 4" />
                    <path d="M50 50C55.5228 50 60 45.5228 60 40C60 34.4772 55.5228 30 50 30C44.4772 30 40 34.4772 40 40C40 45.5228 44.4772 50 50 50Z" stroke="#94A3B8" stroke-width="3" />
                    <path d="M57 47L72 62" stroke="#94A3B8" stroke-width="3" stroke-linecap="round" />
                    <defs>
                        <linearGradient id="emptyStateGradMob" x1="10" y1="10" x2="110" y2="110" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#F8FAFC" />
                            <stop offset="100%" stop-color="#F1F5F9" />
                        </linearGradient>
                    </defs>
                </svg>
                <p class="text-sm text-slate-500 font-medium">No community members matched this rankings filter.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
