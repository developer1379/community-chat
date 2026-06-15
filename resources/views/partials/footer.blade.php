<!-- Professional Multi-Column Footer -->
<footer class="mt-auto bg-white border-t border-slate-200 dark:bg-slate-900 dark:border-slate-800 transition-colors duration-300">
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
        <!-- Top Columns Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-12 gap-8 text-left pb-10 border-b border-slate-100 dark:border-slate-850">
            
            <!-- Column 1: Brand & Socials (Spans 4 columns) -->
            <div class="md:col-span-4 space-y-4">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center shadow-md shadow-blue-500/10 group-hover:scale-105 transition-transform">
                        <span class="material-symbols-outlined text-white text-base">forum</span>
                    </div>
                    <span class="text-sm font-extrabold tracking-tight text-slate-900 dark:text-slate-100 uppercase">
                        Xen<span class="text-blue-600">Professional</span>
                    </span>
                </a>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium leading-relaxed max-w-sm">
                    The premier corporate workspace and discussion space for system architects, software developers, and creators. Connect, trade, and build together.
                </p>
                <!-- Social Icons list -->
                <div class="flex items-center gap-3 pt-2">
                    <a href="#" class="w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-800 text-slate-500 hover:text-blue-600 hover:border-blue-300 dark:hover:text-blue-400 flex items-center justify-center transition-all" title="Twitter">
                        <span class="material-symbols-outlined text-base">alternate_email</span>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-800 text-slate-500 hover:text-blue-600 hover:border-blue-300 dark:hover:text-blue-400 flex items-center justify-center transition-all" title="Discord">
                        <span class="material-symbols-outlined text-base">chat</span>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-800 text-slate-500 hover:text-blue-600 hover:border-blue-300 dark:hover:text-blue-400 flex items-center justify-center transition-all" title="GitHub">
                        <span class="material-symbols-outlined text-base">code</span>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-800 text-slate-500 hover:text-blue-600 hover:border-blue-300 dark:hover:text-blue-400 flex items-center justify-center transition-all" title="Status Panel">
                        <span class="material-symbols-outlined text-base">sensors</span>
                    </a>
                </div>
            </div>

            <!-- Column 2: Platform Links (Spans 2 columns) -->
            <div class="md:col-span-2 space-y-3">
                <h4 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Platform</h4>
                <ul class="space-y-2 text-xs font-semibold text-slate-600 dark:text-slate-405">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Discussions</a>
                    </li>
                    <li>
                        <a href="{{ route('shop.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Shop Items</a>
                    </li>
                    <li>
                        <a href="{{ route('media.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Media Gallery</a>
                    </li>
                    @auth
                    <li>
                        <a href="{{ route('wallet.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">My Wallet</a>
                    </li>
                    @endauth
                </ul>
            </div>

            <!-- Column 3: Resources Links (Spans 2 columns) -->
            <div class="md:col-span-2 space-y-3">
                <h4 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Resources</h4>
                <ul class="space-y-2 text-xs font-semibold text-slate-600 dark:text-slate-405">
                    <li>
                        <a href="{{ route('rankings.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Leaderboard</a>
                    </li>
                    <li>
                        <a href="{{ route('rules') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Rules & Guides</a>
                    </li>
                    <li>
                        <a href="{{ route('bugs.create') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Report a Bug</a>
                    </li>
                    <li>
                        <a href="{{ route('sitemap') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">XML Sitemap</a>
                    </li>
                </ul>
            </div>

            <!-- Column 4: Compliance & Legal (Spans 4 columns) -->
            <div class="md:col-span-4 space-y-3">
                <h4 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Legal & compliance</h4>
                <p class="text-[11px] text-slate-500 dark:text-slate-450 leading-relaxed font-medium">
                    This platform adheres to safe software trading regulations, secure content publishing, and user privacy protection guidelines.
                </p>
                <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs font-semibold text-slate-500">
                    <a href="#" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Privacy Policy</a>
                    <span>•</span>
                    <a href="#" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Terms of Use</a>
                    <span>•</span>
                    <a href="#" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Contact Support</a>
                </div>
            </div>

        </div>

        <!-- Bottom Copyright Row -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-8 text-xs font-semibold text-slate-500">
            <div>
                &copy; {{ date('Y') }} XenProfessional Community. All rights reserved. Built for creators.
            </div>
            <div class="flex items-center gap-1 text-[10px] text-slate-400 uppercase tracking-wider">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> All Systems Operational
            </div>
        </div>
    </div>
</footer>
