<?php

use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows authenticated users to fetch conversations', function () {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->getJson('/dms/conversations');
    
    $response->assertStatus(200);
});

it('allows starting a conversation', function () {
    $user1 = User::factory()->create(['name' => 'Alice']);
    $user2 = User::factory()->create(['name' => 'Bob']);

    $response = $this->actingAs($user1)->postJson('/dms/start/Bob');

    $response->assertStatus(200);
    $response->assertJsonStructure(['conversation_id']);

    $this->assertDatabaseHas('conversations', [
        'user_one_id' => $user1->id,
        'user_two_id' => $user2->id,
    ]);
});

it('allows sending a message', function () {
    $user1 = User::factory()->create(['name' => 'Alice']);
    $user2 = User::factory()->create(['name' => 'Bob']);
    
    $conversation = Conversation::create([
        'user_one_id' => $user1->id,
        'user_two_id' => $user2->id,
    ]);

    $response = $this->actingAs($user1)->postJson("/dms/conversations/{$conversation->id}/send", [
        'body' => 'Hello Bob!',
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure(['id', 'body', 'sender_id']);

    $this->assertDatabaseHas('messages', [
        'conversation_id' => $conversation->id,
        'sender_id' => $user1->id,
        'body' => 'Hello Bob!',
    ]);
});
