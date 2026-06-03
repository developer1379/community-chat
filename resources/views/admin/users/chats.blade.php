@extends('layouts.admin')

@section('content')
<div class="space-y-8 max-w-4xl mx-auto text-left">
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-500">
        <a href="{{ route('admin.users.index') }}" class="hover:text-indigo-600 transition-colors">Users</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-slate-400">Conversations Log</span>
    </div>

    <!-- Header Banner -->
    <div class="relative rounded-3xl overflow-hidden shadow-lg border border-slate-200 bg-gradient-to-r from-slate-800 via-slate-900 to-indigo-950 p-6 sm:p-10 text-white">
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 space-y-3">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-red-650/40 text-red-200 border border-red-500/30 uppercase tracking-widest leading-none">
                <span class="material-symbols-outlined text-xs">history_edu</span> DM Monitor
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight font-sans">
                {{ $user->name }}'s Conversations
            </h1>
            <p class="text-sm sm:text-lg text-slate-350 max-w-xl font-medium leading-relaxed">
                Review active Direct Messages logs involving this user.
            </p>
        </div>
    </div>

    <!-- Conversations Cards List -->
    <div class="border border-slate-200 bg-white dark:bg-slate-900 dark:border-slate-800 rounded-3xl p-6 shadow-xl space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-5">
            <h2 class="text-lg font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-indigo-650">forum</span> Active Chats
                <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 text-xs font-bold">{{ $conversations->count() }} logs</span>
            </h2>
        </div>

        @if($conversations->isEmpty())
            <div class="text-center py-12 text-slate-455 dark:text-slate-500 space-y-3">
                <span class="material-symbols-outlined text-5xl">chat_bubble_outline</span>
                <p class="text-sm font-bold">No direct message history found for this user.</p>
                <a href="{{ route('admin.users.index') }}" class="inline-block px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-750 text-xs font-bold rounded-xl transition-all">Back to Users</a>
            </div>
        @else
            <div class="grid grid-cols-1 gap-4">
                @foreach($conversations as $conversation)
                    @php
                        $other = $conversation->user_one_id === $user->id ? $conversation->userTwo : $conversation->userOne;
                        $lastMessage = \App\Models\Message::where('conversation_id', $conversation->id)->orderBy('created_at', 'desc')->first();
                    @endphp
                    <div class="p-5 border border-slate-150 dark:border-slate-800 rounded-2xl flex items-center justify-between gap-4 bg-slate-50/20 dark:bg-slate-950/10 hover:border-indigo-300 dark:hover:border-indigo-900 transition-colors">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <!-- Avatar Overlaps -->
                            <div class="flex -space-x-4 flex-shrink-0">
                                <img class="w-9 h-9 rounded-full object-cover border-2 border-white dark:border-slate-900 shadow-sm" src="{{ $user->avatar_url }}" alt="avatar">
                                <img class="w-9 h-9 rounded-full object-cover border-2 border-white dark:border-slate-900 shadow-sm" src="{{ $other->avatar_url }}" alt="avatar">
                            </div>
                            <div class="leading-tight min-w-0">
                                <h4 class="font-extrabold text-sm text-slate-900 dark:text-white truncate">
                                    {{ $user->name }} & {{ $other->name }}
                                </h4>
                                @if($lastMessage)
                                    @php
                                        $isImg = preg_match('/^https?:\/\/[^\s]+?\.(jpe?g|png|gif|webp|bmp)(?:\?[^\s]*)?$/i', trim($lastMessage->body)) || 
                                                 str_starts_with(trim($lastMessage->body), 'https://i.ibb.co/');
                                    @endphp
                                    <p class="text-[11px] text-slate-500 dark:text-slate-450 truncate mt-1">
                                        Last message: <span class="font-medium">{{ $isImg ? '📷 Image attachment' : $lastMessage->body }}</span>
                                    </p>
                                    <p class="text-[9px] text-slate-400 dark:text-slate-500 font-bold mt-0.5">{{ $lastMessage->created_at->diffForHumans() }}</p>
                                @else
                                    <p class="text-[11px] text-slate-400 font-medium mt-1">No messages inside this channel yet.</p>
                                @endif
                            </div>
                        </div>

                        <div>
                            <a href="{{ route('admin.users.chats.view', [$user->id, $conversation->id]) }}" class="px-4 py-2 text-xs font-bold text-indigo-650 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/20 dark:hover:bg-indigo-950/45 dark:text-indigo-400 rounded-xl transition-all cursor-pointer flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">visibility</span> View Transcript
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
