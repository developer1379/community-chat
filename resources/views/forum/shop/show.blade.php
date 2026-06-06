@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs font-bold text-slate-400 dark:text-slate-500 mb-6 text-left">
        <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors">Home</a>
        <span class="material-symbols-outlined text-sm">chevron_right</span>
        <a href="{{ route('shop.index') }}" class="hover:text-blue-600 transition-colors">Shop</a>
        <span class="material-symbols-outlined text-sm">chevron_right</span>
        <span class="text-slate-650 dark:text-slate-300">{{ $shopItem->category }}</span>
    </div>

    <!-- Error/Success alerts -->
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

    <!-- Main Grid Content -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start text-left">
        
        <!-- Left Main Box (8 columns) -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Product Title / Header Row -->
            <div class="flex items-start gap-4">
                <!-- Large icon wrapper -->
                <div class="w-16 h-16 rounded-2xl bg-blue-50 dark:bg-blue-950/30 border border-blue-100 dark:border-blue-900/35 flex items-center justify-center text-blue-650 dark:text-blue-400 flex-shrink-0 shadow-sm">
                    <span class="material-symbols-outlined text-3xl">shopping_bag</span>
                </div>
                <div class="space-y-1.5 min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded bg-amber-500 text-white shadow-sm leading-none">
                            {{ $shopItem->category }}
                        </span>
                        <h1 class="text-xl sm:text-2xl font-black text-slate-850 dark:text-white leading-tight">
                            {{ $shopItem->name }}
                        </h1>
                    </div>
                    <p class="text-xs text-slate-400 font-semibold flex items-center gap-1.5 flex-wrap">
                        <span>desifakes</span>
                        <span>•</span>
                        <span>{{ $shopItem->created_at->format('M d, Y') }}</span>
                    </p>
                </div>
            </div>

            <!-- Tabs row -->
            <div class="border-b border-slate-200 dark:border-slate-850/60 flex items-center gap-6 text-xs font-black">
                <button onclick="switchTab('overview')" id="tab-btn-overview" class="border-b-2 border-blue-600 pb-2.5 text-blue-600 uppercase tracking-wider cursor-pointer">Overview</button>
                <button onclick="switchTab('reviews')" id="tab-btn-reviews" class="text-slate-450 dark:text-slate-400 pb-2.5 hover:text-slate-650 dark:hover:text-slate-350 transition-colors uppercase tracking-wider cursor-pointer">Reviews ({{ $shopItem->rating_count }})</button>
            </div>

            <!-- Overview tab content -->
            <div id="tab-content-overview" class="space-y-4">
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm space-y-4">
                    <p class="text-sm sm:text-base font-bold text-slate-850 dark:text-slate-200 leading-relaxed">
                        {{ $shopItem->description }}
                    </p>
                    
                    <p class="text-xs text-slate-450 dark:text-slate-400 leading-relaxed font-semibold">
                        Just purchase the item using DF coins and it will automatically enable the feature.
                    </p>

                    <!-- Footer likes reactions bar -->
                    <div class="flex items-center justify-between border-t border-slate-100 dark:border-slate-850 pt-4 mt-6">
                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-550 dark:text-slate-400">
                            <span class="flex items-center -space-x-1">
                                <span class="w-5 h-5 rounded-full bg-blue-500 text-white flex items-center justify-center text-[10px] shadow-sm border border-white dark:border-slate-950 font-bold">👍</span>
                            </span>
                            <span id="likes-text">
                                @php
                                    $likeCount = $shopItem->interactions->where('type', 'like')->count();
                                @endphp
                                {{ $likeCount }} {{ Str::plural('user', $likeCount) }} liked this item
                            </span>
                        </div>

                        <div class="flex items-center gap-4 text-xs font-bold text-slate-450 dark:text-slate-500">
                            @auth
                                @php
                                    $isBookmarked = $shopItem->isBookmarkedByUser(Auth::id());
                                    $isLiked = $shopItem->isLikedByUser(Auth::id());
                                @endphp
                                <button onclick="toggleInteraction('bookmark')" id="btn-bookmark" class="flex items-center gap-1 cursor-pointer transition-colors {{ $isBookmarked ? 'text-amber-500' : 'hover:text-slate-700 dark:hover:text-slate-300 text-slate-400' }}">
                                    <span class="material-symbols-outlined text-[16px] {{ $isBookmarked ? 'fill-amber-500' : '' }}">bookmark</span> 
                                    <span id="bookmark-text">{{ $isBookmarked ? 'Bookmarked' : 'Bookmark' }}</span>
                                </button>
                                <button onclick="toggleInteraction('like')" id="btn-like" class="flex items-center gap-1 cursor-pointer transition-colors {{ $isLiked ? 'text-blue-600 dark:text-blue-400' : 'hover:text-slate-700 dark:hover:text-slate-300 text-slate-400' }}">
                                    <span class="material-symbols-outlined text-[16px] {{ $isLiked ? 'fill-blue-600' : '' }}">thumb_up</span> 
                                    <span id="like-text">{{ $isLiked ? 'Liked' : 'Like' }}</span>
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="flex items-center gap-1 hover:text-slate-700 text-slate-450">
                                    <span class="material-symbols-outlined text-[16px]">bookmark</span> Bookmark
                                </a>
                                <a href="{{ route('login') }}" class="flex items-center gap-1 hover:text-slate-700 text-slate-455">
                                    <span class="material-symbols-outlined text-[16px]">thumb_up</span> Like
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews tab content -->
            <div id="tab-content-reviews" class="hidden space-y-6">
                <!-- Add Review Form -->
                @auth
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm">
                        <h3 class="text-sm font-black text-slate-850 dark:text-white uppercase tracking-wider mb-4">Write a Review</h3>
                        <form action="{{ route('shop.review', $shopItem->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-slate-450 dark:text-slate-500 uppercase mb-2">Rating</label>
                                <div class="flex items-center gap-2">
                                    <select name="rating" required class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-850 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        <option value="5">⭐⭐⭐⭐⭐ (5 Stars)</option>
                                        <option value="4">⭐⭐⭐⭐ (4 Stars)</option>
                                        <option value="3">⭐⭐⭐ (3 Stars)</option>
                                        <option value="2">⭐⭐ (2 Stars)</option>
                                        <option value="1">⭐ (1 Star)</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-450 dark:text-slate-500 uppercase mb-2">Comments (Optional)</label>
                                <textarea name="review" rows="3" placeholder="Share your experience with this feature upgrade..." class="w-full bg-slate-50 dark:bg-slate-955 border border-slate-200 dark:border-slate-850 rounded-2xl p-4 text-xs text-slate-800 dark:text-slate-105 focus:outline-none focus:ring-1 focus:ring-blue-500 leading-relaxed"></textarea>
                            </div>

                            <button type="submit" class="inline-flex items-center gap-1 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md transition-all cursor-pointer">
                                <span>Submit Review</span>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm text-center">
                        <p class="text-xs text-slate-450 font-bold">Please <a href="{{ route('login') }}" class="text-blue-600 hover:underline">login</a> to write a review.</p>
                    </div>
                @endauth

                <!-- Reviews List -->
                <div class="space-y-4">
                    @forelse($shopItem->reviews as $review)
                        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 shadow-sm space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $review->user->avatar_url }}" alt="{{ $review->user->name }}" class="w-8 h-8 rounded-full border border-slate-200 dark:border-slate-800 object-cover">
                                    <div>
                                        <h4 class="text-xs font-black text-slate-850 dark:text-white">{{ $review->user->name }}</h4>
                                        <span class="text-[9px] font-bold text-slate-400">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-0.5 text-amber-500">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="material-symbols-outlined text-[12px] {{ $i <= $review->rating ? 'fill-amber-500' : 'text-slate-200 dark:text-slate-800' }}">star</span>
                                    @endfor
                                </div>
                            </div>
                            @if($review->review)
                                <p class="text-xs text-slate-655 dark:text-slate-300 font-bold leading-relaxed">
                                    {{ $review->review }}
                                </p>
                            @endif
                        </div>
                    @empty
                        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm text-center text-slate-400 text-xs font-semibold">
                            No reviews yet. Be the first to leave a review!
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Sidebar (4 columns) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Item Information Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 shadow-md space-y-4">
                <h3 class="text-xs font-black text-slate-450 dark:text-slate-500 uppercase tracking-widest border-b border-slate-100 dark:border-slate-850 pb-2">Item Information</h3>
                
                <div class="space-y-3 text-xs font-bold text-slate-650 dark:text-slate-300">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 dark:text-slate-500">Item owner:</span>
                        <span class="font-extrabold text-slate-800 dark:text-slate-200">desifakes</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 dark:text-slate-500">Creation date:</span>
                        <span>{{ $shopItem->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 dark:text-slate-500">Last update:</span>
                        <span>{{ $shopItem->updated_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 dark:text-slate-500">Duration:</span>
                        <span>{{ $shopItem->duration }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 dark:text-slate-500">Items sold:</span>
                        <span>{{ $shopItem->sold_count }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 dark:text-slate-500">Buyer rating:</span>
                        <div class="flex items-center gap-0.5 text-amber-500">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="material-symbols-outlined text-[12px] {{ $i <= round($shopItem->rating) ? 'fill-amber-500' : 'text-slate-250 dark:text-slate-700' }}">star</span>
                            @endfor
                            <span class="text-slate-500 dark:text-slate-400 ml-1">({{ $shopItem->rating_count }} ratings)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Information Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 shadow-md space-y-4">
                <h3 class="text-xs font-black text-slate-450 dark:text-slate-500 uppercase tracking-widest border-b border-slate-100 dark:border-slate-850 pb-2">Pricing information</h3>
                
                <div class="space-y-3 text-xs font-bold text-slate-650 dark:text-slate-350">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 dark:text-slate-500">Stock:</span>
                        <span>{{ $shopItem->stock !== null ? "{$shopItem->stock} / 10" : 'Unlimited' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 dark:text-slate-500">Price:</span>
                        <span class="font-extrabold text-emerald-650 dark:text-emerald-450">
                            {{ number_format($shopItem->price, 2) }} DF Coins
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 dark:text-slate-500">Sell-back value:</span>
                        <span>N/A</span>
                    </div>
                </div>

                <!-- Purchase Action Button -->
                <button onclick="openPurchaseModal()" class="w-full py-2.5 rounded-xl bg-emerald-600 dark:bg-emerald-500 hover:bg-emerald-750 dark:hover:bg-emerald-600 text-white font-extrabold text-xs shadow-md shadow-emerald-500/10 cursor-pointer flex items-center justify-center gap-1.5 active:scale-98 transition-all">
                    <span class="material-symbols-outlined text-sm">shopping_cart</span>
                    <span>Purchase</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Purchase Confirmation Modal -->
<div id="purchase-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/65 backdrop-blur-sm hidden">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl w-full max-w-md overflow-hidden shadow-2xl text-left select-none scale-95 opacity-0 transition-all duration-300" id="purchase-modal-card">
        <!-- Header -->
        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-955/40 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <h3 class="text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-widest">
                Purchase item: {{ $shopItem->name }}
            </h3>
            <button onclick="closePurchaseModal()" class="text-slate-400 hover:text-slate-700 dark:hover:text-white transition-colors cursor-pointer text-sm font-bold">✕</button>
        </div>
        
        <!-- Details Body -->
        <form action="{{ route('shop.purchase', $shopItem->id) }}" method="POST" class="p-6 space-y-4">
            @csrf
            
            <div class="divide-y divide-slate-100 dark:divide-slate-805 text-xs font-bold text-slate-650 dark:text-slate-350">
                <div class="flex justify-between py-3">
                    <span class="text-slate-400 dark:text-slate-500">Price:</span>
                    <span class="text-emerald-650 dark:text-emerald-400 font-extrabold">{{ number_format($shopItem->price, 2) }} DF Coins</span>
                </div>
                <div class="flex justify-between py-3">
                    <span class="text-slate-400 dark:text-slate-500">Duration:</span>
                    <span>{{ $shopItem->duration }}</span>
                </div>
            </div>

            <!-- Submit footer -->
            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="closePurchaseModal()" class="px-4 py-2 rounded-xl text-xs text-slate-500 dark:text-slate-450 hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer font-bold">Cancel</button>
                <button type="submit" class="flex items-center gap-1 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition-all cursor-pointer">
                    <span class="material-symbols-outlined text-[14px]">credit_card</span>
                    <span>Add to cart</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPurchaseModal() {
        const modal = document.getElementById('purchase-modal');
        const card = document.getElementById('purchase-modal-card');
        if (modal && card) {
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.add('flex');
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            }, 10);
        }
    }

    function closePurchaseModal() {
        const modal = document.getElementById('purchase-modal');
        const card = document.getElementById('purchase-modal-card');
        if (modal && card) {
            card.classList.remove('scale-100', 'opacity-100');
            card.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }
    }

    function switchTab(tab) {
        const overviewContent = document.getElementById('tab-content-overview');
        const reviewsContent = document.getElementById('tab-content-reviews');
        const overviewBtn = document.getElementById('tab-btn-overview');
        const reviewsBtn = document.getElementById('tab-btn-reviews');

        if (tab === 'overview') {
            overviewContent.classList.remove('hidden');
            reviewsContent.classList.add('hidden');
            overviewBtn.className = "border-b-2 border-blue-600 pb-2.5 text-blue-600 uppercase tracking-wider cursor-pointer";
            reviewsBtn.className = "text-slate-455 dark:text-slate-400 pb-2.5 hover:text-slate-650 dark:hover:text-slate-350 transition-colors uppercase tracking-wider cursor-pointer";
        } else {
            overviewContent.classList.add('hidden');
            reviewsContent.classList.remove('hidden');
            overviewBtn.className = "text-slate-455 dark:text-slate-400 pb-2.5 hover:text-slate-650 dark:hover:text-slate-350 transition-colors uppercase tracking-wider cursor-pointer";
            reviewsBtn.className = "border-b-2 border-blue-600 pb-2.5 text-blue-600 uppercase tracking-wider cursor-pointer";
        }
    }

    function toggleInteraction(type) {
        const url = `/shop/{{ $shopItem->id }}/${type}`;
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (type === 'like') {
                    const btn = document.getElementById('btn-like');
                    const text = document.getElementById('like-text');
                    const icon = btn.querySelector('.material-symbols-outlined');
                    if (data.liked) {
                        btn.className = "flex items-center gap-1 cursor-pointer transition-colors text-blue-600 dark:text-blue-400";
                        text.innerText = "Liked";
                        icon.classList.add('fill-blue-600');
                    } else {
                        btn.className = "flex items-center gap-1 cursor-pointer transition-colors text-slate-400 hover:text-slate-700 dark:hover:text-slate-300";
                        text.innerText = "Like";
                        icon.classList.remove('fill-blue-600');
                    }
                    document.getElementById('likes-text').innerText = `${data.likes_count} ${data.likes_count === 1 ? 'user' : 'users'} liked this item`;
                } else if (type === 'bookmark') {
                    const btn = document.getElementById('btn-bookmark');
                    const text = document.getElementById('bookmark-text');
                    const icon = btn.querySelector('.material-symbols-outlined');
                    if (data.bookmarked) {
                        btn.className = "flex items-center gap-1 cursor-pointer transition-colors text-amber-500";
                        text.innerText = "Bookmarked";
                        icon.classList.add('fill-amber-500');
                    } else {
                        btn.className = "flex items-center gap-1 cursor-pointer transition-colors text-slate-400 hover:text-slate-700 dark:hover:text-slate-300";
                        text.innerText = "Bookmark";
                        icon.classList.remove('fill-amber-500');
                    }
                }
            }
        })
        .catch(err => console.error(err));
    }
</script>
@endsection
