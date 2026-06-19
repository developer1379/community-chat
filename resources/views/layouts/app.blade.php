<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO Meta Tags -->
    <title>@yield('title', $title ?? 'XenForo Professional Space')</title>
    <meta name="description" content="@yield('meta_description', 'Welcome to XenForo Professional Space - the premier community forum for discussing, sharing, and connecting with tech experts.')">
    <meta name="keywords" content="@yield('meta_keywords', 'community, forum, discussions, threads, tech chat, social network')">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', $title ?? 'XenForo Professional Space')">
    <meta property="og:description" content="@yield('meta_description', 'Welcome to XenForo Professional Space - the premier community forum for discussing, sharing, and connecting with tech experts.')">
    <meta property="og:image" content="@yield('og_image', asset('images/default-og.png'))">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', $title ?? 'XenForo Professional Space')">
    <meta property="twitter:description" content="@yield('meta_description', 'Welcome to XenForo Professional Space - the premier community forum for discussing, sharing, and connecting with tech experts.')">
    <meta property="twitter:image" content="@yield('og_image', asset('images/default-og.png'))">
    
    <!-- Professional Google Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
    
    <!-- Tailwind CSS for utility grids -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @custom-variant dark (&:where(.dark, .dark *));
        @theme {
            --color-slate-50: #f8fafc;
            --color-slate-100: #f1f5f9;
            --color-slate-200: #e2e8f0;
            --color-slate-300: #cbd5e1;
            --color-slate-400: #94a3b8;
            --color-slate-500: #707a8a;
            --color-slate-600: #4c566a;
            --color-slate-700: #3b4252;
            --color-slate-800: #262a35;
            --color-slate-900: #1c1f26;
            --color-slate-950: #12141a;
        }
    </style>
    
    <!-- Quill Rich Text Editor CDN -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
    
    <!-- Global Mobile Responsive Overrides -->
    <style>
        /* Responsive styles for embedded videos (YouTube, Streamable, Sendvid, Vimeo) on mobile devices */
        .ql-editor iframe.ql-video {
            width: 100% !important;
            aspect-ratio: 16 / 9 !important;
            max-width: 100% !important;
            border-radius: 12px !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05) !important;
            margin: 12px 0 !important;
        }
        .dark .ql-editor iframe.ql-video {
            border-color: #262a35 !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.4) !important;
        }

        /* Force Quill toolbar to wrap gracefully on desktop, but scroll horizontally on mobile devices */
        .ql-toolbar.ql-snow {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            padding: 8px 12px;
            background-color: #f8fafc;
            border: none !important;
            border-bottom: 1px solid #e2e8f0 !important;
            align-items: center;
        }
        .ql-container.ql-snow {
            border: none !important;
            background-color: #ffffff;
            font-family: inherit;
        }
        .ql-formats {
            display: inline-flex;
            flex-wrap: nowrap;
            margin-right: 12px !important;
            margin-bottom: 0px !important;
            align-items: center;
        }
        .ql-snow .ql-stroke {
            stroke: #475569 !important;
        }
        .ql-snow .ql-fill {
            fill: #475569 !important;
        }
        .ql-snow .ql-picker {
            color: #475569 !important;
        }
        
        /* Mobile wrapping toolbar rules to prevent dropdown clipping */
        @media (max-width: 639px) {
            .ql-toolbar.ql-snow {
                flex-wrap: wrap !important;
                overflow-x: visible !important;
                padding: 6px 8px !important;
                gap: 2px !important;
            }
            .ql-formats {
                flex-shrink: 0 !important;
                margin-right: 8px !important;
            }
        }

        /* Dark Mode support for Quill Editor */
        .dark .ql-toolbar.ql-snow {
            background-color: #1c1f26 !important;
            border-bottom: 1px solid #262a35 !important;
        }
        .dark .ql-container.ql-snow {
            background-color: #12141a !important;
            color: #e2e8f0 !important;
        }
        .dark .ql-snow .ql-stroke {
            stroke: #94a3b8 !important;
        }
        .dark .ql-snow .ql-fill {
            fill: #94a3b8 !important;
        }
        .dark .ql-snow .ql-picker {
            color: #94a3b8 !important;
        }
        .dark .ql-snow .ql-picker-options {
            background-color: #151c2c !important;
            border-color: #1e293b !important;
        }
        .dark .ql-snow .ql-picker-item:hover,
        .dark .ql-snow .ql-picker-label:hover {
            color: #ffffff !important;
        }
        .dark .ql-snow.ql-toolbar button:hover .ql-stroke,
        .dark .ql-snow.ql-toolbar button.ql-active .ql-stroke {
            stroke: #3b82f6 !important;
        }
        .dark .ql-snow.ql-toolbar button:hover .ql-fill,
        .dark .ql-snow.ql-toolbar button.ql-active .ql-fill {
            fill: #3b82f6 !important;
        }

        /* Dynamic Thread Title Animations */
        @keyframes titleGlow {
            0%, 100% { text-shadow: 0 0 4px currentColor, 0 0 10px currentColor; }
            50% { text-shadow: 0 0 8px currentColor, 0 0 20px currentColor; }
        }
        .animate-glow {
            animation: titleGlow 2s ease-in-out infinite;
        }

        @keyframes titleShimmer {
            0% { background-position: -200% center; }
            100% { background-position: 200% center; }
        }
        .animate-shimmer {
            background: linear-gradient(90deg, currentColor, #ffffff, currentColor);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: titleShimmer 3s linear infinite;
            display: inline;
        }

        /* Hide default ImgBB widget button */
        .imgbb-container, .imgbb-plugin-button, [data-imgbb-trigger] {
            display: none !important;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Premium CSS Overrides for SweetAlert2 Toasts */
        body .swal2-container.swal2-toast-shown .swal2-popup.swal2-toast {
            background-color: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
            border: 1px solid rgba(226, 232, 240, 0.8) !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05) !important;
            border-radius: 16px !important;
            padding: 12px 16px !important;
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif !important;
        }
        
        .dark body .swal2-container.swal2-toast-shown .swal2-popup.swal2-toast {
            background-color: rgba(28, 31, 38, 0.95) !important;
            border: 1px solid rgba(38, 42, 53, 0.8) !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.3) !important;
        }

        body .swal2-popup.swal2-toast .swal2-title {
            color: #0f172a !important;
            font-size: 13px !important;
            font-weight: 700 !important;
            margin-bottom: 2px !important;
        }

        .dark body .swal2-popup.swal2-toast .swal2-title {
            color: #f8fafc !important;
        }

        body .swal2-popup.swal2-toast .swal2-html-container {
            color: #64748b !important;
            font-size: 11px !important;
            font-weight: 500 !important;
            margin-top: 2px !important;
        }

        .dark body .swal2-popup.swal2-toast .swal2-html-container {
            color: #94a3b8 !important;
        }

        body .swal2-popup.swal2-toast .swal2-timer-progress-bar {
            background: linear-gradient(90deg, #3b82f6, #60a5fa) !important;
            height: 3px !important;
        }
    </style>


    <!-- Modularized Custom Corporate stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ file_exists(public_path('css/app.css')) ? filemtime(public_path('css/app.css')) : '1.0' }}">
