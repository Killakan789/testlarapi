<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(\App\Repositories\ClientRepository::class);
        $this->app->singleton(\App\Repositories\LoanRepository::class);
        $this->app->singleton(\App\Services\LoanEligibilityService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
