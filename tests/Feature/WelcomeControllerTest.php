<?php

use App\Models\User;

test('welcome page renders with public navigation for guests', function () {
    $response = $this->get('/');

    $response->assertOk();

    $response->assertSee('Welcome to Chickins Den');
    $response->assertSee(route('chickens.index'));
    $response->assertSee(route('breeds.index'));
    $response->assertSee(route('about'));
    $response->assertSee(route('login'));
});

test('admin navigation is hidden from guests', function () {
    $response = $this->get('/');

    $response->assertDontSee(route('admin.index'));
    $response->assertDontSee(route('chickens.create'));
});

test('admin navigation is shown to authenticated users', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/');

    $response->assertOk();
    $response->assertSee(route('admin.index'));
    $response->assertSee(route('chickens.create'));
    $response->assertSee(route('logout'));
    $response->assertDontSee(route('login'));
});
