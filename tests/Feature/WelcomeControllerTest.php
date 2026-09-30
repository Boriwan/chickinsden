<?php

use App\Models\User;

it('shows the welcome page and a login link to guests', function () {
    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('Welcome to Chickins Den');
    $response->assertSee(route('login'));
    $response->assertDontSee(route('admin.index'));
});

it('sends signed-in users from the home page to their dashboard', function () {
    $response = $this->actingAs(User::factory()->regular()->create())->get('/');

    $response->assertRedirect(route('dashboard'));
});

it('sends admins from the home page to their dashboard too', function () {
    $response = $this->actingAs(User::factory()->admin()->create())->get('/');

    $response->assertRedirect(route('dashboard'));
});

it('hides the admin link from a non-admin', function () {
    $response = $this->actingAs(User::factory()->regular()->create())
        ->get(route('chickens.index'));

    $response->assertOk();
    $response->assertDontSee(route('admin.index'));
    $response->assertSee(route('chickens.create'));
    $response->assertSee(route('logout'));
});

it('shows the admin link to an admin', function () {
    $response = $this->actingAs(User::factory()->admin()->create())
        ->get(route('chickens.index'));

    $response->assertOk();
    $response->assertSee(route('admin.index'));
    $response->assertSee(route('chickens.create'));
    $response->assertSee(route('logout'));
    $response->assertDontSee(route('login'));
});
