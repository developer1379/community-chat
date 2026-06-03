@extends('layouts.admin')

@section('content')
<div class="space-y-8 max-w-4xl mx-auto text-left">
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-500">
        <a href="{{ route('admin.users.index') }}" class="hover:text-indigo-600 transition-colors">Users</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <a href="{{ route('admin.users.chats', $user->id) }}" class="hover:text-indigo-600 transition-colors">Conversations Log</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-slate-400">Transcript</span>
    </div>

    <!-- Header Banner -->
    @php
        $other = $conversation->user_one_id === $user->id ? $conversation->userTwo : $conversation->userOne;
    @endphp
    <div class="relative rounded-3xl overflow-hidden shadow-lg border border-slate-200 bg-gradient-to-r from-slate-800 via-slate-900 to-indigo-950 p-6 sm:p-8 text-white flex items-center justify-between gap-4">
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="relative z-10 space-y-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold bg-red-650/40 text-red-200 border border-red-500/30 uppercase tracking-widest leading-none">
                <span class="material-symbols-outlined text-[12px]">list_alt</span> Transcript Log
            </span>
            <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight">
                {{ $user->name }} & {{ $other->name }}
            </h1>
            <p class="text-xs text-slate-350 font-medium">
                Reviewing full direct message transcripts between these members.
            </p>
        </div>

        <a href="{{ route('admin.users.chats', $user->id) }}" class="relative z-10 px-4 py-2 text-xs font-bold bg-white/10 hover:bg-white/20 text-white rounded-xl border border-white/15 transition-all">
            Back to Directory
        </a>
    </div>

    <!-- Chat Messages Transcript Log -->
    <div class="border border-slate-200 bg-white dark:bg-slate-900 dark:border-slate-800 rounded-3xl p-6 shadow-xl space-y-6 flex flex-col h-[550px] overflow-hidden">
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4 flex-shrink-0">
            <h2 class="text-sm font-extrabold text-slate-800 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-slate-500">forum</span> Log Stream
                <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 text-[10px] font-bold">{{ count($messages) }} messages</span>
            </h2>
        </div>

        @if(count($messages) === 0)
            <div class="flex-grow flex flex-col items-center justify-center text-slate-400 dark:text-slate-550 space-y-2">
                <span class="material-symbols-outlined text-4xl">inbox</span>
                <p class="text-xs font-bold">This conversation is empty.</p>
            </div>
        @else
            <!-- Scrollable Message List -->
            <div class="flex-grow overflow-y-auto space-y-4 p-2 custom-scrollbar">
                @foreach($messages as $msg)
                    @php
                        $isUser = $msg->sender_id === $user->id;
                        $sender = $isUser ? $user : $other;
                        $isImg = preg_match('/^https?:\/\/[^\s]+?\.(jpe?g|png|gif|webp|bmp)(?:\?[^\s]*)?$/i', trim($msg->body)) || 
                                 str_starts_with(trim($msg->body), 'https://i.ibb.co/');
                    @endphp
                    <div class="flex gap-3 {{ $isUser ? 'flex-row' : 'flex-row-reverse' }} items-start">
                        <!-- Sender Avatar -->
                        <img class="w-8 h-8 rounded-full object-cover border border-slate-200 dark:border-slate-850 flex-shrink-0" src="{{ $sender->avatar_url }}" alt="avatar">
                        
                        <!-- Content -->
                        <div class="space-y-1 max-w-[70%] {{ $isUser ? 'text-left' : 'text-right' }}">
                            <div class="flex items-center gap-1.5 {{ $isUser ? 'flex-row' : 'flex-row-reverse' }}">
                                <span class="text-[11px] font-extrabold text-slate-900 dark:text-white">{{ $sender->name }}</span>
                                <span class="text-[9px] text-slate-400 dark:text-slate-500 font-bold">{{ $msg->created_at->format('M d, H:i') }}</span>
                            </div>

                            <!-- Bubble -->
                            <div class="p-3 rounded-2xl border text-xs font-semibold {{ $isUser ? 'bg-slate-50 border-slate-150 text-slate-850 dark:bg-slate-950/20 dark:border-slate-850 dark:text-slate-200 rounded-tl-none' : 'bg-indigo-50/50 border-indigo-100 text-indigo-950 dark:bg-indigo-950/10 dark:border-indigo-900/40 dark:text-indigo-300 rounded-tr-none' }}">
                                @if($isImg)
                                    <img src="{{ $msg->body }}" class="max-w-xs max-h-48 rounded-xl object-contain border border-slate-200/50 dark:border-slate-850 cursor-pointer" onclick="window.open('{{ $msg->body }}', '_blank')">
                                @else
                                    <p class="whitespace-pre-wrap leading-relaxed">{{ $msg->body }}</p>
                                @endif
                                
                                @if($msg->is_edited)
                                    <span class="text-[8px] text-slate-400 dark:text-slate-500 italic block mt-1">(edited)</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
