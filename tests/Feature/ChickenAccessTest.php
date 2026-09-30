<?php

use App\Models\Chicken;
use App\Models\User;

it('redirects a guest away from the chicken pages', function (string $path) {
    $this->get($path)->assertRedirect(route('login'));
})->with(['/chickens', '/chickens/create', '/admin', '/admin/chickens']);

it('forbids a non-admin from the admin area', function (string $path) {
    $this->actingAs(User::factory()->regular()->create())
        ->get($path)
        ->assertForbidden();
})->with(['/admin', '/admin/chickens', '/admin/breeds']);

it('lets an admin into the admin area', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->get('/admin/chickens')
        ->assertOk();
});

it('shows an owner their own chicken', function () {
    $owner = User::factory()->regular()->create();
    $chicken = Chicken::factory()->create(['user_id' => $owner->id]);

    $this->actingAs($owner)
        ->get(route('chickens.show', $chicken))
        ->assertOk();
});

it('forbids a non-owner from opening a chicken they do not own', function () {
    $chicken = Chicken::factory()->create(['user_id' => User::factory()->regular()->create()->id]);

    $this->actingAs(User::factory()->regular()->create())
        ->get(route('chickens.show', $chicken))
        ->assertForbidden();
});

it('forbids a non-owner from editing a chicken they do not own', function () {
    $chicken = Chicken::factory()->create(['user_id' => User::factory()->regular()->create()->id]);

    $this->actingAs(User::factory()->regular()->create())
        ->get(route('chickens.edit', $chicken))
        ->assertForbidden();
});

it('lists only the signed-in users chickens', function () {
    $user = User::factory()->regular()->create();
    $mine = Chicken::factory()->create(['user_id' => $user->id, 'name' => 'Miney']);
    Chicken::factory()->create(['user_id' => User::factory()->regular()->create()->id, 'name' => 'Theirs']);

    $response = $this->actingAs($user)->get(route('chickens.index'));

    $response->assertOk();
    $response->assertSee('Miney');
    $response->assertDontSee('Theirs');
});

it('lists every chicken for an admin and shows who owns each one', function () {
    $admin = User::factory()->admin()->create();
    $owner = User::factory()->regular()->create(['name' => 'Olive Owner']);
    Chicken::factory()->create(['user_id' => $owner->id, 'name' => 'Somebody Elses Chicken']);

    $response = $this->actingAs($admin)->get(route('admin.chickens.index'));

    $response->assertOk();
    $response->assertSee('Somebody Elses Chicken');
    $response->assertSee('Olive Owner');
});
