<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
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
    
    <!-- Modularized Custom Corporate stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="min-h-screen flex flex-col antialiased pb-12 text-sm bg-slate-50/50">

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

        function filterSearch() {
            const val = document.getElementById('modal-search-input').value.toLowerCase();
            const list = document.getElementById('search-results-list');
            if (!list) return;
            const items = list.querySelectorAll('a');
            
            items.forEach(item => {
                const text = item.innerText.toLowerCase();
                if (text.includes(val)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Dynamic XenForo Hover Card Controller
        let hoverTimeout = null;
        let leaveTimeout = null;
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
            
            hoverTimeout = setTimeout(() => {
                const name = trigger.getAttribute('data-user-name');
                const badge = trigger.getAttribute('data-user-badge') || 'Member';
                const joined = trigger.getAttribute('data-user-joined') || 'N/A';
                const threads = trigger.getAttribute('data-user-threads') || '0';
                const posts = trigger.getAttribute('data-user-posts') || '0';
                const uploads = trigger.getAttribute('data-user-uploads') || '0';
                const avatar = trigger.getAttribute('data-user-avatar');
                const banner = trigger.getAttribute('data-user-banner') || '#2563eb';
                const bannerPath = trigger.getAttribute('data-user-banner-path');

                // Populate Popover Elements
                const hoverCardName = document.getElementById('hover-card-name');
                const hoverCardBadge = document.getElementById('hover-card-badge');
                const hoverCardJoined = document.getElementById('hover-card-joined');
                const hoverCardThreads = document.getElementById('hover-card-threads');
                const hoverCardPosts = document.getElementById('hover-card-posts');
                const hoverCardUploads = document.getElementById('hover-card-uploads');
                const hoverCardHeader = document.getElementById('hover-card-header');

                if (hoverCardName) {
                    hoverCardName.innerText = name;
                    hoverCardName.href = `/profile/` + name;
                }
                if (hoverCardBadge) {
                    hoverCardBadge.innerText = badge;
                    hoverCardBadge.style.background = banner;
                }
                if (hoverCardJoined) hoverCardJoined.innerText = joined;
                if (hoverCardThreads) hoverCardThreads.innerText = threads;
                if (hoverCardPosts) hoverCardPosts.innerText = posts;
                if (hoverCardUploads) hoverCardUploads.innerText = uploads;

                // Set avatar image or placeholder
                const img = document.getElementById('hover-card-avatar');
                const placeholder = document.getElementById('hover-card-avatar-placeholder');
                if (img && placeholder) {
                    if (avatar) {
                        img.src = avatar;
                        img.classList.remove('hidden');
                        placeholder.classList.add('hidden');
                    } else {
                        img.classList.add('hidden');
                        placeholder.innerText = name.substring(0, 2).toUpperCase();
                        placeholder.classList.remove('hidden');
                    }
                }

                // Apply header background banner style
                if (hoverCardHeader) {
                    if (bannerPath) {
                        hoverCardHeader.style.background = `url('${bannerPath}')`;
                        hoverCardHeader.style.backgroundSize = 'cover';
                        hoverCardHeader.style.backgroundPosition = 'center';
                    } else {
                        hoverCardHeader.style.background = banner;
                    }
                }

                // Position Popover dynamically
                const rect = trigger.getBoundingClientRect();
                const cardWidth = 288; // w-72
                const cardHeight = 135; 
                
                let top = rect.bottom + window.scrollY + 8;
                let left = rect.left + window.scrollX + (rect.width / 2) - (cardWidth / 2);
                
                // Viewport checks
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
            }, 250); // delay before hiding to allow mouse transitions
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

        // Initialize on DOM load
        document.addEventListener('DOMContentLoaded', setupHoverCardListeners);
        window.addEventListener('searchFiltered', setupHoverCardListeners);
    </script>
</body>
</html>
