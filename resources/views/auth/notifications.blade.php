@extends('layouts.app')

@section('content')
<div class="space-y-6 my-6 max-w-4xl mx-auto px-4 sm:px-6">
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1 text-left">
        <a href="{{ route('home') }}" class="hover:text-blue-600">Forums</a>
        <span>/</span>
        <span class="text-blue-600 font-semibold">Notifications</span>
    </div>

    <!-- Header Panel -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-left border-b border-slate-200 dark:border-slate-800 pb-5">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-950 dark:text-white tracking-tight flex items-center gap-2.5">
                <span class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/20 flex items-center justify-center text-blue-500 border border-blue-200 dark:border-blue-900/30 shadow-sm">
                    <span class="material-symbols-outlined text-lg">notifications</span>
                </span>
                Notifications
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-1">
                Stay updated with activity on your threads, posts, reactions, and followers.
            </p>
        </div>
        
        @if($notifications->count() > 0)
        <div class="flex items-center gap-2">
            <button onclick="markAllNotificationsAsRead()" class="flex items-center gap-1.5 px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs transition-all cursor-pointer">
                <span class="material-symbols-outlined text-sm">done_all</span>
                Mark All as Read
            </button>
        </div>
        @endif
    </div>

    <!-- Notifications List -->
    <div class="space-y-3">
        @forelse($notifications as $notification)
            @php
                $bgClass = $notification->is_read 
                    ? 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800' 
                    : 'bg-blue-50/30 dark:bg-blue-950/10 border-blue-200 dark:border-blue-900/50';
                
                $borderAccent = $notification->is_read 
                    ? 'border-l-4 border-transparent' 
                    : 'border-l-4 border-blue-500';

                // Determine icon and color based on title/type
                $icon = 'notifications';
                $iconColorClass = 'text-blue-500 bg-blue-50 dark:bg-blue-950/35 border-blue-100 dark:border-blue-900/20';
                
                $titleLower = strtolower($notification->title);
                if (str_contains($titleLower, 'reply')) {
                    $icon = 'forum';
                    $iconColorClass = 'text-blue-500 bg-blue-50 dark:bg-blue-950/35 border-blue-100 dark:border-blue-900/20';
                } elseif (str_contains($titleLower, 'reaction') || str_contains($titleLower, 'like')) {
                    $icon = 'thumb_up';
                    $iconColorClass = 'text-amber-500 bg-amber-50 dark:bg-amber-950/35 border-amber-100 dark:border-amber-900/20';
                } elseif (str_contains($titleLower, 'follow')) {
                    $icon = 'person_add';
                    $iconColorClass = 'text-emerald-500 bg-emerald-50 dark:bg-emerald-950/35 border-emerald-100 dark:border-emerald-900/20';
                } elseif (str_contains($titleLower, 'alert') || str_contains($titleLower, 'warning') || $notification->show_alert) {
                    $icon = 'warning';
                    $iconColorClass = 'text-rose-500 bg-rose-50 dark:bg-rose-950/35 border-rose-100 dark:border-rose-900/20';
                }
            @endphp
            
            <a href="{{ route('notifications.read', $notification->id) }}" class="block text-left transition-all hover:scale-[1.005]">
                <div class="flex items-start gap-4 p-4 border rounded-2xl shadow-sm transition-all hover:shadow-md hover:border-blue-300 dark:hover:border-blue-800 {{ $bgClass }} {{ $borderAccent }}">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center border {{ $iconColorClass }} flex-shrink-0">
                        <span class="material-symbols-outlined text-lg">{{ $icon }}</span>
                    </div>
                    
                    <div class="flex-grow min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white truncate">
                                {{ $notification->title }}
                            </h3>
                            <span class="text-[10px] text-slate-400 dark:text-slate-500 whitespace-nowrap font-medium">
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-650 dark:text-slate-400 mt-1 leading-relaxed">
                            {{ $notification->message }}
                        </p>
                    </div>

                    @if(!$notification->is_read)
                        <div class="w-2 h-2 rounded-full bg-blue-500 mt-1.5 flex-shrink-0 animate-pulse"></div>
                    @endif
                </div>
            </a>
        @empty
            <div class="flex flex-col items-center justify-center py-16 px-4 border border-dashed border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 rounded-3xl text-center">
                <span class="material-symbols-outlined text-4xl text-slate-350 dark:text-slate-650 mb-3 animate-pulse">notifications_off</span>
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">All caught up!</h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1 max-w-sm">
                    You have no new or past notifications at this time. We will let you know when action happens!
                </p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>

<script>
function markAllNotificationsAsRead() {
    fetch('/notifications/system/clear', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            window.location.reload();
        }
    })
    .catch(err => console.error('Error clearing system notifications:', err));
}
</script>
@endsection
