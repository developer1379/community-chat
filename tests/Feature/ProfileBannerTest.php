<?php

use App\Models\User;
use App\Models\CoinTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Fake the ImgBB api response
    Http::fake([
        'https://api.imgbb.com/*' => Http::response([
            'data' => [
                'url' => 'https://i.ibb.co/testimage/banner.png'
            ]
        ], 200)
    ]);
});

it('allows user to update profile banner for the first time for free', function () {
    $user = User::factory()->create([
        'coins' => 100, 
        'banner_updates_count' => 0,
    ]);

    $file = UploadedFile::fake()->image('banner.jpg');

    $response = $this->actingAs($user)->post(route('profile.update'), [
        'banner_color' => 'linear-gradient(135deg, #6366f1, #a855f7)',
        'banner' => $file,
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Your profile card has been updated successfully!');

    $user->refresh();
    expect($user->banner_updates_count)->toBe(1);
    expect($user->banner_path)->toBe('https://i.ibb.co/testimage/banner.png');
    // First update is free, so coins should remain 100
    expect($user->coins)->toBe(100);
});

it('charges user 50 coins for subsequent banner updates if they have enough coins', function () {
    $user = User::factory()->create([
        'coins' => 100, 
        'banner_updates_count' => 1,
    ]);

    $file = UploadedFile::fake()->image('banner_two.jpg');

    $response = $this->actingAs($user)->post(route('profile.update'), [
        'banner_color' => 'linear-gradient(135deg, #6366f1, #a855f7)',
        'banner' => $file,
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Your profile card has been updated successfully!');

    $user->refresh();
    expect($user->banner_updates_count)->toBe(2);
    // Charged 50 coins, so 100 - 50 = 50
    expect($user->coins)->toBe(50);

    // Verify transaction was logged
    $transaction = CoinTransaction::where('user_id', $user->id)->first();
    expect($transaction)->not->toBeNull();
    expect($transaction->amount)->toBe(-50);
    expect($transaction->type)->toBe('banner_update');
});

it('prevents user from updating banner if they have insufficient coins on subsequent updates', function () {
    $user = User::factory()->create([
        'coins' => 20, // Less than 50 coins
        'banner_updates_count' => 1,
    ]);

    $file = UploadedFile::fake()->image('banner_three.jpg');

    $response = $this->actingAs($user)->post(route('profile.update'), [
        'banner_color' => 'linear-gradient(135deg, #6366f1, #a855f7)',
        'banner' => $file,
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('error', 'You do not have enough coins to apply these changes. (Requires 50 coins)');

    $user->refresh();
    // Count remains 1, coins not deducted
    expect($user->banner_updates_count)->toBe(1);
    expect($user->coins)->toBe(20);
});

it('allows admins to update banners freely without coin deduction', function () {
    $user = User::factory()->create([
        'coins' => 0,
        'banner_updates_count' => 5,
    ]);
    $user->admin()->create(); // Grant admin role

    $file = UploadedFile::fake()->image('admin_banner.jpg');

    $response = $this->actingAs($user)->post(route('profile.update'), [
        'banner_color' => 'linear-gradient(135deg, #6366f1, #a855f7)',
        'banner' => $file,
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Your profile card has been updated successfully!');

    $user->refresh();
    expect($user->banner_updates_count)->toBe(6);
    expect($user->coins)->toBe(0); // Admin not charged
});

it('charges user 100 coins for profile username color customization', function () {
    $user = User::factory()->create([
        'coins' => 150,
        'title_color' => null,
    ]);

    $response = $this->actingAs($user)->post(route('profile.update'), [
        'banner_color' => 'linear-gradient(135deg, #6366f1, #a855f7)',
        'title_color' => '#ff5555',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Your profile card has been updated successfully!');

    $user->refresh();
    expect($user->title_color)->toBe('#ff5555');
    // Charged 100 coins
    expect($user->coins)->toBe(50);

    // Verify transaction
    $transaction = CoinTransaction::where('user_id', $user->id)
        ->where('type', 'profile_style')
        ->first();
    expect($transaction)->not->toBeNull();
    expect($transaction->amount)->toBe(-100);
});

it('charges user 500 coins for profile username animation customization', function () {
    $user = User::factory()->create([
        'coins' => 600,
        'username_animation' => null,
    ]);

    $response = $this->actingAs($user)->post(route('profile.update'), [
        'banner_color' => 'linear-gradient(135deg, #6366f1, #a855f7)',
        'username_animation' => 'glow',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Your profile card has been updated successfully!');

    $user->refresh();
    expect($user->username_animation)->toBe('glow');
    // Charged 500 coins
    expect($user->coins)->toBe(100);

    // Verify transaction
    $transaction = CoinTransaction::where('user_id', $user->id)
        ->where('type', 'profile_style')
        ->first();
    expect($transaction)->not->toBeNull();
    expect($transaction->amount)->toBe(-500);
});

it('prevents user from updating username style if they have insufficient coins', function () {
    $user = User::factory()->create([
        'coins' => 40,
        'title_color' => null,
    ]);

    $response = $this->actingAs($user)->post(route('profile.update'), [
        'banner_color' => 'linear-gradient(135deg, #6366f1, #a855f7)',
        'title_color' => '#ff5555',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('error', 'You do not have enough coins to apply these changes. (Requires 100 coins)');

    $user->refresh();
    expect($user->title_color)->toBeNull();
    expect($user->coins)->toBe(40);
});

it('allows admins to customize profile username style for free', function () {
    $user = User::factory()->create([
        'coins' => 20,
        'title_color' => null,
        'username_animation' => null,
    ]);
    $user->admin()->create();

    $response = $this->actingAs($user)->post(route('profile.update'), [
        'banner_color' => 'linear-gradient(135deg, #6366f1, #a855f7)',
        'title_color' => '#00ff00',
        'username_animation' => 'shimmer',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Your profile card has been updated successfully!');

    $user->refresh();
    expect($user->title_color)->toBe('#00ff00');
    expect($user->username_animation)->toBe('shimmer');
    expect($user->coins)->toBe(20); // Not charged
});
