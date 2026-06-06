<?php

use App\Models\User;
use App\Models\ShopItem;
use App\Models\PurchasedItem;
use App\Models\CoinTransaction;
use App\Models\ShopItemInteraction;
use App\Models\ShopItemReview;
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

    $response->assertRedirect(route('profile.show', $user->name));
    $response->assertSessionHas('success', 'You purchased "' . $item->name . '" successfully! You can configure it below under the Upgrades section.');

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

it('toggles shop item likes', function () {
    $user = User::factory()->create();
    $item = createTestShopItem();

    // First like
    $response = $this->actingAs($user)->post(route('shop.like', $item->id));
    $response->assertStatus(200);
    expect($response->json('liked'))->toBeTrue();
    expect($response->json('likes_count'))->toBe(1);

    // Unlike
    $response = $this->actingAs($user)->post(route('shop.like', $item->id));
    $response->assertStatus(200);
    expect($response->json('liked'))->toBeFalse();
    expect($response->json('likes_count'))->toBe(0);
});

it('toggles shop item bookmarks', function () {
    $user = User::factory()->create();
    $item = createTestShopItem();

    // First bookmark
    $response = $this->actingAs($user)->post(route('shop.bookmark', $item->id));
    $response->assertStatus(200);
    expect($response->json('bookmarked'))->toBeTrue();

    // Unbookmark
    $response = $this->actingAs($user)->post(route('shop.bookmark', $item->id));
    $response->assertStatus(200);
    expect($response->json('bookmarked'))->toBeFalse();
});

it('stores user reviews and updates ratings', function () {
    $user = User::factory()->create();
    $item = createTestShopItem(['rating' => 5.0, 'rating_count' => 0]);

    $response = $this->actingAs($user)->post(route('shop.review', $item->id), [
        'rating' => 4,
        'review' => 'Pretty good update!'
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Thank you for your review!');

    // Check database review values
    $review = ShopItemReview::where('shop_item_id', $item->id)->first();
    expect($review)->not->toBeNull();
    expect($review->rating)->toBe(4);
    expect($review->review)->toBe('Pretty good update!');

    // Check updated item metrics
    $item = $item->fresh();
    expect((int)$item->rating)->toBe(4);
    expect($item->rating_count)->toBe(1);
});

it('allows admin to access shop management and edit attributes', function () {
    // Setup Admin User
    $user = User::factory()->create();
    $user->admin()->create(); // Grant admin role

    $item = createTestShopItem();

    // 1. Visit index page
    $response = $this->actingAs($user)->get(route('admin.shop.index'));
    $response->assertStatus(200);
    $response->assertSee($item->name);

    // 2. Edit the shop item
    $response = $this->actingAs($user)->put(route('admin.shop.update', $item->id), [
        'name' => 'Updated Feature Upgrade',
        'category' => 'User Access',
        'description' => 'New modified description details.',
        'price' => 15.00,
        'stock' => 5,
        'duration' => '3 months',
        'key' => 'updated_feature_key'
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Shop item updated successfully.');

    // Check updated properties in database
    $item = $item->fresh();
    expect($item->name)->toBe('Updated Feature Upgrade');
    expect($item->category)->toBe('User Access');
    expect($item->price)->toEqual(15.00);
    expect($item->stock)->toBe(5);
    expect($item->duration)->toBe('3 months');
    expect($item->key)->toBe('updated_feature_key');
});

it('allows users to configure purchased upgrades in profile settings', function () {
    $user = User::factory()->create();
    
    // Purchase upgrades
    $itemChangeName = createTestShopItem(['key' => 'username_change']);
    $itemStyle = createTestShopItem(['key' => 'username_style']);
    $itemSticky = createTestShopItem(['key' => 'sticky_thread']);
    
    PurchasedItem::create(['user_id' => $user->id, 'shop_item_id' => $itemChangeName->id]);
    PurchasedItem::create(['user_id' => $user->id, 'shop_item_id' => $itemStyle->id]);
    PurchasedItem::create(['user_id' => $user->id, 'shop_item_id' => $itemSticky->id]);

    $user = $user->fresh();

    // 1. Update Username
    $response = $this->actingAs($user)->post(route('profile.update_username'), [
        'name' => 'NewCoolName'
    ]);
    $response->assertRedirect(route('profile.show', 'NewCoolName'));
    $user = $user->fresh();
    expect($user->name)->toBe('NewCoolName');

    // 2. Update Username Style
    $response = $this->actingAs($user)->post(route('profile.update_username_style'), [
        'title_color' => '#ff0000',
        'title_badge' => 'Exclusive Gold Member'
    ]);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect();
    $user = $user->fresh();
    expect($user->title_color)->toBe('#ff0000');
    expect($user->title_badge)->toBe('Exclusive Gold Member');

    // 3. Update Thread Sticky Options
    $thread = \App\Models\Thread::create([
        'category_id' => \App\Models\Category::factory()->create()->id,
        'user_id' => $user->id,
        'title' => 'My Pinned Discussion',
        'slug' => 'my-pinned-discussion'
    ]);

    $response = $this->actingAs($user)->post(route('profile.update_thread_upgrades'), [
        'thread_id' => $thread->id,
        'apply_sticky' => '1'
    ]);
    if (session('error')) {
        dd(session('error'));
    }
    $response->assertSessionHas('success', 'Thread upgrades applied successfully!');
    $response->assertRedirect();
    expect($thread->fresh()->is_pinned)->toBeTrue();
});
