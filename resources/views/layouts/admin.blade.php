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
    
    <!-- Tailwind CSS -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @custom-variant dark (&:where(.dark, .dark *));
    </style>
    
    <!-- Custom stylesheet override -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ time() }}">
</head>
<body class="min-h-screen flex flex-col antialiased pb-12 text-sm bg-slate-50/50 dark:bg-slate-950 dark:text-slate-100">

    <!-- Admin Navigation Bar -->
    <header class="w-full bg-slate-900 border-b border-slate-850 py-3.5 px-4 sm:px-6 lg:px-8 shadow-md flex-shrink-0 text-white">
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

            <!-- Actions -->
            <div class="flex items-center gap-3">
                <!-- Theme Toggle -->
                <button onclick="toggleDarkMode()" class="w-8 h-8 rounded-lg border border-slate-800 text-slate-400 hover:bg-slate-800 transition-all flex items-center justify-center cursor-pointer" title="Toggle Theme">
                    <span class="material-symbols-outlined text-[18px]" id="theme-toggle-icon">dark_mode</span>
                </button>

                <!-- Back to Forum -->
                <a href="{{ route('home') }}" class="flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-700 hover:bg-slate-800 text-slate-300 font-bold text-xs transition-all cursor-pointer">
                    <span class="material-symbols-outlined text-[16px]">logout</span>
                    <span>Exit Admin</span>
                </a>
            </div>
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

        document.addEventListener('DOMContentLoaded', updateThemeToggleIcon);
    </script>
</body>
</html>
