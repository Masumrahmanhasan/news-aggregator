<?php

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('fetches articles with filters and pagination', function () {

    Article::factory()->create([
        'title' => 'Test Article 1',
        'source' => 'The Guardian',
        'published_at' => '2024-11-01',
        'category' => 'Business',
    ]);
    // Simulate query parameters for filtering (e.g., for keyword, category, date, and source)
    $params = [
        'keyword' => 'test',
        'date' => '2024-11-01',
        'category' => 'Business',
        'source' => 'The Guardian',
    ];

    // Call the API with the filters
    $response = $this->getJson('api/v1/articles', $params);

    // Assert that the response status is OK
    $response->assertOk();

    // Assert that the response contains the expected data structure
    $response->assertJsonStructure([
        'code',
        'message',
        'status',
        'data' => [
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'source',
                    'author',
                    'content',
                    'published_at',
                ],
            ]
        ],
    ]);

    // Assert that the articles are paginated
    $response->assertJsonFragment(['current_page' => 1]);
});