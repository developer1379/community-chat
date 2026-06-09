<?php

use App\Models\User;
use App\Models\Admin;
use App\Models\SearchHistory;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('does not log search queries for guests', function () {
    $response = $this->get('/search?q=testquery');

    $response->assertStatus(200);
    expect(SearchHistory::count())->toBe(0);
});

it('logs search queries for authenticated users', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/search?q=php%20framework');

    $response->assertStatus(200);
    expect(SearchHistory::count())->toBe(1);
    
    $history = SearchHistory::first();
    expect($history->user_id)->toBe($user->id);
    expect($history->query)->toBe('php framework');
});

it('does not log consecutive duplicate queries for the same user', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/search?q=laravel');
    $this->actingAs($user)->get('/search?q=laravel');
    $this->actingAs($user)->get('/search?q=php');

    expect(SearchHistory::count())->toBe(2);
    expect(SearchHistory::pluck('query')->toArray())->toBe(['laravel', 'php']);
});

it('allows user to delete a single query from their search history', function () {
    $user = User::factory()->create();
    $history = SearchHistory::create([
        'user_id' => $user->id,
        'query' => 'testing deletion',
    ]);

    $response = $this->actingAs($user)->delete(route('search.history.delete', $history->id));

    $response->assertRedirect();
    expect(SearchHistory::count())->toBe(0);
});

it('allows user to clear their entire search history', function () {
    $user = User::factory()->create();
    SearchHistory::create(['user_id' => $user->id, 'query' => 'q1']);
    SearchHistory::create(['user_id' => $user->id, 'query' => 'q2']);

    $response = $this->actingAs($user)->delete(route('search.history.clear'));

    $response->assertRedirect();
    expect(SearchHistory::count())->toBe(0);
});

it('allows admin to view search history of any user', function () {
    $admin = User::factory()->create();
    Admin::create(['user_id' => $admin->id]);

    $member = User::factory()->create();
    SearchHistory::create(['user_id' => $member->id, 'query' => 'inspected query']);

    $response = $this->actingAs($admin)->get(route('admin.users.search-history', $member->id));

    $response->assertStatus(200);
    $response->assertSee('inspected query');
});

it('allows admin to clear search history of any user', function () {
    $admin = User::factory()->create();
    Admin::create(['user_id' => $admin->id]);

    $member = User::factory()->create();
    SearchHistory::create(['user_id' => $member->id, 'query' => 'query to clear']);

    $response = $this->actingAs($admin)->delete(route('admin.users.search-history.clear', $member->id));

    $response->assertRedirect();
    expect(SearchHistory::count())->toBe(0);
});

it('prevents non-admins from viewing or clearing user search histories', function () {
    $member1 = User::factory()->create();
    $member2 = User::factory()->create();
    
    SearchHistory::create(['user_id' => $member2->id, 'query' => 'secret search']);

    $response1 = $this->actingAs($member1)->get(route('admin.users.search-history', $member2->id));
    $response1->assertStatus(403);

    $response2 = $this->actingAs($member1)->delete(route('admin.users.search-history.clear', $member2->id));
    $response2->assertStatus(403);

    expect(SearchHistory::count())->toBe(1);
});
