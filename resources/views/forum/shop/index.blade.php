@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs font-bold text-slate-400 dark:text-slate-500 mb-6 text-left">
        <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors">Home</a>
        <span class="material-symbols-outlined text-sm">chevron_right</span>
        <span class="text-slate-650 dark:text-slate-300">Shop</span>
    </div>

    <!-- Banner Info Alerts -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 font-bold text-xs flex items-center gap-2 text-left">
            <span class="material-symbols-outlined text-sm">check_circle</span>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-400 font-bold text-xs flex items-center gap-2 text-left">
            <span class="material-symbols-outlined text-sm">error</span>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Sidebar Column (3 Cols) -->
        <div class="lg:col-span-3 space-y-6">
            
            <!-- Wallet widget -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 shadow-md text-left">
                <h3 class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3">Wallet</h3>
                <div class="flex items-center gap-2 text-lg sm:text-xl font-black text-slate-900 dark:text-white">
                    <span class="material-symbols-outlined text-amber-500">monetization_on</span>
                    DF Coins {{ number_format($userCoins, 2) }}
                </div>
            </div>

            <!-- Categories Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 shadow-md text-left">
                <h3 class="text-xs font-black text-slate-450 dark:text-slate-500 uppercase tracking-widest mb-4">Categories</h3>
                <div class="space-y-2">
                    <a href="{{ route('shop.index') }}" class="flex items-center justify-between text-xs font-bold px-3 py-2 rounded-xl transition-all {{ !$selectedCategory ? 'bg-blue-600 text-white shadow-md' : 'text-slate-650 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-850' }}">
                        <span>All Items</span>
                    </a>
                    @foreach($categories as $cat => $count)
                        <a href="{{ route('shop.index', ['category' => $cat]) }}" class="flex items-center justify-between text-xs font-bold px-3 py-2 rounded-xl transition-all {{ $selectedCategory == $cat ? 'bg-blue-600 text-white shadow-md' : 'text-slate-650 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-850' }}">
                            <span>{{ $cat }}</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $selectedCategory == $cat ? 'bg-white/20 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-550' }}">{{ $count }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Top rated items Widget -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 shadow-md text-left">
                <h3 class="text-xs font-black text-slate-450 dark:text-slate-500 uppercase tracking-widest mb-4">Top rated items</h3>
                <div class="space-y-4">
                    @foreach($topRatedItems as $tri)
                        <div class="flex items-start gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/30 border border-blue-100 dark:border-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-450 flex-shrink-0">
                                <span class="material-symbols-outlined text-sm">shopping_bag</span>
                            </div>
                            <div class="min-w-0">
                                <a href="{{ route('shop.show', $tri->id) }}" class="text-xs font-extrabold text-slate-800 dark:text-slate-200 hover:text-blue-600 dark:hover:text-blue-400 transition-colors line-clamp-1 leading-snug">
                                    {{ $tri->name }}
                                </a>
                                <span class="text-[9px] font-bold text-slate-400 block mt-0.5">{{ $tri->category }}</span>
                                <div class="flex items-center gap-0.5 mt-0.5 text-amber-500">
                                    <span class="material-symbols-outlined text-[10px] fill-amber-500">star</span>
                                    <span class="text-[9.5px] font-bold text-slate-550 dark:text-slate-400">{{ number_format($tri->rating, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Main Items Catalog Column (9 Cols) -->
        <div class="lg:col-span-9 space-y-6">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-md text-left">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 dark:border-slate-850 pb-4 mb-6 gap-3">
                    <div>
                        <h2 class="text-lg font-black text-slate-850 dark:text-white uppercase tracking-wider">
                            {{ $selectedCategory ?: 'All Shop Items' }}
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">Upgrade your account profile style, content visibility, and forum experiences.</p>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($shopItems as $item)
                        <div class="bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 hover:shadow-lg transition-all duration-300 flex flex-col justify-between space-y-4">
                            <div class="space-y-2.5">
                                <!-- Category Badge -->
                                <span class="inline-block text-[8px] font-black uppercase tracking-wider px-2 py-0.5 rounded bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-400 border border-blue-100/40 dark:border-blue-900/35 leading-none">
                                    {{ $item->category }}
                                </span>
                                
                                <h3 class="font-extrabold text-slate-900 dark:text-white text-sm hover:text-blue-650 dark:hover:text-blue-400 transition-colors leading-snug">
                                    <a href="{{ route('shop.show', $item->id) }}">{{ $item->name }}</a>
                                </h3>
                                
                                <p class="text-xs text-slate-450 dark:text-slate-400 leading-relaxed line-clamp-2">
                                    {{ $item->description }}
                                </p>
                            </div>

                            <div class="space-y-3 pt-3 border-t border-slate-100 dark:border-slate-850/60">
                                <!-- Rating & Items Sold line -->
                                <div class="flex items-center justify-between text-[10px] font-bold text-slate-400 dark:text-slate-500">
                                    <div class="flex items-center gap-0.5 text-amber-500">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="material-symbols-outlined text-[12px] {{ $i <= round($item->rating) ? 'fill-amber-500' : 'text-slate-250 dark:text-slate-700' }}">star</span>
                                        @endfor
                                        <span class="text-slate-500 dark:text-slate-400 ml-1">({{ $item->rating_count }} ratings)</span>
                                    </div>
                                    <span>Sold: {{ $item->sold_count }}</span>
                                </div>

                                <!-- Price, Stock & Action -->
                                <div class="flex items-center justify-between pt-1">
                                    <div class="text-left">
                                        <span class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest block leading-none">Price</span>
                                        <span class="text-sm font-black text-emerald-650 dark:text-emerald-400 block mt-0.5 leading-none">
                                            {{ number_format($item->price, 2) }} DF Coins
                                        </span>
                                        <span class="text-[8.5px] font-bold text-slate-400 block mt-1">
                                            {{ $item->stock !== null ? "Stock: {$item->stock} / 10" : 'Stock: Unlimited' }}
                                        </span>
                                    </div>
                                    
                                    <a href="{{ route('shop.show', $item->id) }}" class="inline-flex items-center gap-1 px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md transition-all">
                                        <span>View details</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 py-8 text-center text-slate-400 font-semibold text-xs">
                            No shop items found in this category.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
