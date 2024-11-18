<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('allows a user to log in with valid credentials', function () {

    $user = User::factory()->create([
        'password' => Hash::make('password123'), // Make sure to hash the password
    ]);

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    // Assert the response structure and status
    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'access_token',
                'token_type',
                'expires_in',
            ],
            'message',
        ])
        ->assertJson([
            'message' => 'You have been logged in successfully',
        ]);

    // Assert that the token is created
    $this->assertNotNull($response->json('data.access_token'));
});

it('does not allow a user to log in with invalid credentials', function () {
    // Attempt to log in with invalid credentials
    $response = $this->postJson('/api/v1/auth/login', [
        'email' => 'nonexistent@example.com',
        'password' => 'wrongpassword',
    ]);

    // Assert the response structure and status
    $response->assertStatus(401)
        ->assertJsonStructure([
            'data',
            'message',
        ])
        ->assertJson([
            'message' => __('auth.failed'),
        ]);
});
