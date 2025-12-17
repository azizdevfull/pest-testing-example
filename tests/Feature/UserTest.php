<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;


uses(RefreshDatabase::class);

test('user can register successfully', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertCreated();
    $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
});

test('registration fails if password confirmation does not match', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password',
        'password_confirmation' => 'wrongpassword',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('password');
});

test('registration fails if email already exists', function () {
    User::factory()->create(['email' => 'john@example.com']);
    $response = $this->postJson('/api/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('email');
});
