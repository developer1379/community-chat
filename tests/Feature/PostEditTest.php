<?php

use App\Models\User;
use App\Models\Category;
use App\Models\Thread;
use App\Models\Post;
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

it('allows a user to edit their own post successfully', function () {
    $user = User::factory()->create();
    $thread = Thread::create([
        'user_id' => $user->id,
        'category_id' => $this->category->id,
        'title' => 'My Test Thread',
        'slug' => 'my-test-thread',
    ]);

    $post = Post::create([
        'thread_id' => $thread->id,
        'user_id' => $user->id,
        'content' => 'Original Post Content',
    ]);

    $response = $this->actingAs($user)->put(route('posts.update', $post->id), [
        'content' => 'Updated Post Content',
    ]);

    $response->assertRedirect();
    $post->refresh();

    expect($post->content)->toBe('Updated Post Content');
});

it('does not allow editing another user\'s post', function () {
    $userOne = User::factory()->create();
    $userTwo = User::factory()->create();

    $thread = Thread::create([
        'user_id' => $userOne->id,
        'category_id' => $this->category->id,
        'title' => 'My Test Thread',
        'slug' => 'my-test-thread',
    ]);

    $post = Post::create([
        'thread_id' => $thread->id,
        'user_id' => $userOne->id,
        'content' => 'Original Post Content',
    ]);

    $response = $this->actingAs($userTwo)->put(route('posts.update', $post->id), [
        'content' => 'Hijacked Content',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('error', 'You are not authorized to edit this post.');
    
    $post->refresh();
    expect($post->content)->toBe('Original Post Content');
});
