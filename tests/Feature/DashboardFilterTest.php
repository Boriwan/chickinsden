<?php

use App\Models\Breed;
use App\Models\Chicken;
use App\Models\ChickenTrait;
use App\Models\User;

it('narrows the chicken list to one gender', function () {
    $user = User::factory()->regular()->create();
    Chicken::factory()->create(['user_id' => $user->id, 'gender' => 'male', 'name' => 'A Male']);
    Chicken::factory()->create(['user_id' => $user->id, 'gender' => 'female', 'name' => 'A Female']);

    $this->actingAs($user)
        ->get(route('chickens.index', ['gender' => 'male']))
        ->assertOk()
        ->assertSee('A Male')
        ->assertDontSee('A Female');
});

it('narrows the chicken list to one breed', function () {
    $user = User::factory()->regular()->create();
    $breed = Breed::factory()->create();
    $other = Breed::factory()->create();

    Chicken::factory()->create(['user_id' => $user->id, 'breed_id' => $breed->id, 'name' => 'Of Breed']);
    Chicken::factory()->create(['user_id' => $user->id, 'breed_id' => $other->id, 'name' => 'Of Other']);

    $this->actingAs($user)
        ->get(route('chickens.index', ['breed' => $breed->id]))
        ->assertOk()
        ->assertSee('Of Breed')
        ->assertDontSee('Of Other');
});

it('combines gender and breed filters', function () {
    $user = User::factory()->regular()->create();
    $breed = Breed::factory()->create();

    $otherBreed = Breed::factory()->create();

    Chicken::factory()->create([
        'user_id' => $user->id, 'breed_id' => $breed->id, 'gender' => 'female', 'name' => 'Both Match',
    ]);
    Chicken::factory()->create([
        'user_id' => $user->id, 'breed_id' => $breed->id, 'gender' => 'male', 'name' => 'Wrong Gender',
    ]);
    Chicken::factory()->create([
        'user_id' => $user->id, 'breed_id' => $otherBreed->id, 'gender' => 'female', 'name' => 'Wrong Breed',
    ]);

    $this->actingAs($user)
        ->get(route('chickens.index', ['gender' => 'female', 'breed' => $breed->id]))
        ->assertOk()
        ->assertSee('Both Match')
        ->assertDontSee('Wrong Gender')
        ->assertDontSee('Wrong Breed');
});

it('combines a trait filter with a gender filter', function () {
    $user = User::factory()->regular()->create();
    $trait = ChickenTrait::factory()->create();

    $withTrait = Chicken::factory()->create(['user_id' => $user->id, 'gender' => 'female', 'name' => 'Trait Female']);
    $withTrait->traits()->attach([$trait->id]);

    $male = Chicken::factory()->create(['user_id' => $user->id, 'gender' => 'male', 'name' => 'Trait Male']);
    $male->traits()->attach([$trait->id]);

    $this->actingAs($user)
        ->get(route('chickens.index', ['trait' => $trait->id, 'gender' => 'female']))
        ->assertOk()
        ->assertSee('Trait Female')
        ->assertDontSee('Trait Male');
});

it('ignores a gender value that is not a known one', function () {
    $user = User::factory()->regular()->create();
    Chicken::factory()->create(['user_id' => $user->id, 'name' => 'Unfiltered']);

    $this->actingAs($user)
        ->get(route('chickens.index', ['gender' => 'sideways']))
        ->assertOk()
        ->assertSee('Unfiltered');
});

it('keeps a gender filter inside the signed-in users own chickens', function () {
    $user = User::factory()->regular()->create();
    Chicken::factory()->create([
        'user_id' => User::factory()->regular()->create()->id,
        'gender' => 'male',
        'name' => 'Somebody Elses Male',
    ]);

    $this->actingAs($user)
        ->get(route('chickens.index', ['gender' => 'male']))
        ->assertOk()
        ->assertDontSee('Somebody Elves Male');
});

it('shows a chip per active filter and a clear all link', function () {
    $user = User::factory()->regular()->create();
    $breed = Breed::factory()->create();
    Chicken::factory()->create(['user_id' => $user->id, 'breed_id' => $breed->id, 'gender' => 'female']);

    $response = $this->actingAs($user)
        ->get(route('chickens.index', ['gender' => 'female', 'breed' => $breed->id]));

    $response->assertOk()
        ->assertSee('Filtered chickens')
        ->assertSee('Female')
        ->assertSee($breed->name)
        ->assertSee('Clear all');
});

it('links each dashboard stat card into the filtered list', function () {
    $user = User::factory()->regular()->create();
    $breed = Breed::factory()->create();
    Chicken::factory()->create(['user_id' => $user->id, 'breed_id' => $breed->id]);

    $html = $this->actingAs($user)->get(route('home'))->assertOk()->getContent();

    expect($html)->toContain(route('chickens.index', ['gender' => 'male']));
    expect($html)->toContain(route('chickens.index', ['gender' => 'female']));
    expect($html)->toContain(route('breeds.index', ['used' => 1]));
});

it('narrows the breed list to the breeds the user keeps', function () {
    $user = User::factory()->regular()->create();
    $kept = Breed::factory()->create(['name' => 'Kept Breed']);
    Breed::factory()->create(['name' => 'Unused Breed']);

    Chicken::factory()->create(['user_id' => $user->id, 'breed_id' => $kept->id]);

    $this->actingAs($user)
        ->get(route('breeds.index', ['used' => 1]))
        ->assertOk()
        ->assertSee('Kept Breed')
        ->assertDontSee('Unused Breed');
});

