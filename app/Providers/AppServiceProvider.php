<?php

namespace App\Providers;

use App\Interfaces\RssFeedServiceInterface;
use App\Services\RssFeedService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(RssFeedServiceInterface::class, RssFeedService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
