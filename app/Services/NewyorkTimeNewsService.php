<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Category;
use App\NewsSourceInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class NewyorkTimeNewsService implements NewsSourceInterface
{
    protected string $url;
    protected string $apiKey;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->url = config('services.new_york_times_api.base_url');
        $this->apiKey = config('services.new_york_times_api.api_key');
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
        $response = $this->sendRequest('articlesearch.json');
        $this->storeArticles($response['response']['docs']);
    }

    protected function storeArticles(array $articles): void
    {

        $data = [];
        foreach ($articles as $article) {
            if (!empty($article['lead_paragraph'])) {
                $data[] = [
                    'title' => $article['headline']['main'],
                    'source' => $article['source'],
                    'published_at' => Carbon::parse($article['pub_date'])->format('Y-m-d H:i:s'),
                    'content' => strip_tags($article['lead_paragraph']),
                    'author' => $article['byline']['original'] ?? 'Unknown',
                    'category' => $article['section_name'],
                ];
            }
        }

        // Use a transaction to ensure data integrity and improve performance
        DB::transaction(function () use ($data) {
            Article::query()->upsert($data, ['title', 'source', 'published_at'], ['content', 'author']);
        });
    }
}
