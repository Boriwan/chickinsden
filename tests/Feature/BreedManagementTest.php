<?php

use App\Models\Breed;
use App\Models\Chicken;
use App\Models\User;

it('forbids a non-admin from every breed write route', function (string $method, string $route) {
    // Route model binding resolves before the admin middleware, so the breed
    // has to exist or the request would 404 before reaching the check.
    $breed = Breed::factory()->create();

    $this->actingAs(User::factory()->regular()->create())
        ->call($method, route($route, $breed), [])
        ->assertForbidden();
})->with([
    ['GET', 'admin.breeds.create'],
    ['POST', 'admin.breeds.store'],
    ['GET', 'admin.breeds.edit'],
    ['PUT', 'admin.breeds.update'],
    ['DELETE', 'admin.breeds.destroy'],
]);

it('redirects a guest away from the admin breed routes', function (string $path) {
    $this->get($path)->assertRedirect(route('login'));
})->with(['/admin/breeds/create', '/admin/breeds/1/edit']);

it('lets an admin open the create form', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->get(route('admin.breeds.create'))
        ->assertOk();
});

it('lets an admin create a breed', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->post(route('admin.breeds.store'), [
            'name' => 'Dorking',
            'description' => 'A long, wide breed with very short legs.',
        ])
        ->assertRedirect(route('admin.breeds.index'));

    $this->assertDatabaseHas('breeds', [
        'name' => 'Dorking',
        'description' => 'A long, wide breed with very short legs.',
    ]);
});

it('rejects a breed with no name or description', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->post(route('admin.breeds.store'), ['name' => '', 'description' => ''])
        ->assertSessionHasErrors(['name', 'description']);

    $this->assertDatabaseMissing('breeds', ['name' => '']);
});

it('rejects a duplicate breed name', function () {
    Breed::factory()->create(['name' => 'Houdan']);

    $this->actingAs(User::factory()->admin()->create())
        ->post(route('admin.breeds.store'), [
            'name' => 'Houdan',
            'description' => 'Another one.',
        ])
        ->assertSessionHasErrors('name');
});

it('lets an admin rename a breed to a name it already had', function () {
    $breed = Breed::factory()->create([
        'name' => 'Vorwerk',
        'description' => 'Original description.',
    ]);

    $this->actingAs(User::factory()->admin()->create())
        ->put(route('admin.breeds.update', $breed), [
            'name' => 'Vorwerk',
            'description' => 'Sharper description.',
        ])
        ->assertRedirect(route('admin.breeds.index'))
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas('breeds', [
        'id' => $breed->id,
        'description' => 'Sharper description.',
    ]);
});

it('lets an admin edit a breed', function () {
    $breed = Breed::factory()->create();

    $this->actingAs(User::factory()->admin()->create())
        ->get(route('admin.breeds.edit', $breed))
        ->assertOk()
        ->assertSee($breed->name);
});

it('lets an admin delete a breed nothing uses', function () {
    $breed = Breed::factory()->create(['name' => 'Malay']);

    $this->actingAs(User::factory()->admin()->create())
        ->delete(route('admin.breeds.destroy', $breed))
        ->assertRedirect(route('admin.breeds.index'));

    $this->assertDatabaseMissing('breeds', ['name' => 'Malay']);
});

it('refuses to delete a breed that chickens still use', function () {
    $breed = Breed::factory()->create();
    Chicken::factory()->count(2)->create(['breed_id' => $breed->id]);

    $this->actingAs(User::factory()->admin()->create())
        ->delete(route('admin.breeds.destroy', $breed))
        ->assertRedirect(route('admin.breeds.index'))
        ->assertSessionHas('notice', fn (array $toasts) => ($toasts[0]['action'] ?? null) === 'error');

    $this->assertDatabaseHas('breeds', ['id' => $breed->id]);
    $this->assertDatabaseCount('chickens', 2);
});

it('counts the chickens under each breed for the admin table', function () {
    $breed = Breed::factory()->create(['name' => 'Sussex']);
    Chicken::factory()->count(3)->create(['breed_id' => $breed->id]);

    $this->actingAs(User::factory()->admin()->create())
        ->get(route('admin.breeds.index'))
        ->assertOk()
        ->assertSee('Sussex')
        ->assertSeeInOrder(['Sussex', '3']);
});

it('keeps the public breed page to the description alone', function () {
    $breed = Breed::factory()->create(['name' => 'Leghorn']);
    Chicken::factory()->create(['breed_id' => $breed->id, 'name' => 'Nerissa']);

    $this->get(route('breeds.show', $breed))
        ->assertOk()
        ->assertSee('Leghorn')
        ->assertDontSee('Nerissa')
        ->assertDontSee(route('chickens.index'))
        ->assertDontSee('chicken(s)');
});