it('shows every breed in the wiki when no filter is asked for', function () {
    Breed::factory()->create(['name' => 'Unused Breed']);

    $this->get(route('breeds.index'))
        ->assertOk()
        ->assertSee('Unused Breed')
        ->assertSee('Breeds Wiki');
});

it('links the breed chart legend rows to the filtered chicken list', function () {
    $user = User::factory()->regular()->create();
    $breed = Breed::factory()->create(['name' => 'Charted Breed']);
    Chicken::factory()->create(['user_id' => $user->id, 'breed_id' => $breed->id]);

    $this->actingAs($user)
        ->get(route('home'))
        ->assertOk()
        ->assertSee(route('chickens.index', ['breed' => $breed->id]), false);
});

it('shows a trait chart on the dashboard', function () {
    $user = User::factory()->regular()->create();
    $trait = ChickenTrait::factory()->create(['name' => 'Charted Trait']);

    Chicken::factory()->create(['user_id' => $user->id])->traits()->attach([$trait->id]);

    $this->actingAs($user)
        ->get(route('home'))
        ->assertOk()
        ->assertSee('Charted Trait')
        ->assertSee(route('chickens.index', ['trait' => $trait->id]), false);
});

it('counts trait assignments rather than distinct chickens', function () {
    $user = User::factory()->regular()->create();
    $first = ChickenTrait::factory()->create(['name' => 'Common Trait']);
    $second = ChickenTrait::factory()->create(['name' => 'Rarer Trait']);

    // One chicken carries both, one carries only the common one. That is three
    // assignments across two chickens, and the chart must report three.
    $first_chicken = Chicken::factory()->create(['user_id' => $user->id]);
    $first_chicken->traits()->attach([$first->id, $second->id]);

    $second_chicken = Chicken::factory()->create(['user_id' => $user->id]);
    $second_chicken->traits()->attach([$first->id]);

    $html = $this->actingAs($user)->get(route('home'))->assertOk()->getContent();

    expect($html)->toContain('3 trait tags across your chickens');
});

it('counts only the users own chickens on the stat cards', function () {
    $user = User::factory()->regular()->create();

    Chicken::factory()->count(3)->create(['user_id' => $user->id]);
    Chicken::factory()->count(5)->create(['user_id' => User::factory()->regular()->create()->id]);

    $html = $this->actingAs($user)->get(route('home'))->assertOk()->getContent();

    // Three of the user's own chickens, not the eight in the app.
    expect($html)->toMatch('/bg-brand-100 text-brand-800">.*?text-3xl font-bold text-stone-900">3</s');
});

it('counts every chicken in the app for an admin', function () {
    $admin = User::factory()->admin()->create();

    Chicken::factory()->count(3)->create(['user_id' => $admin->id]);
    Chicken::factory()->count(5)->create(['user_id' => User::factory()->regular()->create()->id]);

    $html = $this->actingAs($admin)->get(route('home'))->assertOk()->getContent();

    expect($html)->toMatch('/bg-brand-100 text-brand-800">.*?text-3xl font-bold text-stone-900">8</s');
});

it('keeps the admin breed card and the page it links to in agreement', function () {
    $admin = User::factory()->admin()->create();
    $other = User::factory()->regular()->create();

    // The admin owns one breed, another user owns a different one. If the
    // card counts app-wide but the list counts only the admin's own, the two
    // disagree.
    Chicken::factory()->create(['user_id' => $admin->id, 'breed_id' => Breed::factory()->create()->id]);
    Chicken::factory()->create(['user_id' => $other->id, 'breed_id' => Breed::factory()->create()->id]);

    $card = $this->actingAs($admin)->get(route('home'))->assertOk()->getContent();

    expect($card)->toMatch('/bg-cyan-100 text-cyan-700">.*?text-3xl font-bold text-stone-900">2</s');

    $listed = $this->actingAs($admin)
        ->get(route('breeds.index', ['used' => 1]))
        ->assertOk()
        ->getContent();

    // Both breeds are listed, including the one that belongs to the other user,
    // because the card counted app-wide.
    expect(preg_match_all('/href="[^"]*\/breeds\/\d+"/', $listed))->toBe(2);
});

it('scopes the trait chart to the user while an admin sees every chicken', function () {
    $userTrait = ChickenTrait::factory()->create(['name' => 'Mine Only Trait']);
    $theirTrait = ChickenTrait::factory()->create(['name' => 'Theirs Only Trait']);

    $mine = Chicken::factory()->create(['user_id' => User::factory()->regular()->create()->id]);
    $mine->traits()->attach([$userTrait->id]);

    $theirs = Chicken::factory()->create(['user_id' => User::factory()->regular()->create()->id]);
    $theirs->traits()->attach([$theirTrait->id]);

    $this->actingAs(User::factory()->regular()->create())
        ->get(route('home'))
        ->assertOk()
        ->assertDontSee('Mine Only Trait')
        ->assertDontSee('Theirs Only Trait');

    $this->actingAs(User::factory()->admin()->create())
        ->get(route('home'))
        ->assertOk()
        ->assertSee('Mine Only Trait')
        ->assertSee('Theirs Only Trait');
});
