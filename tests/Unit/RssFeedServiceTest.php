<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\RssFeedService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RssFeedServiceTest extends TestCase
{
    protected RssFeedService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new RssFeedService();
    }

    public function testGetArticlesReturnsValidRss()
    {
        // Fake Guardian API response
        Http::fake([
            'https://content.guardianapis.com/*' => Http::response([
                'response' => [
                    'results' => [
                        [
                            'webTitle' => 'Test Article',
                            'webUrl' => 'https://www.example.com/test-article',
                            'webPublicationDate' => '2024-03-13T12:00:00Z',
                        ]
                    ]
                ]
            ], 200)
        ]);

        $rss = $this->service->getArticles('politics');

        $this->assertStringContainsString('<title>The Guardian - Politics</title>', $rss);
        $this->assertStringContainsString('<title>Test Article</title>', $rss);
        $this->assertStringContainsString('<link>https://www.example.com/test-article</link>', $rss);
    }

    public function testGetArticlesHandlesApiFailure()
    {
        // Fake Guardian API response with 500 error
        Http::fake([
            'https://content.guardianapis.com/*' => Http::response([], 500)
        ]);

        // Expect the HttpException to be thrown and check the exception message
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $this->expectExceptionMessage('Failed to fetch data from The Guardian.');

        $this->service->getArticles('politics');
    }
}
