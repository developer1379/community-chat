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
    <title>Admin Dashboard | XenProfessional</title>
    
    <!-- Professional Google Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
    
    <!-- Tailwind CSS (Served locally for 0ms external connection/handshake latency) -->
    <script src="{{ asset('js/tailwind-browser.js') }}?v={{ file_exists(public_path('js/tailwind-browser.js')) ? filemtime(public_path('js/tailwind-browser.js')) : '4.0' }}"></script>
    <style type="text/tailwindcss">
        @custom-variant dark (&:where(.dark, .dark *));
        @theme {
            --font-sans: 'Plus Jakarta Sans', 'Inter', system-ui, sans-serif;
        }
    </style>
    
    <!-- Custom stylesheet override -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ file_exists(public_path('css/app.css')) ? filemtime(public_path('css/app.css')) : '1.0' }}">

    <style>
        /* Global typography stack */
        body {
            font-family: 'Plus Jakarta Sans', 'Inter', system-ui, -apple-system, sans-serif !important;
        }

        /* Responsive compact mobile design system overrides */
        @media (max-width: 767px) {
            main {
                margin-top: 0.75rem !important;
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }
            .space-y-8 {
                --tw-space-y-reverse: 0;
                margin-top: calc(0.75rem * calc(1 - var(--tw-space-y-reverse))) !important;
                margin-bottom: calc(0.75rem * var(--tw-space-y-reverse)) !important;
                gap: 0.75rem !important;
            }
            
            /* Compact cards and containers */
            .rounded-3xl {
                border-radius: 0.875rem !important;
            }
            .p-6, .p-10, .sm\:p-10 {
                padding: 0.875rem !important;
            }
            .py-6 {
                padding-top: 0.75rem !important;
                padding-bottom: 0.75rem !important;
            }
            .px-6 {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }
            .grid {
                gap: 0.75rem !important;
            }
            
            /* Dynamic typography resizing */
            .text-3xl {
                font-size: 1.125rem !important;
                line-height: 1.5rem !important;
            }
            .sm\:text-5xl, .text-5xl {
                font-size: 1.375rem !important;
                line-height: 1.875rem !important;
            }
            .text-xl, .sm\:text-2xl {
                font-size: 1.05rem !important;
                line-height: 1.375rem !important;
            }
            .text-base {
                font-size: 0.875rem !important;
            }
            .text-sm {
                font-size: 0.75rem !important;
            }
            .sm\:text-lg, .text-lg {
                font-size: 0.8125rem !important;
                line-height: 1.125rem !important;
            }

            /* Compact form fields */
            input, select, textarea {
                padding-top: 0.5rem !important;
                padding-bottom: 0.5rem !important;
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
                border-radius: 0.625rem !important;
                font-size: 0.75rem !important;
            }
            .rounded-2xl {
                border-radius: 0.625rem !important;
            }

            /* Compact tables */
            table th {
                padding: 0.5rem 0.5rem !important;
                font-size: 10px !important;
            }
            table td {
                padding: 0.5rem 0.5rem !important;
                font-size: 11px !important;
            }
            .px-6.py-4\.5, .px-6.py-4 {
                padding: 0.5rem 0.5rem !important;
            }

            /* Compact buttons */
            .px-6.py-3\.5 {
                padding: 0.5rem 0.875rem !important;
                border-radius: 0.625rem !important;
                font-size: 11px !important;
            }

            /* Avatar downsizing */
            .w-10.h-10 {
                width: 1.75rem !important;
                height: 1.75rem !important;
            }
            .w-8.h-8 {
                width: 1.5rem !important;
                height: 1.5rem !important;
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col antialiased pb-12 text-sm bg-slate-50/50 dark:bg-slate-950 dark:text-slate-100">

    <!-- Admin Navigation Bar -->
    <header class="w-full bg-slate-900 border-b border-slate-850 py-3.5 px-4 sm:px-6 lg:px-8 shadow-md flex-shrink-0 text-white relative z-50">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
            <!-- Brand Logo -->
            <a href="{{ route('admin.bugs.index') }}" class="flex items-center gap-2 group flex-shrink-0">
                <div class="w-8 h-8 rounded-lg bg-rose-600 flex items-center justify-center shadow-md shadow-rose-500/20 group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-white text-base">security</span>
                </div>
                <div>
                    <h1 class="text-xs sm:text-sm font-extrabold tracking-tight text-white flex items-center gap-1.5 font-sans">
                        XEN<span class="text-rose-500">ADMIN</span>
                    </h1>
                </div>
            </a>

            <!-- Actions (Desktop Only) -->
            <div class="hidden md:flex items-center gap-3">
                <a href="{{ route('admin.bugs.index') }}" class="flex items-center gap-1 px-3 py-1.5 rounded-lg {{ Request::routeIs('admin.bugs.*') ? 'bg-rose-650 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-all font-bold text-xs cursor-pointer">
                    <span class="material-symbols-outlined text-[18px] mr-1">bug_report</span>
                    <span>Bugs</span>
                </a>

                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-1 px-3 py-1.5 rounded-lg {{ Request::routeIs('admin.users.*') ? 'bg-rose-650 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-all font-bold text-xs cursor-pointer">
                    <span class="material-symbols-outlined text-[18px] mr-1">group</span>
                    <span>Users</span>
                </a>

                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-1 px-3 py-1.5 rounded-lg {{ Request::routeIs('admin.categories.*') ? 'bg-rose-650 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-all font-bold text-xs cursor-pointer">
                    <span class="material-symbols-outlined text-[18px] mr-1">category</span>
                    <span>Categories</span>
                </a>

                <a href="{{ route('admin.shop.index') }}" class="flex items-center gap-1 px-3 py-1.5 rounded-lg {{ Request::routeIs('admin.shop.*') ? 'bg-rose-650 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-all font-bold text-xs cursor-pointer">
                    <span class="material-symbols-outlined text-[18px] mr-1">shopping_bag</span>
                    <span>Shop</span>
                </a>

                <a href="{{ route('admin.settings') }}" class="flex items-center gap-1 px-3 py-1.5 rounded-lg {{ Request::routeIs('admin.settings') ? 'bg-rose-650 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-all font-bold text-xs cursor-pointer">
                    <span class="material-symbols-outlined text-[18px] mr-1">settings</span>
                    <span>Settings</span>
                </a>

                <!-- Theme Toggle -->
                <button onclick="toggleDarkMode()" class="w-8 h-8 rounded-lg border border-slate-850 text-slate-400 hover:bg-slate-800 transition-all flex items-center justify-center cursor-pointer" title="Toggle Theme">
                    <span class="material-symbols-outlined text-[18px]" id="theme-toggle-icon">dark_mode</span>
                </button>

                <!-- Back to Forum -->
                <a href="{{ route('home') }}" class="flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-700 hover:bg-slate-800 text-slate-300 font-bold text-xs transition-all cursor-pointer">
                    <span class="material-symbols-outlined text-[16px]">logout</span>
                    <span>Exit Admin</span>
                </a>
            </div>

            <!-- Mobile Controls -->
            <div class="flex md:hidden items-center gap-2">
                <!-- Theme Toggle -->
                <button onclick="toggleDarkMode()" class="w-8 h-8 rounded-lg border border-slate-800 text-slate-400 hover:bg-slate-800 transition-all flex items-center justify-center cursor-pointer" title="Toggle Theme">
                    <span class="material-symbols-outlined text-[18px]" id="theme-toggle-icon-mobile">dark_mode</span>
                </button>

                <!-- Hamburger Button -->
                <button onclick="toggleMobileAdminMenu()" class="w-8 h-8 rounded-lg border border-slate-800 text-slate-400 hover:bg-slate-800 transition-all flex items-center justify-center cursor-pointer" title="Toggle Mobile Menu">
                    <span class="material-symbols-outlined text-[20px]" id="mobile-menu-hamburger-icon">menu</span>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Menu Dropdown -->
        <div id="mobile-admin-menu" class="hidden md:hidden mt-3 pt-3 border-t border-slate-800 flex flex-col gap-2 transition-all duration-200 ease-in-out">
            <a href="{{ route('admin.bugs.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ Request::routeIs('admin.bugs.*') ? 'bg-rose-650 text-white' : 'text-slate-300 hover:bg-slate-800' }} transition-all font-bold text-xs">
                <span class="material-symbols-outlined text-[18px]">bug_report</span>
                <span>Bugs</span>
            </a>

            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ Request::routeIs('admin.users.*') ? 'bg-rose-650 text-white' : 'text-slate-300 hover:bg-slate-800' }} transition-all font-bold text-xs">
                <span class="material-symbols-outlined text-[18px]">group</span>
                <span>Users</span>
            </a>

            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ Request::routeIs('admin.categories.*') ? 'bg-rose-650 text-white' : 'text-slate-300 hover:bg-slate-800' }} transition-all font-bold text-xs">
                <span class="material-symbols-outlined text-[18px]">category</span>
                <span>Categories</span>
            </a>

            <a href="{{ route('admin.shop.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ Request::routeIs('admin.shop.*') ? 'bg-rose-650 text-white' : 'text-slate-300 hover:bg-slate-800' }} transition-all font-bold text-xs">
                <span class="material-symbols-outlined text-[18px]">shopping_bag</span>
                <span>Shop</span>
            </a>

            <a href="{{ route('admin.settings') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ Request::routeIs('admin.settings') ? 'bg-rose-650 text-white' : 'text-slate-300 hover:bg-slate-800' }} transition-all font-bold text-xs">
                <span class="material-symbols-outlined text-[18px]">settings</span>
                <span>Settings</span>
            </a>

            <a href="{{ route('home') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg border border-slate-700 hover:bg-slate-800 text-slate-300 font-bold text-xs transition-all">
                <span class="material-symbols-outlined text-[16px]">logout</span>
                <span>Exit Admin</span>
            </a>
        </div>
    </header>

    <!-- Main Admin Container -->
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

    <!-- JavaScript theme controller -->
    <script>
        function updateThemeToggleIcon() {
            const icon = document.getElementById('theme-toggle-icon');
            const iconMobile = document.getElementById('theme-toggle-icon-mobile');
            const isDark = document.documentElement.classList.contains('dark');
            const text = isDark ? 'light_mode' : 'dark_mode';
            
            if (icon) icon.innerText = text;
            if (iconMobile) iconMobile.innerText = text;
        }

        function toggleMobileAdminMenu() {
            const menu = document.getElementById('mobile-admin-menu');
            const icon = document.getElementById('mobile-menu-hamburger-icon');
            if (!menu || !icon) return;
            
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                icon.innerText = 'close';
            } else {
                menu.classList.add('hidden');
                icon.innerText = 'menu';
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

        document.addEventListener('DOMContentLoaded', updateThemeToggleIcon);

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
    </script>
</body>
</html>
