@extends('layouts.admin')

@section('content')
<div class="space-y-8 max-w-4xl mx-auto text-left">
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-500">
        <a href="{{ route('admin.users.index') }}" class="hover:text-indigo-600 transition-colors">Users</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-slate-400">Search History</span>
    </div>

    <!-- Header Banner -->
    <div class="relative rounded-3xl overflow-hidden shadow-lg border border-slate-200 bg-gradient-to-r from-slate-800 via-slate-900 to-indigo-950 p-6 sm:p-10 text-white">
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 space-y-3">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-red-650/40 text-red-200 border border-red-500/30 uppercase tracking-widest leading-none">
                <span class="material-symbols-outlined text-xs">history</span> Activity Logs
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight font-sans">
                {{ $user->name }}'s Search History
            </h1>
            <p class="text-sm sm:text-lg text-slate-355 max-w-xl font-medium leading-relaxed">
                Inspect and monitor search queries entered by this member.
            </p>
        </div>
    </div>

    <!-- History Card List -->
    <div class="border border-slate-200 bg-white dark:bg-slate-900 dark:border-slate-800 rounded-3xl p-6 shadow-xl space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-5 gap-3">
            <h2 class="text-lg font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-indigo-650">search</span> Query History
                <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 text-xs font-bold">{{ $history->total() }} entries</span>
            </h2>

            @if($history->isNotEmpty())
                <form action="{{ route('admin.users.search-history.clear', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to clear search history for this user?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 dark:bg-rose-950/20 dark:hover:bg-rose-950/40 dark:text-rose-400 text-xs font-bold rounded-xl transition-all cursor-pointer flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">delete_sweep</span> Clear History
                    </button>
                </form>
            @endif
        </div>

        @if($history->isEmpty())
            <div class="text-center py-12 text-slate-400 dark:text-slate-500 space-y-3">
                <span class="material-symbols-outlined text-5xl">manage_search</span>
                <p class="text-sm font-bold">No search history logs recorded for this user.</p>
                <a href="{{ route('admin.users.index') }}" class="inline-block px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-750 text-xs font-bold rounded-xl transition-all">Back to Users</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-505 dark:text-slate-400">
                    <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-950/40 text-slate-705 dark:text-slate-300 font-extrabold border-b border-slate-200 dark:border-slate-800">
                        <tr>
                            <th scope="col" class="px-6 py-4">Search Query</th>
                            <th scope="col" class="px-6 py-4">Date & Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 font-semibold text-slate-800 dark:text-slate-200">
                        @foreach($history as $entry)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-950/20 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-extrabold bg-slate-50 dark:bg-slate-950 px-2.5 py-1 rounded border border-slate-200 dark:border-slate-850">
                                        {{ $entry->query }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400">
                                    {{ $entry->created_at->format('M d, Y H:i:s') }} ({{ $entry->created_at->diffForHumans() }})
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-4">
                {{ $history->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
