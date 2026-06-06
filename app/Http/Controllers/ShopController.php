<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShopItem;
use App\Models\PurchasedItem;
use App\Models\ShopItemReview;
use App\Models\ShopItemInteraction;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    /**
     * Display the shop homepage.
     */
    public function index(Request $request)
    {
        $selectedCategory = $request->input('category');
        $query = ShopItem::query();

        if ($selectedCategory) {
            $query->where('category', $selectedCategory);
        }

        $shopItems = $query->orderBy('created_at', 'desc')->get();
        
        $categories = [
            'Feature Updates' => ShopItem::where('category', 'Feature Updates')->count(),
            'Promot your content' => ShopItem::where('category', 'Promot your content')->count(),
            'User Access' => ShopItem::where('category', 'User Access')->count(),
            'Private threads' => ShopItem::where('category', 'Private threads')->count(),
        ];

        $topRatedItems = ShopItem::orderBy('rating', 'desc')->take(4)->get();
        $userCoins = Auth::check() ? Auth::user()->coins : 0;

        return view('forum.shop.index', compact('shopItems', 'categories', 'topRatedItems', 'selectedCategory', 'userCoins'));
    }

    /**
     * Display a specific shop item details.
     */
    public function show(string $id)
    {
        $shopItem = ShopItem::findOrFail($id);
        $topRatedItems = ShopItem::where('id', '!=', $id)->orderBy('rating', 'desc')->take(4)->get();
        $userCoins = Auth::check() ? Auth::user()->coins : 0;

        return view('forum.shop.show', compact('shopItem', 'topRatedItems', 'userCoins'));
    }

    /**
     * Handle item purchase.
     */
    public function purchase(Request $request, string $id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to purchase items.');
        }

        $shopItem = ShopItem::findOrFail($id);

        if ($user->coins < $shopItem->price) {
            return redirect()->back()->with('error', 'You do not have enough DF Coins to buy this item.');
        }

        // Deduct coins and log transaction using standard helper
        $user->addCoins(-(int)$shopItem->price, 'purchase', 'Purchased shop item: ' . $shopItem->name);

        // Determine expiration
        $expiresAt = null;
        if ($shopItem->duration === '1 months') {
            $expiresAt = now()->addMonth();
        }

        // Record the purchased item
        PurchasedItem::create([
            'user_id' => $user->id,
            'shop_item_id' => $shopItem->id,
            'expires_at' => $expiresAt,
        ]);

        // Increment sold count
        $shopItem->increment('sold_count');

        return redirect()->route('shop.index')->with('success', 'You purchased "' . $shopItem->name . '" successfully!');
    }

    /**
     * Toggle like status for a shop item.
     */
    public function toggleLike(string $id)
    {
        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $like = ShopItemInteraction::where('shop_item_id', $id)
            ->where('user_id', $userId)
            ->where('type', 'like')
            ->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            ShopItemInteraction::create([
                'shop_item_id' => $id,
                'user_id' => $userId,
                'type' => 'like',
            ]);
            $liked = true;
        }

        $count = ShopItemInteraction::where('shop_item_id', $id)
            ->where('type', 'like')
            ->count();

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $count
        ]);
    }

    /**
     * Toggle bookmark status for a shop item.
     */
    public function toggleBookmark(string $id)
    {
        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $bookmark = ShopItemInteraction::where('shop_item_id', $id)
            ->where('user_id', $userId)
            ->where('type', 'bookmark')
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            $bookmarked = false;
        } else {
            ShopItemInteraction::create([
                'shop_item_id' => $id,
                'user_id' => $userId,
                'type' => 'bookmark',
            ]);
            $bookmarked = true;
        }

        return response()->json([
            'success' => true,
            'bookmarked' => $bookmarked
        ]);
    }

    /**
     * Store a user review for a shop item.
     */
    public function storeReview(Request $request, string $id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'You must be logged in to leave a review.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $shopItem = ShopItem::findOrFail($id);

        ShopItemReview::updateOrCreate(
            [
                'shop_item_id' => $id,
                'user_id' => $user->id,
            ],
            [
                'rating' => $validated['rating'],
                'review' => $validated['review'],
            ]
        );

        // Recalculate average rating & counts on the shop item
        $reviews = $shopItem->reviews;
        $ratingCount = $reviews->count();
        $averageRating = $reviews->avg('rating') ?: 5.0;

        $shopItem->update([
            'rating' => $averageRating,
            'rating_count' => $ratingCount,
        ]);

        return redirect()->back()->with('success', 'Thank you for your review!');
    }
}
