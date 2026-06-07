<?php

use App\Models\Category;

it('homepage returns a 200 when categories exist', function () {
    Category::factory()->create();

    $response = $this->get('/');

    $response->assertStatus(200);
});

it('sitemap.xml returns a valid xml response', function () {
    $response = $this->get('/sitemap.xml');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
});

it('rules page returns 200', function () {
    $response = $this->get('/rules');

    $response->assertStatus(200);
});

it('search page returns 200 with empty query', function () {
    $response = $this->get('/search?q=');

    $response->assertStatus(200);
});