it('keeps chicken counts off the public wiki', function () {
    $breed = Breed::factory()->create(['name' => 'Australorp']);
    Chicken::factory()->count(4)->create(['breed_id' => $breed->id]);

    $this->get(route('breeds.index'))
        ->assertOk()
        ->assertSee('Australorp')
        ->assertDontSee('4 chickens');
});

it('offers breed edit and delete to an admin on the breed page', function () {
    $breed = Breed::factory()->create(['name' => 'Brahma']);

    $this->actingAs(User::factory()->admin()->create())
        ->get(route('breeds.show', $breed))
        ->assertOk()
        ->assertSee(route('admin.breeds.edit', $breed))
        ->assertSee(route('admin.breeds.destroy', $breed));
});

it('tags each breed write with its own notification action', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.breeds.store'), ['name' => 'Hamburg', 'description' => 'A spotted German breed.'])
        ->assertSessionHas('notice', fn (array $t) => ($t[0]['action'] ?? null) === 'created');

    $breed = Breed::where('name', 'Hamburg')->firstOrFail();

    $this->actingAs($admin)
        ->put(route('admin.breeds.update', $breed), ['name' => 'Hamburg', 'description' => 'Revised.'])
        ->assertSessionHas('notice', fn (array $t) => ($t[0]['action'] ?? null) === 'updated');

    $this->actingAs($admin)
        ->delete(route('admin.breeds.destroy', $breed))
        ->assertSessionHas('notice', fn (array $t) => ($t[0]['action'] ?? null) === 'deleted');
});

it('renders a delete notification in red with a dismiss button', function () {
    $admin = User::factory()->admin()->create();
    $breed = Breed::factory()->create();

    $this->actingAs($admin)->delete(route('admin.breeds.destroy', $breed));

    $response = $this->actingAs($admin)->get(route('admin.breeds.index'));

    $response->assertOk()
        ->assertSee('was deleted')
        ->assertSee('border-red-200 bg-red-50 text-red-800')
        ->assertSee('Dismiss notification');
});

it('hides breed edit and delete from everyone else', function () {
    $breed = Breed::factory()->create();

    $this->get(route('breeds.show', $breed))
        ->assertOk()
        ->assertDontSee(route('admin.breeds.edit', $breed));

    $this->actingAs(User::factory()->regular()->create())
        ->get(route('breeds.show', $breed))
        ->assertOk()
        ->assertDontSee(route('admin.breeds.edit', $breed));
});

it('tags chicken writes with their own notification action', function () {
    $user = User::factory()->regular()->create();
    $breed = Breed::factory()->create();
    $data = [
        'name' => 'Pepper',
        'gender' => 'female',
        'birth_date' => '2024-01-05',
        'breed_id' => $breed->id,
        'height' => 30,
        'weight' => 'light',
    ];

    $this->actingAs($user)
        ->post(route('chickens.store'), $data)
        ->assertSessionHas('notice', fn (array $t) => ($t[0]['action'] ?? null) === 'created');

    $chicken = $user->chickens()->firstOrFail();

    $this->actingAs($user)
        ->put(route('chickens.update', $chicken), $data)
        ->assertSessionHas('notice', fn (array $t) => ($t[0]['action'] ?? null) === 'updated');

    $this->actingAs($user)
        ->delete(route('chickens.destroy', $chicken))
        ->assertSessionHas('notice', fn (array $t) => ($t[0]['action'] ?? null) === 'deleted');
});

it('renders a created notification in green and an updated one in cyan', function () {
    $user = User::factory()->regular()->create();
    $breed = Breed::factory()->create();
    $data = [
        'name' => 'Comet',
        'gender' => 'male',
        'birth_date' => '2024-02-02',
        'breed_id' => $breed->id,
        'height' => 28,
        'weight' => 'medium',
    ];

    $this->actingAs($user)
        ->followingRedirects()
        ->post(route('chickens.store'), $data)
        ->assertOk()
        ->assertSee('Comet was added.')
        ->assertSee('border-green-200 bg-green-50 text-green-800');

    $this->actingAs($user)
        ->followingRedirects()
        ->put(route('chickens.update', $user->chickens()->firstOrFail()), $data)
        ->assertOk()
        ->assertSee('Comet was updated.')
        ->assertSee('border-cyan-200 bg-cyan-50 text-cyan-800');
});

it('renders a plain status flash as an uncoloured notice', function () {
    // The auth flows flash a bare 'status'. It must still surface, in the
    // neutral brand tone, rather than being dropped by the new action split.
    $breed = Breed::factory()->create();

    $this->actingAs(User::factory()->admin()->create())
        ->withSession(['status' => 'profile-updated'])
        ->get(route('breeds.show', $breed))
        ->assertOk()
        ->assertSee('profile-updated')
        ->assertSee('border-brand-200 bg-brand-50 text-brand-800');
});

it('lets guests read the breed wiki', function () {
    Breed::factory()->create(['name' => 'Brahma']);

    $this->get(route('breeds.index'))
        ->assertOk()
        ->assertSee('Brahma');
});
