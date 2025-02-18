<?php

namespace App\Providers;

use App\Policies\VerbEventPolicy;
use App\Policies\VerbSnapshotPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Thunk\Verbs\Models\VerbEvent;
use Thunk\Verbs\Models\VerbSnapshot;

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
        Gate::policy(VerbSnapshot::class, VerbSnapshotPolicy::class);
    }
}
