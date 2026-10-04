<?php

use App\Models\Breed;
use App\Models\Chicken;
use App\Models\ChickenTrait;
use App\Models\User;

it('forbids a non-admin from every trait write route', function (string $method, string $route) {
    $trait = ChickenTrait::factory()->create();

    $this->actingAs(User::factory()->regular()->create())
        ->call($method, route($route, $trait), [])
        ->assertForbidden();
})->with([
    ['GET', 'admin.traits.index'],
    ['GET', 'admin.traits.create'],
    ['POST', 'admin.traits.store'],
    ['PUT', 'admin.traits.update'],
    ['DELETE', 'admin.traits.destroy'],
]);

it('lets an admin list traits with a chicken count', function () {
    $trait = ChickenTrait::factory()->create(['name' => 'Broody']);

    Chicken::factory()->count(2)->create()->each(function (Chicken $chicken) use ($trait) {
        $chicken->traits()->attach([$trait->id]);
    });

    $this->actingAs(User::factory()->admin()->create())
        ->get(route('admin.traits.index'))
        ->assertOk()
        ->assertSee('Broody')
        ->assertSeeInOrder(['Broody', '2']);
});

it('lets an admin add a trait inline', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->post(route('admin.traits.store'), ['name' => 'Glittering'])
        ->assertRedirect(route('admin.traits.index'));

    $this->assertDatabaseHas('chicken_traits', ['name' => 'Glittering']);
});

it('rejects a duplicate trait name', function () {
    ChickenTrait::factory()->create(['name' => 'Shy']);

    $this->actingAs(User::factory()->admin()->create())
        ->post(route('admin.traits.store'), ['name' => 'Shy'])
        ->assertSessionHasErrors('name');
});

it('rejects an empty trait name', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->post(route('admin.traits.store'), ['name' => ''])
        ->assertSessionHasErrors('name');
});

it('lets an admin rename a trait to the name it already had', function () {
    $trait = ChickenTrait::factory()->create(['name' => 'Loud']);

    $this->actingAs(User::factory()->admin()->create())
        ->put(route('admin.traits.update', $trait), ['name' => 'Loud'])
        ->assertRedirect(route('admin.traits.index'))
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas('chicken_traits', ['id' => $trait->id, 'name' => 'Loud']);
});

it('leaves a chickens traits untouched when its trait is renamed', function () {
    $trait = ChickenTrait::factory()->create(['name' => 'Curious']);
    $chicken = Chicken::factory()->create();
    $chicken->traits()->attach([$trait->id]);

    $this->actingAs(User::factory()->admin()->create())
        ->put(route('admin.traits.update', $trait), ['name' => 'Nosy']);

    $this->assertDatabaseHas('chicken_trait', [
        'chicken_id' => $chicken->id,
        'trait_id' => $trait->id,
    ]);
});

it('lets an admin delete a trait and drops it from every chicken', function () {
    $trait = ChickenTrait::factory()->create(['name' => 'Playful']);
    $chicken = Chicken::factory()->create();
    $chicken->traits()->attach([$trait->id]);

    $this->actingAs(User::factory()->admin()->create())
        ->delete(route('admin.traits.destroy', $trait))
        ->assertRedirect(route('admin.traits.index'));

    $this->assertDatabaseMissing('chicken_traits', ['id' => $trait->id]);
    $this->assertDatabaseMissing('chicken_trait', [
        'chicken_id' => $chicken->id,
        'trait_id' => $trait->id,
    ]);

    // The chicken itself must survive.
    $this->assertDatabaseHas('chickens', ['id' => $chicken->id]);
});

it('assigns traits to a chicken on creation', function () {
    $breed = Breed::factory()->create();
    $traits = ChickenTrait::factory()->count(2)->create();
    $user = User::factory()->regular()->create();

    $this->actingAs($user)->post(route('chickens.store'), [
        'name' => 'Pepper',
        'gender' => 'female',
        'birth_date' => '2024-01-05',
        'breed_id' => $breed->id,
        'height' => 30,
        'weight' => 'light',
        'traits' => $traits->pluck('id')->all(),
    ]);

    $chicken = $user->chickens()->firstOrFail();

    $this->assertEqualsCanonicalizing(
        $traits->pluck('id')->all(),
        $chicken->traits->pluck('id')->all(),
    );
});

