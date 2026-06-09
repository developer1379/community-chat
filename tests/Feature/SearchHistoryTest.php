<?php

use App\Models\User;
use App\Models\Admin;
use App\Models\SearchHistory;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('logs search queries in session for guest users', function () {
    $response = $this->get('/search?q=unregistered%20query');

    $response->assertStatus(200);
    expect(SearchHistory::count())->toBe(0);
    expect(session('guest_search_history'))->toBe(['unregistered query']);
});

it('logs search queries for authenticated users in database', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/search?q=php%20framework');

    $response->assertStatus(200);
    expect(SearchHistory::count())->toBe(1);
    expect(session()->has('guest_search_history'))->toBeFalse();
    
    $history = SearchHistory::first();
    expect($history->user_id)->toBe($user->id);
    expect($history->query)->toBe('php framework');
});

it('does not log consecutive duplicate queries for the same user or guest', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/search?q=laravel');
    $this->actingAs($user)->get('/search?q=laravel');
    $this->actingAs($user)->get('/search?q=php');

    expect(SearchHistory::count())->toBe(2);
    expect(SearchHistory::pluck('query')->toArray())->toBe(['laravel', 'php']);

    // Guest duplicate check
    $this->post(route('logout'));
    $this->get('/search?q=guest-query');
    $this->get('/search?q=guest-query');
    $this->get('/search?q=another-guest');

    expect(session('guest_search_history'))->toBe(['guest-query', 'another-guest']);
});

it('allows guest user to delete a single query from their search history', function () {
    session()->put('guest_search_history', ['q1', 'q2', 'q3']);

    $response = $this->delete(route('search.history.delete', 'q2'));

    $response->assertRedirect();
    expect(session('guest_search_history'))->toBe(['q1', 'q3']);
});

it('allows guest user to clear their entire search history', function () {
    session()->put('guest_search_history', ['q1', 'q2']);

    $response = $this->delete(route('search.history.clear'));

    $response->assertRedirect();
    expect(session()->has('guest_search_history'))->toBeFalse();
});

it('allows user to delete a single query from their database search history', function () {
    $user = User::factory()->create();
    $history = SearchHistory::create([
        'user_id' => $user->id,
        'query' => 'testing deletion',
    ]);

    $response = $this->actingAs($user)->delete(route('search.history.delete', $history->id));

    $response->assertRedirect();
    expect(SearchHistory::count())->toBe(0);
});

it('allows user to clear their entire database search history', function () {
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
