<?php

use App\Models\User;
use App\Models\SystemNotification;
use App\Models\Category;
use App\Models\Thread;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows the notifications index to authenticated users', function () {
    $user = User::factory()->create();
    
    SystemNotification::create([
        'user_id' => $user->id,
        'title' => 'Test Notification',
        'message' => 'This is a test message',
        'is_read' => false,
    ]);

    $response = $this->actingAs($user)->get(route('notifications.index'));
    $response->assertStatus(200);
    $response->assertSee('Notifications');
    $response->assertSee('Test Notification');
    $response->assertSee('This is a test message');
});

it('marks a notification as read and redirects when clicking read endpoint', function () {
    $user = User::factory()->create();
    $notification = SystemNotification::create([
        'user_id' => $user->id,
        'title' => 'Test Link Notification',
        'message' => 'Go check this out',
        'link' => 'http://localhost/target-test-path',
        'is_read' => false,
    ]);

    $response = $this->actingAs($user)->get(route('notifications.read', $notification->id));
    $response->assertRedirect('http://localhost/target-test-path');

    $this->assertTrue($notification->fresh()->is_read);
});

it('marks all system notifications as read via post clear endpoint', function () {
    $user = User::factory()->create();
    
    SystemNotification::create([
        'user_id' => $user->id,
        'title' => 'Notif 1',
        'message' => 'Message 1',
        'is_read' => false,
    ]);
    SystemNotification::create([
        'user_id' => $user->id,
        'title' => 'Notif 2',
        'message' => 'Message 2',
        'is_read' => false,
    ]);

    $response = $this->actingAs($user)->post('/notifications/system/clear');
    $response->assertStatus(200);
    $response->assertJson(['status' => 'success']);

    $this->assertEquals(0, SystemNotification::where('user_id', $user->id)->where('is_read', false)->count());
});

it('generates a notification when a user is followed', function () {
    $follower = User::factory()->create(['name' => 'FollowerUser']);
    $followed = User::factory()->create(['name' => 'FollowedUser']);

    $response = $this->actingAs($follower)->post(route('members.follow', $followed->name));
    $response->assertStatus(200);

    $this->assertDatabaseHas('system_notifications', [
        'user_id' => $followed->id,
        'title' => 'New Follower',
        'message' => 'FollowerUser followed you!',
    ]);
});

it('generates a notification when a user replies to a thread', function () {
    $threadOwner = User::factory()->create();
    $replier = User::factory()->create(['name' => 'ReplierUser']);
    
    $category = Category::create([
        'name' => 'General',
        'slug' => 'general',
        'description' => 'General forum',
        'order' => 1,
        'is_active' => true,
    ]);

    $thread = Thread::create([
        'user_id' => $threadOwner->id,
        'category_id' => $category->id,
        'title' => 'My Cool Thread',
        'slug' => 'my-cool-thread',
        'body' => 'Thread body content',
    ]);

    $response = $this->actingAs($replier)->post(route('threads.reply', $thread->slug), [
        'content' => 'I am replying to your thread!',
    ]);
    $response->assertRedirect();

    $this->assertDatabaseHas('system_notifications', [
        'user_id' => $threadOwner->id,
        'title' => 'New Reply to your Thread',
        'message' => 'ReplierUser replied to your thread: "My Cool Thread"',
    ]);
});

it('generates a notification when a user reacts to a post', function () {
    $postOwner = User::factory()->create();
    $reactor = User::factory()->create(['name' => 'ReactorUser']);
    
    $category = Category::create([
        'name' => 'General',
        'slug' => 'general',
        'description' => 'General forum',
        'order' => 1,
        'is_active' => true,
    ]);

    $thread = Thread::create([
        'user_id' => $postOwner->id,
        'category_id' => $category->id,
        'title' => 'Thread Title',
        'slug' => 'thread-title',
        'body' => 'Thread body content',
    ]);

    $post = Post::create([
        'thread_id' => $thread->id,
        'user_id' => $postOwner->id,
        'content' => 'This is a test post that will receive a reaction',
    ]);

    $response = $this->actingAs($reactor)->post("/posts/{$post->id}/react", [
        'type' => 'like',
    ]);
    $response->assertStatus(200);

    $this->assertDatabaseHas('system_notifications', [
        'user_id' => $postOwner->id,
        'title' => 'New Reaction on your Post',
        'message' => 'ReactorUser reacted with Like to your post in: "Thread Title"',
    ]);
});
