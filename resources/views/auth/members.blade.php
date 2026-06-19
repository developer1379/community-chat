@extends('layouts.app')

@section('title')
Registered Members Directory | XenForo Professional
@endsection
@section('meta_description')
Browse the community directory of registered members. Discover active specialists, custom badges, follow users, and check recent activity.
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- LEFT SIDEBAR -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Navigation Menu -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm rounded-none">
                <div class="p-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-955/50">
                    <h3 class="font-bold text-slate-800 dark:text-slate-200 text-sm tracking-tight flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg text-blue-500">group</span>
                        Members
                    </h3>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-850">
                    <a href="{{ route('members.index', ['tab' => 'overview']) }}" class="flex items-center justify-between px-4 py-3 text-xs font-bold transition-all {{ $tab === 'overview' ? 'bg-blue-50/50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 border-l-4 border-blue-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-850' }}">
                        <span>Overview</span>
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </a>
                    <a href="{{ route('members.index', ['tab' => 'most_messages']) }}" class="flex items-center justify-between px-4 py-3 text-xs font-bold transition-all {{ $tab === 'most_messages' ? 'bg-blue-50/50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 border-l-4 border-blue-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-850' }}">
                        <span>Most messages</span>
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </a>
                    <a href="{{ route('members.index', ['tab' => 'most_badges']) }}" class="flex items-center justify-between px-4 py-3 text-xs font-bold transition-all {{ $tab === 'most_badges' ? 'bg-blue-50/50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 border-l-4 border-blue-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-850' }}">
                        <span>Most badges</span>
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </a>
                    <a href="{{ route('members.index', ['tab' => 'highest_reaction']) }}" class="flex items-center justify-between px-4 py-3 text-xs font-bold transition-all {{ $tab === 'highest_reaction' ? 'bg-blue-50/50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 border-l-4 border-blue-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-850' }}">
                        <span>Highest reaction score</span>
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </a>
                    <a href="{{ route('members.index', ['tab' => 'most_points']) }}" class="flex items-center justify-between px-4 py-3 text-xs font-bold transition-all {{ $tab === 'most_points' ? 'bg-blue-50/50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 border-l-4 border-blue-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-850' }}">
                        <span>Most points</span>
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </a>
                    <a href="{{ route('members.index', ['tab' => 'most_items']) }}" class="flex items-center justify-between px-4 py-3 text-xs font-bold transition-all {{ $tab === 'most_items' ? 'bg-blue-50/50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 border-l-4 border-blue-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-850' }}">
                        <span>Most items</span>
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </a>
                    <a href="{{ route('members.index', ['tab' => 'birthdays']) }}" class="flex items-center justify-between px-4 py-3 text-xs font-bold transition-all {{ $tab === 'birthdays' ? 'bg-blue-50/50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 border-l-4 border-blue-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-850' }}">
                        <span>Today's birthdays</span>
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </a>
                    <a href="{{ route('members.index', ['tab' => 'staff']) }}" class="flex items-center justify-between px-4 py-3 text-xs font-bold transition-all {{ $tab === 'staff' ? 'bg-blue-50/50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 border-l-4 border-blue-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-850' }}">
                        <span>Staff members</span>
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </a>
                </div>
            </div>

            <!-- Search Form -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm rounded-none p-4">
                <h3 class="font-bold text-slate-800 dark:text-slate-200 text-xs uppercase tracking-wider mb-3">Find member</h3>
                <form action="{{ route('members.index') }}" method="GET" class="space-y-3">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    <div class="relative">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Name..." class="block w-full border border-slate-200 dark:border-slate-800 rounded-none bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 text-xs px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-2 px-4 rounded-none transition-colors">Search</button>
                </form>
            </div>

            <!-- Newest Members -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm rounded-none p-4">
                <h3 class="font-bold text-slate-800 dark:text-slate-200 text-xs uppercase tracking-wider mb-4">Newest members</h3>
                <div class="grid grid-cols-4 gap-3 justify-items-center">
                    @foreach($newestMembers as $member)
                        @php
                            $initial = strtoupper(substr($member->name, 0, 1));
                            $colors = ['bg-pink-500', 'bg-purple-500', 'bg-indigo-500', 'bg-blue-500', 'bg-teal-500', 'bg-green-500', 'bg-yellow-500', 'bg-orange-500', 'bg-red-500', 'bg-slate-500'];
                            $colorIndex = ord($initial) % count($colors);
                            $bgClass = $colors[$colorIndex];
                        @endphp
                        @if($member->avatar_path)
                            <img src="{{ $member->avatar_url }}" class="w-10 h-10 rounded-full object-cover border border-slate-200 dark:border-slate-800 cursor-pointer hover:opacity-85 transition-opacity"
                                 data-user-hover="true" data-user-name="{{ $member->name }}" alt="{{ $member->name }}">
                        @else
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-xs select-none cursor-pointer {{ $bgClass }} hover:opacity-85 transition-opacity"
                                 data-user-hover="true" data-user-name="{{ $member->name }}">
                                 {{ $initial }}
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- MAIN AREA -->
        <div class="lg:col-span-3">
            @if($tab === 'overview')
                <!-- OVERVIEW DASHBOARD -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Most Messages -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm rounded-none flex flex-col justify-between">
                        <div>
                            <div class="p-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-955/50">
                                <h3 class="font-bold text-slate-800 dark:text-slate-200 text-xs uppercase tracking-wider flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-sm text-blue-500">forum</span>
                                    Most messages
                                </h3>
                            </div>
                            <div class="p-4 divide-y divide-slate-100 dark:divide-slate-850">
                                @forelse($mostMessages as $user)
                                    <div class="flex items-center justify-between py-2.5 first:pt-0 last:pb-0">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <img src="{{ $user->avatar_url }}" class="w-8 h-8 rounded-full object-cover shrink-0" data-user-hover="true" data-user-name="{{ $user->name }}">
                                            <div class="min-w-0">
                                                <a href="{{ route('profile.show', $user->name) }}" class="font-bold text-xs text-slate-900 dark:text-white hover:underline block truncate {{ $user->username_style }}" style="{{ $user->username_style_css }}" data-user-hover="true" data-user-name="{{ $user->name }}">
                                                    {{ $user->name }}
                                                </a>
                                                <span class="text-[10px] text-slate-500 dark:text-slate-450">{{ $user->title_badge ?: 'Community Member' }}</span>
                                            </div>
                                        </div>
                                        <span class="text-xs font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800/80 px-2 py-0.5 rounded-none">{{ number_format($user->posts_count) }}</span>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-400 italic py-4">No members found</p>
                                @endforelse
                            </div>
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-slate-955/30 border-t border-slate-100 dark:border-slate-850 text-right">
                            <a href="{{ route('members.index', ['tab' => 'most_messages']) }}" class="text-[10px] font-bold text-blue-600 hover:underline">See more...</a>
                        </div>
                    </div>

                    <!-- Most Badges -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm rounded-none flex flex-col justify-between">
                        <div>
                            <div class="p-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-955/50">
                                <h3 class="font-bold text-slate-800 dark:text-slate-200 text-xs uppercase tracking-wider flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-sm text-yellow-500">verified</span>
                                    Most badges
                                </h3>
                            </div>
                            <div class="p-4 divide-y divide-slate-100 dark:divide-slate-850">
                                @forelse($mostBadges as $user)
                                    @php
                                        $level = $user->computed_anime_tier['level'] ?? 1;
                                    @endphp
                                    <div class="flex items-center justify-between py-2.5 first:pt-0 last:pb-0">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <img src="{{ $user->avatar_url }}" class="w-8 h-8 rounded-full object-cover shrink-0" data-user-hover="true" data-user-name="{{ $user->name }}">
                                            <div class="min-w-0">
                                                <a href="{{ route('profile.show', $user->name) }}" class="font-bold text-xs text-slate-900 dark:text-white hover:underline block truncate {{ $user->username_style }}" style="{{ $user->username_style_css }}" data-user-hover="true" data-user-name="{{ $user->name }}">
                                                    {{ $user->name }}
                                                </a>
                                                <span class="text-[10px] text-slate-500 dark:text-slate-450">{{ $user->title_badge ?: 'Community Member' }}</span>
                                            </div>
                                        </div>
                                        <span class="text-xs font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800/80 px-2 py-0.5 rounded-none">{{ $level }}</span>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-400 italic py-4">No members found</p>
                                @endforelse
                            </div>
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-slate-955/30 border-t border-slate-100 dark:border-slate-850 text-right">
                            <a href="{{ route('members.index', ['tab' => 'most_badges']) }}" class="text-[10px] font-bold text-blue-600 hover:underline">See more...</a>
                        </div>
                    </div>

                    <!-- Highest Reaction Score -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm rounded-none flex flex-col justify-between">
                        <div>
                            <div class="p-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-955/50">
                                <h3 class="font-bold text-slate-800 dark:text-slate-200 text-xs uppercase tracking-wider flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-sm text-rose-500">favorite</span>
                                    Highest reaction score
                                </h3>
                            </div>
                            <div class="p-4 divide-y divide-slate-100 dark:divide-slate-850">
                                @forelse($highestReaction as $user)
                                    <div class="flex items-center justify-between py-2.5 first:pt-0 last:pb-0">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <img src="{{ $user->avatar_url }}" class="w-8 h-8 rounded-full object-cover shrink-0" data-user-hover="true" data-user-name="{{ $user->name }}">
                                            <div class="min-w-0">
                                                <a href="{{ route('profile.show', $user->name) }}" class="font-bold text-xs text-slate-900 dark:text-white hover:underline block truncate {{ $user->username_style }}" style="{{ $user->username_style_css }}" data-user-hover="true" data-user-name="{{ $user->name }}">
                                                    {{ $user->name }}
                                                </a>
                                                <span class="text-[10px] text-slate-500 dark:text-slate-450">{{ $user->title_badge ?: 'Community Member' }}</span>
                                            </div>
                                        </div>
                                        <span class="text-xs font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800/80 px-2 py-0.5 rounded-none">{{ number_format($user->reactions_count ?? 0) }}</span>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-400 italic py-4">No members found</p>
                                @endforelse
                            </div>
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-slate-955/30 border-t border-slate-100 dark:border-slate-850 text-right">
                            <a href="{{ route('members.index', ['tab' => 'highest_reaction']) }}" class="text-[10px] font-bold text-blue-600 hover:underline">See more...</a>
                        </div>
                    </div>

                    <!-- Most Points -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm rounded-none flex flex-col justify-between">
                        <div>
                            <div class="p-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-955/50">
                                <h3 class="font-bold text-slate-800 dark:text-slate-200 text-xs uppercase tracking-wider flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-sm text-indigo-500">military_tech</span>
                                    Most points
                                </h3>
                            </div>
                            <div class="p-4 divide-y divide-slate-100 dark:divide-slate-850">
                                @forelse($mostPoints as $user)
                                    <div class="flex items-center justify-between py-2.5 first:pt-0 last:pb-0">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <img src="{{ $user->avatar_url }}" class="w-8 h-8 rounded-full object-cover shrink-0" data-user-hover="true" data-user-name="{{ $user->name }}">
                                            <div class="min-w-0">
                                                <a href="{{ route('profile.show', $user->name) }}" class="font-bold text-xs text-slate-900 dark:text-white hover:underline block truncate {{ $user->username_style }}" style="{{ $user->username_style_css }}" data-user-hover="true" data-user-name="{{ $user->name }}">
                                                    {{ $user->name }}
                                                </a>
                                                <span class="text-[10px] text-slate-500 dark:text-slate-450">{{ $user->title_badge ?: 'Community Member' }}</span>
                                            </div>
                                        </div>
                                        <span class="text-xs font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800/80 px-2 py-0.5 rounded-none">{{ number_format($user->activity_points) }}</span>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-400 italic py-4">No members found</p>
                                @endforelse
                            </div>
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-slate-955/30 border-t border-slate-100 dark:border-slate-850 text-right">
                            <a href="{{ route('members.index', ['tab' => 'most_points']) }}" class="text-[10px] font-bold text-blue-600 hover:underline">See more...</a>
                        </div>
                    </div>

                    <!-- Most Items -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm rounded-none flex flex-col justify-between">
                        <div>
                            <div class="p-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-955/50">
                                <h3 class="font-bold text-slate-800 dark:text-slate-200 text-xs uppercase tracking-wider flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-sm text-teal-500">shopping_bag</span>
                                    Most items
                                </h3>
                            </div>
                            <div class="p-4 divide-y divide-slate-100 dark:divide-slate-850">
                                @forelse($mostItems as $user)
                                    <div class="flex items-center justify-between py-2.5 first:pt-0 last:pb-0">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <img src="{{ $user->avatar_url }}" class="w-8 h-8 rounded-full object-cover shrink-0" data-user-hover="true" data-user-name="{{ $user->name }}">
                                            <div class="min-w-0">
                                                <a href="{{ route('profile.show', $user->name) }}" class="font-bold text-xs text-slate-900 dark:text-white hover:underline block truncate {{ $user->username_style }}" style="{{ $user->username_style_css }}" data-user-hover="true" data-user-name="{{ $user->name }}">
                                                    {{ $user->name }}
                                                </a>
                                                <span class="text-[10px] text-slate-500 dark:text-slate-450">{{ $user->title_badge ?: 'Community Member' }}</span>
                                            </div>
                                        </div>
                                        <span class="text-xs font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800/80 px-2 py-0.5 rounded-none">{{ number_format($user->purchases_count ?? 0) }}</span>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-400 italic py-4">No members found</p>
                                @endforelse
                            </div>
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-slate-955/30 border-t border-slate-100 dark:border-slate-850 text-right">
                            <a href="{{ route('members.index', ['tab' => 'most_items']) }}" class="text-[10px] font-bold text-blue-600 hover:underline">See more...</a>
                        </div>
                    </div>

                    <!-- Today's Birthdays -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm rounded-none flex flex-col justify-between">
                        <div>
                            <div class="p-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-955/50">
                                <h3 class="font-bold text-slate-800 dark:text-slate-200 text-xs uppercase tracking-wider flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-sm text-pink-500">cake</span>
                                    Today's birthdays
                                </h3>
                            </div>
                            <div class="p-4 divide-y divide-slate-100 dark:divide-slate-850">
                                @forelse($birthdays as $user)
                                    @php
                                        // Calculate age based on dob if present, otherwise simulate a realistic age
                                        $age = 25;
                                        if ($user->dob) {
                                            $age = \Carbon\Carbon::parse($user->dob)->age;
                                        } else {
                                            $age = (ord(substr($user->name, 0, 1)) % 15) + 20;
                                        }
                                    @endphp
                                    <div class="flex items-center justify-between py-2.5 first:pt-0 last:pb-0">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <img src="{{ $user->avatar_url }}" class="w-8 h-8 rounded-full object-cover shrink-0" data-user-hover="true" data-user-name="{{ $user->name }}">
                                            <div class="min-w-0">
                                                <a href="{{ route('profile.show', $user->name) }}" class="font-bold text-xs text-slate-900 dark:text-white hover:underline block truncate {{ $user->username_style }}" style="{{ $user->username_style_css }}" data-user-hover="true" data-user-name="{{ $user->name }}">
                                                    {{ $user->name }}
                                                </a>
                                                <span class="text-[10px] text-slate-500 dark:text-slate-450">{{ $user->title_badge ?: 'Community Member' }}</span>
                                            </div>
                                        </div>
                                        <span class="text-xs font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800/80 px-2 py-0.5 rounded-none">{{ $age }}</span>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-400 italic py-4">No members found</p>
                                @endforelse
                            </div>
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-slate-955/30 border-t border-slate-100 dark:border-slate-850 text-right">
                            <a href="{{ route('members.index', ['tab' => 'birthdays']) }}" class="text-[10px] font-bold text-blue-600 hover:underline">See more...</a>
                        </div>
                    </div>

                    <!-- Staff Members -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm rounded-none flex flex-col justify-between md:col-span-2">
                        <div>
                            <div class="p-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-955/50">
                                <h3 class="font-bold text-slate-800 dark:text-slate-200 text-xs uppercase tracking-wider flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-sm text-purple-500">shield_person</span>
                                    Staff members
                                </h3>
                            </div>
                            <div class="p-4 divide-y divide-slate-100 dark:divide-slate-850 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-1">
                                @forelse($staff as $user)
                                    <div class="flex items-center justify-between py-2.5 border-b border-slate-100 last:border-0 dark:border-slate-850/40">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <img src="{{ $user->avatar_url }}" class="w-8 h-8 rounded-full object-cover shrink-0" data-user-hover="true" data-user-name="{{ $user->name }}">
                                            <div class="min-w-0">
                                                <a href="{{ route('profile.show', $user->name) }}" class="font-bold text-xs text-slate-900 dark:text-white hover:underline block truncate {{ $user->username_style }}" style="{{ $user->username_style_css }}" data-user-hover="true" data-user-name="{{ $user->name }}">
                                                    {{ $user->name }}
                                                </a>
                                                <span class="text-[10px] text-slate-500 dark:text-slate-450">{{ $user->title_badge ?: 'Administrator' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-400 italic py-4 col-span-full">No members found</p>
                                @endforelse
                            </div>
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-slate-955/30 border-t border-slate-100 dark:border-slate-850 text-right">
                            <a href="{{ route('members.index', ['tab' => 'staff']) }}" class="text-[10px] font-bold text-blue-600 hover:underline">See more...</a>
                        </div>
                    </div>

                </div>
            @else
                <!-- TAB-SPECIFIC LIST -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm rounded-none">
                    <div class="p-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-955/50 flex items-center justify-between">
                        <h3 class="font-bold text-slate-800 dark:text-slate-200 text-xs uppercase tracking-wider">
                            @switch($tab)
                                @case('most_messages') Most Messages @break
                                @case('most_badges') Most Badges @break
                                @case('highest_reaction') Highest Reaction Score @break
                                @case('most_points') Most Points @break
                                @case('most_items') Most Items @break
                                @case('birthdays') Today's Birthdays @break
                                @case('staff') Staff Members @break
                            @endswitch
                        </h3>
                    </div>
                    <div class="divide-y divide-slate-100 dark:divide-slate-850">
                        @forelse($users as $user)
                            @php
                                $userTier = $user->computed_anime_tier;
                                $level = $userTier['level'] ?? 1;
                                $hasStatus = !empty($user->status) || !empty($user->status_image);
                                $isFollowing = Auth::check() ? Auth::user()->isFollowing($user) : false;
                            @endphp
                            <div class="p-4 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <!-- Avatar -->
                                    <div class="relative w-12 h-12 shrink-0 @if($hasStatus) cursor-pointer hover:scale-105 transition-transform duration-200 @endif"
                                         @if($hasStatus) onclick="viewUserStatus('{{ $user->id }}', '{{ addslashes($user->name) }}', '{{ $user->avatar_url }}', '{{ addslashes($user->title_badge ?: 'Community Member') }}', '{{ addslashes($user->status) }}', '{{ $user->status_image }}')" @endif>
                                        <div class="w-full h-full rounded-full overflow-hidden p-[2.5px] bg-slate-200 dark:bg-slate-800">
                                            <div class="w-full h-full rounded-full overflow-hidden bg-white dark:bg-slate-900 p-[0.5px]">
                                                <img src="{{ $user->avatar_url }}" class="w-full h-full object-cover rounded-full" alt="avatar" data-user-hover="true" data-user-name="{{ $user->name }}">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Member Info -->
                                    <div class="min-w-0">
                                        <h4 class="font-bold text-sm text-slate-900 dark:text-white truncate {{ $user->username_style }}" style="{{ $user->username_style_css }}">
                                            <a href="{{ route('profile.show', $user->name) }}" data-user-hover="true" data-user-name="{{ $user->name }}">{{ $user->name }}</a>
                                        </h4>
                                        <div class="text-xs text-slate-500 dark:text-slate-455 mt-0.5">
                                            {{ $user->title_badge ?: 'Community Member' }}
                                        </div>
                                        <div class="flex items-center gap-2 mt-1 flex-wrap text-[10px] text-slate-400 dark:text-slate-500 font-semibold">
                                            <span>Posts: <strong class="text-slate-600 dark:text-slate-350">{{ number_format($user->posts()->count()) }}</strong></span>
                                            <span>•</span>
                                            @php
                                                // calculate reaction count
                                                $recCount = \App\Models\React::whereIn('post_id', $user->posts()->pluck('id'))->count();
                                            @endphp
                                            <span>Reactions: <strong class="text-slate-600 dark:text-slate-350">{{ number_format($recCount) }}</strong></span>
                                            <span>•</span>
                                            <span>Level: <strong style="color: {{ $userTier['color'] }}">{{ $level }}</strong></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Metric Count or Action -->
                                <div class="flex items-center gap-3 shrink-0">
                                    <div class="text-right">
                                        @switch($tab)
                                            @case('most_messages')
                                                <span class="text-sm font-bold text-slate-900 dark:text-white">{{ number_format($user->posts_count ?? $user->posts()->count()) }}</span>
                                                <span class="block text-[9px] uppercase tracking-wider text-slate-400">posts</span>
                                                @break
                                            @case('most_badges')
                                                <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $level }}</span>
                                                <span class="block text-[9px] uppercase tracking-wider text-slate-400">badges</span>
                                                @break
                                            @case('highest_reaction')
                                                <span class="text-sm font-bold text-slate-900 dark:text-white">{{ number_format($user->reactions_count ?? 0) }}</span>
                                                <span class="block text-[9px] uppercase tracking-wider text-slate-400">reactions</span>
                                                @break
                                            @case('most_points')
                                                <span class="text-sm font-bold text-slate-900 dark:text-white">{{ number_format($user->activity_points) }}</span>
                                                <span class="block text-[9px] uppercase tracking-wider text-slate-400">points</span>
                                                @break
                                            @case('most_items')
                                                <span class="text-sm font-bold text-slate-900 dark:text-white">{{ number_format($user->purchases_count ?? $user->purchases()->count()) }}</span>
                                                <span class="block text-[9px] uppercase tracking-wider text-slate-400">items</span>
                                                @break
                                            @case('birthdays')
                                                @php
                                                    $age = 25;
                                                    if ($user->dob) {
                                                        $age = \Carbon\Carbon::parse($user->dob)->age;
                                                    } else {
                                                        $age = (ord(substr($user->name, 0, 1)) % 15) + 20;
                                                    }
                                                @endphp
                                                <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $age }}</span>
                                                <span class="block text-[9px] uppercase tracking-wider text-slate-400">years old</span>
                                                @break
                                            @case('staff')
                                                <span class="text-[10px] font-bold uppercase text-slate-400 dark:text-slate-550 bg-slate-100 dark:bg-slate-800 px-2 py-0.5">Staff</span>
                                                @break
                                        @endswitch
                                    </div>
                                    
                                    @auth
                                        @if(Auth::id() !== $user->id)
                                            <button type="button" 
                                                    onclick="toggleFollowUser('{{ $user->name }}', '{{ $user->id }}')" 
                                                    id="follow-btn-{{ $user->id }}" 
                                                    class="text-[10px] font-bold py-1 px-3 border transition-all cursor-pointer min-w-[76px] text-center
                                                    {{ $isFollowing 
                                                        ? 'bg-slate-100 dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300' 
                                                        : 'bg-white dark:bg-slate-900 border-blue-600 text-blue-600 hover:bg-blue-50' }}">
                                                {{ $isFollowing ? 'Following' : 'Follow' }}
                                            </button>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        @empty
                            <div class="py-16 text-center">
                                <span class="material-symbols-outlined text-4xl text-slate-350 mb-2">person_search</span>
                                <h3 class="font-bold text-slate-800 dark:text-slate-200 text-base mb-1">No Members Found</h3>
                                <p class="text-xs text-slate-450 dark:text-slate-500 max-w-sm mx-auto">We couldn't find any registered members matching your query.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator && $users->hasPages())
                        <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

