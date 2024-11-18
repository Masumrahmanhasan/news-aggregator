<?php

namespace App\Services;

use App\Models\Article;
use App\NewsSourceInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class GuardianNewsService implements NewsSourceInterface
{
    protected string $url;
    protected string $apiKey;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->url = config('services.guardian_api.base_url');
        $this->apiKey = config('services.guardian_api.api_key');
    }

    /**
     * @throws ConnectionException
     * @throws Exception
     */
    protected function sendRequest(string $endpoint, $params = []): array
    {
        $params['api-key'] = $this->apiKey;
        $response = Http::baseUrl($this->url)->get($endpoint, $params);

        if (!$response->successful()) {
            throw new \Exception('Failed to fetch data from News API: ' . $response->body());
        }
        return $response->json();
    }

    /**
     * @throws ConnectionException
     */
    public function fetchArticles(): void
    {
        $response = $this->sendRequest('search', ['show-fields' => 'all']);
        $articles = $response['response']['results'] ?? [];
        $this->storeArticles($articles);
    }

    protected function storeArticles(array $articles): void
    {
        $data = [];
        foreach ($articles as $article) {
            if (!empty($article['fields']['body'])) {
                $data[] = [
                    'title' => $article['webTitle'],
                    'source' => $article['publication'],
                    'published_at' => Carbon::parse($article['webPublicationDate'])->format('Y-m-d H:i:s'),
                    'content' => strip_tags($article['fields']['body']),
                    'author' => $article['fields']['byline'] ?? 'Unknown',
                    'category' => $article['sectionName'] ?? 'Uncategorized',
                ];
            }
        }

        // Use a transaction to ensure data integrity and improve performance
        DB::transaction(function () use ($data) {
            Article::query()
                ->upsert($data, ['title', 'source', 'published_at'], ['content', 'author', 'category']);
        });
    }
}
