<?php

namespace App\Providers;

use App\Contracts\Repositories\DocumentRepositoryInterface;
use App\Contracts\Repositories\DocumentRevisionRepositoryInterface;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Repositories\EloquentDocumentRepository;
use App\Repositories\EloquentDocumentRevisionRepository;
use App\Repositories\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            DocumentRepositoryInterface::class,
            EloquentDocumentRepository::class
        );

        $this->app->bind(
            DocumentRevisionRepositoryInterface::class,
            EloquentDocumentRevisionRepository::class
        );

        $this->app->bind(
            UserRepositoryInterface::class,
            EloquentUserRepository::class
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
