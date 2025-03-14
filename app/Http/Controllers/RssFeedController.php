<?php

namespace App\Http\Controllers;

use App\Interfaces\RssFeedServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class RssFeedController extends Controller
{
    protected RssFeedServiceInterface $rssFeedService;

    public function __construct(RssFeedServiceInterface $rssFeedService)
    {
        $this->rssFeedService = $rssFeedService;
    }

    public function getFeed(string $section): Response
    {
        return response($this->rssFeedService->getArticles($section), 200)
            ->header('Content-Type', 'application/rss+xml');
    }
}
