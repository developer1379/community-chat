@extends('layouts.admin')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto text-left">
    <!-- Premium Admin Users Banner -->
    <div class="relative rounded-3xl overflow-hidden shadow-lg border border-slate-200 bg-gradient-to-r from-slate-800 via-slate-900 to-indigo-950 p-6 sm:p-10 text-white">
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 space-y-3">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-red-650/40 text-red-200 border border-red-500/30 uppercase tracking-widest leading-none">
                <span class="material-symbols-outlined text-xs">group</span> User Directory
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight font-sans">
                Community User Management
            </h1>
            <p class="text-sm sm:text-lg text-slate-350 max-w-2xl font-medium leading-relaxed">
                Oversee community members, send direct administrative notifications, suspend/reinstate accounts, and view user DMs for compliance.
            </p>
        </div>
    </div>

    <!-- Users Table Card -->
    <div class="border border-slate-200 bg-white dark:bg-slate-900 dark:border-slate-800 rounded-3xl p-6 shadow-xl space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-5">
            <h2 class="text-lg font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-indigo-650">manage_accounts</span> User Directory List
                <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 text-xs font-bold">{{ $users->count() }} members</span>
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-500 dark:text-slate-400">
                <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-950/40 text-slate-700 dark:text-slate-300 font-extrabold border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th scope="col" class="px-6 py-4">Member</th>
                        <th scope="col" class="px-6 py-4">Title Badge</th>
                        <th scope="col" class="px-6 py-4">Account Status</th>
                        <th scope="col" class="px-6 py-4">Coins Balance</th>
                        <th scope="col" class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 font-semibold text-slate-800 dark:text-slate-200">
                    @foreach($users as $user)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-950/20 transition-colors">
                            <td class="px-6 py-4.5 flex items-center gap-3">
                                <img class="w-10 h-10 rounded-full object-cover border border-slate-200 dark:border-slate-800" src="{{ $user->avatar_url }}" alt="avatar">
                                <div class="leading-tight">
                                    <p class="font-extrabold text-slate-900 dark:text-white">{{ $user->name }}</p>
                                    <p class="text-[11px] text-slate-400 font-medium">{{ $user->email }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase tracking-wider text-white" style="background: {{ $user->banner_color }}">{{ $user->title_badge }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->is_blocked)
                                    <span class="inline-flex items-center gap-1 text-xs text-rose-600 font-bold dark:text-rose-400">
                                        <span class="w-2 h-2 rounded-full bg-rose-650"></span> Suspended
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs text-emerald-600 font-bold dark:text-emerald-400">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Active
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-amber-600 dark:text-amber-400">
                                <span class="flex items-center gap-0.5"><span class="material-symbols-outlined text-[16px] text-amber-500">monetization_on</span>{{ $user->coins }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Send Notification -->
                                    <button onclick="openNotificationModal('{{ $user->id }}', '{{ $user->name }}')" class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 dark:bg-slate-800 dark:hover:bg-slate-750 dark:text-slate-300 transition-all cursor-pointer" title="Send Official Alert">
                                        <span class="material-symbols-outlined text-sm">notifications</span>
                                    </button>

                                    <!-- Read DM chats -->
                                    <a href="{{ route('admin.users.chats', $user->id) }}" class="p-2 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-650 dark:bg-indigo-950/20 dark:hover:bg-indigo-950/40 dark:text-indigo-400 transition-all cursor-pointer" title="Inspect DM Logs">
                                        <span class="material-symbols-outlined text-sm">chat</span>
                                    </a>

                                    <!-- View Search History -->
                                    <a href="{{ route('admin.users.search-history', $user->id) }}" class="p-2 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-650 dark:bg-emerald-950/20 dark:hover:bg-emerald-950/40 dark:text-emerald-400 transition-all cursor-pointer" title="Inspect Search History">
                                        <span class="material-symbols-outlined text-sm">history</span>
                                    </a>

                                    <!-- Block Toggle -->
                                    <form action="{{ route('admin.users.block', $user->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @if($user->is_blocked)
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-100 hover:bg-emerald-200 text-emerald-800 dark:bg-emerald-950/20 dark:hover:bg-emerald-950/40 dark:text-emerald-400 text-xs font-bold transition-all cursor-pointer">
                                                Reinstate
                                            </button>
                                        @else
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 dark:bg-rose-950/20 dark:hover:bg-rose-950/40 dark:text-rose-400 text-xs font-bold transition-all cursor-pointer">
                                                Suspend
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Send Alert Notification Overlay Modal -->
<div id="notify-user-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl scale-95 transform transition-all duration-350 space-y-6 text-left" id="notify-modal-box">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
            <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-1.5">
                <span class="material-symbols-outlined text-rose-550">notifications_active</span> Send Alert Notification
            </h3>
            <button onclick="closeNotificationModal()" class="text-slate-400 hover:text-slate-650 dark:hover:text-slate-200 cursor-pointer">
                <span class="material-symbols-outlined text-base">close</span>
            </button>
        </div>

        <form id="notify-user-form" action="" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <p class="text-xs text-slate-500 font-semibold mb-3">Sending system notification warning to user: <span id="notify-recipient-name" class="font-extrabold text-slate-900 dark:text-white"></span></p>
            </div>

            <div class="space-y-1.5">
                <label for="notif-title" class="block text-xs font-extrabold uppercase tracking-widest text-slate-505 dark:text-slate-400">Notification Title</label>
                <input type="text" name="title" id="notif-title" required placeholder="e.g. Profile Signature Warning" 
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 text-slate-850 dark:text-slate-100 text-xs focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all font-semibold">
            </div>

            <div class="space-y-1.5">
                <label for="notif-message" class="block text-xs font-extrabold uppercase tracking-widest text-slate-505 dark:text-slate-400">Message Content</label>
                <textarea name="message" id="notif-message" required rows="4" placeholder="Type instructions, warnings, or alerts to user here."
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 text-slate-855 dark:text-slate-100 text-xs focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all font-semibold"></textarea>
            </div>

            <!-- Screen Alert Checkbox -->
            <div class="flex items-center gap-2 py-1">
                <input type="checkbox" name="show_alert" id="show_alert" value="1" class="w-4 h-4 text-rose-650 bg-slate-100 border-slate-300 rounded focus:ring-rose-500 dark:focus:ring-rose-600 dark:ring-offset-slate-800 dark:bg-slate-700 dark:border-slate-650">
                <label for="show_alert" class="text-xs font-bold text-slate-700 dark:text-slate-300 select-none cursor-pointer flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px] text-amber-500">campaign</span> Display as modal popup screen alert on user login
                </label>
            </div>

            <div class="pt-2 flex items-center gap-3">
                <button type="submit" class="px-5 py-3 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs shadow-lg shadow-rose-500/20 transition-all cursor-pointer">
                    Send Notification
                </button>
                <button type="button" onclick="closeNotificationModal()" class="px-5 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-750 dark:text-slate-350 text-xs font-extrabold transition-all cursor-pointer">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openNotificationModal(userId, userName) {
        const modal = document.getElementById('notify-user-modal');
        const box = document.getElementById('notify-modal-box');
        const form = document.getElementById('notify-user-form');
        const nameSpan = document.getElementById('notify-recipient-name');

        if (modal && box && form && nameSpan) {
            nameSpan.innerText = userName;
            form.action = `/admin/users/${userId}/notify`;
            modal.classList.remove('opacity-0', 'pointer-events-none');
            box.classList.remove('scale-95');
            box.classList.add('scale-100');
        }
    }

    function closeNotificationModal() {
        const modal = document.getElementById('notify-user-modal');
        const box = document.getElementById('notify-modal-box');
        if (modal && box) {
            modal.classList.add('opacity-0', 'pointer-events-none');
            box.classList.remove('scale-100');
            box.classList.add('scale-95');
        }
    }
</script>
@endsection
