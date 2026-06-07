<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('checks username availability correctly', function () {
    // Create an existing user
    User::factory()->create(['name' => 'TakenUsername']);

    // Check availability of an available username
    $responseAvailable = $this->postJson(route('register.check-username'), [
        'name' => 'AvailableUsername'
    ]);
    $responseAvailable->assertStatus(200);
    $responseAvailable->assertJson(['available' => true]);

    // Check availability of a taken username
    $responseTaken = $this->postJson(route('register.check-username'), [
        'name' => 'TakenUsername'
    ]);
    $responseTaken->assertStatus(200);
    $responseTaken->assertJson(['available' => false]);
});

it('registers a user successfully through the post route', function () {
    $response = $this->post('/register', [
        'name' => 'NewUser',
        'email' => 'newuser@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertRedirect(route('home'));
    $this->assertDatabaseHas('users', [
        'name' => 'NewUser',
        'email' => 'newuser@example.com',
    ]);
    $this->assertTrue(auth()->check());
});

it('redirects non-onboarded users to setup-profile page', function () {
    $user = User::factory()->create([
        'is_onboarded' => false,
    ]);

    $response = $this->actingAs($user)->get(route('home'));
    $response->assertRedirect(route('register.setup-profile'));
});

it('allows non-onboarded user to submit setup-profile and saves name and preset avatar', function () {
    $user = User::factory()->create([
        'name' => 'TemporaryGoogleUser',
        'is_onboarded' => false,
    ]);

    $response = $this->actingAs($user)->post(route('register.save-setup-profile'), [
        'name' => 'OnboardedGoogleUser',
        'avatar_preset' => 'https://api.dicebear.com/7.x/pixel-art/svg?seed=Luna',
    ]);

    $response->assertRedirect(route('home'));
    $user->refresh();

    $this->assertEquals('OnboardedGoogleUser', $user->name);
    $this->assertEquals('https://api.dicebear.com/7.x/pixel-art/svg?seed=Luna', $user->avatar_path);
    $this->assertTrue($user->is_onboarded);
});
