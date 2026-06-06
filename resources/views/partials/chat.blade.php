@auth
    <style>
        @media (min-width: 640px) {
            #chat-drawer-container.chat-fullscreen {
                width: calc(100% - 3rem) !important;
                height: calc(100% - 3rem) !important;
                bottom: 1.5rem !important;
                right: 1.5rem !important;
                top: 1.5rem !important;
                left: 1.5rem !important;
                max-width: 1200px !important;
                margin: auto !important;
                border-radius: 1.25rem !important;
                flex-direction: row !important; /* Side-by-side split layout */
            }

            /* Hide standard global header in fullscreen mode */
            #chat-drawer-container.chat-fullscreen > #chat-global-header {
                display: none !important;
            }

            /* Force display both left sidebar & right chat pane side-by-side (30/70 Ratio) */
            #chat-drawer-container.chat-fullscreen #chat-conversations-view {
                display: flex !important;
                width: 30% !important;
                border-right: 1px solid #e2e8f0 !important;
                flex-shrink: 0 !important;
            }
            .dark #chat-drawer-container.chat-fullscreen #chat-conversations-view {
                border-right-color: #1e293b !important;
            }

            #chat-drawer-container.chat-fullscreen #chat-messages-view {
                display: flex !important;
                width: 70% !important;
                flex-grow: 1 !important;
            }

            /* Show local split headers only in fullscreen mode */
            #chat-drawer-container.chat-fullscreen .chat-local-header {
                display: flex !important;
            }
        }
    </style>
    <!-- sliding chat panel drawer -->
    <div id="chat-drawer-container" class="chat-drawer translate-x-full fixed inset-0 sm:inset-auto sm:bottom-6 sm:right-6 sm:w-96 sm:h-[500px] z-50 bg-white border border-slate-200 sm:rounded-2xl shadow-2xl flex flex-col overflow-hidden pointer-events-auto dark:bg-slate-900 dark:border-slate-800">
        
        <!-- Global Header area (Hidden in fullscreen desktop split layout) -->
        <div id="chat-global-header" class="px-4 py-3 bg-blue-600 text-white flex items-center justify-between shadow-sm flex-shrink-0">
            <div class="flex items-center gap-2">
                <!-- Back Button (shown only when in active conversation view) -->
                <button id="chat-back-btn" onclick="showConversationsList()" class="hidden hover:bg-white/10 rounded-lg p-1 transition-colors cursor-pointer">
                    <span class="material-symbols-outlined text-base">arrow_back</span>
                </button>
                <div class="leading-tight">
                    <h3 id="chat-title" class="font-bold text-xs">Direct Messages</h3>
                    <p id="chat-subtitle" class="text-[9px] text-blue-100 font-medium">Conversations</p>
                </div>
            </div>
            <div class="flex items-center gap-1.5">
                <button onclick="toggleChatFullscreen()" id="chat-fullscreen-btn" class="hidden sm:inline-flex hover:bg-white/10 rounded-lg p-1 transition-colors cursor-pointer" title="Toggle Fullscreen">
                    <span class="material-symbols-outlined text-base" id="chat-fullscreen-icon">fullscreen</span>
                </button>
                <button onclick="toggleChatDrawer()" class="hover:bg-white/10 rounded-lg p-1 transition-colors cursor-pointer" title="Close Panel">
                    <span class="material-symbols-outlined text-base">close</span>
                </button>
            </div>
        </div>

        <!-- Conversations List View -->
        <div id="chat-conversations-view" class="flex-grow flex flex-col overflow-hidden relative dark:bg-slate-900">
            <!-- Local Sidebar Header (Visible only in fullscreen) -->
            <div class="chat-local-header hidden px-4 py-3 bg-blue-600 text-white items-center justify-between flex-shrink-0">
                <div class="leading-tight">
                    <h3 class="font-bold text-xs">Direct Messages</h3>
                    <p class="text-[9px] text-blue-100 font-medium">Conversations</p>
                </div>
            </div>

            <!-- Search bar to start new conversation -->
            <div class="p-3 border-b border-slate-100 bg-slate-50 flex flex-col gap-2 flex-shrink-0 relative dark:bg-slate-950/40 dark:border-slate-850">
                <div class="flex items-center gap-2">
                    <div class="relative flex-grow">
                        <input type="text" id="chat-search-input" oninput="handleSearchInputChange(this)" class="w-full bg-white border border-slate-250 rounded-lg pl-8 pr-3 py-1.5 text-[11px] text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent transition-all placeholder:text-slate-400 font-medium dark:bg-slate-950 dark:border-slate-800 dark:text-slate-100 dark:focus:ring-blue-500" placeholder="Search user by name..." autocomplete="off">
                        <span class="material-symbols-outlined absolute left-2.5 top-2 text-slate-400 text-xs dark:text-slate-500">search</span>
                    </div>
                </div>
                <!-- Suggestions Dropdown results panel -->
                <div id="chat-search-suggestions" class="hidden absolute left-3 right-3 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-xl z-30 divide-y divide-slate-100 overflow-y-auto max-h-48 custom-scrollbar dark:bg-slate-900 dark:border-slate-800 dark:divide-slate-800">
                    <!-- Suggested entries go here dynamically -->
                </div>
            </div>

            <!-- Admins quick start bar -->
            <div id="chat-admins-container" class="px-4 py-2.5 border-b border-slate-100 bg-slate-50/50 flex flex-col gap-1.5 flex-shrink-0 dark:bg-slate-950/20 dark:border-slate-850 hidden">
                <span class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest flex items-center gap-1">
                    <span class="material-symbols-outlined text-xs text-blue-500">verified_user</span> Quick Admin Help
                </span>
                <div id="chat-admins-list" class="flex items-center gap-3 overflow-x-auto pb-1 custom-scrollbar">
                    <!-- Dynamic admins list -->
                </div>
            </div>

            <!-- List of existing conversations -->
            <div id="chat-conversations-list" class="flex-grow overflow-y-auto divide-y divide-slate-100 custom-scrollbar p-1 dark:divide-slate-850">
                <!-- Loaded dynamically by JS -->
                <div class="py-12 text-center text-xs text-slate-400 font-medium dark:text-slate-500">
                    <span class="animate-pulse">Loading conversations...</span>
                </div>
            </div>
        </div>

        <!-- Thread Messages View (Hidden initially, but side-by-side in fullscreen mode) -->
        <div id="chat-messages-view" class="hidden flex-grow flex flex-col overflow-hidden bg-slate-50/50 dark:bg-slate-950/20">
            <!-- Local Main Chat Header (Visible only in fullscreen) -->
            <div class="chat-local-header hidden px-4 py-3 bg-blue-600 text-white items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-2">
                    <div class="leading-tight">
                        <h3 id="chat-main-title" class="font-bold text-xs">Select a conversation</h3>
                        <p id="chat-main-subtitle" class="text-[9px] text-blue-100 font-medium">Offline</p>
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    <button onclick="toggleChatFullscreen()" class="hover:bg-white/10 rounded-lg p-1 transition-colors cursor-pointer" title="Exit Fullscreen">
                        <span class="material-symbols-outlined text-base">fullscreen_exit</span>
                    </button>
                    <button onclick="toggleChatDrawer()" class="hover:bg-white/10 rounded-lg p-1 transition-colors cursor-pointer" title="Close Panel">
                        <span class="material-symbols-outlined text-base">close</span>
                    </button>
                </div>
            </div>

            <!-- Loading Indicator -->
            <div id="chat-messages-loading" class="hidden absolute inset-0 bg-white/80 z-10 flex items-center justify-center dark:bg-slate-900/80">
                <span class="animate-pulse text-xs font-bold text-blue-600 dark:text-blue-400">Retrieving messages...</span>
            </div>

            <!-- No Conversation Selected Fallback Box -->
            <div id="chat-no-conversation-selected" class="hidden flex-grow flex-col items-center justify-center text-center p-8 bg-slate-50/50 dark:bg-slate-950/20 select-none">
                <div class="w-14 h-14 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 dark:text-slate-500 mb-3 shadow-inner">
                    <span class="material-symbols-outlined text-2xl">chat_bubble</span>
                </div>
                <h4 class="font-bold text-slate-700 dark:text-slate-350 text-xs">No Conversation Active</h4>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 max-w-[220px] mt-1 leading-normal">Choose a conversation from the sidebar list or search for a member name to start a new chat.</p>
            </div>

            <!-- Chat Message Thread Content Wrapper -->
            <div id="chat-messages-content" class="flex-grow flex flex-col overflow-hidden">
                <!-- Messages list -->
                <div id="chat-messages-list" class="flex-grow overflow-y-auto p-4 space-y-3.5 custom-scrollbar">
                    <!-- Loaded dynamically by JS -->
                </div>

                <!-- Input reply container -->
                <div class="p-3 border-t border-slate-200/80 bg-white dark:border-slate-850 dark:bg-slate-900 flex-shrink-0">
                    <form id="chat-send-form" onsubmit="handleSendSubmit(event)" class="flex items-center gap-2">
                        <!-- Hidden file input for images/GIFs -->
                        <input type="file" id="chat-file-input" class="hidden" accept="image/*" onchange="handleChatFileSelect(this)">
                        <button type="button" onclick="document.getElementById('chat-file-input').click()" class="w-8.5 h-8.5 rounded-xl bg-slate-50 border border-slate-200/60 hover:bg-slate-100 hover:border-slate-350 text-slate-500 flex items-center justify-center cursor-pointer transition-all active:scale-95 flex-shrink-0 dark:bg-slate-950 dark:border-slate-800 dark:hover:bg-slate-850 dark:text-slate-400" title="Attach Image or GIF">
                            <span class="material-symbols-outlined text-base">image</span>
                        </button>

                        <input type="text" id="chat-message-input" class="flex-grow bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent transition-all placeholder:text-slate-400 font-medium dark:bg-slate-950 dark:border-slate-800 dark:text-slate-100" placeholder="Type a message..." autocomplete="off">
                        <button type="submit" class="w-8.5 h-8.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center cursor-pointer shadow-md shadow-blue-500/10 transition-transform active:scale-95 flex-shrink-0">
                            <span class="material-symbols-outlined text-sm">send</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- Chat Engine Controller Script -->
    <script>
        const currentUserId = '{{ Auth::id() }}';
        let chatOpen = false;
        let activeConversationId = null;
        let activeConversationPartner = null;
        let chatPollingInterval = null;
        let badgePollingInterval = null;

        // Toggle visibility of the chat drawer
        function toggleChatDrawer() {
            const drawer = document.getElementById('chat-drawer-container');
            if (!drawer) return;

            chatOpen = !chatOpen;
            if (chatOpen) {
                drawer.classList.remove('translate-x-full');
                drawer.classList.add('active');
                loadConversations();
                startChatPolling();

                // If starting in fullscreen, configure layout states
                if (drawer.classList.contains('chat-fullscreen')) {
                    const noConv = document.getElementById('chat-no-conversation-selected');
                    const msgContent = document.getElementById('chat-messages-content');
                    if (activeConversationId) {
                        if (noConv) {
                            noConv.classList.add('hidden');
                            noConv.classList.remove('sm:flex');
                        }
                        if (msgContent) msgContent.classList.remove('hidden');
                    } else {
                        if (noConv) {
                            noConv.classList.remove('hidden');
                            noConv.classList.add('sm:flex');
                        }
                        if (msgContent) msgContent.classList.add('hidden');
                    }
                }
            } else {
                drawer.classList.add('translate-x-full');
                drawer.classList.remove('active');
                drawer.classList.remove('chat-fullscreen');
                const icon = document.getElementById('chat-fullscreen-icon');
                if (icon) {
                    icon.innerText = 'fullscreen';
                    icon.title = 'Toggle Fullscreen';
                }
                stopChatPolling();
            }
        }

        // Toggle fullscreen mode on desktop (WhatsApp/Telegram split side-by-side mode)
        function toggleChatFullscreen() {
            const drawer = document.getElementById('chat-drawer-container');
            const icon = document.getElementById('chat-fullscreen-icon');
            if (!drawer || !icon) return;

            drawer.classList.toggle('chat-fullscreen');
            
            const noConv = document.getElementById('chat-no-conversation-selected');
            const msgContent = document.getElementById('chat-messages-content');

            if (drawer.classList.contains('chat-fullscreen')) {
                icon.innerText = 'fullscreen_exit';
                icon.title = 'Exit Fullscreen';
                
                // Show side-by-side split layout: If conversation active show messages, otherwise show selection fallback
                if (activeConversationId) {
                    if (noConv) {
                        noConv.classList.add('hidden');
                        noConv.classList.remove('sm:flex');
                    }
                    if (msgContent) msgContent.classList.remove('hidden');
                } else {
                    if (noConv) {
                        noConv.classList.remove('hidden');
                        noConv.classList.add('sm:flex');
                    }
                    if (msgContent) msgContent.classList.add('hidden');
                }
            } else {
                icon.innerText = 'fullscreen';
                icon.title = 'Toggle Fullscreen';
                
                // Normal compact single-column mode: Hide selection fallback
                if (noConv) {
                    noConv.classList.add('hidden');
                    noConv.classList.remove('sm:flex');
                }
                if (msgContent) msgContent.classList.remove('hidden');

                // Toggle views based on active state
                if (activeConversationId) {
                    document.getElementById('chat-conversations-view').classList.add('hidden');
                    document.getElementById('chat-messages-view').classList.remove('hidden');
                } else {
                    document.getElementById('chat-conversations-view').classList.remove('hidden');
                    document.getElementById('chat-messages-view').classList.add('hidden');
                }
            }
            
            // Auto scroll messages to bottom on size change
            const listContainer = document.getElementById('chat-messages-list');
            if (listContainer) {
                setTimeout(() => {
                    listContainer.scrollTop = listContainer.scrollHeight;
                }, 100);
            }
        }

        // Poll global unread count and populate notification dropdown
        // Poll global unread count and populate notification dropdown
        function checkUnreadBadge() {
            Promise.all([
                fetch('/dms/unread-count').then(r => r.json()),
                fetch('/notifications/system').then(r => r.json())
            ])
            .then(([chatData, systemNotifs]) => {
                const badge = document.getElementById('global-chat-badge');
                const notifyBadge = document.getElementById('global-notifications-badge');
                const chatCount = chatData.unread_count || 0;
                const systemCount = systemNotifs.length || 0;

                // 1. Update primary chat quick icon badge
                if (badge) {
                    if (chatCount > 0) {
                        badge.innerText = chatCount;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }

                // 2. Update Combined Notification Badge (direct chat unreads + system notifications!)
                if (notifyBadge) {
                    const totalNotifications = chatCount + systemCount;
                    notifyBadge.innerText = totalNotifications;
                    if (totalNotifications > 0) {
                        notifyBadge.classList.remove('hidden');
                    } else {
                        notifyBadge.classList.add('hidden');
                    }
                }

                // Check for Screen Alert Popups
                systemNotifs.forEach(n => {
                    if (n.show_alert) {
                        Swal.fire({
                            title: n.title,
                            text: n.message,
                            icon: 'warning',
                            confirmButtonColor: '#e11d48',
                            confirmButtonText: 'I Understand'
                        });

                        // Dismiss this screen alert immediately so it doesn't pop up again
                        fetch(`/notifications/system/${n.id}/dismiss-alert`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        }).catch(e => console.error('Error dismissing system notification alert:', e));
                    }
                });

                // 3. Populate direct chat alert activities in the notifications list dynamically
                fetch('/dms/conversations')
                    .then(res => res.json())
                    .then(conversations => {
                        const list = document.getElementById('notifications-dropdown-list');
                        if (!list) return;

                        // Extract only unread conversations
                        const unreads = conversations.filter(c => c.unread_count > 0);
                        
                        let html = '<div class="space-y-1 divide-y divide-slate-100 dark:divide-slate-800">';
                        
                        // Insert system notifications
                        systemNotifs.forEach(n => {
                            let borderClass = 'border-rose-500 dark:border-rose-900/60';
                            let bgClass = 'bg-rose-50/60 hover:bg-rose-50/80 dark:bg-rose-950/20';
                            let tagColor = 'text-rose-600 bg-rose-100 dark:text-rose-400 dark:bg-rose-900/40';
                            let tagLabel = 'Alert';

                            const titleLower = n.title.toLowerCase();
                            if (titleLower.includes('reply')) {
                                borderClass = 'border-blue-500 dark:border-blue-900/60';
                                bgClass = 'bg-blue-50/60 hover:bg-blue-50/80 dark:bg-blue-950/20';
                                tagColor = 'text-blue-600 bg-blue-100 dark:text-blue-400 dark:bg-blue-900/40';
                                tagLabel = 'Reply';
                            } else if (titleLower.includes('reaction') || titleLower.includes('like')) {
                                borderClass = 'border-amber-500 dark:border-amber-900/60';
                                bgClass = 'bg-amber-50/60 hover:bg-amber-50/80 dark:bg-amber-950/20';
                                tagColor = 'text-amber-600 bg-amber-100 dark:text-amber-400 dark:bg-amber-900/40';
                                tagLabel = 'Reaction';
                            } else if (titleLower.includes('follow')) {
                                borderClass = 'border-emerald-500 dark:border-emerald-900/60';
                                bgClass = 'bg-emerald-50/60 hover:bg-emerald-50/80 dark:bg-emerald-950/20';
                                tagColor = 'text-emerald-600 bg-emerald-100 dark:text-emerald-400 dark:bg-emerald-900/40';
                                tagLabel = 'Follow';
                            }

                            html += `
                                <a href="/notifications/${n.id}/read" class="block p-2 rounded-lg ${bgClass} transition-all text-xs border-l-2 ${borderClass}">
                                    <div class="flex items-center justify-between">
                                        <p class="font-bold text-slate-800 dark:text-slate-200 flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                                            ${escapeHtml(n.title)}
                                        </p>
                                        <span class="text-[7.5px] font-extrabold ${tagColor} px-1 rounded uppercase tracking-wider">${tagLabel}</span>
                                    </div>
                                    <p class="text-[10px] text-slate-650 dark:text-slate-400 mt-0.5">${escapeHtml(n.message)}</p>
                                    <p class="text-[8.5px] text-slate-450 dark:text-slate-500 font-bold mt-0.5">${new Date(n.created_at).toLocaleDateString()}</p>
                                </a>
                            `;
                        });

                        // Insert direct message triggers
                        unreads.forEach(c => {
                            let bodyPreview = 'Sent a message';
                            if (c.last_message) {
                                const isImg = /^https?:\/\/[^\s]+?\.(jpe?g|png|gif|webp|bmp)(?:\?[^\s]*)?$/i.test(c.last_message.body.trim()) || 
                                              c.last_message.body.trim().startsWith('https://i.ibb.co/');
                                bodyPreview = isImg ? '📷 Image attachment' : c.last_message.body;
                            }

                            html += `
                                <div onclick="toggleDropdown('notify-dropdown'); startDirectChat('${c.other_user.name}')" class="block p-2 rounded-lg bg-blue-50/60 hover:bg-blue-50 transition-all text-xs cursor-pointer border-l-2 border-blue-500 dark:bg-blue-950/20 dark:border-blue-900/60">
                                    <div class="flex items-center justify-between">
                                        <p class="font-bold text-slate-800 dark:text-slate-200 flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-soft-pulse"></span>
                                            New Message from ${c.other_user.name}
                                        </p>
                                        <span class="text-[7.5px] font-bold text-blue-600 bg-blue-100/70 px-1 rounded uppercase tracking-wider dark:text-blue-400 dark:bg-blue-900/40">Chat</span>
                                    </div>
                                    <p class="text-[10px] text-slate-650 dark:text-slate-400 truncate mt-0.5">${escapeHtml(bodyPreview)}</p>
                                    <p class="text-[8px] text-slate-450 dark:text-slate-500 font-bold mt-0.5">${c.last_message ? c.last_message.created_at : ''}</p>
                                </div>
                            `;
                        });

                        // Add fallback/static forum announcements at bottom
                        html += `
                            <a href="#" class="block p-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-all text-xs pt-2">
                                <p class="font-bold text-slate-800 dark:text-slate-200">Welcome to XenProfessional!</p>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">Customize your forum signature under quick settings.</p>
                            </a>
                            <a href="#" class="block p-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-all text-xs pt-2">
                                <p class="font-bold text-slate-800 dark:text-slate-200">Admin Replied</p>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">Founder admin replied in General Discussion.</p>
                            </a>
                        </div>`;

                        list.innerHTML = html;
                    })
                    .catch(err => console.error('Error listing notifications:', err));
            })
            .catch(e => console.error('Error fetching counts:', e));
        }

        // Clear all direct chat unread alerts locally/remotely
        function clearAllNotificationsLocal(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }

            // Clear static notification elements on view
            const staticEl = document.getElementById('forum-notifications-static');
            if (staticEl) {
                staticEl.innerHTML = `
                    <div class="p-4 text-center text-slate-400 text-[10px] font-bold">
                        No new notifications
                    </div>
                `;
            }

            // Hide global notifications badge
            const notifBadge = document.getElementById('global-notifications-badge');
            if (notifBadge) {
                notifBadge.innerText = '0';
                notifBadge.classList.add('hidden');
            }
            
            // Clear system notifications in database
            fetch('/notifications/system/clear', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).catch(err => console.error('Error clearing system notifications:', err));
            
            fetch('/dms/conversations')
                .then(r => r.json())
                .then(conversations => {
                    const unreads = conversations.filter(c => c.unread_count > 0);
                    if (unreads.length === 0) return;

                    const promises = unreads.map(c => {
                        return fetch(`/dms/conversations/${c.id}/read`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                    });

                    Promise.all(promises)
                        .then(() => {
                            checkUnreadBadge();
                            if (chatOpen) loadConversations();
                        })
                        .catch(err => console.error('Error clearing conversations:', err));
                });
        }

        // View All Notifications (Toggles open chat drawer to review conversations)
        function openAllNotificationsPage(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            toggleDropdown('notify-dropdown');
            if (!chatOpen) toggleChatDrawer();
        }

        // Initialize unread check and set recurring schedule
        document.addEventListener('DOMContentLoaded', () => {
            checkUnreadBadge();
            
            // Poll global badge every 10 seconds, but ONLY when tab is focused and active
            badgePollingInterval = setInterval(() => {
                if (document.visibilityState === 'visible') {
                    checkUnreadBadge();
                }
            }, 10000);
        });

        // Load quick start admin list
        function loadAdmins() {
            const container = document.getElementById('chat-admins-container');
            const list = document.getElementById('chat-admins-list');
            if (!container || !list) return;

            fetch('/dms/admins')
                .then(r => r.json())
                .then(admins => {
                    if (admins.length === 0) {
                        container.classList.add('hidden');
                        return;
                    }

                    container.classList.remove('hidden');
                    let html = '';
                    admins.forEach(admin => {
                        html += `
                            <div onclick="startDirectChat('${escapeHtml(admin.name)}')" class="flex flex-col items-center gap-1 cursor-pointer flex-shrink-0 group">
                                <div class="w-8.5 h-8.5 rounded-full overflow-hidden border-2 border-blue-500/20 group-hover:border-blue-500 transition-all flex-shrink-0 shadow-sm relative">
                                    <img src="${admin.avatar_url}" class="w-full h-full object-cover">
                                </div>
                                <span class="text-[8px] font-extrabold text-slate-500 dark:text-slate-400 group-hover:text-blue-500 transition-colors truncate w-12 text-center leading-none">${admin.name}</span>
                            </div>
                        `;
                    });
                    list.innerHTML = html;
                })
                .catch(e => {
                    console.error('Admins list error:', e);
                    container.classList.add('hidden');
                });
        }

        // Load list of conversations
        function loadConversations() {
            loadAdmins();
            const listContainer = document.getElementById('chat-conversations-list');
            if (!listContainer) return;

            fetch('/dms/conversations')
                .then(r => r.json())
                .then(conversations => {
                    if (conversations.length === 0) {
                        listContainer.innerHTML = `
                            <div class="py-16 text-center text-xs text-slate-400 font-semibold p-4">
                                <span class="material-symbols-outlined text-3xl opacity-40 mb-1.5">chat_bubble</span>
                                <p>No direct messages yet.</p>
                                <p class="text-[10px] text-slate-350 mt-1">Start chatting by searching a user name above or clicking "Send Message" on their profile!</p>
                            </div>
                        `;
                        return;
                    }

                    let html = '';
                    conversations.forEach(conv => {
                        const hasUnread = conv.unread_count > 0;
                        const activeClass = activeConversationId === conv.id ? 'bg-slate-50 border-l-2 border-blue-600' : '';
                        
                        let bodyPreview = 'Conversation started';
                        if (conv.last_message) {
                            const isImg = /^https?:\/\/[^\s]+?\.(jpe?g|png|gif|webp|bmp)(?:\?[^\s]*)?$/i.test(conv.last_message.body.trim()) || 
                                          conv.last_message.body.trim().startsWith('https://i.ibb.co/');
                            bodyPreview = isImg ? '📷 Image attachment' : conv.last_message.body;
                        }

                        html += `
                            <div onclick="openConversation('${conv.id}', '${conv.other_user.name}')" class="p-3 hover:bg-slate-50/80 transition-colors cursor-pointer flex items-center justify-between gap-3 ${activeClass}">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-9 h-9 rounded-xl overflow-hidden border border-slate-200 flex-shrink-0">
                                        <img src="${conv.other_user.avatar_url}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="min-w-0 leading-tight">
                                        <h4 class="font-bold text-slate-800 text-[11px] truncate flex items-center gap-1.5">
                                            ${conv.other_user.name}
                                            <span class="text-[7px] px-1 bg-slate-100 text-slate-500 rounded uppercase tracking-wider">${conv.other_user.title_badge || 'Member'}</span>
                                        </h4>
                                        <p class="text-[10px] text-slate-450 truncate mt-0.5 ${hasUnread ? 'font-extrabold text-slate-800' : ''}">
                                            ${escapeHtml(bodyPreview)}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-1.5 flex-shrink-0 text-right leading-none">
                                    <span class="text-[8px] text-slate-400 font-bold">${conv.last_message ? conv.last_message.created_at : ''}</span>
                                    ${hasUnread ? `<span class="w-2 h-2 rounded-full bg-blue-600 animate-soft-pulse"></span>` : ''}
                                </div>
                            </div>
                        `;
                    });
                    listContainer.innerHTML = html;
                })
                .catch(e => {
                    listContainer.innerHTML = `<div class="py-8 text-center text-xs text-rose-500 font-medium">Failed to retrieve conversations.</div>`;
                    console.error('Conversations list error:', e);
                });
        }

        // Helper to query and update the active conversation partner's presence
        function updateChatHeaderPresence(partnerName) {
            if (!partnerName) return;
            const subtitle = document.getElementById('chat-subtitle');
            const mainSubtitle = document.getElementById('chat-main-subtitle');
            if (!subtitle && !mainSubtitle) return;
            
            fetch(`/dms/user-card/${encodeURIComponent(partnerName)}`)
                .then(r => r.json())
                .then(data => {
                    let html = '';
                    if (data.is_online) {
                        html = `<span class="flex items-center gap-1 text-[9px] text-emerald-500 font-bold"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Online</span>`;
                    } else {
                        html = `<span class="flex items-center gap-1 text-[9px] text-slate-400 font-bold"><span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> ${data.last_active}</span>`;
                    }
                    if (subtitle) subtitle.innerHTML = html;
                    if (mainSubtitle) mainSubtitle.innerHTML = html;
                })
                .catch(() => {
                    if (subtitle) subtitle.innerText = 'Offline';
                    if (mainSubtitle) mainSubtitle.innerText = 'Offline';
                });
        }

        // Open a specific conversation thread
        function openConversation(convId, partnerName) {
            activeConversationId = convId;
            activeConversationPartner = partnerName;

            // Show and hide correct panels
            document.getElementById('chat-conversations-view').classList.add('hidden');
            document.getElementById('chat-messages-view').classList.remove('hidden');
            document.getElementById('chat-back-btn').classList.remove('hidden');

            // Set Header details
            document.getElementById('chat-title').innerText = partnerName;
            const subtitle = document.getElementById('chat-subtitle');
            if (subtitle) subtitle.innerHTML = `<span class="animate-pulse opacity-70">Connecting...</span>`;

            // Set Local Fullscreen Header details
            const mainTitle = document.getElementById('chat-main-title');
            if (mainTitle) mainTitle.innerText = partnerName;
            const mainSubtitle = document.getElementById('chat-main-subtitle');
            if (mainSubtitle) mainSubtitle.innerHTML = `<span class="animate-pulse opacity-70">Connecting...</span>`;

            // Handle panel displays for fullscreen side-by-side mode
            const noConv = document.getElementById('chat-no-conversation-selected');
            const msgContent = document.getElementById('chat-messages-content');
            if (noConv) {
                noConv.classList.add('hidden');
                noConv.classList.remove('sm:flex');
            }
            if (msgContent) msgContent.classList.remove('hidden');

            // Instantly query presence details
            updateChatHeaderPresence(partnerName);

            loadMessages(true);
            
            // Highlight active in list loader behind the scenes
            loadConversations();

            // Restart poller with faster 3s interval for active messaging stream
            if (chatOpen) {
                startChatPolling();
            }
        }

        // Close thread back to conversations list
        function showConversationsList() {
            activeConversationId = null;
            activeConversationPartner = null;

            document.getElementById('chat-messages-view').classList.add('hidden');
            document.getElementById('chat-conversations-view').classList.remove('hidden');
            document.getElementById('chat-back-btn').classList.add('hidden');

            document.getElementById('chat-title').innerText = 'Direct Messages';
            const subtitle = document.getElementById('chat-subtitle');
            if (subtitle) subtitle.innerText = 'Conversations';

            // Reset Local Fullscreen Header details
            const mainTitle = document.getElementById('chat-main-title');
            if (mainTitle) mainTitle.innerText = 'Select a conversation';
            const mainSubtitle = document.getElementById('chat-main-subtitle');
            if (mainSubtitle) mainSubtitle.innerText = 'Offline';

            // Reset panel displays for fullscreen side-by-side mode
            const noConv = document.getElementById('chat-no-conversation-selected');
            const msgContent = document.getElementById('chat-messages-content');
            if (noConv) {
                noConv.classList.remove('hidden');
                noConv.classList.add('sm:flex');
            }
            if (msgContent) msgContent.classList.add('hidden');

            loadConversations();
            checkUnreadBadge();

            // Restart poller with slower 10s interval for conversation listing view
            if (chatOpen) {
                startChatPolling();
            }
        }

        // Load messages for the active conversation
        function loadMessages(isInitial = false) {
            if (!activeConversationId) return;

            const loader = document.getElementById('chat-messages-loading');
            const listContainer = document.getElementById('chat-messages-list');
            if (isInitial && loader) loader.classList.remove('hidden');

            fetch(`/dms/conversations/${activeConversationId}`)
                .then(r => r.json())
                .then(data => {
                    if (isInitial && loader) loader.classList.add('hidden');

                    const messages = data.messages || [];
                    if (messages.length === 0) {
                        listContainer.innerHTML = `
                            <div class="py-16 text-center text-xs text-slate-400 font-semibold">
                                <span class="material-symbols-outlined text-2xl opacity-40 mb-1">chat</span>
                                <p>Send a message to start conversation!</p>
                            </div>
                        `;
                        return;
                    }

                    // Check if scrolled near bottom to keep scroll
                    const isAtBottom = listContainer.scrollHeight - listContainer.clientHeight - listContainer.scrollTop < 60;

                    let html = '';
                    messages.forEach(msg => {
                        const bubbleClass = msg.is_own 
                            ? 'bg-blue-600 text-white rounded-t-xl rounded-l-xl self-end' 
                            : 'bg-white text-slate-800 border border-slate-200/80 rounded-t-xl rounded-r-xl self-start dark:bg-slate-800 dark:text-slate-100 dark:border-slate-700';
                        const alignmentClass = msg.is_own ? 'flex flex-col items-end' : 'flex flex-col items-start';

                        const isImage = /^https?:\/\/[^\s]+?\.(jpe?g|png|gif|webp|bmp)(?:\?[^\s]*)?$/i.test(msg.body.trim()) || 
                                        msg.body.trim().startsWith('https://i.ibb.co/');

                        let bubbleContent = '';
                        if (isImage) {
                            bubbleContent = `
                                <div onclick="openLightbox('${msg.body.trim()}', 'Image Attachment')" class="block rounded-lg overflow-hidden border border-slate-200 bg-slate-100 max-w-[180px] sm:max-w-[220px] hover:opacity-90 transition-opacity cursor-zoom-in dark:border-slate-700 dark:bg-slate-900">
                                    <img src="${msg.body.trim()}" class="w-full h-auto object-cover max-h-[140px]" alt="Image attachment" loading="lazy">
                                </div>
                            `;
                        } else {
                            bubbleContent = escapeHtml(msg.body);
                        }

                        html += `
                            <div class="${alignmentClass} max-w-[85%] ${msg.is_own ? 'ml-auto' : 'mr-auto'} leading-snug animate-fade-in group" id="msg-${msg.id}" data-body="${escapeHtml(msg.body)}">
                                <div class="flex items-center gap-1.5 w-full ${msg.is_own ? 'justify-end' : 'justify-start'}">
                                    ${msg.is_own ? `
                                        <div class="opacity-100 sm:opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1 mr-1 flex-shrink-0">
                                            ${msg.can_edit && !isImage ? `
                                                <button onclick="startEditMsg('${msg.id}')" class="text-slate-400 hover:text-blue-500 dark:hover:text-blue-400 p-0.5 rounded transition-colors cursor-pointer" title="Edit Message">
                                                    <span class="material-symbols-outlined text-[14px]">edit</span>
                                                </button>
                                            ` : ''}
                                            ${msg.can_delete ? `
                                                <button onclick="startDeleteMsg('${msg.id}')" class="text-slate-400 hover:text-rose-500 dark:hover:text-rose-400 p-0.5 rounded transition-colors cursor-pointer" title="Delete Message">
                                                    <span class="material-symbols-outlined text-[14px]">delete</span>
                                                </button>
                                            ` : ''}
                                        </div>
                                    ` : ''}
                                    <div class="px-3.5 py-2 text-xs font-medium shadow-sm leading-normal break-words ${bubbleClass}">
                                        ${bubbleContent}
                                    </div>
                                </div>
                                <span class="text-[8px] text-slate-400 font-bold mt-1 px-1 flex items-center gap-1 select-none">
                                    ${msg.created_at}
                                    ${msg.is_edited ? '<span class="text-[8px] text-slate-400 font-normal italic dark:text-slate-500">(edited)</span>' : ''}
                                    ${msg.is_own ? (msg.is_read 
                                        ? `<span class="material-symbols-outlined text-[10px] text-blue-500 font-extrabold" title="Seen">done_all</span>` 
                                        : `<span class="material-symbols-outlined text-[10px] text-slate-400 dark:text-slate-550" title="Sent">done</span>`
                                    ) : ''}
                                </span>
                            </div>
                        `;
                    });

                    listContainer.innerHTML = html;

                    if (isInitial || isAtBottom) {
                        listContainer.scrollTop = listContainer.scrollHeight;
                    }
                })
                .catch(e => {
                    if (loader) loader.classList.add('hidden');
                    console.error('Messages list fetch error:', e);
                });
        }

        // Send new message submit handler
        function handleSendSubmit(e) {
            e.preventDefault();
            if (!activeConversationId) return;

            const input = document.getElementById('chat-message-input');
            const body = input.value.trim();
            if (!body) return;

            // Clear input instantly for UI responsiveness
            input.value = '';

            fetch(`/dms/conversations/${activeConversationId}/send`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ body: body })
            })
            .then(r => r.json())
            .then(message => {
                // Instantly append message and scroll
                loadMessages(false);
            })
            .catch(e => {
                console.error('Error sending message:', e);
                Swal.fire({
                    icon: 'error',
                    title: 'Message Delivery Failed',
                    text: 'Could not deliver message. Please try again.',
                    confirmButtonColor: '#2563eb'
                });
            });
        }

        // Handle direct image file selection and upload
        function handleChatFileSelect(input) {
            if (!activeConversationId) return;
            if (!input.files || input.files.length === 0) return;

            const file = input.files[0];
            
            // Basic validation
            if (!file.type.startsWith('image/')) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid File',
                    text: 'Please select an image or GIF file.',
                    confirmButtonColor: '#2563eb'
                });
                input.value = '';
                return;
            }

            if (file.size > 8 * 1024 * 1024) {
                Swal.fire({
                    icon: 'warning',
                    title: 'File Too Large',
                    text: 'File size exceeds the 8MB limit.',
                    confirmButtonColor: '#2563eb'
                });
                input.value = '';
                return;
            }

            // Create temporary uploading bubble block with dynamic local image preview!
            const listContainer = document.getElementById('chat-messages-list');
            const tempId = 'upload-temp-' + Date.now();
            
            // Generate a local blob URL for instant client-side caching/preview
            const localImageSrc = URL.createObjectURL(file);

            if (listContainer) {
                const tempBubble = `
                    <div id="${tempId}" class="flex flex-col items-end max-w-[80%] ml-auto leading-snug">
                        <div class="px-1 py-1 text-xs font-semibold shadow-sm bg-blue-600 rounded-t-xl rounded-l-xl relative group overflow-hidden max-w-[180px] sm:max-w-[220px]">
                            <!-- Local Image Preview -->
                            <img src="${localImageSrc}" class="w-full h-auto object-cover max-h-[140px] rounded-lg opacity-70 blur-[1px]">
                            
                            <!-- Sleek Loading Overlay -->
                            <div class="absolute inset-0 flex flex-col items-center justify-center bg-slate-900/40 gap-1.5 text-white">
                                <svg class="animate-spin h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-[9px] font-bold tracking-wider uppercase text-white/95">Uploading...</span>
                            </div>
                        </div>
                        <span class="text-[8px] text-slate-400 font-bold mt-1 px-1">Sending...</span>
                    </div>
                `;
                listContainer.insertAdjacentHTML('beforeend', tempBubble);
                listContainer.scrollTop = listContainer.scrollHeight;
            }

            const formData = new FormData();
            formData.append('image', file);

            // Clear file input immediately so same file can be selected again
            input.value = '';

            fetch(`/dms/conversations/${activeConversationId}/send`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(r => {
                if (!r.ok) {
                    return r.json().then(err => { throw new Error(err.error || 'Upload failed') });
                }
                return r.json();
            })
            .then(message => {
                document.getElementById(tempId)?.remove();
                loadMessages(false);
            })
            .catch(e => {
                document.getElementById(tempId)?.remove();
                console.error('Error uploading message attachment:', e);
                Swal.fire({
                    icon: 'error',
                    title: 'Upload Failed',
                    text: e.message || 'Could not upload image. Please try again.',
                    confirmButtonColor: '#2563eb'
                });
            });
        }

        // Start chat with user directly
        function startDirectChat(username) {
            // Open drawer
            if (!chatOpen) toggleChatDrawer();

            fetch(`/dms/start/${encodeURIComponent(username)}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(r => {
                if (!r.ok) throw new Error('Could not start conversation');
                return r.json();
            })
            .then(data => {
                openConversation(data.conversation_id, username);
            })
            .catch(e => {
                console.error('Error starting conversation:', e);
                Swal.fire({
                    icon: 'error',
                    title: 'Conversation Failed',
                    text: 'Failed to initiate conversation with this member.',
                    confirmButtonColor: '#2563eb'
                });
            });
        }

        // Debounce helper for AJAX searches
        let searchTimeout = null;

        function handleSearchInputChange(inputElement) {
            const query = inputElement.value.trim();
            const suggestionsPanel = document.getElementById('chat-search-suggestions');
            if (!suggestionsPanel) return;

            clearTimeout(searchTimeout);

            if (query.length < 1) {
                suggestionsPanel.innerHTML = '';
                suggestionsPanel.classList.add('hidden');
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`/dms/search-users?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(users => {
                        if (users.length === 0) {
                            suggestionsPanel.innerHTML = '<div class="p-3 text-[10px] text-slate-400 text-center font-medium">No members found matching "' + escapeHtml(query) + '"</div>';
                            suggestionsPanel.classList.remove('hidden');
                            return;
                        }

                        let html = '';
                        users.forEach(user => {
                            html += `
                                <div onclick="selectSearchUser('${user.name}')" class="p-2.5 hover:bg-slate-50 transition-colors flex items-center gap-2 cursor-pointer text-left">
                                    <div class="w-6.5 h-6.5 rounded-lg overflow-hidden border border-slate-200 flex-shrink-0">
                                        <img src="${user.avatar_url}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="leading-tight truncate flex-grow">
                                        <p class="font-bold text-slate-800 text-[10.5px] truncate">${user.name}</p>
                                        <span class="text-[6.5px] px-1 bg-slate-100 text-slate-450 rounded uppercase font-bold tracking-wide">${user.title_badge || 'Member'}</span>
                                    </div>
                                </div>
                            `;
                        });
                        suggestionsPanel.innerHTML = html;
                        suggestionsPanel.classList.remove('hidden');
                    })
                    .catch(err => {
                        console.error('AJAX autocomplete error:', err);
                    });
            }, 300); // 300ms debounce
        }

        // Selection handler for dynamic search list
        function selectSearchUser(username) {
            const suggestionsPanel = document.getElementById('chat-search-suggestions');
            const searchInput = document.getElementById('chat-search-input');
            
            if (suggestionsPanel) {
                suggestionsPanel.innerHTML = '';
                suggestionsPanel.classList.add('hidden');
            }
            if (searchInput) {
                searchInput.value = '';
            }

            startDirectChat(username);
        }

        // Hide suggestions panel on clicking outside search areas
        window.addEventListener('click', function(e) {
            const suggestionsPanel = document.getElementById('chat-search-suggestions');
            const searchInput = document.getElementById('chat-search-input');
            if (suggestionsPanel && !e.target.closest('#chat-search-suggestions') && e.target !== searchInput) {
                suggestionsPanel.classList.add('hidden');
            }
        });

        // Search trigger manually
        function triggerSearchUser() {
            const input = document.getElementById('chat-search-input');
            const val = input.value.trim();
            if (!val) return;

            startDirectChat(val);
            input.value = '';
            const suggestionsPanel = document.getElementById('chat-search-suggestions');
            if (suggestionsPanel) suggestionsPanel.classList.add('hidden');
        }

        // Register enter search triggers
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('chat-search-input');
            if (searchInput) {
                searchInput.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        triggerSearchUser();
                    }
                });
            }
        });

        let chatPollCycleCount = 0;

        // Toggle recurring message pollers
        function startChatPolling() {
            stopChatPolling();
            
            // Determine dynamic interval: fast 3s for active thread, slower 10s for conversation overview listing
            const interval = activeConversationId ? 3000 : 10000;
            chatPollCycleCount = 0;
            
            chatPollingInterval = setInterval(() => {
                // OPTIMIZATION: Only poll if the tab/page is currently in active focus
                if (document.visibilityState !== 'visible') return;

                if (activeConversationId) {
                    loadMessages(false);
                    
                    // Periodically update active chat partner presence status (every 5 cycles = 15 seconds)
                    chatPollCycleCount++;
                    if (chatPollCycleCount >= 5) {
                        chatPollCycleCount = 0;
                        updateChatHeaderPresence(activeConversationPartner);
                    }
                } else {
                    loadConversations();
                }
            }, interval);
        }

        function stopChatPolling() {
            if (chatPollingInterval) {
                clearInterval(chatPollingInterval);
                chatPollingInterval = null;
            }
        }

        function startEditMsg(msgId) {
            const msgEl = document.getElementById(`msg-${msgId}`);
            if (!msgEl) return;
            const currentBody = msgEl.getAttribute('data-body');

            Swal.fire({
                title: 'Edit Message',
                input: 'textarea',
                inputValue: currentBody,
                inputAttributes: {
                    maxlength: 1000,
                    autocapitalize: 'off',
                    autocorrect: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Save',
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                preConfirm: (newBody) => {
                    if (!newBody.trim()) {
                        Swal.showValidationMessage('Message body cannot be empty.');
                    }
                    return newBody.trim();
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/dms/messages/${msgId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ body: result.value })
                    })
                    .then(r => {
                        if (!r.ok) return r.json().then(e => { throw new Error(e.error || 'Failed to update message') });
                        return r.json();
                    })
                    .then(data => {
                        loadMessages(false);
                    })
                    .catch(err => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: err.message,
                            confirmButtonColor: '#2563eb'
                        });
                    });
                }
            });
        }

        function startDeleteMsg(msgId) {
            Swal.fire({
                title: 'Delete Message',
                text: 'Are you sure you want to delete this message? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/dms/messages/${msgId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(r => {
                        if (!r.ok) return r.json().then(e => { throw new Error(e.error || 'Failed to delete message') });
                        return r.json();
                    })
                    .then(data => {
                        loadMessages(false);
                    })
                    .catch(err => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: err.message,
                            confirmButtonColor: '#2563eb'
                        });
                    });
                }
            });
        }

        // Simple HTML Escaper
        function escapeHtml(text) {
            return text
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }
    </script>
@endauth
