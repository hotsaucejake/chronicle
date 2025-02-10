<?php

namespace App\Providers;

use App\Contracts\Services\DocumentRevisionServiceInterface;
use App\Contracts\Services\DocumentServiceInterface;
use App\Contracts\Services\UserServiceInterface;
use App\Services\DocumentRevisionService;
use App\Services\DocumentService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class ServiceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            DocumentRevisionServiceInterface::class,
            DocumentRevisionService::class
        );

        $this->app->bind(
            DocumentServiceInterface::class,
            DocumentService::class
        );

        $this->app->bind(
            UserServiceInterface::class,
            UserService::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
