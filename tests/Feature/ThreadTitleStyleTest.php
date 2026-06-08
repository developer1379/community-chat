<?php

use App\Models\User;
use App\Models\Category;
use App\Models\Thread;
use App\Models\CoinTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->category = Category::create([
        'name' => 'General',
        'slug' => 'general',
        'description' => 'General forum',
        'order' => 1,
        'is_active' => true,
    ]);
});

it('charges user 100 coins for thread title color customization', function () {
    $user = User::factory()->create(['coins' => 200]);
    $thread = Thread::create([
        'user_id' => $user->id,
        'category_id' => $this->category->id,
        'title' => 'My Test Thread Title',
        'slug' => 'my-test-thread-title',
        'title_color' => null,
        'title_animation' => null,
    ]);

    $response = $this->actingAs($user)->post(route('threads.customize-title', $thread->id), [
        'title_color' => '#ff0000',
        'title_animation' => 'none',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Your thread title has been styled successfully!');

    $thread->refresh();
    $user->refresh();

    expect($thread->title_color)->toBe('#ff0000');
    expect($thread->title_animation)->toBeNull();
    expect($thread->is_title_styled)->toBeTrue();
    expect($user->coins)->toBe(100);

    // Verify transaction
    $transaction = CoinTransaction::where('user_id', $user->id)->first();
    expect($transaction)->not->toBeNull();
    expect($transaction->amount)->toBe(-100);
    expect($transaction->type)->toBe('thread_style');
});

it('charges user 500 coins for thread title animation customization', function () {
    $user = User::factory()->create(['coins' => 600]);
    $thread = Thread::create([
        'user_id' => $user->id,
        'category_id' => $this->category->id,
        'title' => 'My Test Thread Title',
        'slug' => 'my-test-thread-title',
        'title_color' => null,
        'title_animation' => null,
    ]);

    $response = $this->actingAs($user)->post(route('threads.customize-title', $thread->id), [
        'title_color' => null,
        'title_animation' => 'glow',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Your thread title has been styled successfully!');

    $thread->refresh();
    $user->refresh();

    expect($thread->title_color)->toBeNull();
    expect($thread->title_animation)->toBe('glow');
    expect($thread->is_title_styled)->toBeTrue();
    expect($user->coins)->toBe(100);

    // Verify transaction
    $transaction = CoinTransaction::where('user_id', $user->id)->first();
    expect($transaction)->not->toBeNull();
    expect($transaction->amount)->toBe(-500);
    expect($transaction->type)->toBe('thread_style');
});

it('charges combined 600 coins for customizing both color and animation', function () {
    $user = User::factory()->create(['coins' => 700]);
    $thread = Thread::create([
        'user_id' => $user->id,
        'category_id' => $this->category->id,
        'title' => 'My Test Thread Title',
        'slug' => 'my-test-thread-title',
        'title_color' => null,
        'title_animation' => null,
    ]);

    $response = $this->actingAs($user)->post(route('threads.customize-title', $thread->id), [
        'title_color' => '#00ff00',
        'title_animation' => 'pulse',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Your thread title has been styled successfully!');

    $thread->refresh();
    $user->refresh();

    expect($thread->title_color)->toBe('#00ff00');
    expect($thread->title_animation)->toBe('pulse');
    expect($user->coins)->toBe(100);
});

it('prevents customization if user has insufficient coins', function () {
    $user = User::factory()->create(['coins' => 50]);
    $thread = Thread::create([
        'user_id' => $user->id,
        'category_id' => $this->category->id,
        'title' => 'My Test Thread Title',
        'slug' => 'my-test-thread-title',
        'title_color' => null,
        'title_animation' => null,
    ]);

    $response = $this->actingAs($user)->post(route('threads.customize-title', $thread->id), [
        'title_color' => '#00ff00',
        'title_animation' => 'none',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('error', 'You do not have enough coins to apply these changes. (Requires 100 coins)');

    $thread->refresh();
    $user->refresh();

    expect($thread->title_color)->toBeNull();
    expect($user->coins)->toBe(50);
});

it('allows admins to customize thread title style for free', function () {
    $user = User::factory()->create(['coins' => 10]);
    $user->admin()->create(); // Grant admin role

    $thread = Thread::create([
        'user_id' => $user->id,
        'category_id' => $this->category->id,
        'title' => 'My Test Thread Title',
        'slug' => 'my-test-thread-title',
        'title_color' => null,
        'title_animation' => null,
    ]);

    $response = $this->actingAs($user)->post(route('threads.customize-title', $thread->id), [
        'title_color' => '#ffffff',
        'title_animation' => 'shimmer',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Your thread title has been styled successfully!');

    $thread->refresh();
    $user->refresh();

    expect($thread->title_color)->toBe('#ffffff');
    expect($thread->title_animation)->toBe('shimmer');
    expect($user->coins)->toBe(10); // Not charged
});
