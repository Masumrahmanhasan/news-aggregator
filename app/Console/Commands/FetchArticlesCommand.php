<?php

namespace App\Console\Commands;

use App\Services\GuardianNewsService;
use App\Services\NewsApiService;
use App\Services\NewyorkTimeNewsService;
use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;

class FetchArticlesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch-articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch articles from APIs';

    public function __construct(
        protected NewsApiService $newsApiService,
        protected GuardianNewsService $guardianNewsService,
        protected NewyorkTimeNewsService $newyorkTimeNewsService
    )
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @throws ConnectionException
     */
    public function handle(): void
    {
        $this->info('Fetching articles from APIs...');

        $this->newyorkTimeNewsService->fetchArticles();
        $this->guardianNewsService->fetchArticles();
        $this->newsApiService->fetchArticles();

        $this->info('Fetched articles successfully!');
    }
}
