<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows a user to register with valid data', function () {
    // Define valid registration data
    $data = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123', // Assuming you have password confirmation in your request
    ];

    // Attempt to register
    $response = $this->postJson('api/v1/auth/register', $data);

    // Assert the response structure and status
    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
            ],
            'message',
        ])
        ->assertJson([
            'message' => 'You have successfully registered.',
        ]);

    // Assert that the user was created in the database
    $this->assertDatabaseHas('users', [
        'email' => 'john@example.com',
    ]);
});

it('does not allow a user to register with invalid data', function () {
    // Define invalid registration data (e.g., missing required fields)
    $data = [
        'name' => '', // Invalid name
        'email' => 'not-an-email', // Invalid email format
        'password' => 'short', // Invalid password (assuming minimum length is greater than 4)
        'password_confirmation' => 'notmatching', // Password confirmation does not match
    ];

    // Attempt to register
    $response = $this->postJson('api/v1/auth/register', $data);

    // Assert the response structure and status
    $response->assertStatus(422) // Unprocessable Entity
    ->assertJsonStructure([
        'message',
        'data' => [
            'name',
            'email',
            'password',
        ],
    ]);
});
