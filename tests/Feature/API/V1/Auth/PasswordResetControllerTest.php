<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

uses(RefreshDatabase::class);

it('sends a password reset link for a valid email', function () {
    // Create a test user
    $user = User::factory()->create();

    // Attempt to send a password reset link
    $response = $this->postJson('api/v1/auth/password/forgot', [
        'email' => $user->email,
    ]);

    // Assert the response structure and status
    $response->assertStatus(200)
        ->assertJsonStructure([
            'data',
            'message',
        ])
        ->assertJson([
            'message' => trans(Password::RESET_LINK_SENT),
        ]);
});

it('returns an error when sending a password reset link for an invalid email', function () {
    // Attempt to send a password reset link with an invalid email
    $response = $this->postJson('api/v1/auth/password/forgot', [
        'email' => 'nonexistent@example.com',
    ]);

    // Assert the response structure and status
    $response->assertStatus(422)
        ->assertJsonStructure([
            'data',
            'message',
        ])
        ->assertJson([
            'message' => 'Password reset request failed',
        ]);
});

it('resets the password with a valid token', function () {
    // Create a test user
    $user = User::factory()->create([
        'password' => bcrypt('oldpassword'),
    ]);

    // Create a password reset token
    $token = Password::createToken($user);

    // Attempt to reset the password
    $response = $this->postJson('api/v1/auth/password/reset', [
        'email' => $user->email,
        'password' => 'newpassword',
        'password_confirmation' => 'newpassword',
        'token' => $token,
    ]);

    // Assert the response structure and status
    $response->assertStatus(200)
        ->assertJsonStructure([
            'data',
            'message',
        ])
        ->assertJson([
            'message' => trans(Password::PASSWORD_RESET),
        ]);

    // Assert that the password has been updated
    $this->assertTrue(Hash::check('newpassword', $user->fresh()->password));
});

it('returns an error when resetting the password with an invalid token', function () {
    // Create a test user
    $user = User::factory()->create();

    // Attempt to reset the password with an invalid token
    $response = $this->postJson('/api/v1/auth/password/reset', [
        'email' => $user->email,
        'password' => 'newpassword',
        'password_confirmation' => 'newpassword',
        'token' => 'invalid-token',
    ]);

    // Assert the response structure and status
    $response->assertStatus(400)
        ->assertJsonStructure([
            'data',
            'message',
        ])
        ->assertJson([
            'message' => trans(Password::INVALID_TOKEN),
        ]);
});