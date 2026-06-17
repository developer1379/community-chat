@extends('layouts.app')

@section('title')
{{ $user->name }}'s Member Profile | XenForo Professional
@endsection
@section('meta_description')
{{ $user->name }} - Joined community on {{ $user->created_at->format('M d, Y') }}. Check out their recent discussions, uploads, and updates.
@endsection
@section('meta_keywords')
{{ $user->name }}, member profile, forum user, conversations, posts
@endsection
@section('og_type')
profile
@endsection

@section('content')
<!-- JSON-LD Structured Schema for User Profile -->
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "Person",
  "name": "{{ e($user->name) }}",
  "url": "{{ url()->current() }}",
  "image": "{{ $user->avatar_url }}"
}
</script>
<div class="space-y-6">
    @php
        $points = $user->activity_points;
        $tier = $user->computed_anime_tier;
        $level = $tier['level'] ?? 1;
        $glowClass = 'border border-slate-200 shadow-md';
        $avatarGlow = 'border-4 border-white';
        if ($level >= 20) {
            $glowClass = 'border border-rose-500/40 shadow-[0_0_20px_rgba(225,29,72,0.2)] ring-1 ring-rose-500/10';
            $avatarGlow = 'border-4 border-rose-500 shadow-[0_0_15px_rgba(225,29,72,0.5)] ring-2 ring-rose-500/20';
        } elseif ($level >= 16) {
            $glowClass = 'border border-purple-500/40 shadow-[0_0_15px_rgba(124,58,237,0.15)]';
            $avatarGlow = 'border-4 border-purple-500 shadow-[0_0_10px_rgba(124,58,237,0.4)] ring-2 ring-purple-500/20';
        } elseif ($level >= 12) {
            $glowClass = 'border border-amber-500/40 shadow-[0_0_12px_rgba(217,119,6,0.1)]';
            $avatarGlow = 'border-4 border-amber-500 shadow-[0_0_8px_rgba(217,119,6,0.3)]';
        } elseif ($level >= 2) {
            $glowClass = 'border border-blue-500/30 shadow-sm';
            $avatarGlow = 'border-4 border-blue-500 shadow-[0_0_6px_rgba(37,99,235,0.2)]';
        }

        $reactionsCount = \App\Models\React::whereIn('post_id', $user->posts()->pluck('id'))->count();
        $badgesCount = max(1, min(10, floor($reactionsCount / 100) + floor($user->posts()->count() / 50) + 1));
        $awardsCount = max(1, min(5, floor($user->coins / 1500) + ($user->isAdmin() ? 3 : 0)));

        $milestones = \App\Models\RankMilestone::orderBy('level', 'asc')->get();
        $coins = $user->coins;
        
        $currentMilestone = $milestones->first();
        $nextMilestone = null;
        
        foreach ($milestones as $ms) {
            if ($coins >= $ms->coins_required) {
                $currentMilestone = $ms;
            } else {
                $nextMilestone = $ms;
                break;
            }
        }
        
        if (!$nextMilestone) {
            $nextMilestone = $currentMilestone;
            $percent = 100;
            $target = $currentMilestone->coins_required;
        } else {
            $prevReq = $currentMilestone->coins_required;
            $nextReq = $nextMilestone->coins_required;
            $denom = $nextReq - $prevReq;
            $percent = $denom > 0 ? min(100, (int)(($coins - $prevReq) / $denom * 100)) : 100;
            $target = $nextMilestone->coins_required;
        }
    @endphp

    <!-- Professional & Compact Profile Hero Card -->
    <div class="relative rounded-2xl overflow-hidden bg-white dark:bg-slate-900 transition-all duration-300 {{ $glowClass }}">
        <!-- Dynamic Gradient / Image Cover Banner (Sleek Compact Height) -->
        <div id="profile-banner-bg" class="h-28 sm:h-36 relative bg-cover bg-center" style="background: {{ $user->banner_path ? 'url(' . $user->banner_path . ')' : $user->banner_color }}">
            <div class="absolute inset-0 bg-black/10 backdrop-blur-[0.5px]"></div>
            
            @auth
                @if(Auth::id() === $user->id)
                    <button onclick="document.getElementById('banner').click();" class="absolute top-3 right-3 bg-slate-900/60 hover:bg-slate-900/80 text-white rounded-lg px-2.5 py-1 text-[10px] font-bold transition-all backdrop-blur-sm border border-white/10 flex items-center gap-1 cursor-pointer z-20 shadow-md" title="Edit Cover Photo">
                        <span class="material-symbols-outlined text-xs">photo_camera</span>
                        <span>Edit Cover</span>
                    </button>
                @endif
            @endauth
        </div>

        <!-- User Info & Action Row -->
        <div class="bg-white dark:bg-slate-900 p-5 relative border-t border-slate-100 dark:border-slate-850 flex flex-col md:flex-row items-center md:items-start justify-between gap-5 transition-colors duration-300">
            <!-- Avatar & Details -->
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 -mt-12 sm:-mt-16 z-10 text-center sm:text-left">
                <!-- Compact Avatar frame -->
                <div class="w-24 h-24 rounded-2xl overflow-hidden {{ $avatarGlow }} bg-slate-50 dark:bg-slate-800 shadow-md relative group/avatar flex-shrink-0">
                    <img id="profile-avatar-img" src="{{ $user->avatar_url }}" class="w-full h-full object-cover" alt="avatar">

                    @auth
                        @if(Auth::id() === $user->id)
                            <div onclick="document.getElementById('avatar').click();" class="absolute inset-0 bg-black/50 opacity-0 group-hover/avatar:opacity-100 transition-all flex flex-col items-center justify-center cursor-pointer text-white z-20 font-bold text-[8px] uppercase tracking-wider" title="Change Avatar">
                                <span class="material-symbols-outlined text-base mb-0.5">photo_camera</span>
                                <span>Edit</span>
                            </div>
                        @endif
                    @endauth
                </div>

                <div class="space-y-1 pt-1 sm:pt-4">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2.5">
                        <h2 class="text-xl font-black text-slate-900 dark:text-white tracking-tight {{ $user->username_style }}" style="{{ $user->username_style_css }}">{{ $user->name }}</h2>
                        <span class="text-[9px] px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider shadow-sm" style="color: #ffffff; background: {{ $user->banner_color }}">
                            {{ $user->title_badge }}
                        </span>
                    </div>

                    <!-- Custom Status Section (Compact Pill) -->
                    <div class="mt-1 flex items-center justify-center sm:justify-start">
                        @if($user->status || $user->status_image)
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-slate-50 dark:bg-slate-800/80 rounded-full border border-slate-100 dark:border-slate-800 shadow-sm max-w-full text-[11px] font-semibold text-slate-700 dark:text-slate-300">
                                <span>💬</span>
                                @if($user->status)
                                    <span class="truncate max-w-[200px] sm:max-w-[300px]" id="status-display-text">{{ $user->status }}</span>
                                @endif
                                @if($user->status_image)
                                    <img src="{{ $user->status_image }}" onclick="openLightbox('{{ $user->status_image }}', '{{ $user->name }}\'s status image')" class="w-4 h-4 rounded object-cover cursor-zoom-in hover:scale-110 transition-transform" id="status-display-image">
                                @endif
                                @auth
                                    @if(Auth::id() === $user->id)
                                        <button onclick="editStatusInline()" class="hover:text-blue-500 text-slate-400 transition-colors inline-flex items-center ml-0.5" title="Update Status">
                                            <span class="material-symbols-outlined text-[12px]">edit</span>
                                        </button>
                                    @endif
                                @endauth
                            </div>
                        @elseif(Auth::check() && Auth::id() === $user->id)
                            <button onclick="editStatusInline()" class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-slate-50/50 dark:bg-slate-850 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-450 dark:text-slate-500 hover:text-blue-500 dark:hover:text-blue-400 rounded-full border border-dashed border-slate-200 dark:border-slate-700 transition-all text-[11px] font-medium">
                                <span class="material-symbols-outlined text-[10px]">add_circle</span>
                                <span>Add status</span>
                            </button>
                        @endif
                    </div>

                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-1.5 text-xs font-bold text-slate-500 dark:text-slate-400 pt-0.5">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: {{ $tier['color'] }}">
                            <path d="M12 2L3 5V11C3 16.55 6.84 21.74 12 23C17.16 21.74 21 16.55 21 11V5L12 2Z" fill="currentColor" fill-opacity="0.2" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                            @if($level >= 20)
                                <path d="M8 15L10 9L12 11.5L14 9L16 15H8Z" fill="currentColor"/>
                                <circle cx="12" cy="7" r="1" fill="currentColor"/>
                            @elseif($level >= 16)
                                <path d="M12 7L13.5 10L16.8 10.5L14.4 12.8L15 16L12 14.5L9 16L9.6 12.8L7.2 10.5L10.5 10L12 7Z" fill="currentColor"/>
                            @elseif($level >= 12)
                                <path d="M10 9L11 11L13 11.3L11.5 12.7L12 14.7L10 13.7L8 14.7L8.5 12.7L7 11.3L9 11L10 9Z" fill="currentColor"/>
                                <path d="M14 9L15 11L17 11.3L15.5 12.7L16 14.7L14 13.7L12 14.7L12.5 12.7L11 11.3L13 11L14 9Z" fill="currentColor"/>
                            @elseif($level >= 8)
                                <path d="M12 8L15 11L12 14L9 11L12 8Z" fill="currentColor"/>
                            @else
                                <circle cx="12" cy="12" r="3" fill="currentColor"/>
                            @endif
                        </svg>
                        <span style="color: {{ $tier['color'] }}">{{ $tier['name'] }}</span>
                        <span class="text-[8px] px-1.5 py-0.2 bg-slate-100 dark:bg-slate-800 rounded text-slate-500 dark:text-slate-400 uppercase font-black tracking-wide">{{ $tier['badge'] }}</span>
                        <span class="text-slate-300 dark:text-slate-700">•</span>
                        <span>Joined {{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Stats & Actions Block -->
            <div class="flex flex-col items-center md:items-end gap-3 z-10 w-full md:w-auto flex-shrink-0">
                <!-- Dashboard Metrics -->
                <div class="flex items-center gap-3 text-center bg-slate-50 dark:bg-slate-950 p-2.5 rounded-xl border border-slate-100 dark:border-slate-850 w-full sm:w-auto justify-around">
                    <div class="px-2">
                        <span class="block text-base font-black text-slate-900 dark:text-white leading-tight">{{ $user->posts()->count() }}</span>
                        <span class="text-[9px] font-bold text-slate-450 uppercase tracking-wider">Posts</span>
                    </div>
                    <div class="w-px h-5 bg-slate-200 dark:bg-slate-800"></div>
                    <div class="px-2">
                        <span class="block text-base font-black text-slate-900 dark:text-white leading-tight">{{ $reactionsCount }}</span>
                        <span class="text-[9px] font-bold text-slate-450 uppercase tracking-wider">Reacts</span>
                    </div>
                    <div class="w-px h-5 bg-slate-200 dark:bg-slate-800"></div>
                    <div class="px-2">
                        <span class="block text-base font-black text-slate-900 dark:text-white leading-tight">{{ $badgesCount }}</span>
                        <span class="text-[9px] font-bold text-slate-450 uppercase tracking-wider">Badges</span>
                    </div>
                    <div class="w-px h-5 bg-slate-200 dark:bg-slate-800"></div>
                    <div class="px-2">
                        <span class="block text-base font-black text-slate-900 dark:text-white leading-tight">{{ $user->activity_points }}</span>
                        <span class="text-[9px] font-bold text-slate-450 uppercase tracking-wider">Points</span>
                    </div>
                </div>

                <!-- Action Button Row -->
                <div class="flex gap-2 w-full justify-center md:justify-end">
                    @auth
                        @if(Auth::id() !== $user->id)
                            <button type="button" 
                                    onclick="toggleFollowUser('{{ $user->name }}', '{{ $user->id }}')" 
                                    id="follow-btn-{{ $user->id }}" 
                                    class="flex-1 sm:flex-none text-xs font-bold py-1.5 px-3.5 rounded-lg transition-all cursor-pointer border flex items-center justify-center gap-1 shadow-sm
                                    {{ Auth::user()->isFollowing($user) 
                                        ? 'bg-blue-50 dark:bg-blue-955/30 border-blue-200 dark:border-blue-900 text-blue-700 dark:text-blue-400 hover:bg-rose-50 dark:hover:bg-rose-955/20 hover:text-rose-700 dark:hover:text-rose-450 hover:border-rose-200 dark:hover:border-rose-900 group/follow active:scale-97' 
                                        : 'bg-white dark:bg-slate-850 border-slate-250 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 active:scale-97' }}">
                                @if(Auth::user()->isFollowing($user))
                                    <span class="material-symbols-outlined text-[13px] group-hover/follow:hidden">check</span>
                                    <span class="group-hover/follow:hidden">Following</span>
                                    <span class="material-symbols-outlined text-[13px] hidden group-hover/follow:inline-block">person_remove</span>
                                    <span class="hidden group-hover/follow:inline">Unfollow</span>
                                @else
                                    <span class="material-symbols-outlined text-[13px]">person_add</span>
                                    <span>Follow</span>
                                @endif
                            </button>

                            <button type="button" 
                                    onclick="startDirectChat('{{ $user->name }}')" 
                                    class="flex-1 sm:flex-none text-xs font-bold py-1.5 px-3.5 rounded-lg transition-all cursor-pointer border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-850 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 active:scale-97 flex items-center justify-center gap-1 shadow-sm">
                                <span class="material-symbols-outlined text-[13px]">chat</span>
                                <span>Message</span>
                            </button>
                        @endif
                    @endauth

                    <button type="button" 
                            onclick="copyProfileLink()" 
                            class="flex-1 sm:flex-none text-xs font-bold py-1.5 px-3.5 rounded-lg transition-all cursor-pointer border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-850 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 active:scale-97 flex items-center justify-center gap-1 shadow-sm"
                            title="Copy profile link">
                        <span class="material-symbols-outlined text-[14px]">share</span>
                        <span>Share</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Signature quote footer -->
        @if($user->signature)
            <div class="bg-slate-50 dark:bg-slate-900/50 px-5 py-2.5 border-t border-slate-100 dark:border-slate-850 text-xs text-slate-650 dark:text-slate-400 italic text-center sm:text-left font-medium transition-colors">
                💬 "{{ $user->signature }}"
            </div>
        @endif
    </div>

    <!-- Main Content Tabbed Panels (Grid Layout) -->
    @if($isProfilePrivate)
        <div class="max-w-xl mx-auto my-12 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-lg overflow-hidden p-8 sm:p-12 text-center space-y-6">
            <div class="w-16 h-16 mx-auto bg-slate-105 dark:bg-slate-800 text-slate-400 rounded-2xl flex items-center justify-center shadow-inner">
                <span class="material-symbols-outlined text-3xl">lock</span>
            </div>
            <div class="space-y-2">
                <h3 class="text-lg font-black text-slate-900 dark:text-white tracking-tight">This Profile is Private</h3>
                <p class="text-xs text-slate-550 dark:text-slate-400 max-w-sm mx-auto font-medium leading-relaxed">
                    {{ $user->name }} has chosen to keep their profile activity private.
                </p>
            </div>
            <div class="pt-2">
                <a href="{{ route('home') }}" class="xen-button text-xs font-bold text-white px-5 py-2 rounded-lg shadow-md cursor-pointer inline-flex items-center gap-1.5 hover:opacity-90">
                    <span class="material-symbols-outlined text-xs">home</span>
                    <span>Return to Discussions</span>
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <!-- Left Column: Tabbed Content Areas (8 Cols) -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Navigation Tabs bar -->
                <div class="border-b border-slate-200 dark:border-slate-800 flex items-center justify-between pb-px">
                    <div class="flex gap-1 overflow-x-auto no-scrollbar scroll-smooth">
                        <button onclick="switchTab('discussions')" id="tab-discussions-btn" class="profile-tab-btn px-4 py-2.5 text-xs font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-855 dark:hover:text-slate-300 transition-all flex items-center gap-1.5 whitespace-nowrap cursor-pointer">
                            <span class="material-symbols-outlined text-sm">forum</span>
                            Discussions
                        </button>
                        <button onclick="switchTab('media')" id="tab-media-btn" class="profile-tab-btn px-4 py-2.5 text-xs font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-855 dark:hover:text-slate-300 transition-all flex items-center gap-1.5 whitespace-nowrap cursor-pointer">
                            <span class="material-symbols-outlined text-sm">photo_library</span>
                            Media Gallery
                        </button>
                        @auth
                            @if(Auth::id() === $user->id)
                                <button onclick="switchTab('customize')" id="tab-customize-btn" class="profile-tab-btn px-4 py-2.5 text-xs font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-855 dark:hover:text-slate-300 transition-all flex items-center gap-1.5 whitespace-nowrap cursor-pointer">
                                    <span class="material-symbols-outlined text-sm">settings</span>
                                    Settings & Shop
                                </button>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Tab content blocks -->
                <div id="profile-tab-contents">
                    <!-- Tab 1: Recent Discussions -->
                    <div id="tab-discussions-content" class="profile-tab-content">
                        <div class="mui-card overflow-hidden shadow-sm border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-900 rounded-xl">
                            <div class="divide-y divide-slate-100 dark:divide-slate-850">
                                @forelse($threads as $thread)
                                    <div class="px-5 py-3.5 flex items-center justify-between gap-4 hover:bg-slate-50/50 dark:hover:bg-slate-850/30 transition-colors">
                                        <div class="space-y-0.5 text-left min-w-0">
                                            <h4 class="font-bold text-slate-800 dark:text-white text-xs hover:text-blue-600 dark:hover:text-blue-450 transition-colors truncate">
                                                <a href="{{ route('threads.show', $thread->slug) }}">{!! $thread->prefix_badge !!}{{ $thread->title }}</a>
                                            </h4>
                                            <div class="flex items-center gap-1.5 text-[10px] text-slate-450 dark:text-slate-400">
                                                <span class="font-bold px-1.5 py-0.5 rounded bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-900/50">{{ $thread->category->name }}</span>
                                                <span>•</span>
                                                <span>Created {{ $thread->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        <div class="text-[10px] font-bold text-slate-600 dark:text-slate-400 bg-slate-50 dark:bg-slate-950 px-2 py-1 rounded border border-slate-205 dark:border-slate-800 flex-shrink-0">
                                            {{ $thread->views_count }} views
                                        </div>
                                    </div>
                                @empty
                                    <div class="px-6 py-10 text-center text-xs text-slate-450 dark:text-slate-500 font-medium">
                                        No discussions posted by this member yet.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Tab 2: Media Showroom -->
                    <div id="tab-media-content" class="profile-tab-content hidden">
                        <div class="mui-card p-5 border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm rounded-xl">
                            @if(count($attachments) > 0)
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    @foreach($attachments as $attach)
                                        <div class="relative group rounded-xl overflow-hidden bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 shadow-sm">
                                            <!-- Padlock Toggle for Owner -->
                                            @auth
                                                @if(Auth::id() === $user->id)
                                                    <button onclick="toggleAttachmentPrivacy('{{ $attach->id }}')" 
                                                            id="privacy-btn-{{ $attach->id }}"
                                                            class="absolute top-1.5 left-1.5 w-6 h-6 rounded-lg bg-slate-900/60 hover:bg-slate-900/80 text-white flex items-center justify-center transition-all backdrop-blur-sm border border-white/10 cursor-pointer z-10" 
                                                            title="{{ $attach->is_private ? 'Make Public' : 'Make Private' }}">
                                                        <span class="material-symbols-outlined text-[13px] font-bold" id="privacy-icon-{{ $attach->id }}">
                                                            {{ $attach->is_private ? 'lock' : 'lock_open' }}
                                                        </span>
                                                    </button>
                                                @endif
                                            @endauth

                                            <button onclick="openLightbox('{{ $attach->url }}', '{{ $attach->file_name }}')" class="block w-full h-24 overflow-hidden cursor-zoom-in text-left p-0 border-0 outline-none w-full bg-slate-100 dark:bg-slate-950">
                                                <img src="{{ $attach->url }}" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-200" alt="uploaded media">
                                            </button>
                                            <!-- Check if GIF -->
                                            @if(str_contains($attach->file_name, '.gif') || str_contains($attach->file_type, 'gif'))
                                                <span class="absolute top-1.5 right-1.5 px-1 py-0.5 rounded text-[7px] font-bold bg-pink-500 text-white uppercase tracking-widest">
                                                    GIF
                                                </span>
                                            @endif
                                            <div class="bg-slate-100/80 dark:bg-slate-900/80 p-1.5 text-[8px] text-slate-500 dark:text-slate-400 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between">
                                                <span class="truncate pr-2 font-medium">{{ $attach->file_name }}</span>
                                                @if($attach->thread)
                                                    <a href="{{ route('threads.show', $attach->thread->slug) }}" class="hover:text-blue-600 transition-colors font-bold" title="View thread">
                                                        🔗
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-10 border border-slate-200 dark:border-slate-800 border-dashed rounded-xl">
                                    <span class="text-2xl block mb-1 opacity-50">🖼️</span>
                                    <p class="text-xs text-slate-450 dark:text-slate-500 max-w-[200px] mx-auto font-medium">No custom images or GIFs uploaded by this member yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Tab 3: Customize Profile (Owner Only) -->
                    @auth
                        @if(Auth::id() === $user->id)
                            <div id="tab-customize-content" class="profile-tab-content hidden space-y-6">
                                
                                <!-- Shop Upgrades Section -->
                                @php
                                    $hasUsernameStyle = $user->hasActiveShopItem('username_style');
                                    $hasUsernameChange = $user->hasActiveShopItem('username_change');
                                    $hasStickyUpgrade = $user->hasActiveShopItem('sticky_thread');
                                    $hasFeaturedThread = $user->hasActiveShopItem('featured_homepage_thread');
                                    $hasTitleStyle = $user->hasActiveShopItem('thread_title_style');
                                    $hasHighlight = $user->hasActiveShopItem('thread_highlight');
                                    
                                    $purchasedItems = $user->purchases()->with('shopItem')->where(function($q) {
                                        $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                                    })->get();
                                @endphp

                                @if($purchasedItems->isNotEmpty())
                                    <div id="shop-upgrades-card" class="mui-card overflow-hidden bg-white dark:bg-slate-900 border border-slate-250 dark:border-slate-800 shadow-sm rounded-xl text-left transition-colors duration-300">
                                        <div class="bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-slate-900 dark:to-slate-850 px-5 py-3 border-b border-slate-200 dark:border-slate-800">
                                            <h3 class="font-bold text-slate-850 dark:text-white text-xs flex items-center gap-2">
                                                <span class="material-symbols-outlined text-emerald-600 text-sm">shopping_bag</span>
                                                My Purchased Shop Upgrades
                                            </h3>
                                        </div>
                                        <div class="p-5 space-y-5">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                @foreach($purchasedItems as $purchase)
                                                    <div class="p-3 rounded-lg border border-slate-100 dark:border-slate-850 bg-slate-50/50 dark:bg-slate-950/20 flex items-start gap-3 justify-between">
                                                        <div class="min-w-0">
                                                            <span class="inline-block text-[8px] font-black uppercase bg-emerald-100 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-450 px-1.5 py-0.5 rounded leading-none">Active Upgrade</span>
                                                            <h4 class="text-xs font-black text-slate-850 dark:text-white mt-1">{{ $purchase->shopItem->name }}</h4>
                                                            <p class="text-[9px] text-slate-405 dark:text-slate-500 mt-0.5 font-bold">
                                                                Expires: {{ $purchase->expires_at ? $purchase->expires_at->format('M d, Y') : 'Never (Permanent)' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="border-t border-slate-100 dark:border-slate-850 pt-4 space-y-4">
                                                <h4 class="text-xs font-extrabold text-slate-800 dark:text-white uppercase tracking-wider">Configure Upgrades</h4>
                                                
                                                @if($hasUsernameChange)
                                                    <form action="{{ route('profile.update_username') }}" method="POST" class="p-3.5 rounded-xl border border-slate-200 dark:border-slate-800 space-y-3">
                                                        @csrf
                                                        <div>
                                                            <label class="block text-[9px] font-black uppercase text-slate-400 dark:text-slate-550 tracking-wider">Change Username (Purchased Upgrade)</label>
                                                            <input type="text" name="name" required value="{{ $user->name }}" class="w-full mt-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 dark:text-slate-150 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                                        </div>
                                                        <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md transition-all cursor-pointer">
                                                            Update Username
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($hasUsernameStyle)
                                                    <form action="{{ route('profile.update_username_style') }}" method="POST" class="p-3.5 rounded-xl border border-slate-200 dark:border-slate-800 space-y-3">
                                                        @csrf
                                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                            <div>
                                                                <label class="block text-[9px] font-black uppercase text-slate-400 dark:text-slate-550 tracking-wider">Text/Badge Color</label>
                                                                <input type="color" name="title_color" value="{{ $user->title_color ?: '#4f46e5' }}" class="w-10 h-10 border-0 rounded-lg cursor-pointer mt-1.5">
                                                            </div>
                                                            <div>
                                                                <label class="block text-[9px] font-black uppercase text-slate-400 dark:text-slate-550 tracking-wider">Title Badge Text (Custom)</label>
                                                                <input type="text" name="title_badge" value="{{ $user->title_badge ?: 'VIP Member' }}" class="w-full mt-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 dark:text-slate-150 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                                            </div>
                                                        </div>
                                                        <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md transition-all cursor-pointer">
                                                            Apply Custom Styles
                                                        </button>
                                                    </form>
                                                @endif

                                                @if(($hasTitleStyle || $hasHighlight || $hasFeaturedThread || $hasStickyUpgrade) && $threads->isNotEmpty())
                                                    <form action="{{ route('profile.update_thread_upgrades') }}" method="POST" class="p-3.5 rounded-xl border border-slate-200 dark:border-slate-800 space-y-3">
                                                        @csrf
                                                        <div class="space-y-2">
                                                            <label class="block text-[9px] font-black uppercase text-slate-400 dark:text-slate-550 tracking-wider">Apply Upgrades to Your Threads</label>
                                                            <select name="thread_id" required class="w-full bg-slate-50 dark:bg-slate-955 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-800 dark:text-slate-150 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                                                <option value="">-- Select one of your threads --</option>
                                                                @foreach($threads as $t)
                                                                    <option value="{{ $t->id }}">{{ $t->title }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="flex flex-wrap gap-4 text-xs font-bold text-slate-650 dark:text-slate-350 pt-1">
                                                            @if($hasTitleStyle)
                                                                <label class="flex items-center gap-2">
                                                                    <input type="checkbox" name="apply_title_style" value="1" class="rounded border-slate-350 text-blue-600 focus:ring-blue-500">
                                                                    <span>Title Glow</span>
                                                                </label>
                                                            @endif
                                                            @if($hasHighlight)
                                                                <label class="flex items-center gap-2">
                                                                    <input type="checkbox" name="apply_highlight" value="1" class="rounded border-slate-350 text-blue-600 focus:ring-blue-500">
                                                                    <span>Highlight</span>
                                                                </label>
                                                            @endif
                                                            @if($hasFeaturedThread)
                                                                <label class="flex items-center gap-2">
                                                                    <input type="checkbox" name="apply_featured" value="1" class="rounded border-slate-350 text-blue-600 focus:ring-blue-500">
                                                                    <span>Featured Slider</span>
                                                                </label>
                                                            @endif
                                                            @if($hasStickyUpgrade)
                                                                <label class="flex items-center gap-2">
                                                                    <input type="checkbox" name="apply_sticky" value="1" class="rounded border-slate-350 text-blue-600 focus:ring-blue-500">
                                                                    <span>Sticky (Pin)</span>
                                                                </label>
                                                            @endif
                                                        </div>
                                                        <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md transition-all cursor-pointer mt-1">
                                                            Apply Thread Upgrades
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Customize Profile Card settings -->
                                <div class="mui-card overflow-hidden bg-white dark:bg-slate-900 border border-slate-250 dark:border-slate-800 shadow-sm rounded-xl transition-all duration-300">
                                    <div class="bg-slate-50 dark:bg-slate-900/50 px-5 py-3 border-b border-slate-205 dark:border-slate-800">
                                        <h3 class="font-bold text-slate-750 dark:text-slate-300 text-xs flex items-center gap-2">
                                            <span class="material-symbols-outlined text-blue-600 text-sm">settings</span>
                                            Customize Profile Card
                                        </h3>
                                    </div>
                                    <form id="profile-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-4 bg-white dark:bg-slate-900">
                                        @csrf
                                        
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                            <!-- Avatar Upload -->
                                            <div class="relative border border-slate-200 dark:border-slate-800 focus-within:border-blue-500 rounded-xl p-3 bg-slate-50/50 dark:bg-slate-950/20 hover:bg-slate-50 dark:hover:bg-slate-950/40 transition-all flex flex-col justify-center text-left">
                                                <label for="avatar" class="text-[8px] font-black text-slate-400 dark:text-slate-550 uppercase tracking-widest absolute top-1.5 left-3">Avatar Image</label>
                                                <input type="file" id="avatar" name="avatar" class="block w-full text-xs text-slate-550 mt-2 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-[9px] file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                            </div>
                                            <!-- Banner Upload -->
                                            <div class="relative border border-slate-200 dark:border-slate-800 focus-within:border-blue-500 rounded-xl p-3 bg-slate-50/50 dark:bg-slate-950/20 hover:bg-slate-50 dark:hover:bg-slate-950/40 transition-all flex flex-col justify-center text-left">
                                                <label for="banner" class="text-[8px] font-black text-slate-400 dark:text-slate-550 uppercase tracking-widest absolute top-1.5 left-3 flex items-center gap-1">
                                                    Cover Photo
                                                    @if($user->banner_updates_count >= 1 && !$user->isAdmin())
                                                        <span class="text-[7px] text-rose-650 bg-rose-50 px-1 py-0.2 rounded font-black">💰 50 Coins</span>
                                                    @endif
                                                </label>
                                                <input type="file" id="banner" name="banner" class="block w-full text-xs text-slate-550 mt-2 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-[9px] file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                            </div>
                                            <!-- Custom title badge -->
                                            <div class="relative border border-slate-200 dark:border-slate-800 focus-within:border-blue-500 rounded-xl p-3 bg-white dark:bg-slate-955/10 transition-all text-left">
                                                <label for="title_badge" class="text-[8px] font-black text-slate-400 dark:text-slate-550 uppercase tracking-widest absolute top-1.5 left-3 flex items-center gap-1">
                                                    Title Badge
                                                    @if($tier['level'] < 20 && !$user->isAdmin())
                                                        <span class="text-[7px] text-amber-600 bg-amber-50 px-1 py-0.2 rounded font-black">🔒 PK (Lvl 20)</span>
                                                    @endif
                                                </label>
                                                <input type="text" id="title_badge" name="title_badge" {{ ($tier['level'] < 20 && !$user->isAdmin()) ? 'disabled' : '' }} value="{{ old('title_badge', $user->title_badge) }}" class="w-full mt-2 bg-transparent border-0 p-0 text-slate-800 dark:text-white text-xs font-semibold focus:outline-none focus:ring-0 placeholder:text-slate-400" placeholder="{{ ($tier['level'] < 20 && !$user->isAdmin()) ? 'Locked' : 'Guru, Ninja...' }}">
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <!-- Username Color -->
                                            <div class="relative border border-slate-200 dark:border-slate-800 focus-within:border-blue-500 rounded-xl p-3 bg-white dark:bg-slate-900 transition-all text-left">
                                                <label class="text-[8px] font-black text-slate-400 dark:text-slate-550 uppercase tracking-widest absolute top-1.5 left-3 flex items-center gap-1">
                                                    Username Color
                                                    @if(!$user->isAdmin())
                                                        <span class="text-[7px] text-rose-650 bg-rose-50 px-1 py-0.2 rounded font-black">💰 100 Coins</span>
                                                    @endif
                                                </label>
                                                <input type="hidden" name="title_color" id="profile-color-hidden-input" value="{{ $user->title_color }}">
                                                <div class="flex items-center gap-2 mt-2">
                                                    <div class="flex items-center gap-1.5">
                                                        <input type="checkbox" id="profile-color-reset" class="rounded border-slate-350 text-indigo-650 dark:text-indigo-400 focus:ring-indigo-500" {{ !$user->title_color ? 'checked' : '' }}>
                                                        <label for="profile-color-reset" class="text-[10px] text-slate-500 dark:text-slate-400 font-bold cursor-pointer">Default</label>
                                                    </div>
                                                    <input type="color" id="profile-color-input" value="{{ $user->title_color ?: '#4f46e5' }}" class="w-7 h-7 border-0 rounded-lg cursor-pointer">
                                                    <span class="text-[10px] text-slate-405 dark:text-slate-500 font-bold">Pick</span>
                                                </div>
                                            </div>

                                            <!-- Username Animation -->
                                            <div class="relative border border-slate-200 dark:border-slate-800 focus-within:border-blue-500 rounded-xl p-3 bg-white dark:bg-slate-900 transition-all text-left">
                                                <label for="username_animation" class="text-[8px] font-black text-slate-400 dark:text-slate-550 uppercase tracking-widest absolute top-1.5 left-3 flex items-center gap-1">
                                                    Username Animation
                                                    @if(!$user->isAdmin())
                                                        <span class="text-[7px] text-rose-650 bg-rose-50 px-1 py-0.2 rounded font-black">💰 500 Coins</span>
                                                    @endif
                                                </label>
                                                <select name="username_animation" id="profile-anim-select" class="w-full mt-2 bg-transparent dark:bg-slate-900 border-0 p-0 text-slate-850 dark:text-slate-100 text-xs font-semibold focus:outline-none focus:ring-0">
                                                    <option value="none" {{ !$user->username_animation || $user->username_animation === 'none' ? 'selected' : '' }}>None (Static Color)</option>
                                                    <option value="glow" {{ $user->username_animation === 'glow' ? 'selected' : '' }}>Glow (Soft neon pulse)</option>
                                                    <option value="pulse" {{ $user->username_animation === 'pulse' ? 'selected' : '' }}>Pulse (Scale and fade)</option>
                                                    <option value="crackle" {{ $user->username_animation === 'crackle' ? 'selected' : '' }}>Crackle (Lightning glow)</option>
                                                    <option value="shimmer" {{ $user->username_animation === 'shimmer' ? 'selected' : '' }}>Shimmer (Metallic shine)</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Theme Gradient Presets -->
                                        <div class="relative border border-slate-200 dark:border-slate-800 rounded-xl p-3 bg-white dark:bg-slate-900 transition-all text-left">
                                            <label class="text-[8px] font-black text-slate-400 dark:text-slate-550 uppercase tracking-widest absolute top-1.5 left-3">
                                                Choose Theme Gradient
                                            </label>
                                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 mt-2">
                                                <label class="cursor-pointer flex items-center justify-between p-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 hover:bg-slate-100 dark:hover:bg-slate-850 transition-all">
                                                    <input type="radio" name="banner_color" value="linear-gradient(135deg, #6366f1, #a855f7)" {{ $user->banner_color === 'linear-gradient(135deg, #6366f1, #a855f7)' ? 'checked' : '' }} class="mr-1 text-blue-600 focus:ring-blue-500">
                                                    <span class="w-5 h-5 rounded bg-gradient-to-r from-indigo-500 to-purple-500 shadow-inner"></span>
                                                </label>

                                                <label class="cursor-pointer flex items-center justify-between p-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 hover:bg-slate-100 dark:hover:bg-slate-850 transition-all">
                                                    <input type="radio" name="banner_color" value="linear-gradient(135deg, #ec4899, #8b5cf6)" {{ $user->banner_color === 'linear-gradient(135deg, #ec4899, #8b5cf6)' ? 'checked' : '' }} class="mr-1 text-pink-600 focus:ring-pink-500">
                                                    <span class="w-5 h-5 rounded bg-gradient-to-r from-pink-500 to-violet-500 shadow-inner"></span>
                                                </label>

                                                <label class="cursor-pointer flex items-center justify-between p-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 hover:bg-slate-100 dark:hover:bg-slate-850 transition-all">
                                                    <input type="radio" name="banner_color" value="linear-gradient(135deg, #f97316, #ef4444)" {{ $user->banner_color === 'linear-gradient(135deg, #f97316, #ef4444)' ? 'checked' : '' }} class="mr-1 text-orange-600 focus:ring-orange-500">
                                                    <span class="w-5 h-5 rounded bg-gradient-to-r from-orange-500 to-red-500 shadow-inner"></span>
                                                </label>

                                                <label class="cursor-pointer flex items-center justify-between p-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 hover:bg-slate-100 dark:hover:bg-slate-850 transition-all">
                                                    <input type="radio" name="banner_color" value="linear-gradient(135deg, #06b6d4, #3b82f6)" {{ $user->banner_color === 'linear-gradient(135deg, #06b6d4, #3b82f6)' ? 'checked' : '' }} class="mr-1 text-cyan-600 focus:ring-cyan-500">
                                                    <span class="w-5 h-5 rounded bg-gradient-to-r from-cyan-500 to-blue-500 shadow-inner"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Custom Status Message -->
                                        <div class="relative border border-slate-200 dark:border-slate-800 focus-within:border-blue-500 rounded-xl p-3 bg-white dark:bg-slate-900 transition-all text-left">
                                            <label for="status" class="text-[8px] font-black text-slate-400 dark:text-slate-550 uppercase tracking-widest absolute top-1.5 left-3">Status Message</label>
                                            <input type="text" id="status" name="status" value="{{ old('status', $user->status) }}" class="w-full mt-2 bg-transparent border-0 p-0 text-slate-800 dark:text-white text-xs font-semibold focus:outline-none focus:ring-0 placeholder:text-slate-400" placeholder="What are you doing today?">
                                        </div>

                                        <!-- Custom Status Image -->
                                        <div class="relative border border-slate-200 dark:border-slate-800 focus-within:border-blue-500 rounded-xl p-3 bg-white dark:bg-slate-900 transition-all text-left">
                                            <label for="status_image" class="text-[8px] font-black text-slate-400 dark:text-slate-550 uppercase tracking-widest absolute top-1.5 left-3">Status Image Attachment</label>
                                            <div class="mt-2 flex flex-col gap-2">
                                                <input type="file" id="status_image" name="status_image" accept="image/*" class="block w-full text-[10px] text-slate-550 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all cursor-pointer">
                                                @if($user->status_image)
                                                    <div class="flex items-center gap-2 mt-1">
                                                        <img src="{{ $user->status_image }}" class="w-10 h-10 rounded-lg object-cover border border-slate-200">
                                                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                                            <input type="checkbox" name="remove_status_image" value="1" class="rounded text-blue-600 focus:ring-blue-500 scale-90">
                                                            <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Remove Status Image</span>
                                                        </label>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Custom Signature -->
                                        <div class="relative border border-slate-200 dark:border-slate-800 focus-within:border-blue-500 rounded-xl p-3 bg-white dark:bg-slate-900 transition-all text-left">
                                            <label for="signature" class="text-[8px] font-black text-slate-400 dark:text-slate-550 uppercase tracking-widest absolute top-1.5 left-3">Signature Quote</label>
                                            <textarea id="signature" name="signature" rows="2" class="w-full mt-2 bg-transparent border-0 p-0 text-slate-800 dark:text-white text-xs font-semibold focus:outline-none focus:ring-0 placeholder:text-slate-400 resize-none" placeholder="Write a short custom signature quote...">{{ old('signature', $user->signature) }}</textarea>
                                        </div>

                                        <!-- Profile Privacy -->
                                        <div class="p-3.5 rounded-xl border border-slate-100 dark:border-slate-850 bg-slate-50/50 dark:bg-slate-950/20">
                                            <div class="flex items-center justify-between">
                                                <div class="text-left">
                                                    <h4 class="text-xs font-bold text-slate-800 dark:text-white uppercase tracking-wider flex items-center gap-1">
                                                        <span class="material-symbols-outlined text-xs text-blue-600">visibility</span>
                                                        Profile Privacy
                                                    </h4>
                                                    <p class="text-[9px] font-medium text-slate-450 dark:text-slate-500">Hide activity details from other community members</p>
                                                </div>
                                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                                    <input type="checkbox" name="is_private" value="1" {{ $user->is_private ? 'checked' : '' }} class="sr-only peer">
                                                    <div class="w-9 h-5 bg-slate-200 dark:bg-slate-850 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                                                </label>
                                            </div>
                                        </div>

                                        @if(Auth::id() === $user->id && $threads->isNotEmpty())
                                            <!-- Feature Thread Section -->
                                            <div class="border-t border-slate-100 dark:border-slate-850 pt-4 space-y-2 text-left">
                                                <h4 class="text-xs font-bold text-slate-800 dark:text-white uppercase tracking-wider flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-xs text-amber-500">star</span>
                                                    Feature one of your discussions (50 Coins)
                                                </h4>
                                                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                                                    <select id="feature_thread_id" class="flex-grow bg-slate-50 dark:bg-slate-955 border border-slate-200 dark:border-slate-800 rounded-lg px-3 py-2 text-slate-805 dark:text-white text-xs font-semibold focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all">
                                                        <option value="">-- Choose a thread to feature --</option>
                                                        @foreach($threads as $t)
                                                            @if(!$t->is_featured)
                                                                <option value="{{ $t->id }}">{{ $t->title }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <button type="button" onclick="featureThreadFromProfile()" class="px-4 py-2 rounded-lg bg-amber-500 hover:bg-amber-605 text-white font-bold text-xs shadow-sm cursor-pointer flex items-center justify-center gap-1.5 flex-shrink-0">
                                                        <span class="material-symbols-outlined text-xs">star</span> Feature Thread
                                                    </button>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Save button -->
                                        <div class="text-right pt-2 border-t border-slate-100 dark:border-slate-850">
                                            <button type="submit" class="xen-button text-xs font-bold text-white px-5 py-2 rounded-lg shadow-sm cursor-pointer">
                                                Save Changes
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Right Column: Reputation Rank & Social Grid (4 Cols) -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Premium Clickable Rank Progress Widget -->
                <div onclick="openRoadmapModal()" class="border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-900 rounded-xl p-5 shadow-sm relative overflow-hidden text-left cursor-pointer hover:shadow-md hover:border-blue-300 dark:hover:border-blue-800 transition-all group">
                    <div class="absolute -right-8 -top-8 w-20 h-20 rounded-full blur-xl pointer-events-none opacity-10" style="background-color: {{ $tier['color'] }}"></div>
                    <div class="space-y-3">
                        <div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[8px] font-extrabold uppercase tracking-widest text-slate-500 bg-slate-50 dark:bg-slate-850 dark:text-slate-400 border border-slate-200/50 dark:border-slate-800">
                                <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $tier['color'] }}"></span> Reputation Level
                            </span>
                            <h3 class="text-lg font-black text-slate-800 dark:text-white tracking-tight flex items-center gap-1.5 mt-1">
                                <span style="color: {{ $tier['color'] }}">{{ $tier['name'] }}</span>
                                <span class="px-1.5 py-0.2 text-[8px] font-extrabold uppercase rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-250 dark:border-slate-700">Lvl {{ $tier['level'] ?? 1 }}</span>
                            </h3>
                        </div>
                        
                        <div class="leading-none">
                            <span class="text-2xl font-black tracking-tight" style="color: {{ $tier['color'] }}">{{ number_format($coins) }}</span>
                            <span class="text-[8px] font-black text-slate-400 tracking-widest uppercase ml-1">COINS</span>
                        </div>

                        @if($nextMilestone && $nextMilestone->level !== $currentMilestone->level)
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between text-[8px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                                    <span>Next: {{ $nextMilestone->name }}</span>
                                    <span>{{ $percent }}%</span>
                                </div>
                                <div class="w-full h-2 rounded-full bg-slate-100 dark:bg-slate-950 border border-slate-200/50 dark:border-slate-800 overflow-hidden p-0.5 shadow-inner">
                                    <div class="h-full rounded-full transition-all duration-700" style="width: {{ $percent }}%; background-color: {{ $tier['color'] }}"></div>
                                </div>
                                <div class="text-[8px] text-blue-605 dark:text-blue-400 font-extrabold flex items-center gap-0.5 justify-center mt-1 group-hover:translate-x-0.5 transition-transform">
                                    <span>Journey Roadmap →</span>
                                </div>
                            </div>
                        @else
                            <div class="p-2.5 bg-rose-50 border border-rose-100 rounded-lg text-[9px] text-rose-800 font-bold text-center dark:bg-rose-955/20 dark:text-rose-400 dark:border-rose-900/30">
                                👑 pirate king peak achieved!
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Community Connections Widget -->
                <div class="border border-slate-250 dark:border-slate-800 bg-white dark:bg-slate-900 rounded-xl p-5 shadow-sm space-y-4">
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 dark:text-slate-300 uppercase tracking-wider flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-sm text-blue-500">group</span>
                            Network
                        </h4>
                    </div>

                    <!-- Following -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-[9px] font-extrabold uppercase text-slate-405 dark:text-slate-500 tracking-wider">Following ({{ $user->following()->count() }})</span>
                        </div>
                        @php $following = $user->following()->latest()->take(8)->get(); @endphp
                        @if($following->isNotEmpty())
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($following as $f)
                                    <a href="{{ route('profile.show', $f->name) }}" class="w-8 h-8 rounded-lg overflow-hidden border border-slate-205 dark:border-slate-800 block hover:scale-105 transition-transform" title="{{ $f->name }}">
                                        <img src="{{ $f->avatar_url }}" class="w-full h-full object-cover" alt="{{ $f->name }}">
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 font-medium italic">Not following anyone yet.</p>
                        @endif
                    </div>

                    <!-- Followers -->
                    <div class="space-y-2 pt-3 border-t border-slate-100 dark:border-slate-850">
                        <div class="flex items-center justify-between">
                            <span class="text-[9px] font-extrabold uppercase text-slate-405 dark:text-slate-500 tracking-wider">Followers ({{ $user->followers()->count() }})</span>
                        </div>
                        @php $followers = $user->followers()->latest()->take(8)->get(); @endphp
                        @if($followers->isNotEmpty())
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($followers as $f)
                                    <a href="{{ route('profile.show', $f->name) }}" class="w-8 h-8 rounded-lg overflow-hidden border border-slate-205 dark:border-slate-800 block hover:scale-105 transition-transform" title="{{ $f->name }}">
                                        <img src="{{ $f->avatar_url }}" class="w-full h-full object-cover" alt="{{ $f->name }}">
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 font-medium italic">No followers yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Tab Switcher script -->
<script>
    function switchTab(tabName) {
        // Hide all tab content
        document.querySelectorAll('.profile-tab-content').forEach(el => el.classList.add('hidden'));
        
        // Reset tab buttons to inactive state
        document.querySelectorAll('.profile-tab-btn').forEach(el => {
            el.classList.remove('border-blue-600', 'text-blue-600', 'dark:border-blue-500', 'dark:text-blue-400');
            el.classList.add('border-transparent', 'text-slate-500');
        });

        // Show target tab content
        const targetContent = document.getElementById(`tab-${tabName}-content`);
        if (targetContent) {
            targetContent.classList.remove('hidden');
        }

        // Set target tab button to active state
        const targetBtn = document.getElementById(`tab-${tabName}-btn`);
        if (targetBtn) {
            targetBtn.classList.remove('border-transparent', 'text-slate-500');
            targetBtn.classList.add('border-blue-600', 'text-blue-600', 'dark:border-blue-500', 'dark:text-blue-400');
        }

        // Persist tab state
        localStorage.setItem('profile_active_tab', tabName);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const savedTab = localStorage.getItem('profile_active_tab') || 'discussions';
        const tabBtn = document.getElementById(`tab-${savedTab}-btn`);
        if (tabBtn) {
            switchTab(savedTab);
        } else {
            switchTab('discussions');
        }
    });
</script>

@auth
    <!-- Status Editing Script -->
    <script>
        function editStatusInline() {
            const currentStatus = @json($user->status);
            const currentStatusImage = @json($user->status_image);
            
            Swal.fire({
                title: 'Update Status',
                html: `
                    <div class="text-left space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Status Message (max 255 chars)</label>
                            <input id="swal-status-text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 text-slate-850 dark:text-white font-semibold" placeholder="What are you doing today?" value="${currentStatus || ''}">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Status Image</label>
                            <input id="swal-status-image" type="file" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                        </div>
                        <div id="swal-image-preview-container" class="mt-2 ${currentStatusImage ? '' : 'hidden'}">
                            <span class="block text-[9px] font-black text-slate-450 dark:text-slate-500 uppercase tracking-widest mb-1">Preview</span>
                            <div class="relative w-24 h-24 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-800">
                                <img id="swal-image-preview" src="${currentStatusImage || ''}" class="w-full h-full object-cover">
                            </div>
                            ${currentStatusImage ? `
                            <label class="inline-flex items-center gap-1.5 mt-2 cursor-pointer select-none">
                                <input type="checkbox" id="swal-remove-image" class="rounded text-blue-600 focus:ring-blue-500 scale-90">
                                <span class="text-xs text-slate-500 font-semibold">Remove Image</span>
                            </label>
                            ` : ''}
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Save Status',
                didOpen: () => {
                    const fileInput = document.getElementById('swal-status-image');
                    const previewContainer = document.getElementById('swal-image-preview-container');
                    const previewImg = document.getElementById('swal-image-preview');
                    
                    fileInput.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(evt) {
                                previewImg.src = evt.target.result;
                                previewContainer.classList.remove('hidden');
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                },
                preConfirm: () => {
                    const statusText = document.getElementById('swal-status-text').value;
                    const fileInput = document.getElementById('swal-status-image');
                    const removeCheckbox = document.getElementById('swal-remove-image');
                    
                    if (statusText.length > 255) {
                        Swal.showValidationMessage('Status message cannot exceed 255 characters!');
                        return false;
                    }
                    
                    return {
                        status: statusText,
                        image: fileInput.files[0],
                        remove_image: removeCheckbox ? removeCheckbox.checked : false
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('status', result.value.status);
                    if (result.value.image) {
                        formData.append('status_image', result.value.image);
                    }
                    if (result.value.remove_image) {
                        formData.append('remove_status_image', '1');
                    }
                    
                    // Show updating loader
                    Swal.fire({
                        title: 'Updating status...',
                        didOpen: () => {
                            Swal.showLoading();
                        },
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false
                    });
                    
                    fetch('{{ route("profile.update_status") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Failed to update status');
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Status Updated',
                                text: 'Your status has been updated successfully!',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true
                            }).then(() => {
                                window.location.reload();
                            });
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Update Failed',
                            text: 'Could not update status. Please try again.',
                            confirmButtonColor: '#3b82f6'
                        });
                    });
                }
            });
        }
    </script>

    @if(Auth::id() === $user->id)
        <!-- JS Live Profile/Avatar/Banner Previewer & Save Scroller Cue -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const avatarInput = document.getElementById('avatar');
                const bannerInput = document.getElementById('banner');
                const profileForm = document.getElementById('profile-form');
                const submitBtn = profileForm ? profileForm.querySelector('button[type="submit"]') : null;

                function highlightSaveButton() {
                    if (submitBtn) {
                        submitBtn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
                        submitBtn.classList.add('animate-pulse');
                        submitBtn.innerText = '💾 Save Changes!';
                    }
                }

                if (avatarInput) {
                    avatarInput.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file && file.type.startsWith('image/')) {
                            const objectUrl = URL.createObjectURL(file);
                            const imgEl = document.getElementById('profile-avatar-img');
                            const placeholderEl = document.getElementById('profile-avatar-placeholder');
                            
                            if (imgEl) {
                                imgEl.src = objectUrl;
                                imgEl.classList.remove('hidden');
                            }
                            if (placeholderEl) {
                                placeholderEl.classList.add('hidden');
                            }
                            highlightSaveButton();
                        }
                    });
                }

                if (bannerInput) {
                    bannerInput.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file && file.type.startsWith('image/')) {
                            const objectUrl = URL.createObjectURL(file);
                            const bannerBg = document.getElementById('profile-banner-bg');
                            if (bannerBg) {
                                bannerBg.style.backgroundImage = `url('${objectUrl}')`;
                            }
                            highlightSaveButton();
                        }
                    });
                }

                // Username color reset control
                const colorInput = document.getElementById('profile-color-input');
                const colorReset = document.getElementById('profile-color-reset');
                const colorHidden = document.getElementById('profile-color-hidden-input');
                const animSelect = document.getElementById('profile-anim-select');

                function updateProfileColorsAndInputs() {
                    if (colorReset.checked) {
                        colorInput.disabled = true;
                        colorInput.style.opacity = '0.5';
                        colorHidden.value = '';
                    } else {
                        colorInput.disabled = false;
                        colorInput.style.opacity = '1';
                        colorHidden.value = colorInput.value;
                    }
                    highlightSaveButton();
                }

                if (colorInput && colorReset && colorHidden) {
                    colorInput.addEventListener('input', updateProfileColorsAndInputs);
                    colorReset.addEventListener('change', updateProfileColorsAndInputs);
                    updateProfileColorsAndInputs();
                }

                if (animSelect) {
                    animSelect.addEventListener('change', highlightSaveButton);
                }

                if (profileForm) {
                    profileForm.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const currentCoins = @json(Auth::user()->coins);
                        const isAdmin = @json(Auth::user()->isAdmin());
                        const origColor = @json(Auth::user()->title_color);
                        const origAnim = @json(Auth::user()->username_animation ?: 'none');
                        const hasBannerBefore = @json(Auth::user()->banner_updates_count >= 1);

                        const isResetChecked = colorReset.checked;
                        const chosenColor = isResetChecked ? null : colorInput.value;
                        const chosenAnim = animSelect.value;

                        const bannerFile = document.getElementById('banner');
                        const hasNewBanner = bannerFile && bannerFile.files && bannerFile.files.length > 0;

                        let bannerCost = 0;
                        if (hasNewBanner && hasBannerBefore && !isAdmin) {
                            bannerCost = 50;
                        }

                        let styleCost = 0;
                        const normalizedOrigColor = origColor ? origColor.toLowerCase() : null;
                        const normalizedChosenColor = chosenColor ? chosenColor.toLowerCase() : null;

                        if (normalizedChosenColor !== normalizedOrigColor) {
                            styleCost += 100;
                        }

                        const normalizedOrigAnim = origAnim === 'none' ? 'none' : origAnim;
                        const normalizedChosenAnim = chosenAnim === 'none' ? 'none' : chosenAnim;

                        if (normalizedChosenAnim !== normalizedOrigAnim) {
                            styleCost += 500;
                        }

                        const totalCost = bannerCost + styleCost;

                        if (totalCost > currentCoins && !isAdmin) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Insufficient Coins',
                                text: `You do not have enough coins to apply these changes. Required: ${totalCost} coins, Balance: ${currentCoins} coins.`,
                                confirmButtonColor: '#3b82f6'
                            });
                            return;
                        }

                        const username = @json(Auth::user()->name);
                        let displayStyle = '';
                        if (chosenColor) {
                            displayStyle += `color: ${chosenColor} !important;`;
                        }

                        let animClass = '';
                        if (chosenAnim === 'glow') animClass = 'animate-glow';
                        else if (chosenAnim === 'pulse') animClass = 'animate-pulse';
                        else if (chosenAnim === 'crackle') animClass = 'animate-bolt';
                        else if (chosenAnim === 'shimmer') animClass = 'animate-shimmer';

                        let costBreakdownHtml = '';
                        if (totalCost > 0) {
                            costBreakdownHtml = `
                                <div style="margin-top: 15px; padding: 10px; border-radius: 12px; background: #f8fafc; font-size: 11px; text-align: left; border: 1px solid #e2e8f0; color: #475569;">
                                    <b style="display:block; margin-bottom: 5px; color: #1e293b; font-size: 12px;">Coin Usage Summary</b>
                                    ${bannerCost > 0 ? `<div style="display:flex; justify-content:space-between; margin-bottom: 3px;"><span>Cover Photo Update:</span> <b>50 Coins</b></div>` : ''}
                                    ${styleCost > 0 && normalizedChosenColor !== normalizedOrigColor ? `<div style="display:flex; justify-content:space-between; margin-bottom: 3px;"><span>Username Color:</span> <b>100 Coins</b></div>` : ''}
                                    ${styleCost > 0 && normalizedChosenAnim !== normalizedOrigAnim ? `<div style="display:flex; justify-content:space-between; margin-bottom: 3px;"><span>Username Animation:</span> <b>500 Coins</b></div>` : ''}
                                    <div style="margin-top:5px; border-top:1px solid #e2e8f0; padding-top:5px; display:flex; justify-content:space-between; font-weight:bold; color:#0f172a;">
                                        <span>Total Cost:</span> <span>${totalCost} Coins</span>
                                    </div>
                                </div>
                            `;
                        } else {
                            costBreakdownHtml = `<div style="margin-top: 10px; font-size: 11px; color: #10b981; font-weight: bold; text-align: center;">✓ This update is free!</div>`;
                        }

                        const previewHtml = `
                            <div style="font-family: inherit;">
                                <p style="font-size: 12px; color: #64748b; margin-bottom: 15px; text-align: center;">Here is how your styled username will look across the community:</p>
                                <div style="padding: 20px; border-radius: 16px; background: #0f172a; text-align: center; margin-bottom: 15px; box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06);">
                                    <span class="text-xl font-black tracking-tight ${animClass}" style="${displayStyle}">${username}</span>
                                </div>
                                ${costBreakdownHtml}
                            </div>
                        `;

                        Swal.fire({
                            title: 'Preview Username Style',
                            html: previewHtml,
                            showCancelButton: true,
                            confirmButtonColor: '#3b82f6',
                            cancelButtonColor: '#64748b',
                            confirmButtonText: 'Confirm & Save',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                profileForm.submit();
                            }
                        });
                    });
                }
            });

            function toggleAttachmentPrivacy(attachmentId) {
                const btn = document.getElementById(`privacy-btn-${attachmentId}`);
                const icon = document.getElementById(`privacy-icon-${attachmentId}`);
                if (!btn || !icon) return;

                btn.disabled = true;

                fetch(`/media/${attachmentId}/toggle-privacy`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Toggle privacy failed.');
                    return response.json();
                })
                .then(data => {
                    btn.disabled = false;
                    if (data.success) {
                        if (data.is_private) {
                            icon.innerText = 'lock';
                            btn.title = 'Make Public';
                            Swal.fire({
                                icon: 'info',
                                title: 'Privacy Updated',
                                text: 'This image is now Private and visible only to you.',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });
                        } else {
                            icon.innerText = 'lock_open';
                            btn.title = 'Make Private';
                            Swal.fire({
                                icon: 'success',
                                title: 'Privacy Updated',
                                text: 'This image is now Public and visible to all community members.',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });
                        }
                    }
                })
                .catch(error => {
                    btn.disabled = false;
                    console.error('Privacy Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Action Failed',
                        text: 'Could not change media privacy. Please try again.',
                        confirmButtonColor: '#0f172a'
                    });
                });
            }
        </script>
    @endif
@endauth

@auth
    @if(Auth::id() !== $user->id)
        <!-- Follow System Asynchronous API Controller -->
        <script>
            function toggleFollowUser(username, userId) {
                const btn = document.getElementById(`follow-btn-${userId}`);
                if (!btn) return;

                btn.disabled = true;

                fetch(`/members/${encodeURIComponent(username)}/follow`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Follow toggle failed.');
                    return response.json();
                })
                .then(data => {
                    btn.disabled = false;
                    if (data.success) {
                        if (data.following) {
                            btn.className = "flex-1 sm:flex-none text-xs font-bold py-1.5 px-3.5 rounded-lg transition-all cursor-pointer border flex items-center justify-center gap-1 shadow-sm bg-blue-50 border-blue-200 text-blue-700 hover:bg-rose-50 hover:text-rose-700 hover:border-rose-200 group/follow active:scale-97";
                            btn.innerHTML = `
                                <span class="material-symbols-outlined text-[13px] group-hover/follow:hidden">check</span>
                                <span class="group-hover/follow:hidden">Following</span>
                                <span class="material-symbols-outlined text-[13px] hidden group-hover/follow:inline-block">person_remove</span>
                                <span class="hidden group-hover/follow:inline">Unfollow</span>
                            `;
                        } else {
                            btn.className = "flex-1 sm:flex-none text-xs font-bold py-1.5 px-3.5 rounded-lg transition-all cursor-pointer border flex items-center justify-center gap-1 shadow-sm bg-white border-slate-205 text-slate-700 hover:bg-slate-50 active:scale-97";
                            btn.innerHTML = `
                                <span class="material-symbols-outlined text-[13px]">person_add</span>
                                <span>Follow</span>
                            `;
                        }
                    }
                })
                .catch(error => {
                    btn.disabled = false;
                    console.error('Follow Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Action Failed',
                        text: 'Could not toggle follow status. Please try again.',
                        confirmButtonColor: '#0f172a'
                    });
                });
            }
        </script>
    @endif
@endauth

<!-- Profile Sharing Clipboard Script -->
<script>
    function copyProfileLink() {
        const link = window.location.href;
        navigator.clipboard.writeText(link).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Link Copied',
                text: 'Profile link copied to clipboard!',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true
            });
        }).catch(err => {
            console.error('Clipboard write failed, using fallback:', err);
            const textarea = document.createElement('textarea');
            textarea.value = link;
            document.body.appendChild(textarea);
            textarea.select();
            try {
                document.execCommand('copy');
                Swal.fire({
                    icon: 'success',
                    title: 'Link Copied',
                    text: 'Profile link copied to clipboard!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true
                });
            } catch (e) {
                Swal.fire({
                    icon: 'error',
                    title: 'Copy Failed',
                    text: 'Could not copy link automatically. Please copy the URL from your address bar.',
                    confirmButtonColor: '#0f172a'
                });
            }
            document.body.removeChild(textarea);
        });
    }

    function featureThreadFromProfile() {
        const threadId = document.getElementById('feature_thread_id').value;
        if (!threadId) {
            Swal.fire({
                icon: 'warning',
                title: 'No Thread Selected',
                text: 'Please select one of your threads to feature.',
                confirmButtonColor: '#3b82f6'
            });
            return;
        }

        if (confirm('Spend 50 coins to feature this thread on the homepage?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/threads/${threadId}/feature`;
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            
            form.appendChild(csrfInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection
