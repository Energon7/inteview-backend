<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
it('can create a new user', function () {
    $user = User::factory()->create();

    expect($user->id)->toBeInt();
    expect($user->name)->not->toBeEmpty();
    expect($user->email)->not->toBeEmpty();
    expect($user->phone)->not->toBeEmpty();
    expect($user->address)->not->toBeEmpty();
});

it('can retrieve a user', function () {
    $user = User::factory()->create();
    $retrievedUser = User::find($user->id);

    expect($retrievedUser->id)->toBe($user->id);
    expect($retrievedUser->name)->toBe($user->name);
    expect($retrievedUser->email)->toBe($user->email);
    expect($retrievedUser->phone)->toBe($user->phone);
    expect($retrievedUser->address)->toBe($user->address);
});

it('can update a user', function () {
    $user = User::factory()->create();

    $user->update([
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
        'phone' => '1234567890',
        'address' => 'Updated Address',
    ]);

    $user->refresh();

    expect($user->name)->toBe('Updated Name');
    expect($user->email)->toBe('updated@example.com');
    expect($user->phone)->toBe('1234567890');
    expect($user->address)->toBe('Updated Address');
});

it('can delete a user', function () {
    $user = User::factory()->create();

    $user->delete();

    expect(User::find($user->id))->toBeNull();
});
