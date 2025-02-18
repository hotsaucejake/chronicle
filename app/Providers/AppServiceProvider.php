<?php

namespace App\Providers;

use App\Policies\VerbEventPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Thunk\Verbs\Models\VerbEvent;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(VerbEvent::class, VerbEventPolicy::class);
    }
}
