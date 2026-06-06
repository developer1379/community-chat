@foreach($transactions as $tx)
    <tr class="hover:bg-slate-50/30 dark:hover:bg-slate-800/10 transition-colors">
        <td class="px-4 py-3 text-left">
            <p class="font-bold text-slate-800 dark:text-slate-200 leading-tight">{{ $tx->description }}</p>
        </td>
        <td class="px-4 py-3 text-left">
            @php
                $label = match($tx->type) {
                    'thread_created' => '📚 Thread Created',
                    'reply_posted' => '💬 Reply Posted',
                    'reaction_received' => '💖 Like Received',
                    'reaction_removed' => '💔 Like Removed',
                    'welcome_gift' => '🎁 Welcome Gift',
                    default => '🪙 System Adjustment'
                };
                $badgeClass = match($tx->type) {
                    'thread_created' => 'bg-indigo-50 dark:bg-indigo-950/20 text-indigo-700 dark:text-indigo-400 border-indigo-150 dark:border-indigo-900/30',
                    'reply_posted' => 'bg-blue-50 dark:bg-blue-950/20 text-blue-700 dark:text-blue-400 border-blue-150 dark:border-blue-900/30',
                    'reaction_received' => 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border-emerald-150 dark:border-emerald-900/30',
                    'reaction_removed' => 'bg-rose-50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-400 border-rose-150 dark:border-rose-900/30',
                    default => 'bg-slate-50 dark:bg-slate-950/20 text-slate-700 dark:text-slate-400 border-slate-150 dark:border-slate-800'
                };
            @endphp
            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[9px] font-bold border {{ $badgeClass }}">
                {{ $label }}
            </span>
        </td>
        <td class="px-4 py-3 text-right">
            @if($tx->amount > 0)
                <span class="font-extrabold text-emerald-600 dark:text-emerald-400 text-xs">+{{ $tx->amount }} 🪙</span>
            @else
                <span class="font-extrabold text-rose-600 dark:text-rose-455 text-xs">{{ $tx->amount }} 🪙</span>
            @endif
        </td>
        <td class="px-4 py-3 text-right text-slate-400 text-[10px]">
            {{ \Carbon\Carbon::parse($tx->created_at)->diffForHumans() }}
        </td>
    </tr>
@endforeach
