<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShopItem;
use App\Models\PurchasedItem;
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

        // Deduct coins and log transaction
        $user->coins -= $shopItem->price;
        $user->save();

        $user->transactions()->create([
            'amount' => -$shopItem->price,
            'type' => 'purchase',
            'description' => 'Purchased shop item: ' . $shopItem->name,
        ]);

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
}
