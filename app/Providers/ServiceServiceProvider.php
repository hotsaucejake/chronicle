<?php

namespace App\Providers;

use App\Contracts\Services\UserServiceInterface;
use App\Contracts\Services\VerbsDocumentRevisionServiceInterface;
use App\Contracts\Services\VerbsDocumentServiceInterface;
use App\Services\UserService;
use App\Services\VerbsDocumentRevisionService;
use App\Services\VerbsDocumentService;
use Illuminate\Support\ServiceProvider;

class ServiceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            VerbsDocumentRevisionServiceInterface::class,
            VerbsDocumentRevisionService::class
        );

        $this->app->bind(
            VerbsDocumentServiceInterface::class,
            VerbsDocumentService::class
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
