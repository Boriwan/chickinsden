<?php

use App\Models\Chicken;
use App\Models\User;
use App\Policies\ChickenPolicy;

dataset('chicken abilities', ['view', 'update', 'delete']);

it('lets an owner act on their own chicken', function (string $ability) {
    $owner = User::factory()->regular()->create();
    $chicken = Chicken::factory()->create(['user_id' => $owner->id]);

    expect((new ChickenPolicy)->{$ability}($owner, $chicken))->toBeTrue();
})->with('chicken abilities');

it('lets an admin act on any chicken', function (string $ability) {
    $admin = User::factory()->admin()->create();
    $chicken = Chicken::factory()->create(['user_id' => User::factory()->regular()->create()->id]);

    expect((new ChickenPolicy)->{$ability}($admin, $chicken))->toBeTrue();
})->with('chicken abilities');

it('refuses a non-owner from acting on another users chicken', function (string $ability) {
    $user = User::factory()->regular()->create();
    $chicken = Chicken::factory()->create(['user_id' => User::factory()->regular()->create()->id]);

    expect((new ChickenPolicy)->{$ability}($user, $chicken))->toBeFalse();
})->with('chicken abilities');
