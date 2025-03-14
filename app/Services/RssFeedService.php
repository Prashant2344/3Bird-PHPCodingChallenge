<?php

namespace App\Services;

use App\Interfaces\RssFeedServiceInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RssFeedService implements RssFeedServiceInterface
{
    public $client;
    protected string $apiKey;
    protected string $apiUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('GUARDIAN_API_KEY');
        $this->apiUrl = env('GUARDIAN_API_URL');
    }

    public function getArticles(string $section): string
    {
        $cacheKey = 'rss_feed_' . $section;
        $cacheTime = now()->addMinutes(10);

        // Check if the cache already exists
        $rssFeed = Cache::get($cacheKey);

        if ($rssFeed) {
            Log::info('Fetching RSS feed from Cache for section: ' . $section);
            return response($rssFeed, 200, ['Content-Type' => 'application/rss+xml']);
        }

        try {
            // If not in cache, fetch the data from API
            Log::info('Fetching RSS feed from API for section: ' . $section);

            $response = Http::get("{$this->apiUrl}{$section}", [
                'api-key' => $this->apiKey,
                'headers' => ['Accept' => 'application/json'],
            ]);

            if ($response->failed()) {
                Log::error("Guardian API error", ['section' => $section, 'response' => $response->body()]);
                throw new \Symfony\Component\HttpKernel\Exception\HttpException(500, 'Failed to fetch data from The Guardian.');
            }

            $articles = $response->json()['response']['results'] ?? [];

            $rssFeed = $this->generateRssFeed($section, $articles);

            // Cache the result if fetching from API is successful
            Cache::put($cacheKey, $rssFeed, $cacheTime);
        } catch (\Exception $e) {
            // Log the error once before throwing the exception again
            Log::error('Error: ' . $e->getMessage());

            // Rethrow the exception so the test can catch it
            throw $e;
        }
        
        return response($rssFeed, 200, ['Content-Type' => 'application/rss+xml']);
    }

    private function generateRssFeed(string $section, array $articles): string
    {
        $rssFeed = new \SimpleXMLElement('<rss version="2.0"><channel></channel></rss>');
        $channel = $rssFeed->channel;
        $channel->addChild('title', "The Guardian - " . ucfirst($section));
        $channel->addChild('link', "https://www.theguardian.com/{$section}");
        $channel->addChild('description', "Latest articles from The Guardian {$section}");

        foreach ($articles as $article) {
            $item = $channel->addChild('item');
            $item->addChild('title', $article['webTitle']);
            $item->addChild('link', $article['webUrl']);
            $item->addChild('pubDate', date(DATE_RSS, strtotime($article['webPublicationDate'])));
        }

        return $rssFeed->asXML();
    }
}
