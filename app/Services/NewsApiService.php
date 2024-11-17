<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use function dd;

class NewsApiService extends BaseNewsServices
{
    protected string $url;
    protected string $apiKey;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->url = config('services.news_api.base_url');
        $this->apiKey = config('services.news_api.api_key');
    }

    /**
     * @throws ConnectionException
     * @throws Exception
     */
    protected function sendRequest(string $endpoint): array
    {
        $response = Http::withHeaders(['x-api-key' => $this->apiKey])
            ->baseUrl($this->url)->get($endpoint);

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
        $response = $this->sendRequest('top-headlines?category=business');
        $this->storeArticles($response['articles'], 'business');
    }
}
