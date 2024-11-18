<?php

use App\Console\Commands\FetchArticlesCommand;
use App\Services\GuardianNewsService;
use App\Services\NewsApiService;
use App\Services\NewyorkTimeNewsService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('fetches articles from all news services', function () {
    // Mock the services
    $newsApiService = Mockery::mock(NewsApiService::class);
    $guardianNewsService = Mockery::mock(GuardianNewsService::class);
    $newyorkTimeNewsService = Mockery::mock(NewyorkTimeNewsService::class);

    // Expect the fetchArticles method to be called on each service
    $newsApiService->shouldReceive('fetchArticles')->once();
    $guardianNewsService->shouldReceive('fetchArticles')->once();
    $newyorkTimeNewsService->shouldReceive('fetchArticles')->once();

    // Bind the mocked services to the service container
    $this->app->instance(NewsApiService::class, $newsApiService);
    $this->app->instance(GuardianNewsService::class, $guardianNewsService);
    $this->app->instance(NewyorkTimeNewsService::class, $newyorkTimeNewsService);

    // Run the command
    $this->artisan(FetchArticlesCommand::class)->expectsOutput('Fetching articles from APIs...')
        ->expectsOutput('Fetched articles successfully!')
        ->assertExitCode(0);
});
