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
    <title>{{ $title ?? 'XenForo Professional Space' }}</title>
    
    <!-- Professional Google Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
    
    <!-- Tailwind CSS for utility grids -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @custom-variant dark (&:where(.dark, .dark *));
    </style>
    
    <!-- Quill Rich Text Editor CDN -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    
    <!-- Global Mobile Responsive Overrides -->
    <style>
        /* Force Quill toolbar to wrap gracefully on mobile devices */
        .ql-toolbar.ql-snow {
            display: flex;
            flex-wrap: wrap;
            gap: 2px;
            padding: 8px;
        }
        .ql-formats {
            display: inline-flex;
            flex-wrap: wrap;
            margin-right: 8px !important;
            margin-bottom: 4px;
        }
    </style>

    <!-- SweetAlert2 library for premium corporate dialogs -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Modularized Custom Corporate stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="min-h-screen flex flex-col antialiased pb-12 text-sm bg-slate-50/50 dark:bg-slate-950 dark:text-slate-100">

    <!-- Modular Header Bar & Subnavigation -->
    @include('partials.header')

    <!-- Main Container -->
    <main class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 mt-6 flex-grow">
        @if(session('success'))
            <div class="mb-4 p-3 rounded-xl border border-emerald-500/20 bg-emerald-50 text-emerald-800 flex items-center justify-between shadow-sm shadow-emerald-500/5">
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

    <!-- Reusable Javascript Controllers & Dynamic Engines -->
    <script>
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

        window.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSearchModal();
                closeSignatureModal();
                closeLightbox();
                closeNotificationsModal();
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
                
                trigger.addEventListener('mouseenter', handleMouseEnter);
                trigger.addEventListener('mouseleave', handleMouseLeave);
            });
        }

        function handleMouseEnter(e) {
            clearTimeout(leaveTimeout);
            clearTimeout(hoverTimeout);
            
            const trigger = e.currentTarget;
            const name = trigger.getAttribute('data-user-name');
            if (!name) return;
            
            hoverTimeout = setTimeout(() => {
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
                        const hoverCardThreads = document.getElementById('hover-card-threads');
                        const hoverCardPosts = document.getElementById('hover-card-posts');
                        const hoverCardUploads = document.getElementById('hover-card-uploads');
                        const hoverCardHeader = document.getElementById('hover-card-header');
                        const hoverCardActions = document.getElementById('hover-card-actions');

                        if (hoverCardName) {
                            hoverCardName.innerText = data.name;
                            hoverCardName.href = `/profile/` + data.name;
                        }
                        if (hoverCardBadge) {
                            hoverCardBadge.innerText = data.title_badge;
                            hoverCardBadge.style.background = data.banner_color;
                        }
                        if (hoverCardJoined) hoverCardJoined.innerText = data.joined;
                        if (hoverCardThreads) hoverCardThreads.innerText = data.threads_count;
                        if (hoverCardPosts) hoverCardPosts.innerText = data.posts_count;
                        if (hoverCardUploads) hoverCardUploads.innerText = data.uploads_count;

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
                            hoverCard.classList.remove('opacity-0', 'pointer-events-none', 'scale-95');
                            hoverCard.classList.add('scale-100');
                        }
                    })
                    .catch(err => console.error('Error loading user card details:', err));
            }, 300); // 300ms hover delay threshold
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

        // Initialize on DOM load
        document.addEventListener('DOMContentLoaded', setupHoverCardListeners);
        window.addEventListener('searchFiltered', setupHoverCardListeners);
    </script>
</body>
</html>