@auth
    <!-- Follow System Asynchronous API Controller -->
    <script>
        function toggleFollowUser(username, userId) {
            const btn = document.getElementById(`follow-btn-${userId}`);
            if (!btn) return;

            // Instantly disable temporarily to avoid race conditions
            btn.disabled = true;

            const url = `/members/${encodeURIComponent(username)}/follow`;

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Follow action failed.');
                }
                return response.json();
            })
            .then(data => {
                btn.disabled = false;
                if (data.success) {
                    if (data.following) {
                        btn.className = "text-[10px] font-bold py-1 px-3 border transition-all cursor-pointer min-w-[76px] text-center bg-slate-100 dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300";
                        btn.innerText = 'Following';
                    } else {
                        btn.className = "text-[10px] font-bold py-1 px-3 border transition-all cursor-pointer min-w-[76px] text-center bg-white dark:bg-slate-900 border-blue-600 text-blue-600 hover:bg-blue-50";
                        btn.innerText = 'Follow';
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
@endauth

<script>
    function viewUserStatus(userId, userName, avatarUrl, titleBadge, statusText, statusImage) {
        const currentUserId = "{{ Auth::id() }}";
        const isOwner = currentUserId === userId;

        // Log status view if viewer is authenticated and not owner
        if (currentUserId && !isOwner) {
            fetch(`/profile/${userId}/view-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .catch(err => console.error("Error logging status view:", err));
        }

        // Build viewer information HTML
        let viewersSection = '';
        if (isOwner) {
            viewersSection = `
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 text-left">
                    <span class="block text-[10px] font-black text-slate-400 dark:text-slate-555 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[13px]">visibility</span>
                        Seen by
                    </span>
                    <div id="status-viewers-list" class="flex flex-col gap-2 max-h-[120px] overflow-y-auto pr-1">
                        <div class="text-[11px] text-slate-400 font-semibold italic text-center py-2">Loading viewers...</div>
                    </div>
                </div>
            `;

            // Load viewers list asynchronously
            setTimeout(() => {
                fetch(`/profile/${userId}/status-viewers`)
                .then(res => res.json())
                .then(data => {
                    const container = document.getElementById('status-viewers-list');
                    if (!container) return;
                    if (data.success && data.viewers.length > 0) {
                        let html = '';
                        data.viewers.forEach(viewer => {
                            html += `
                                <div class="flex items-center justify-between py-1 border-b border-slate-50 last:border-0 dark:border-slate-800/40">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full overflow-hidden border border-slate-200 dark:border-slate-700">
                                            <img src="${viewer.avatar_url}" class="w-full h-full object-cover">
                                        </div>
                                        <a href="/profile/${encodeURIComponent(viewer.name)}" class="text-xs font-bold text-slate-800 dark:text-slate-200 hover:text-blue-500 hover:underline truncate max-w-[120px]">${viewer.name}</a>
                                    </div>
                                    <span class="text-[9px] px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-550 font-bold uppercase tracking-wider">${viewer.title_badge}</span>
                                </div>
                            `;
                        });
                        container.innerHTML = html;
                    } else {
                        container.innerHTML = `<div class="text-[11px] text-slate-400 font-semibold italic text-center py-2">No views yet</div>`;
                    }
                })
                .catch(err => {
                    console.error("Error fetching status viewers:", err);
                    const container = document.getElementById('status-viewers-list');
                    if (container) container.innerHTML = `<div class="text-[11px] text-rose-500 font-semibold italic text-center py-2">Failed to load viewers</div>`;
                });
            }, 100);
        }

        Swal.fire({
            html: `
                <div class="instagram-story-card relative overflow-hidden rounded-none bg-gradient-to-b from-slate-900 via-slate-950 to-black text-white p-5 shadow-2xl border border-slate-800 flex flex-col justify-between min-h-[460px] font-sans">
                    <!-- Progress Bar Header -->
                    <div class="flex gap-1 mb-3">
                        <div class="h-1 flex-1 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full"></div>
                    </div>

                    <!-- User Header -->
                    <div class="flex items-center gap-3 text-left">
                        <div class="w-10 h-10 rounded-full p-[2px] bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-650">
                            <div class="w-full h-full rounded-full overflow-hidden border border-black bg-slate-900">
                                <img src="${avatarUrl}" class="w-full h-full object-cover">
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-black tracking-tight text-white">${userName}</h4>
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">${titleBadge}</span>
                        </div>
                    </div>

                    <!-- Main Story Content -->
                    <div class="flex-grow flex flex-col justify-center items-center py-4 text-center space-y-3">
                        ${statusImage ? `
                            <div class="w-full max-h-[160px] rounded-none overflow-hidden border border-white/10 shadow-lg bg-black/40">
                                <img src="${statusImage}" class="w-full h-full object-contain cursor-zoom-in" onclick="window.open('${statusImage}', '_blank')">
                            </div>
                        ` : ''}

                        ${statusText ? `
                            <p class="text-sm font-extrabold text-white leading-relaxed max-w-[280px] drop-shadow-md bg-white/5 backdrop-blur-sm py-2 px-3 rounded-none border border-white/5">
                                "${statusText}"
                            </p>
                        ` : ''}
                    </div>

                    <!-- Interaction Actions Bar -->
                    <div class="flex items-center justify-between border-t border-white/5 pt-3 pb-2 px-1">
                        <div class="flex items-center gap-4">
                            <!-- Like Button -->
                            <button id="status-like-btn" class="flex items-center gap-1.5 focus:outline-none group transition-transform active:scale-95 cursor-pointer bg-transparent border-0 text-left">
                                <span id="status-like-icon" class="material-symbols-outlined text-[20px] transition-colors duration-250 text-slate-400 hover:text-rose-500">favorite</span>
                                <span id="status-likes-count" class="text-xs font-bold text-slate-400">0</span>
                            </button>

                            <!-- Comment Count Display -->
                            <div class="flex items-center gap-1.5 text-slate-400">
                                <span class="material-symbols-outlined text-[20px]">chat_bubble</span>
                                <span id="status-comments-count" class="text-xs font-bold">0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Comments List Container -->
                    <div class="border-t border-white/5 pt-2 text-left">
                        <span class="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Comments</span>
                        <div id="status-comments-list" class="flex flex-col gap-1.5 max-h-[90px] overflow-y-auto pr-1">
                            <div class="text-[10px] text-slate-500 font-semibold italic text-center py-2">No comments yet</div>
                        </div>
                    </div>

                    <!-- Comment Input Box -->
                    <div class="mt-2.5">
                        @auth
                            <div class="flex items-center gap-2 bg-white/5 border border-white/10 rounded-none py-1 px-3">
                                <input type="text" id="status-comment-input" placeholder="Type a comment..." class="bg-transparent border-0 text-xs text-white focus:outline-none focus:ring-0 flex-1 placeholder-slate-500" minlength="1" maxlength="500">
                                <button id="status-comment-submit" class="text-xs font-bold text-blue-500 hover:text-blue-450 focus:outline-none active:scale-95 transition-transform cursor-pointer bg-transparent border-0">Send</button>
                            </div>
                        @else
                            <div class="text-center py-1.5 text-[10px] text-slate-500 bg-white/5 rounded-none font-bold">
                                <a href="{{ route('login') }}" class="text-blue-450 hover:underline">Login</a> to like or comment
                            </div>
                        @endauth
                    </div>

                    <!-- Footer Details (Viewers) -->
                    ${viewersSection}
                </div>
            `,
            showConfirmButton: false,
            showCloseButton: true,
            background: 'transparent',
            width: '380px',
            customClass: {
                popup: 'bg-transparent border-0 shadow-none p-0 overflow-visible',
                closeButton: 'text-white border-0 bg-transparent hover:text-red-500'
            },
            didOpen: () => {
                const likeIcon = document.getElementById('status-like-icon');
                const likesCountSpan = document.getElementById('status-likes-count');
                const commentsCountSpan = document.getElementById('status-comments-count');
                const commentsList = document.getElementById('status-comments-list');
                const commentInput = document.getElementById('status-comment-input');
                const commentSubmit = document.getElementById('status-comment-submit');
                const likeBtn = document.getElementById('status-like-btn');

                function updateLikesUI(count, hasLiked) {
                    if (likesCountSpan) likesCountSpan.textContent = count;
                    if (likeIcon) {
                        if (hasLiked) {
                            likeIcon.style.fontVariationSettings = "'FILL' 1, 'wght' 600, 'GRAD' 0, 'opsz' 20";
                            likeIcon.classList.remove('text-slate-400');
                            likeIcon.classList.add('text-rose-500');
                        } else {
                            likeIcon.style.fontVariationSettings = "'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 20";
                            likeIcon.classList.remove('text-rose-500');
                            likeIcon.classList.add('text-slate-400');
                        }
                    }
                }

                function updateCommentsUI(comments) {
                    if (commentsCountSpan) commentsCountSpan.textContent = comments.length;
                    if (!commentsList) return;

                    if (comments.length > 0) {
                        let html = '';
                        comments.forEach(c => {
                            html += `
                                <div class="flex items-start gap-2 py-1.5 border-b border-white/5 last:border-0">
                                    <div class="w-6 h-6 rounded-full overflow-hidden shrink-0 border border-white/10">
                                        <img src="${c.avatar_url}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center justify-between">
                                            <span class="text-[9.5px] font-black text-slate-300 truncate">${c.name}</span>
                                            <span class="text-[8px] text-slate-500">${c.time_ago}</span>
                                        </div>
                                        <p class="text-[10px] text-slate-200 break-words mt-0.5 leading-normal font-semibold">${c.comment}</p>
                                    </div>
                                </div>
                            `;
                        });
                        commentsList.innerHTML = html;
                    } else {
                        commentsList.innerHTML = `<div class="text-[10px] text-slate-500 font-semibold italic text-center py-2">No comments yet</div>`;
                    }
                }

                // Fetch initial status interactions
                fetch(`/profile/${userId}/status-interactions`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            updateLikesUI(data.likes_count, data.has_liked);
                            updateCommentsUI(data.comments);
                        }
                    })
                    .catch(err => console.error("Error loading interactions:", err));

                // Like status action
                if (likeBtn) {
                    likeBtn.onclick = () => {
                        fetch(`/profile/${userId}/like-status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                updateLikesUI(data.likes_count, data.liked);
                            }
                        })
                        .catch(err => console.error("Error liking status:", err));
                    };
                }

                // Comment status action
                if (commentSubmit && commentInput) {
                    const submitComment = () => {
                        const text = commentInput.value.trim();
                        if (!text) return;

                        commentSubmit.disabled = true;
                        commentSubmit.textContent = '...';

                        fetch(`/profile/${userId}/comment-status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ comment: text })
                        })
                        .then(res => res.json())
                        .then(data => {
                            commentSubmit.disabled = false;
                            commentSubmit.textContent = 'Send';
                            if (data.success) {
                                commentInput.value = '';
                                updateCommentsUI(data.comments);
                                commentsList.scrollTop = commentsList.scrollHeight;
                            }
                        })
                        .catch(err => {
                            console.error("Error posting comment:", err);
                            commentSubmit.disabled = false;
                            commentSubmit.textContent = 'Send';
                        });
                    };

                    commentSubmit.onclick = submitComment;
                    commentInput.onkeydown = (e) => {
                        if (e.key === 'Enter') {
                            submitComment();
                        }
                    };
                }
            }
        });
    }

    // Force run hover card listeners on page load
    document.addEventListener('DOMContentLoaded', () => {
        if (window.setupHoverCardListeners) {
            window.setupHoverCardListeners();
        }
    });
</script>
@endsection
