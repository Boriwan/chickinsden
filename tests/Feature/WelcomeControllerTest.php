<?php

use App\Models\Chicken;
use App\Models\User;

it('shows the welcome page with a login link to guests', function () {
    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('Welcome to Chickins Den');
    $response->assertSee(route('login'));
    $response->assertSee(route('register'));
    $response->assertDontSee(route('admin.index'));
    $response->assertDontSee('Recently added');
});

it('shows a welcome message and the dashboard to a signed-in user', function () {
    $user = User::factory()->regular()->create(['name' => 'Bella Keeper']);
    Chicken::factory()->create(['user_id' => $user->id, 'name' => 'Nugget']);

    $response = $this->actingAs($user)->get('/');

    $response->assertOk();
    $response->assertSee('Welcome back, Bella');
    $response->assertSee('Recently added');
    $response->assertSee('Nugget');
    $response->assertSee('Chickens');
});

it('shows the dashboard to an admin as well', function () {
    $response = $this->actingAs(User::factory()->admin()->create(['name' => 'Alex Admin']))->get('/');

    $response->assertOk();
    $response->assertSee('Welcome back, Alex');
    $response->assertSee('Recently added');
    $response->assertSee('Admin area');
});

it('does not show another users chickens on the home page', function () {
    $user = User::factory()->regular()->create();
    Chicken::factory()->create(['user_id' => $user->id, 'name' => 'Miney']);
    Chicken::factory()->create(['user_id' => User::factory()->create()->id, 'name' => 'Theirs']);

    $response = $this->actingAs($user)->get('/');

    $response->assertSee('Miney');
    $response->assertDontSee('Theirs');
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

it('hides the chickens link from guests', function () {
    $response = $this->get(route('about'));

    $response->assertOk();
    $response->assertDontSee(route('chickens.index'));
    $response->assertSee(route('breeds.index'));
    $response->assertSee(route('login'));
    $response->assertSee(route('register'));
});

it('shows the chickens link once signed in', function () {
    $response = $this->actingAs(User::factory()->regular()->create())
        ->get(route('chickens.index'));

    $response->assertOk();
    $response->assertSee(route('chickens.index'));
});

it('always sends a login to the home page', function () {
    User::factory()->regular()->create([
        'email' => 'keeper@example.com',
        'password' => 'password',
    ]);

    $response = $this->post(route('login'), [
        'email' => 'keeper@example.com',
        'password' => 'password',
    ]);

    $response->assertRedirect(route('home'));
    $this->assertAuthenticated();
});

it('sends a login to the home page even after being bounced from a protected page', function () {
    User::factory()->regular()->create([
        'email' => 'keeper@example.com',
        'password' => 'password',
    ]);

    // Bouncing off the protected page would normally record it as the
    // intended destination and send the user back there after logging in.
    $this->get(route('chickens.index'))->assertRedirect(route('login'));

    $response = $this->post(route('login'), [
        'email' => 'keeper@example.com',
        'password' => 'password',
    ]);

    $response->assertRedirect(route('home'));
    $this->assertAuthenticated();
});
