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
<div class="space-y-8">
    @php
        $points = $user->activity_points;
        $tier = $user->computed_anime_tier;
        $level = $tier['level'] ?? 1;
        $glowClass = 'border border-slate-200 shadow-lg';
        $avatarGlow = 'border-4 border-white';
        if ($level >= 20) {
            $glowClass = 'border border-rose-500/40 shadow-[0_0_30px_rgba(225,29,72,0.35)] ring-2 ring-rose-500/10';
            $avatarGlow = 'border-4 border-rose-500 shadow-[0_0_20px_rgba(225,29,72,0.6)] ring-4 ring-rose-500/20';
        } elseif ($level >= 16) {
            $glowClass = 'border border-purple-500/40 shadow-[0_0_25px_rgba(124,58,237,0.25)]';
            $avatarGlow = 'border-4 border-purple-500 shadow-[0_0_15px_rgba(124,58,237,0.5)] ring-2 ring-purple-500/20';
        } elseif ($level >= 12) {
            $glowClass = 'border border-amber-500/40 shadow-[0_0_20px_rgba(217,119,6,0.18)]';
            $avatarGlow = 'border-4 border-amber-500 shadow-[0_0_10px_rgba(217,119,6,0.4)]';
        } elseif ($level >= 2) {
            $glowClass = 'border border-blue-500/30 shadow-md';
            $avatarGlow = 'border-4 border-blue-500 shadow-[0_0_8px_rgba(37,99,235,0.3)]';
        }
    @endphp
    <!-- Premium Profile Hero Card -->
    <div class="relative rounded-3xl overflow-hidden bg-white dark:bg-slate-900 transition-all duration-300 {{ $glowClass }}">
        <!-- Dynamic Gradient or Custom Uploaded Image Banner Background -->
        <div id="profile-banner-bg" class="h-44 sm:h-56 relative bg-cover bg-center" style="background: {{ $user->banner_path ? 'url(' . $user->banner_path . ')' : $user->banner_color }}">
            <div class="absolute inset-0 bg-black/10 backdrop-blur-[1px]"></div>
            
            @auth
                @if(Auth::id() === $user->id)
                    <button onclick="document.getElementById('banner').click();" class="absolute top-4 right-4 bg-slate-900/60 hover:bg-slate-900/80 text-white rounded-xl px-3 py-1.5 text-xs font-bold transition-all backdrop-blur-sm border border-white/20 flex items-center gap-1.5 cursor-pointer z-20 shadow-md" title="Edit Cover Photo">
                        <span class="material-symbols-outlined text-sm">photo_camera</span>
                        <span>Edit Cover</span>
                    </button>
                @endif
            @endauth
        </div>

        <!-- User Stats & Info Section -->
        <div class="bg-white p-6 sm:p-8 relative border-t border-slate-200 flex flex-col sm:flex-row items-center sm:items-end justify-between gap-6">
            <!-- User Avatar & Core info -->
            <div class="flex flex-col sm:flex-row items-center sm:items-end gap-5 -mt-20 sm:-mt-24 z-10 text-center sm:text-left">
                <!-- Large Avatar frame -->
                <div class="w-32 h-32 rounded-3xl overflow-hidden {{ $avatarGlow }} bg-slate-50 shadow-lg relative group/avatar">
                    <img id="profile-avatar-img" src="{{ $user->avatar_url }}" class="w-full h-full object-cover" alt="avatar">

                    @auth
                        @if(Auth::id() === $user->id)
                            <div onclick="document.getElementById('avatar').click();" class="absolute inset-0 bg-black/45 opacity-0 group-hover/avatar:opacity-100 transition-all flex flex-col items-center justify-center cursor-pointer text-white z-20 font-bold text-[9px] uppercase tracking-wider" title="Change Avatar">
                                <span class="material-symbols-outlined text-lg mb-0.5">photo_camera</span>
                                <span>Edit</span>
                            </div>
                        @endif
                    @endauth
                </div>

                <div class="space-y-1.5 pb-2">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3">
                        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight {{ $user->username_style }}" style="{{ $user->username_style_css }}">{{ $user->name }}</h2>
                        <span class="text-xs px-3 py-0.5 rounded-full font-bold uppercase tracking-wider shadow-sm" style="color: #ffffff; background: {{ $user->banner_color }}">
                            {{ $user->title_badge }}
                        </span>
                    </div>
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-1.5 text-xs font-bold">
                        <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: {{ $tier['color'] }}">
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
                        <span class="text-[9px] px-1.5 py-0.2 bg-slate-100 dark:bg-slate-800 rounded text-slate-600 dark:text-slate-400 uppercase font-black tracking-wide">{{ $tier['badge'] }}</span>
                    </div>
                    <p class="text-xs text-slate-500 font-bold">Joined community on {{ $user->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            <!-- Quick stats counts & follow button -->
            <div class="flex flex-col items-center sm:items-end gap-3 z-10 pb-2 flex-shrink-0">
                <div class="flex gap-6 sm:gap-8 text-center flex-wrap justify-center sm:justify-end">
                    <div class="w-16 sm:w-20">
                        <span class="block text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">{{ $user->posts()->count() }}</span>
                        <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Posts</span>
                    </div>
                    @php
                        $reactionsCount = \App\Models\React::whereIn('post_id', $user->posts()->pluck('id'))->count();
                        $badgesCount = max(1, min(10, floor($reactionsCount / 100) + floor($user->posts()->count() / 50) + 1));
                        $awardsCount = max(1, min(5, floor($user->coins / 1500) + ($user->isAdmin() ? 3 : 0)));
                    @endphp
                    <div class="w-16 sm:w-20">
                        <span class="block text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">{{ $reactionsCount }}</span>
                        <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Reactions</span>
                    </div>
                    <div class="w-16 sm:w-20">
                        <span class="block text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">{{ $badgesCount }}</span>
                        <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Badges</span>
                    </div>
                    <div class="w-16 sm:w-20">
                        <span class="block text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">{{ $user->activity_points }}</span>
                        <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Points</span>
                    </div>
                    <div class="w-20 sm:w-24">
                        <span class="block text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">{{ number_format($user->coins, 2) }}</span>
                        <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">DF Coins</span>
                    </div>
                    <div class="w-16 sm:w-20">
                        <span class="block text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">{{ $awardsCount }}</span>
                        <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Awards</span>
                    </div>
                </div>

                @auth
                    @if(Auth::id() !== $user->id)
                        <button type="button" 
                                onclick="toggleFollowUser('{{ $user->name }}', '{{ $user->id }}')" 
                                id="follow-btn-{{ $user->id }}" 
                                class="w-full sm:w-44 text-xs font-bold py-1.5 px-3 rounded-xl transition-all cursor-pointer border flex items-center justify-center gap-1.5 shadow-sm
                                {{ Auth::user()->isFollowing($user) 
                                    ? 'bg-blue-50 border-blue-200 text-blue-700 hover:bg-rose-50 hover:text-rose-700 hover:border-rose-200 group/follow active:scale-97' 
                                    : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-100 active:scale-97' }}">
                            @if(Auth::user()->isFollowing($user))
                                <span class="material-symbols-outlined text-[11px] group-hover/follow:hidden">check</span>
                                <span class="group-hover/follow:hidden font-bold">Following</span>
                                <span class="material-symbols-outlined text-[11px] hidden group-hover/follow:inline-block">person_remove</span>
                                <span class="hidden group-hover/follow:inline font-bold">Unfollow</span>
                            @else
                                <span class="material-symbols-outlined text-[11px]">person_add</span>
                                <span class="font-bold">Follow Member</span>
                            @endif
                        </button>

                        <button type="button" 
                                onclick="startDirectChat('{{ $user->name }}')" 
                                class="w-full sm:w-44 text-xs font-bold py-1.5 px-3 rounded-xl transition-all cursor-pointer border border-slate-200 bg-white text-slate-700 hover:bg-slate-100 active:scale-97 flex items-center justify-center gap-1.5 shadow-sm">
                            <span class="material-symbols-outlined text-[11px]">chat</span>
                            <span class="font-bold">Send Message</span>
                        </button>
                    @endif
                @endauth

                <!-- Share Profile Button -->
                <button type="button" 
                        onclick="copyProfileLink()" 
                        class="w-full sm:w-44 text-xs font-bold py-1.5 px-3 rounded-xl transition-all cursor-pointer border border-slate-200 bg-white text-slate-700 hover:bg-slate-100 active:scale-97 flex items-center justify-center gap-1.5 shadow-sm"
                        title="Copy profile link to clipboard">
                    <span class="material-symbols-outlined text-[14px]">share</span>
                    <span class="font-bold">Share Profile</span>
                </button>
            </div>
        </div>

        <!-- Signature quote if it exists -->
        @if($user->signature)
            <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 text-xs text-slate-650 italic text-center sm:text-left font-medium">
                💬 "{{ $user->signature }}"
            </div>
        @endif
    </div>
    @php
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
    <!-- Premium Clickable Rank Progress Widget -->
    <div onclick="openRoadmapModal()" class="border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-md relative overflow-hidden text-left cursor-pointer hover:shadow-lg hover:border-blue-300 dark:hover:border-blue-800 transition-all group">
        <div class="absolute -right-8 -top-8 w-24 h-24 rounded-full blur-xl pointer-events-none opacity-10" style="background-color: {{ $tier['color'] }}"></div>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-extrabold uppercase tracking-widest text-slate-500 bg-slate-100 dark:bg-slate-850 dark:text-slate-400 border border-slate-200/50 dark:border-slate-800">
                    <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $tier['color'] }}"></span> Otaku Reputation Level
                </span>
                <h3 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white tracking-tight flex items-center gap-2">
                    <span style="color: {{ $tier['color'] }}">{{ $tier['name'] }}</span>
                    <span class="px-2 py-0.5 text-[9px] font-extrabold uppercase rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">Level {{ $tier['level'] ?? 1 }}</span>
                </h3>
            </div>
            <div class="text-left sm:text-right leading-none flex-shrink-0">
                <span class="text-3xl font-black tracking-tight" style="color: {{ $tier['color'] }}">{{ number_format($coins) }}</span>
                <span class="text-[9px] font-black text-slate-400 block tracking-widest uppercase mt-0.5">COINS</span>
            </div>
        </div>

        @if($nextMilestone && $nextMilestone->level !== $currentMilestone->level)
            <!-- Progress Bar to next rank -->
            <div class="mt-4 space-y-2">
                <div class="flex items-center justify-between text-[9px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                    <span>Next Rank: {{ $nextMilestone->name }} {{ $nextMilestone->icon }} (Lvl {{ $nextMilestone->level }})</span>
                    <span>{{ $percent }}%</span>
                </div>
                <div class="w-full h-3.5 rounded-full bg-slate-100 dark:bg-slate-950 border border-slate-200/50 dark:border-slate-800 overflow-hidden p-0.5 shadow-inner">
                    <div class="h-full rounded-full transition-all duration-700" style="width: {{ $percent }}%; background-color: {{ $tier['color'] }}"></div>
                </div>
                <div class="flex items-center justify-between text-[9px] font-bold text-slate-400 mt-1">
                    <span>{{ number_format($currentMilestone->coins_required) }} Coins</span>
                    <span class="text-blue-600 dark:text-blue-400 font-extrabold flex items-center gap-1 group-hover:translate-x-1 transition-transform">Tap to view journey roadmap →</span>
                    <span>{{ number_format($target) }} coins required</span>
                </div>
            </div>
        @else
            <div class="mt-4 p-3.5 bg-rose-50 border border-rose-200/30 rounded-2xl text-[10px] text-rose-800 font-bold text-center dark:bg-rose-950/20 dark:text-rose-450 dark:border-rose-900/30">
                👑 Congratulations! You have achieved the peak Legendary Rank of Pirate King!
            </div>
        @endif
    </div>

    <!-- Main Content Panels -->
    @if($isProfilePrivate)
        <div class="max-w-xl mx-auto my-12 bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden p-8 sm:p-12 text-center space-y-6">
            <div class="w-20 h-20 mx-auto bg-slate-100 text-slate-400 rounded-3xl flex items-center justify-center shadow-inner">
                <span class="material-symbols-outlined text-4xl">lock</span>
            </div>
            <div class="space-y-2">
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">This Profile is Private</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto font-bold leading-relaxed">
                    {{ $user->name }} has chosen to keep their profile activity private.
                </p>
            </div>
            <div class="pt-2">
                <a href="{{ route('home') }}" class="xen-button text-xs font-bold text-white px-6 py-2.5 rounded-xl shadow-md cursor-pointer inline-flex items-center gap-1.5 hover:opacity-90">
                    <span class="material-symbols-outlined text-sm">home</span>
                    <span>Return to Discussions</span>
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Section: Threads & Customization Forms (8 Cols) -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Customization Panel (Shown only to the owner) -->
                @auth
                    @if(Auth::id() === $user->id)
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
                            <div id="shop-upgrades-card" class="mui-card overflow-hidden bg-white border border-slate-200 shadow-lg rounded-2xl mb-8 text-left">
                                <div class="bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-slate-900 dark:to-slate-850 px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                                    <h3 class="font-bold text-slate-800 dark:text-white text-xs flex items-center gap-2">
                                        <span class="material-symbols-outlined text-emerald-600 text-sm">shopping_bag</span>
                                        My Purchased Shop Upgrades
                                    </h3>
                                </div>
                                <div class="p-6 space-y-6">
                                    <!-- List of upgrades -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach($purchasedItems as $purchase)
                                            <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 dark:bg-slate-950/20 dark:border-slate-850 flex items-start gap-3 justify-between">
                                                <div class="min-w-0">
                                                    <span class="inline-block text-[8px] font-black uppercase bg-emerald-100 dark:bg-emerald-955/50 text-emerald-700 dark:text-emerald-400 px-1.5 py-0.5 rounded leading-none">Active Upgrade</span>
                                                    <h4 class="text-xs font-black text-slate-850 dark:text-white mt-1">{{ $purchase->shopItem->name }}</h4>
                                                    <p class="text-[10px] text-slate-400 mt-0.5 font-bold">
                                                        Expires: {{ $purchase->expires_at ? $purchase->expires_at->format('M d, Y') : 'Never (Permanent)' }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Action Upgrades Controls -->
                                    <div class="border-t border-slate-100 dark:border-slate-800 pt-5 space-y-5">
                                        <h4 class="text-xs font-extrabold text-slate-800 dark:text-white uppercase tracking-wider">Configure Upgrades</h4>
                                        
                                        @if($hasUsernameChange)
                                            <!-- Username change upgrade action -->
                                            <form action="{{ route('profile.update_username') }}" method="POST" class="p-4 rounded-xl border border-slate-200 dark:border-slate-850 space-y-3">
                                                @csrf
                                                <div>
                                                    <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider">Change Username (Purchased Upgrade)</label>
                                                    <input type="text" name="name" required value="{{ $user->name }}" class="w-full mt-2 bg-slate-50 dark:bg-slate-955 border border-slate-200 dark:border-slate-850 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-1 focus:ring-blue-550">
                                                </div>
                                                <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md transition-all cursor-pointer">
                                                    Update Username
                                                </button>
                                            </form>
                                        @endif

                                        @if($hasUsernameStyle)
                                            <!-- Username style customization -->
                                            <form action="{{ route('profile.update_username_style') }}" method="POST" class="p-4 rounded-xl border border-slate-200 dark:border-slate-850 space-y-4">
                                                @csrf
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider">Text/Badge Color</label>
                                                        <input type="color" name="title_color" value="{{ $user->title_color ?: '#4f46e5' }}" class="w-10 h-10 border-0 rounded-lg cursor-pointer mt-2">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider">Title Badge Text (Custom)</label>
                                                        <input type="text" name="title_badge" value="{{ $user->title_badge ?: 'VIP Member' }}" class="w-full mt-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-850 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                                    </div>
                                                </div>
                                                <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md transition-all cursor-pointer">
                                                    Apply Custom Styles
                                                </button>
                                            </form>
                                        @endif

                                        @if(($hasTitleStyle || $hasHighlight || $hasFeaturedThread || $hasStickyUpgrade) && $threads->isNotEmpty())
                                            <!-- Thread styles upgrade actions -->
                                            <form action="{{ route('profile.update_thread_upgrades') }}" method="POST" class="p-4 rounded-xl border border-slate-200 dark:border-slate-850 space-y-4">
                                                @csrf
                                                <div class="space-y-3">
                                                    <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider">Apply Upgrades to Your Threads</label>
                                                    <select name="thread_id" required class="w-full bg-slate-50 dark:bg-slate-955 border border-slate-200 dark:border-slate-850 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                                        <option value="">-- Select one of your threads --</option>
                                                        @foreach($threads as $t)
                                                            <option value="{{ $t->id }}">{{ $t->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="flex flex-wrap gap-4 text-xs font-bold text-slate-650 dark:text-slate-350">
                                                    @if($hasTitleStyle)
                                                        <label class="flex items-center gap-2">
                                                            <input type="checkbox" name="apply_title_style" value="1" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                                            <span>Thread Title Style (Glow Effect)</span>
                                                        </label>
                                                    @endif
                                                    @if($hasHighlight)
                                                        <label class="flex items-center gap-2">
                                                            <input type="checkbox" name="apply_highlight" value="1" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                                            <span>Thread Highlight (Colored Box)</span>
                                                        </label>
                                                    @endif
                                                    @if($hasFeaturedThread)
                                                        <label class="flex items-center gap-2">
                                                            <input type="checkbox" name="apply_featured" value="1" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                                            <span>Homepage Featured Slider</span>
                                                        </label>
                                                    @endif
                                                    @if($hasStickyUpgrade)
                                                        <label class="flex items-center gap-2">
                                                            <input type="checkbox" name="apply_sticky" value="1" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                                            <span>Sticky (Pin to Top)</span>
                                                        </label>
                                                    @endif
                                                </div>
                                                <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md transition-all cursor-pointer">
                                                    Apply Thread Upgrades
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="mui-card overflow-hidden bg-white border border-slate-200 shadow-lg rounded-2xl">
                            <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                                <h3 class="font-bold text-slate-700 text-xs flex items-center gap-2">
                                    <span class="material-symbols-outlined text-blue-600 text-sm">settings</span>
                                    Customize Profile Card
                                </h3>
                            </div>
                            <form id="profile-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5 bg-white">
                                @csrf
                                        <!-- Material Design Upload & Input Grid -->
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                    <!-- Avatar Upload -->
                                    <div class="relative border border-slate-200 focus-within:border-blue-500 rounded-2xl p-4 bg-slate-50/50 hover:bg-slate-50 transition-all flex flex-col justify-center text-left">
                                        <label for="avatar" class="text-[9px] font-black text-slate-400 uppercase tracking-widest absolute top-1.5 left-4">Upload New Avatar</label>
                                        <input type="file" id="avatar" name="avatar" class="block w-full text-xs text-slate-550 mt-2.5 file:mr-3 file:py-1 file:px-2.5 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                    </div>
                                    <!-- Banner Upload -->
                                    <div class="relative border border-slate-200 focus-within:border-blue-500 rounded-2xl p-4 bg-slate-50/50 hover:bg-slate-50 transition-all flex flex-col justify-center text-left">
                                        <label for="banner" class="text-[9px] font-black text-slate-400 uppercase tracking-widest absolute top-1.5 left-4 flex items-center gap-1">
                                            Upload Cover Photo
                                            @if($user->banner_updates_count >= 1 && !$user->isAdmin())
                                                <span class="text-[8px] text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded font-black">💰 Costs 50 Coins</span>
                                            @endif
                                        </label>
                                        <input type="file" id="banner" name="banner" class="block w-full text-xs text-slate-550 mt-2.5 file:mr-3 file:py-1 file:px-2.5 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                    </div>
                                    <!-- Custom title badge -->
                                    <div class="relative border border-slate-200 focus-within:border-blue-500 rounded-2xl p-4 bg-white transition-all text-left">
                                        <label for="title_badge" class="text-[9px] font-black text-slate-400 uppercase tracking-widest absolute top-1.5 left-4 flex items-center gap-1">
                                            Custom Title Badge
                                            @if($tier['level'] < 20 && !$user->isAdmin())
                                                <span class="text-[8px] text-amber-605 bg-amber-50 px-1 py-0.5 rounded font-black">🔒 PK (Lvl 20)</span>
                                            @endif
                                        </label>
                                        <input type="text" id="title_badge" name="title_badge" {{ ($tier['level'] < 20 && !$user->isAdmin()) ? 'disabled' : '' }} value="{{ old('title_badge', $user->title_badge) }}" class="w-full mt-2.5 bg-transparent border-0 p-0 text-slate-800 text-xs font-semibold focus:outline-none focus:ring-0 placeholder:text-slate-350" placeholder="{{ ($tier['level'] < 20 && !$user->isAdmin()) ? 'Locked' : 'Guru, Wizard, Ninja...' }}">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <!-- Username Color -->
                                    <div class="relative border border-slate-200 focus-within:border-blue-500 rounded-2xl p-4 bg-white transition-all text-left">
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest absolute top-1.5 left-4 flex items-center gap-1">
                                            Username Color
                                            @if(!$user->isAdmin())
                                                <span class="text-[8px] text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded font-black">💰 100 Coins</span>
                                            @endif
                                        </label>
                                        <input type="hidden" name="title_color" id="profile-color-hidden-input" value="{{ $user->title_color }}">
                                        <div class="flex items-center gap-2.5 mt-2.5">
                                            <div class="flex items-center gap-1.5">
                                                <input type="checkbox" id="profile-color-reset" class="rounded border-slate-300 text-indigo-650 focus:ring-indigo-500" {{ !$user->title_color ? 'checked' : '' }}>
                                                <label for="profile-color-reset" class="text-[10px] text-slate-500 font-bold cursor-pointer">Default</label>
                                            </div>
                                            <input type="color" id="profile-color-input" value="{{ $user->title_color ?: '#4f46e5' }}" class="w-8 h-8 border-0 rounded-lg cursor-pointer">
                                            <span class="text-[10px] text-slate-400 font-bold">Pick color</span>
                                        </div>
                                    </div>

                                    <!-- Username Animation -->
                                    <div class="relative border border-slate-200 focus-within:border-blue-500 rounded-2xl p-4 bg-white transition-all text-left">
                                        <label for="username_animation" class="text-[9px] font-black text-slate-400 uppercase tracking-widest absolute top-1.5 left-4 flex items-center gap-1">
                                            Username Animation
                                            @if(!$user->isAdmin())
                                                <span class="text-[8px] text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded font-black">💰 500 Coins</span>
                                            @endif
                                        </label>
                                        <select name="username_animation" id="profile-anim-select" class="w-full mt-2.5 bg-transparent border-0 p-0 text-slate-800 text-xs font-semibold focus:outline-none focus:ring-0">
                                            <option value="none" {{ !$user->username_animation || $user->username_animation === 'none' ? 'selected' : '' }}>None (Static Color)</option>
                                            <option value="glow" {{ $user->username_animation === 'glow' ? 'selected' : '' }}>Glow (Soft neon pulse)</option>
                                            <option value="pulse" {{ $user->username_animation === 'pulse' ? 'selected' : '' }}>Pulse (Scale and fade)</option>
                                            <option value="crackle" {{ $user->username_animation === 'crackle' ? 'selected' : '' }}>Crackle (Lightning glow)</option>
                                            <option value="shimmer" {{ $user->username_animation === 'shimmer' ? 'selected' : '' }}>Shimmer (Metallic shine)</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Custom Banner Color (Gradients Preset) -->
                                <div class="relative border border-slate-200 rounded-2xl p-4 bg-white transition-all text-left">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest absolute top-1.5 left-4 flex items-center gap-1">
                                        Choose Profile Theme Gradient
                                    </label>
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-2.5">
                                        <label class="cursor-pointer flex items-center justify-between p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 transition-all">
                                            <input type="radio" name="banner_color" value="linear-gradient(135deg, #6366f1, #a855f7)" {{ $user->banner_color === 'linear-gradient(135deg, #6366f1, #a855f7)' ? 'checked' : '' }} class="mr-2 text-blue-600 focus:ring-blue-500">
                                            <span class="w-6 h-6 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-500 shadow-inner"></span>
                                        </label>

                                        <label class="cursor-pointer flex items-center justify-between p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 transition-all">
                                            <input type="radio" name="banner_color" value="linear-gradient(135deg, #ec4899, #8b5cf6)" {{ $user->banner_color === 'linear-gradient(135deg, #ec4899, #8b5cf6)' ? 'checked' : '' }} class="mr-2 text-pink-600 focus:ring-pink-500">
                                            <span class="w-6 h-6 rounded-lg bg-gradient-to-r from-pink-500 to-violet-500 shadow-inner"></span>
                                        </label>

                                        <label class="cursor-pointer flex items-center justify-between p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 transition-all">
                                            <input type="radio" name="banner_color" value="linear-gradient(135deg, #f97316, #ef4444)" {{ $user->banner_color === 'linear-gradient(135deg, #f97316, #ef4444)' ? 'checked' : '' }} class="mr-2 text-orange-600 focus:ring-orange-500">
                                            <span class="w-6 h-6 rounded-lg bg-gradient-to-r from-orange-500 to-red-500 shadow-inner"></span>
                                        </label>

                                        <label class="cursor-pointer flex items-center justify-between p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 transition-all">
                                            <input type="radio" name="banner_color" value="linear-gradient(135deg, #06b6d4, #3b82f6)" {{ $user->banner_color === 'linear-gradient(135deg, #06b6d4, #3b82f6)' ? 'checked' : '' }} class="mr-2 text-cyan-600 focus:ring-cyan-500">
                                            <span class="w-6 h-6 rounded-lg bg-gradient-to-r from-cyan-500 to-blue-500 shadow-inner"></span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Profile Privacy Setting -->
                                <div class="space-y-1.5 p-4 rounded-2xl border border-slate-100 bg-slate-50/50">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1">
                                                <span class="material-symbols-outlined text-xs text-blue-600">visibility</span>
                                                Profile Privacy
                                            </h4>
                                            <p class="text-[10px] font-medium text-slate-550">When private, other community members won't be able to see your discussions, uploads, and signature.</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer select-none">
                                            <input type="checkbox" name="is_private" value="1" {{ $user->is_private ? 'checked' : '' }} class="sr-only peer">
                                            <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Custom Signature -->
                                <div class="relative border border-slate-200 focus-within:border-blue-500 rounded-2xl p-4 bg-white transition-all text-left">
                                    <label for="signature" class="text-[9px] font-black text-slate-400 uppercase tracking-widest absolute top-1.5 left-4">Forum Signature Quote</label>
                                    <textarea id="signature" name="signature" rows="3" class="w-full mt-2.5 bg-transparent border-0 p-0 text-slate-800 text-xs font-semibold focus:outline-none focus:ring-0 placeholder:text-slate-400 resize-none" placeholder="Write a short custom signature to display at the footer of all your posts...">{{ old('signature', $user->signature) }}</textarea>
                                </div>

                                @if(Auth::id() === $user->id && $threads->isNotEmpty())
                                    <!-- Feature Thread Section -->
                                    <div class="border-t border-slate-100 pt-5 space-y-2 text-left">
                                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1">
                                            <span class="material-symbols-outlined text-xs text-amber-500">star</span>
                                            Feature one of your discussions (50 Coins)
                                        </h4>
                                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                                            <select id="feature_thread_id" class="flex-grow bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                                                <option value="">-- Choose a thread to feature --</option>
                                                @foreach($threads as $t)
                                                    @if(!$t->is_featured)
                                                        <option value="{{ $t->id }}">{{ $t->title }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <button type="button" onclick="featureThreadFromProfile()" class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-md shadow-amber-500/10 cursor-pointer flex items-center justify-center gap-1.5 flex-shrink-0">
                                                <span class="material-symbols-outlined text-xs">star</span> Feature Thread
                                            </button>
                                        </div>
                                    </div>
                                @endif

                                <!-- Save changes -->
                                <div class="text-right pt-3 border-t border-slate-100">
                                    <button type="submit" class="xen-button text-xs font-bold text-white px-5 py-2 rounded-xl shadow-md cursor-pointer">
                                        Save Profile Card
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                @endauth

                <!-- Recent Discussions list by this user -->
                <div class="mui-card overflow-hidden shadow-lg border border-slate-200 bg-white rounded-2xl">
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                        <h3 class="font-bold text-slate-700 text-xs uppercase tracking-wider">📝 Recent Discussions</h3>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse($threads as $thread)
                            <div class="px-6 py-4 flex items-center justify-between gap-4 hover:bg-slate-50/50 transition-colors">
                                <div class="space-y-0.5">
                                    <h4 class="font-bold text-slate-800 text-xs hover:text-blue-600 transition-colors">
                                        <a href="{{ route('threads.show', $thread->slug) }}">{{ $thread->title }}</a>
                                    </h4>
                                    <div class="flex items-center gap-2 text-[10px] text-slate-450">
                                        <span class="font-bold px-1.5 py-0.5 rounded bg-blue-50 text-blue-600 border border-blue-100">{{ $thread->category->name }}</span>
                                        <span>•</span>
                                        <span>Created {{ $thread->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="text-xs font-bold text-slate-700 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200 flex-shrink-0">
                                    {{ $thread->views_count }} views
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-xs text-slate-400">
                                No threads have been posted by this member yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Section: Media Gallery Grid (4 Cols) -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Media Upload Gallery grid -->
                <div class="mui-card p-6 border border-slate-200 bg-white shadow-lg rounded-2xl">
                    <h3 class="text-xs font-bold tracking-wider text-slate-500 uppercase mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-pink-500 text-sm">photo_library</span>
                        Media Showroom
                    </h3>
                    
                    @if(count($attachments) > 0)
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($attachments as $attach)
                                <div class="relative group rounded-xl overflow-hidden bg-slate-50 border border-slate-200 shadow-sm">
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

                                    <button onclick="openLightbox('{{ $attach->url }}', '{{ $attach->file_name }}')" class="block w-full h-24 overflow-hidden cursor-zoom-in text-left p-0 border-0 outline-none w-full">
                                        <img src="{{ $attach->url }}" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-200" alt="uploaded media">
                                    </button>
                                    <!-- Check if GIF -->
                                    @if(str_contains($attach->file_name, '.gif') || str_contains($attach->file_type, 'gif'))
                                        <span class="absolute top-1.5 right-1.5 px-1 py-0.5 rounded text-[7px] font-bold bg-pink-500 text-white uppercase tracking-widest">
                                            GIF
                                        </span>
                                    @endif
                                    <div class="bg-slate-100/80 p-1.5 text-[8px] text-slate-500 border-t border-slate-200 flex items-center justify-between">
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
                        <div class="text-center py-12 border-2 border-slate-200 border-dashed rounded-xl">
                            <span class="text-2xl block mb-2 opacity-50">🖼️</span>
                            <p class="text-xs text-slate-450 max-w-[200px] mx-auto font-medium">No custom images or GIFs uploaded by this member yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

@auth
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
                            btn.className = "w-full sm:w-44 text-xs font-bold py-1.5 px-3 rounded-xl transition-all cursor-pointer border flex items-center justify-center gap-1.5 shadow-sm bg-blue-50 border-blue-200 text-blue-700 hover:bg-rose-50 hover:text-rose-700 hover:border-rose-200 group/follow active:scale-97";
                            btn.innerHTML = `
                                <span class="material-symbols-outlined text-[11px] group-hover/follow:hidden">check</span>
                                <span class="group-hover/follow:hidden font-bold">Following</span>
                                <span class="material-symbols-outlined text-[11px] hidden group-hover/follow:inline-block">person_remove</span>
                                <span class="hidden group-hover/follow:inline font-bold">Unfollow</span>
                            `;
                        } else {
                            btn.className = "w-full sm:w-44 text-xs font-bold py-1.5 px-3 rounded-xl transition-all cursor-pointer border flex items-center justify-center gap-1.5 shadow-sm bg-white border-slate-200 text-slate-700 hover:bg-slate-100 active:scale-97";
                            btn.innerHTML = `
                                <span class="material-symbols-outlined text-[11px]">person_add</span>
                                <span class="font-bold">Follow Member</span>
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
