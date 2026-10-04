<?php

use App\Models\Breed;
use App\Models\Chicken;
use App\Models\ChickenTrait;
use App\Models\User;

it('paginates the chicken list fifteen to a page', function () {
    $user = User::factory()->admin()->create();
    Chicken::factory()->count(17)->create();

    $response = $this->actingAs($user)->get(route('chickens.index'));

    $response->assertOk();

    expect($response->viewData('chickens')->total())->toBe(17)
        ->and($response->viewData('chickens')->count())->toBe(15)
        ->and($response->viewData('chickens')->lastPage())->toBe(2);
});

it('shows a page link when there is more than one page', function () {
    $admin = User::factory()->admin()->create();
    Chicken::factory()->count(16)->create();

    $this->actingAs($admin)->get(route('chickens.index'))->assertSee('Pagination Navigation');
});

it('shows no page link when everything fits on one page', function () {
    $admin = User::factory()->admin()->create();
    Chicken::factory()->count(4)->create();

    $this->actingAs($admin)->get(route('chickens.index'))->assertDontSee('Pagination Navigation');
});

it('carries an active filter onto the page links', function () {
    $admin = User::factory()->admin()->create();
    $breed = Breed::factory()->create();
    $otherBreed = Breed::factory()->create();

    // Enough of one breed to need a second page, so a page link exists to carry
    // the filter. The other breed is pinned explicitly because the factory
    // otherwise picks one at random and could land back on $breed.
    Chicken::factory()->count(16)->create(['breed_id' => $breed->id]);
    Chicken::factory()->count(2)->create(['breed_id' => $otherBreed->id]);

    $response = $this->actingAs($admin)->get(route('chickens.index', ['breed' => $breed->id]));

    $response->assertOk();

    expect($response->viewData('chickens')->total())->toBe(16);

    // The page 2 link must keep ?breed= or the filter silently resets.
    $this->get(route('chickens.index', ['breed' => $breed->id, 'page' => 2]))
        ->assertOk()
        ->assertSee('Filtered chickens');
});

it('still scopes pagination to the signed-in user', function () {
    $user = User::factory()->regular()->create();
    $stranger = User::factory()->regular()->create();

    Chicken::factory()->count(14)->create(['user_id' => $user->id]);
    Chicken::factory()->count(10)->create(['user_id' => $stranger->id]);

    $response = $this->actingAs($user)->get(route('chickens.index'));

    expect($response->viewData('chickens')->total())->toBe(14);
});

it('lets an admin pick an icon when creating a trait', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.traits.store'), ['name' => 'Feisty', 'icon' => 'flame'])
        ->assertSessionHasNoErrors();

    expect(ChickenTrait::where('name', 'Feisty')->firstOrFail()->icon)->toBe('flame');
});

it('accepts a trait with no icon at all', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->post(route('admin.traits.store'), ['name' => 'Undecided'])
        ->assertSessionHasNoErrors();

    $trait = ChickenTrait::where('name', 'Undecided')->firstOrFail();

    expect($trait->icon)->toBeNull()
        ->and($trait->iconOrDefault())->toBe('tag');
});

it('rejects an icon the map has no glyph for', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->post(route('admin.traits.store'), ['name' => 'Sneaky', 'icon' => 'not-an-icon'])
        ->assertSessionHasErrors('icon');

    expect(ChickenTrait::where('name', 'Sneaky')->count())->toBe(0);
});

it('rejects an svg payload smuggled in as an icon', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->post(route('admin.traits.store'), [
            'name' => 'Sneaky',
            'icon' => '<svg onload=alert(1)>',
        ])
        ->assertSessionHasErrors('icon');
});

it('changes a traits icon on edit', function () {
    $trait = ChickenTrait::factory()->create(['icon' => 'zap']);

    $this->actingAs(User::factory()->admin()->create())
        ->put(route('admin.traits.update', $trait), ['name' => $trait->name, 'icon' => 'crown'])
        ->assertSessionHasNoErrors();

    expect($trait->fresh()->icon)->toBe('crown');
});

it('keeps the icon when a trait is renamed without one', function () {
    $trait = ChickenTrait::factory()->create(['name' => 'Chatty', 'icon' => 'volume']);

    $this->actingAs(User::factory()->admin()->create())
        ->put(route('admin.traits.update', $trait), ['name' => 'Very chatty'])
        ->assertSessionHasNoErrors();

    // An omitted icon must not blank the one already stored.
    expect($trait->fresh()->icon)->toBe('volume');
});

it('every offered icon actually has a glyph in the icon component', function () {
    // The icon keys are stored in the database, so an entry with no matching
    // path in the <x-icon> map would render an invisible glyph rather than fail.
    foreach (ChickenTrait::icons() as $icon) {
        $svg = view('components.icon', ['name' => $icon, 'class' => 'h-4 w-4'])->render();

        $hasGlyph = str_contains($svg, '<path') || str_contains($svg, '<circle');

        expect($hasGlyph)->toBeTrue("icon [{$icon}] rendered no path or circle");
    }
});

it('shows the icon on the chicken page', function () {
    $chicken = Chicken::factory()->create();
    $trait = ChickenTrait::factory()->create(['name' => 'Feisty', 'icon' => 'flame']);
    $chicken->traits()->attach([$trait->id]);

    $this->actingAs($chicken->user)
        ->get(route('chickens.show', $chicken))
        ->assertOk()
        ->assertSee('Feisty');
});

it('falls back to a generic icon for a trait that has none', function () {
    $chicken = Chicken::factory()->create();
    $trait = ChickenTrait::factory()->create(['name' => 'Undecided', 'icon' => null]);
    $chicken->traits()->attach([$trait->id]);

    expect($trait->iconOrDefault())->toBe('tag');

    $this->actingAs($chicken->user)
        ->get(route('chickens.show', $chicken))
        ->assertOk()
        ->assertSee('Undecided');
});

it('offers every trait icon in the admin picker', function () {
    $html = $this->actingAs(User::factory()->admin()->create())
        ->get(route('admin.traits.index'))
        ->assertOk()
        ->getContent();

    foreach (ChickenTrait::icons() as $icon) {
        expect($html)->toContain('value="'.$icon.'"');
    }
});

it('stores height as a number rather than text', function () {
    $chicken = Chicken::factory()->create(['height' => 33.25]);

    expect($chicken->fresh()->height)->toBe(33.25)
        ->and(is_numeric($chicken->fresh()->height))->toBeTrue();
});