it('replaces the trait set on update rather than stacking duplicates', function () {
    $breed = Breed::factory()->create();
    $user = User::factory()->regular()->create();
    $chicken = Chicken::factory()->create(['user_id' => $user->id, 'breed_id' => $breed->id]);
    $first = ChickenTrait::factory()->create();
    $second = ChickenTrait::factory()->create();

    $chicken->traits()->attach([$first->id]);

    $this->actingAs($user)->put(route('chickens.update', $chicken), [
        'name' => $chicken->name,
        'gender' => $chicken->gender,
        'birth_date' => $chicken->birth_date,
        'breed_id' => $breed->id,
        'height' => 30,
        'weight' => 'light',
        'traits' => [$second->id],
    ]);

    $this->assertSame([$second->id], $chicken->fresh()->traits->pluck('id')->all());
});

it('clears every trait when none are submitted', function () {
    $breed = Breed::factory()->create();
    $user = User::factory()->regular()->create();
    $chicken = Chicken::factory()->create(['user_id' => $user->id, 'breed_id' => $breed->id]);
    $chicken->traits()->attach([ChickenTrait::factory()->create()->id]);

    $this->actingAs($user)->put(route('chickens.update', $chicken), [
        'name' => $chicken->name,
        'gender' => $chicken->gender,
        'birth_date' => $chicken->birth_date,
        'breed_id' => $breed->id,
        'height' => 30,
        'weight' => 'light',
    ]);

    $this->assertCount(0, $chicken->fresh()->traits);
});

it('rejects a trait id that does not exist', function () {
    $breed = Breed::factory()->create();
    $user = User::factory()->regular()->create();

    $this->actingAs($user)->post(route('chickens.store'), [
        'name' => 'Ghost',
        'gender' => 'male',
        'birth_date' => '2024-01-05',
        'breed_id' => $breed->id,
        'height' => 30,
        'weight' => 'light',
        'traits' => [99999],
    ])->assertSessionHasErrors('traits.0');
});

it('forbids a non-owner from attaching traits to a chicken they do not own', function () {
    $chicken = Chicken::factory()->create(['user_id' => User::factory()->regular()->create()->id]);
    $breed = Breed::factory()->create();

    $this->actingAs(User::factory()->regular()->create())
        ->put(route('chickens.update', $chicken), [
            'name' => $chicken->name,
            'gender' => $chicken->gender,
            'birth_date' => $chicken->birth_date,
            'breed_id' => $breed->id,
            'height' => 30,
            'weight' => 'light',
            'traits' => [ChickenTrait::factory()->create()->id],
        ])
        ->assertForbidden();

    $this->assertCount(0, $chicken->fresh()->traits);
});

it('offers only the signed-in users chickens when filtering by trait', function () {
    $user = User::factory()->regular()->create();
    $trait = ChickenTrait::factory()->create(['name' => 'Curious']);

    Chicken::factory()->create([
        'user_id' => $user->id,
        'name' => 'Mine With Trait',
    ])->traits()->attach([$trait->id]);

    Chicken::factory()->create([
        'user_id' => User::factory()->regular()->create()->id,
        'name' => 'Theirs With Trait',
    ])->traits()->attach([$trait->id]);

    $response = $this->actingAs($user)->get(route('chickens.index', ['trait' => $trait->id]));

    $response->assertOk()
        ->assertSee('Mine With Trait')
        ->assertDontSee('Theirs With Trait');
});

it('shows every chickens trait to an admin when filtering', function () {
    $admin = User::factory()->admin()->create();
    $trait = ChickenTrait::factory()->create(['name' => 'Loud']);

    Chicken::factory()->create(['name' => 'Admin Side Chicken'])
        ->traits()->attach([$trait->id]);

    $this->actingAs($admin)
        ->get(route('chickens.index', ['trait' => $trait->id]))
        ->assertOk()
        ->assertSee('Admin Side Chicken');
});

it('shows an unfiltered list when the trait id is unknown', function () {
    $user = User::factory()->regular()->create();
    Chicken::factory()->create(['user_id' => $user->id, 'name' => 'Unfiltered']);

    $this->actingAs($user)
        ->get(route('chickens.index', ['trait' => 99999]))
        ->assertOk()
        ->assertSee('Unfiltered');
});

it('links a traits pill on the chicken page to the filtered list', function () {
    $chicken = Chicken::factory()->create();
    $trait = ChickenTrait::factory()->create(['name' => 'Shy']);
    $chicken->traits()->attach([$trait->id]);

    $this->actingAs($chicken->user)
        ->get(route('chickens.show', $chicken))
        ->assertOk()
        ->assertSee('Shy')
        ->assertSee(route('chickens.index', ['trait' => $trait->id]), false);
});

it('offers the trait picker on both chicken forms', function () {
    $user = User::factory()->regular()->create();
    ChickenTrait::factory()->create(['name' => 'Bold']);

    $this->actingAs($user)->get(route('chickens.create'))->assertOk()->assertSee('traits[]', false);

    $chicken = Chicken::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)->get(route('chickens.edit', $chicken))->assertOk()->assertSee('traits[]', false);
});
