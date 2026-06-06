<?php

use App\Models\User;
use App\Models\ShopItem;
use App\Models\PurchasedItem;
use App\Models\CoinTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createTestShopItem(array $attributes = []): ShopItem {
    return ShopItem::create(array_merge([
        'name' => 'Test Item',
        'category' => 'Feature Updates',
        'description' => 'This is a test shop item',
        'price' => 5,
        'stock' => 10,
        'sold_count' => 0,
        'rating' => 5.0,
        'rating_count' => 1,
        'duration' => '1 months',
        'key' => 'test_item',
    ], $attributes));
}

it('shows the shop index page to authenticated users', function () {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get(route('shop.index'));
    $response->assertStatus(200);
    $response->assertSee('Wallet');
    $response->assertSee('Categories');
});

it('shows a shop item details page to authenticated users', function () {
    $user = User::factory()->create();
    $item = createTestShopItem();

    $response = $this->actingAs($user)->get(route('shop.show', $item->id));
    $response->assertStatus(200);
    $response->assertSee($item->name);
    $response->assertSee($item->description);
});

it('redirects guest user to login on purchase', function () {
    $item = createTestShopItem();

    $response = $this->post(route('shop.purchase', $item->id));
    $response->assertRedirect(route('login'));
});

it('fails to purchase if user has insufficient coins', function () {
    $user = User::factory()->create(['coins' => 1]); // Set non-admin to ensure coins count is exact
    $item = createTestShopItem(['price' => 5]);

    $response = $this->actingAs($user)->post(route('shop.purchase', $item->id));
    $response->assertSessionHas('error', 'You do not have enough DF Coins to buy this item.');
});

it('successfully purchases item and deducts coins', function () {
    $user = User::factory()->create(['coins' => 100]);
    $item = createTestShopItem(['price' => 5]);

    $initialSoldCount = $item->sold_count;

    $response = $this->actingAs($user)->post(route('shop.purchase', $item->id));

    $response->assertRedirect(route('shop.index'));
    $response->assertSessionHas('success', 'You purchased "' . $item->name . '" successfully!');

    // Check coin deduction
    expect($user->fresh()->coins)->toBe(95);

    // Check transaction logged
    $transaction = CoinTransaction::where('user_id', $user->id)->first();
    expect($transaction)->not->toBeNull();
    expect($transaction->amount)->toBe(-5);
    expect($transaction->type)->toBe('purchase');

    // Check purchased item logged
    $purchased = PurchasedItem::where('user_id', $user->id)
        ->where('shop_item_id', $item->id)
        ->first();
    expect($purchased)->not->toBeNull();

    // Check incremented sold count
    expect($item->fresh()->sold_count)->toBe($initialSoldCount + 1);
});