</head>
<body class="min-h-screen flex flex-col antialiased pb-12 text-sm bg-slate-50/50 dark:bg-slate-950 dark:text-slate-100">

    <!-- Modular Header Bar & Subnavigation -->
    @include('partials.header')

    <!-- Main Container -->
    <main class="max-w-[1440px] w-full mx-auto px-0 sm:px-6 lg:px-8 mt-6 mb-24 flex-grow">
        @if(session('success'))
            <div class="mx-4 sm:mx-0 mb-4 p-3 rounded-xl border border-emerald-500/20 bg-emerald-50 text-emerald-800 flex items-center justify-between shadow-sm shadow-emerald-500/5">
                <div class="flex items-center gap-2.5">
                    <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                    <p class="font-semibold text-xs">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Modular Footer -->
    @include('partials.footer')

    <!-- Modular Modals & Popovers -->
    @include('partials.modals')



    @include('partials.chat')

    <!-- Third-Party Library Scripts (Loaded right before execution to prevent render-blocking) -->
    <script src="https://www.gstatic.com/firebasejs/10.8.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.8.0/firebase-database-compat.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

    <!-- Reusable Javascript Controllers & Dynamic Engines -->
    <script>
        // Firebase Client-side Config (Loads from Blade/Laravel config)
        const firebaseConfig = {
            apiKey: "{{ config('firebase.api_key') }}",
            authDomain: "{{ config('firebase.auth_domain') }}",
            databaseURL: "{{ config('firebase.database_url') }}",
            projectId: "{{ config('firebase.project_id') }}",
            storageBucket: "{{ config('firebase.storage_bucket') }}",
            messagingSenderId: "{{ config('firebase.messaging_sender_id') }}",
            appId: "{{ config('firebase.app_id') }}"
        };

        let firebaseDatabase = null;
        let firebaseApp = null;

        if (firebaseConfig.databaseURL) {
            try {
                console.log("[Firebase] Initializing database with URL:", firebaseConfig.databaseURL);
                firebaseApp = firebase.initializeApp(firebaseConfig);
                firebaseDatabase = firebase.database();
                console.log("[Firebase] Database initialized successfully.");
            } catch (e) {
                console.warn("[Firebase] Failed to initialize:", e);
            }
        } else {
            console.log("[Firebase] databaseURL is empty, Firebase database disabled.");
        }

        function updateThemeToggleIcon() {
            const icon = document.getElementById('theme-toggle-icon');
            if (!icon) return;
            if (document.documentElement.classList.contains('dark')) {
                icon.innerText = 'light_mode';
            } else {
                icon.innerText = 'dark_mode';
            }
        }

        function toggleDarkMode() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            updateThemeToggleIcon();
        }

        // Initialize icon on load
        document.addEventListener('DOMContentLoaded', updateThemeToggleIcon);

        function toggleDropdown(id) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                if (menu.id !== id) menu.classList.remove('show');
            });
            const element = document.getElementById(id);
            if (element) {
                element.classList.toggle('show');
                // Proactively pull chat notifications if the notification dropdown is toggled open
                if (id === 'notify-dropdown' && element.classList.contains('show') && typeof checkUnreadBadge === 'function') {
                    checkUnreadBadge();
                }
            }
        }

        window.addEventListener('click', function(e) {
            if (!e.target.closest('.relative')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });

        function openSignatureModal() {
            const modal = document.getElementById('settings-modal');
            if (modal) modal.classList.remove('opacity-0', 'pointer-events-none');
        }

        function closeSignatureModal() {
            const modal = document.getElementById('settings-modal');
            if (modal) modal.classList.add('opacity-0', 'pointer-events-none');
        }

        function openSearchModal() {
            const modal = document.getElementById('search-modal');
            if (modal) {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                document.getElementById('modal-search-input').focus();
            }
        }

        function closeSearchModal() {
            const modal = document.getElementById('search-modal');
            if (modal) modal.classList.add('opacity-0', 'pointer-events-none');
        }

        function openNotificationsModal() {
            const modal = document.getElementById('notifications-modal');
            if (modal) {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                if (typeof checkUnreadBadge === 'function') {
                    checkUnreadBadge();
                }
            }
        }
        
        // Initialize unread check and set recurring schedule
        document.addEventListener('DOMContentLoaded', () => {
            checkUnreadBadge();
            
            if (firebaseDatabase && currentUserId) {
                console.log("[Firebase] Registering real-time notification listener for user:", currentUserId);
                // Real-time Event-Driven Notifications via Firebase Database Pings
                try {
                    const notifRef = firebaseDatabase.ref('users/' + currentUserId + '/notification');
                    notifRef.on('value', (snapshot) => {
                        console.log("[Firebase] Real-time notification ping received.");
                        checkUnreadBadge();
                    });
                } catch (e) {
                    console.error("[Firebase] Notification listener failed, falling back to polling:", e);
                    setupFallbackNotificationPolling();
                }
            } else {
                console.log("[Firebase] Real-time notifications disabled (no database or guest). Falling back to HTTP polling.");
                setupFallbackNotificationPolling();
            }
        });

        function setupFallbackNotificationPolling() {
            // Poll global badge every 30 seconds, but ONLY when tab is focused and active
            badgePollingInterval = setInterval(() => {
                if (document.visibilityState === 'visible') {
                    checkUnreadBadge();
                }
            }, 30000);
        }

        function closeNotificationsModal() {
            const modal = document.getElementById('notifications-modal');
            if (modal) modal.classList.add('opacity-0', 'pointer-events-none');
        }

        // Reusable Premium Lightbox Modal Functions
        function openLightbox(src, name) {
            const modal = document.getElementById('lightbox-modal');
            const img = document.getElementById('lightbox-image');
            const caption = document.getElementById('lightbox-caption');
            if (modal && img && caption) {
                img.src = src;
                caption.innerText = name || 'Media View';
                modal.classList.remove('opacity-0', 'pointer-events-none');
            }
        }

        function closeLightbox() {
            const modal = document.getElementById('lightbox-modal');
            if (modal) modal.classList.add('opacity-0', 'pointer-events-none');
        }

        function openAuthModal(tab = 'login') {
            const modal = document.getElementById('login-auth-modal');
            const content = document.getElementById('login-auth-modal-content');
            if (modal) {
                switchAuthTab(tab);
                modal.classList.remove('opacity-0', 'pointer-events-none');
                if (content) {
                    content.classList.remove('scale-95');
                    content.classList.add('scale-100');
                }
            }
        }

        function closeAuthModal() {
            const modal = document.getElementById('login-auth-modal');
            const content = document.getElementById('login-auth-modal-content');
            if (modal) {
                modal.classList.add('opacity-0', 'pointer-events-none');
                if (content) {
                    content.classList.remove('scale-100');
                    content.classList.add('scale-95');
                }
            }
        }

        function switchAuthTab(tab) {
            const tabBtnLogin = document.getElementById('tab-btn-login');
            const tabBtnRegister = document.getElementById('tab-btn-register');
            const viewLogin = document.getElementById('auth-view-login');
            const viewRegister = document.getElementById('auth-view-register');

            if (tab === 'login') {
                if (tabBtnLogin) tabBtnLogin.className = "flex-1 pb-3 text-sm font-bold text-blue-600 border-b-2 border-blue-600 focus:outline-none transition-all cursor-pointer bg-transparent border-0";
                if (tabBtnRegister) tabBtnRegister.className = "flex-1 pb-3 text-sm font-bold text-slate-400 border-b-2 border-transparent hover:text-slate-655 focus:outline-none transition-all cursor-pointer bg-transparent border-0";
                if (viewLogin) viewLogin.classList.remove('hidden');
                if (viewRegister) viewRegister.classList.add('hidden');
            } else {
                if (tabBtnRegister) tabBtnRegister.className = "flex-1 pb-3 text-sm font-bold text-blue-600 border-b-2 border-blue-600 focus:outline-none transition-all cursor-pointer bg-transparent border-0";
                if (tabBtnLogin) tabBtnLogin.className = "flex-1 pb-3 text-sm font-bold text-slate-400 border-b-2 border-transparent hover:text-slate-655 focus:outline-none transition-all cursor-pointer bg-transparent border-0";
                if (viewRegister) viewRegister.classList.remove('hidden');
                if (viewLogin) viewLogin.classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            @if(old('name') || $errors->has('name') || $errors->has('avatar_file') || $errors->has('avatar_preset'))
                openAuthModal('register');
            @elseif(session('show_login_modal_redirect') || $errors->has('email') || $errors->has('password'))
                openAuthModal('login');
            @endif
        });

        window.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSearchModal();
                closeSignatureModal();
                closeLightbox();
                closeNotificationsModal();
                closeAuthModal();
                if (typeof chatOpen !== 'undefined' && chatOpen) {
                    toggleChatDrawer();
                }
            }
        });



        // Dynamic XenForo Hover Card Controller
        let hoverTimeout = null;
        let leaveTimeout = null;
        let activeHoveredUser = null;
        const hoverCard = document.getElementById('user-hover-card');

        function setupHoverCardListeners() {
            document.querySelectorAll('[data-user-hover]').forEach(trigger => {
                trigger.removeEventListener('mouseenter', handleMouseEnter);
                trigger.removeEventListener('mouseleave', handleMouseLeave);
                trigger.removeEventListener('click', handleTriggerClick);
                
                trigger.addEventListener('mouseenter', handleMouseEnter);
                trigger.addEventListener('mouseleave', handleMouseLeave);
                trigger.addEventListener('click', handleTriggerClick);
            });
        }
        window.setupHoverCardListeners = setupHoverCardListeners;

        function showCardForTrigger(trigger) {
            const name = trigger.getAttribute('data-user-name');
            if (!name) return;

            // Fetch dynamic, real-time details from backend endpoint
            fetch(`/dms/user-card/${encodeURIComponent(name)}`)
                .then(r => r.json())
                .then(data => {
                    if (data.error) return;
                    activeHoveredUser = data;

                    // Populate Popover Elements
                    const hoverCardName = document.getElementById('hover-card-name');
                    const hoverCardBadge = document.getElementById('hover-card-badge');
                    const hoverCardJoined = document.getElementById('hover-card-joined');
                    const hoverCardPosts = document.getElementById('hover-card-posts');
                    const hoverCardReactions = document.getElementById('hover-card-reactions');
                    const hoverCardBadges = document.getElementById('hover-card-badges');
                    const hoverCardPoints = document.getElementById('hover-card-points');
                    const hoverCardCoins = document.getElementById('hover-card-coins');
                    const hoverCardHeader = document.getElementById('hover-card-header');
                    const hoverCardActions = document.getElementById('hover-card-actions');
                    const hoverCardRankBadge = document.getElementById('hover-card-rank-badge');

                    if (hoverCardName) {
                        hoverCardName.innerText = data.name;
                        hoverCardName.href = `/profile/` + data.name;
                    }
                    if (hoverCardBadge) {
                        hoverCardBadge.innerText = data.title_badge;
                        hoverCardBadge.style.background = data.banner_color;
                    }
                    if (hoverCardRankBadge) {
                        hoverCardRankBadge.innerText = data.rank_name;
                        hoverCardRankBadge.style.background = data.rank_color;
                    }
                    const hoverCardStatusContainer = document.getElementById('hover-card-status-container');
                    const hoverCardStatus = document.getElementById('hover-card-status');
                    const hoverCardStatusImage = document.getElementById('hover-card-status-image');
                    if (hoverCardStatusContainer) {
                        if (data.status || data.status_image) {
                            hoverCardStatusContainer.classList.remove('hidden');
                            if (hoverCardStatus) {
                                hoverCardStatus.innerText = data.status || '';
                                if (data.status) {
                                    hoverCardStatus.classList.remove('hidden');
                                } else {
                                    hoverCardStatus.classList.add('hidden');
                                }
                            }
                            if (hoverCardStatusImage) {
                                if (data.status_image) {
                                    hoverCardStatusImage.src = data.status_image;
                                    hoverCardStatusImage.classList.remove('hidden');
                                } else {
                                    hoverCardStatusImage.classList.add('hidden');
                                }
                            }
                        } else {
                            hoverCardStatusContainer.classList.add('hidden');
                        }
                    }
                    if (hoverCardJoined) hoverCardJoined.innerText = data.joined;
                    if (hoverCardPosts) hoverCardPosts.innerText = data.posts_count;
                    if (hoverCardReactions) hoverCardReactions.innerText = data.reactions_count;
                    if (hoverCardBadges) hoverCardBadges.innerText = data.badges_count;
                    if (hoverCardPoints) hoverCardPoints.innerText = data.activity_points;
                    if (hoverCardCoins) hoverCardCoins.innerText = data.coins;

                    // Online / Presence Indicators
                    const presenceDot = document.getElementById('hover-card-presence-dot');
                    const innerDot = document.getElementById('hover-card-presence-inner-dot');
                    const presenceText = document.getElementById('hover-card-presence-text');
                    
                    if (data.is_online) {
                        if (presenceDot) presenceDot.className = "absolute bottom-0 right-0 w-3.5 h-3.5 rounded-full border-2 border-white bg-emerald-500 animate-pulse";
                        if (innerDot) innerDot.className = "w-1 h-1 rounded-full bg-emerald-500";
                        if (presenceText) presenceText.innerText = "Online";
                    } else {
                        if (presenceDot) presenceDot.className = "absolute bottom-0 right-0 w-3.5 h-3.5 rounded-full border-2 border-white bg-slate-400";
                        if (innerDot) innerDot.className = "w-1 h-1 rounded-full bg-slate-400";
                        if (presenceText) presenceText.innerText = data.last_active;
                    }

                    // Avatar image or placeholder
                    const img = document.getElementById('hover-card-avatar');
                    const placeholder = document.getElementById('hover-card-avatar-placeholder');
                    if (img && placeholder) {
                        if (data.avatar_url) {
                            img.src = data.avatar_url;
                            img.classList.remove('hidden');
                            placeholder.classList.add('hidden');
                        } else {
                            img.classList.add('hidden');
                            placeholder.innerText = data.name.substring(0, 2).toUpperCase();
                            placeholder.classList.remove('hidden');
                        }
                    }

                    // Header Banner Color/Path
                    if (hoverCardHeader) {
                        if (data.banner_path) {
                            hoverCardHeader.style.background = `url('${data.banner_path}')`;
                            hoverCardHeader.style.backgroundSize = 'cover';
                            hoverCardHeader.style.backgroundPosition = 'center';
                        } else {
                            hoverCardHeader.style.background = data.banner_color;
                        }
                    }

                    // Hide follow/message controls if user is hovering over their own card
                    if (hoverCardActions) {
                        if (data.is_self) {
                            hoverCardActions.classList.add('hidden');
                        } else {
                            hoverCardActions.classList.remove('hidden');
                            
                            // Setup initial follow button state
                            const followBtn = document.getElementById('hover-card-follow-btn');
                            const followText = document.getElementById('hover-card-follow-text');
                            if (followBtn && followText) {
                                if (data.is_following) {
                                    followText.innerText = 'Unfollow';
                                    followBtn.querySelector('.material-symbols-outlined').innerText = 'person_remove';
                                    followBtn.className = "flex-1 py-2 text-rose-600 hover:bg-rose-50/20 transition-colors cursor-pointer flex items-center justify-center gap-1 font-bold";
                                } else {
                                    followText.innerText = 'Follow';
                                    followBtn.querySelector('.material-symbols-outlined').innerText = 'person_add';
                                    followBtn.className = "flex-1 py-2 text-blue-600 hover:bg-blue-50/20 transition-colors cursor-pointer flex items-center justify-center gap-1 font-bold";
                                }
                            }
                        }
                    }

                    // Position Popover dynamically
                    const rect = trigger.getBoundingClientRect();
                    const cardWidth = 288; // w-72
                    const cardHeight = 175; // dynamic adjusted height
                    
                    let top = rect.bottom + window.scrollY + 8;
                    let left = rect.left + window.scrollX + (rect.width / 2) - (cardWidth / 2);
                    
                    // Viewport bounds checks
                    if (left < 10) left = 10;
                    if (left + cardWidth > window.innerWidth - 10) {
                        left = window.innerWidth - cardWidth - 10;
                    }
                    
                    if (rect.bottom + cardHeight > window.innerHeight) {
                        top = rect.top + window.scrollY - cardHeight - 8;
                    }

                    if (hoverCard) {
                        hoverCard.style.top = `${top}px`;
                        hoverCard.style.left = `${left}px`;
                        
                        // Apply dynamic glowing border/shadow classes to the hover card depending on points rank
                        hoverCard.className = "absolute z-50 w-72 bg-white rounded-xl border transition-all duration-200 scale-95 opacity-0 pointer-events-none";
                        const pts = data.activity_points;
                        if (pts >= 1000) {
                            hoverCard.classList.add('border-rose-500/40', 'shadow-[0_0_25px_rgba(225,29,72,0.3)]', 'ring-2', 'ring-rose-500/10');
                        } else if (pts >= 500) {
                            hoverCard.classList.add('border-purple-500/40', 'shadow-[0_0_20px_rgba(124,58,237,0.25)]');
                        } else if (pts >= 200) {
                            hoverCard.classList.add('border-amber-500/40', 'shadow-[0_0_15px_rgba(217,119,6,0.18)]');
                        } else if (pts >= 50) {
                            hoverCard.classList.add('border-blue-500/30', 'shadow-lg');
                        } else {
                            hoverCard.classList.add('border-slate-200', 'shadow-2xl');
                        }

                        hoverCard.classList.remove('opacity-0', 'pointer-events-none', 'scale-95');
                        hoverCard.classList.add('scale-100');
                    }
                })
                .catch(err => console.error('Error loading user card details:', err));
        }

        function handleMouseEnter(e) {
            clearTimeout(leaveTimeout);
            clearTimeout(hoverTimeout);
            
            const trigger = e.currentTarget;
            hoverTimeout = setTimeout(() => {
                showCardForTrigger(trigger);
            }, 300); // 300ms hover delay threshold
        }

        function handleTriggerClick(e) {
            const trigger = e.currentTarget;
            const name = trigger.getAttribute('data-user-name');
            if (!name) return;

            // If the card is already visible for this user, let the click navigate normally
            if (hoverCard && !hoverCard.classList.contains('opacity-0') && activeHoveredUser && activeHoveredUser.name === name) {
                return;
            }

            // Otherwise, show the card and prevent default link navigation
            e.preventDefault();
            e.stopPropagation();
            clearTimeout(hoverTimeout);
            clearTimeout(leaveTimeout);
            showCardForTrigger(trigger);
        }

        function handleMouseLeave() {
            clearTimeout(hoverTimeout);
            clearTimeout(leaveTimeout);
            leaveTimeout = setTimeout(() => {
                if (hoverCard) {
                    hoverCard.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
                    hoverCard.classList.remove('scale-100');
                }
            }, 250);
        }

        if (hoverCard) {
            hoverCard.addEventListener('mouseenter', () => {
                clearTimeout(leaveTimeout);
            });

            hoverCard.addEventListener('mouseleave', () => {
                clearTimeout(leaveTimeout);
                handleMouseLeave();
            });
        }

        // Live Follow/Unfollow toggle inside hover card
        function handleHoverFollow() {
            if (!activeHoveredUser) return;
            
            // Guest block check
            if (!currentUserId || currentUserId === 'null' || currentUserId === '') {
                Swal.fire({
                    icon: 'info',
                    title: 'Authentication Required',
                    text: 'Please register or log in to follow other members.',
                    confirmButtonColor: '#2563eb'
                });
                return;
            }

            const followBtn = document.getElementById('hover-card-follow-btn');
            const followText = document.getElementById('hover-card-follow-text');
            if (!followBtn || !followText) return;

            const wasFollowing = activeHoveredUser.is_following;
            activeHoveredUser.is_following = !wasFollowing;

            // Optimistic UI updates
            if (activeHoveredUser.is_following) {
                followText.innerText = 'Unfollow';
                followBtn.querySelector('.material-symbols-outlined').innerText = 'person_remove';
                followBtn.className = "flex-1 py-2 text-rose-600 hover:bg-rose-50/20 transition-colors cursor-pointer flex items-center justify-center gap-1 font-bold";
            } else {
                followText.innerText = 'Follow';
                followBtn.querySelector('.material-symbols-outlined').innerText = 'person_add';
                followBtn.className = "flex-1 py-2 text-blue-600 hover:bg-blue-50/20 transition-colors cursor-pointer flex items-center justify-center gap-1 font-bold";
            }

            // Sync with DB
            fetch(`/members/${activeHoveredUser.name}/follow`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(r => r.json())
            .then(res => {
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
                toast.fire({
                    icon: 'success',
                    title: res.status === 'followed' 
                        ? `You are now following ${activeHoveredUser.name}` 
                        : `You unfollowed ${activeHoveredUser.name}`
                });
            })
            .catch(err => {
                console.error('Follow request error:', err);
                // Revert state on error
                activeHoveredUser.is_following = wasFollowing;
                if (wasFollowing) {
                    followText.innerText = 'Unfollow';
                    followBtn.querySelector('.material-symbols-outlined').innerText = 'person_remove';
                    followBtn.className = "flex-1 py-2 text-rose-600 hover:bg-rose-50/20 transition-colors cursor-pointer flex items-center justify-center gap-1 font-bold";
                } else {
                    followText.innerText = 'Follow';
                    followBtn.querySelector('.material-symbols-outlined').innerText = 'person_add';
                    followBtn.className = "flex-1 py-2 text-blue-600 hover:bg-blue-50/20 transition-colors cursor-pointer flex items-center justify-center gap-1 font-bold";
                }
            });
        }

        // Live Chat direct messaging shortcut inside hover card
        function handleHoverMessage() {
            if (!activeHoveredUser) return;

            if (!currentUserId || currentUserId === 'null' || currentUserId === '') {
                Swal.fire({
                    icon: 'info',
                    title: 'Authentication Required',
                    text: 'Please register or log in to message other members.',
                    confirmButtonColor: '#2563eb'
                });
                return;
            }

            // Gracefully close hover card popover
            if (hoverCard) {
                hoverCard.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
                hoverCard.classList.remove('scale-100');
            }

            // Launch DM system and load conversation stream
            if (typeof startDirectChat === 'function') {
                startDirectChat(activeHoveredUser.name);
            } else if (typeof toggleChatDrawer === 'function') {
                toggleChatDrawer();
                setTimeout(() => {
                    if (typeof startDirectChat === 'function') {
                        startDirectChat(activeHoveredUser.name);
                    }
                }, 200);
            }
        }

        // Close hover card when clicking outside of it (crucial for mobile/tablet users)
        document.addEventListener('click', function(e) {
            if (hoverCard && !hoverCard.classList.contains('opacity-0')) {
                if (!hoverCard.contains(e.target) && !e.target.closest('[data-user-hover]')) {
                    hoverCard.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
                    hoverCard.classList.remove('scale-100');
                }
            }
        });

        // Initialize on DOM load
        document.addEventListener('DOMContentLoaded', setupHoverCardListeners);
        window.addEventListener('searchFiltered', setupHoverCardListeners);

        // Global capture-phase listener to handle broken image loads gracefully with clean SVG placeholders
        window.addEventListener('error', function (e) {
            if (e.target && e.target.tagName === 'IMG') {
                const isAvatar = e.target.classList.contains('avatar') || e.target.src.includes('avatar') || e.target.closest('[data-user-name]');
                if (isAvatar) {
                    e.target.src = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100%" height="100%" fill="%23e2e8f0"/><text x="50%" y="55%" font-family="sans-serif" font-size="36" fill="%2394a3b8" dominant-baseline="middle" text-anchor="middle">👤</text></svg>';
                } else {
                    e.target.src = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100%" height="100%" fill="%23f1f5f9"/><text x="50%" y="50%" font-family="sans-serif" font-size="10" fill="%2394a3b8" dominant-baseline="middle" text-anchor="middle">Image Error</text></svg>';
                }
                e.target.onerror = null; // Prevent infinite loop if fallback itself has an error
            }
        }, true);
        // Robust utility to parse video URLs and extract embeddable URLs for YouTube, Vimeo, Sendvid, and Streamable
        function getEmbedUrl(videoUrl) {
            if (!videoUrl) return '';
            videoUrl = videoUrl.trim();
            
            // Remove trailing slash if present to avoid split/pop empty string issues on mobile browsers
            if (videoUrl.endsWith('/')) {
                videoUrl = videoUrl.slice(0, -1);
            }
            
            // YouTube Watch URL
            if (videoUrl.includes('youtube.com/watch')) {
                try {
                    const urlObj = new URL(videoUrl);
                    const id = urlObj.searchParams.get('v');
                    if (id) {
                        return `https://www.youtube.com/embed/${id}`;
                    }
                } catch (e) {}
                
                let parts = videoUrl.split('watch?v=');
                if (parts.length > 1) {
                    let id = parts[1].split('&')[0];
                    return `https://www.youtube.com/embed/${id}`;
                }
            }
            
            // YouTube Short URL (youtu.be)
            if (videoUrl.includes('youtu.be/')) {
                let parts = videoUrl.split('youtu.be/');
                if (parts.length > 1) {
                    let id = parts[1].split('?')[0].split('/')[0];
                    return `https://www.youtube.com/embed/${id}`;
                }
            }
            
            // YouTube Embed URL (already embed)
            if (videoUrl.includes('youtube.com/embed/')) {
                return videoUrl;
            }
            
            // Vimeo
            if (videoUrl.includes('vimeo.com/')) {
                if (videoUrl.includes('player.vimeo.com/video/')) {
                    return videoUrl;
                }
                let parts = videoUrl.split('vimeo.com/');
                if (parts.length > 1) {
                    let id = parts[1].split('?')[0].split('/')[0];
                    return `https://player.vimeo.com/video/${id}`;
                }
            }
            
            // Sendvid
            if (videoUrl.includes('sendvid.com/')) {
                if (videoUrl.includes('sendvid.com/embed/')) {
                    return videoUrl;
                }
                let parts = videoUrl.split('sendvid.com/');
                if (parts.length > 1) {
                    let id = parts[1].split('?')[0].split('/')[0];
                    return `https://sendvid.com/embed/${id}`;
                }
            }
            
            // Streamable
            if (videoUrl.includes('streamable.com/')) {
                if (videoUrl.includes('streamable.com/e/')) {
                    return videoUrl;
                }
                let parts = videoUrl.split('streamable.com/');
                if (parts.length > 1) {
                    let id = parts[1].split('?')[0].split('/')[0];
                    return `https://streamable.com/e/${id}`;
                }
            }
            
            return videoUrl;
        }
    </script>
</body>
</html>
