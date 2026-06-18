<?php

use App\Models\User;
use App\Models\Admin;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('prevents non-admins from accessing the settings page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('admin.settings'));
    $response->assertStatus(403);
});

it('allows admin to access settings page and see default config values', function () {
    $user = User::factory()->create();
    Admin::create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get(route('admin.settings'));
    $response->assertStatus(200);
    $response->assertSee('Firebase Realtime Database Settings');
});

it('saves firebase configuration to settings table and updates application config dynamically', function () {
    $user = User::factory()->create();
    Admin::create(['user_id' => $user->id]);

    // Ensure we start with clean/default configs
    config(['firebase.api_key' => 'old-api-key']);
    config(['firebase.secret' => 'old-secret']);

    $payload = [
        'api_key' => 'new-test-api-key',
        'auth_domain' => 'new-test-auth-domain.firebaseapp.com',
        'database_url' => 'https://new-test-db-url.firebaseio.com',
        'project_id' => 'new-test-project-id',
        'storage_bucket' => 'new-test-storage-bucket.appspot.com',
        'messaging_sender_id' => '987654321',
        'app_id' => '1:987654321:web:abc123xyz',
        'secret' => 'new-test-secret-token',
    ];

    $response = $this->actingAs($user)
        ->from(route('admin.settings'))
        ->put(route('admin.settings.firebase.update'), $payload);

    $response->assertRedirect(route('admin.settings'));
    $response->assertSessionHas('success');

    // Assert database has the values
    foreach ($payload as $key => $value) {
        $this->assertDatabaseHas('settings', [
            'key' => 'firebase_' . $key,
            'value' => $value,
        ]);
    }

    // Call service provider boot manually or simulate page reload to assert configs are dynamically set
    // In our case, the AppServiceProvider boot method was already executed, but we can verify that the updated configs
    // are reloaded. Let's run a fresh app boot simulator, or call the provider's boot method directly.
    (new \App\Providers\AppServiceProvider(app()))->boot();

    $this->assertEquals('new-test-api-key', config('firebase.api_key'));
    $this->assertEquals('new-test-secret-token', config('firebase.secret'));
    $this->assertEquals('https://new-test-db-url.firebaseio.com', config('firebase.database_url'));
});
