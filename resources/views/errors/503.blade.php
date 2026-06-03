<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 - Under Maintenance | XenProfessional</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 flex items-center justify-center p-6 transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">
    <div class="max-w-md w-full text-center space-y-8 bg-white border border-slate-200 rounded-[2rem] p-8 sm:p-12 shadow-2xl dark:bg-slate-900 dark:border-slate-800">
        <!-- Icon -->
        <div class="inline-flex w-20 h-20 rounded-3xl bg-orange-50 text-orange-600 border border-orange-100 items-center justify-center shadow-lg shadow-orange-500/10 dark:bg-orange-950/20 dark:border-orange-900/50 dark:text-orange-400">
            <span class="material-symbols-outlined text-4xl">construction</span>
        </div>

        <div class="space-y-3">
            <h1 class="text-7xl font-black tracking-tight text-transparent bg-clip-text bg-gradient-to-b from-orange-500 to-orange-700">503</h1>
            <h2 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">Under Maintenance</h2>
            <p class="text-sm text-slate-500 leading-relaxed dark:text-slate-400">
                We are currently performing scheduled maintenance to improve our platform. We'll be back online shortly. Thank you for your patience!
            </p>
        </div>

        <div class="pt-4 flex flex-col sm:flex-row items-center gap-3">
            <button onclick="window.location.reload()" class="w-full text-center py-3.5 rounded-2xl bg-orange-600 hover:bg-orange-700 text-white font-bold text-sm shadow-lg shadow-orange-500/25 transition-all duration-200 cursor-pointer">
                Check Status
            </button>
            <a href="/" class="w-full text-center py-3.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 font-bold text-sm transition-all duration-200">
                Back to Home
            </a>
        </div>
    </div>
</body>
</html>
