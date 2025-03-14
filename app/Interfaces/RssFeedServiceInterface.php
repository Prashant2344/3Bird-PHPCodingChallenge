<?php

namespace App\Interfaces;

interface RssFeedServiceInterface
{
    public function getArticles(string $section): string;
}
