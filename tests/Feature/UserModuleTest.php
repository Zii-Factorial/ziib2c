<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;

test('authenticated users can view the user index', function () {
    config(['inertia.testing.ensure_pages_exist' => false]);

    $user = User::factory()->create();
    User::factory()->count(2)->create();

    $this->actingAs($user)
        ->get(route('users.index'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('users/index')
            ->has('users.data', 3)
            ->has('filters')
        );
});

test('authenticated users can create a user', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('users.store'), [
            'name' => 'New User',
            'email' => 'new-user@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])
        ->assertRedirect(route('users.index'));

    $createdUser = User::query()->where('email', 'new-user@example.com')->first();

    expect($createdUser)->not->toBeNull()
        ->and(Hash::check('password', $createdUser->password))->toBeTrue();
});

test('authenticated users can update a user', function () {
    $user = User::factory()->create();
    $managedUser = User::factory()->create();

    $this->actingAs($user)
        ->put(route('users.update', $managedUser), [
            'name' => 'Updated User',
            'email' => 'updated-user@example.com',
            'password' => null,
            'password_confirmation' => null,
        ])
        ->assertRedirect(route('users.index'));

    $managedUser->refresh();

    expect($managedUser->name)->toBe('Updated User')
        ->and($managedUser->email)->toBe('updated-user@example.com');
});

test('authenticated users can delete a user', function () {
    $user = User::factory()->create();
    $managedUser = User::factory()->create();

    $this->actingAs($user)
        ->delete(route('users.destroy', $managedUser))
        ->assertRedirect(route('users.index'));

    $this->assertModelMissing($managedUser);
});
