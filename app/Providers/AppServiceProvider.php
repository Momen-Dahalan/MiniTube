<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;

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
        Paginator::useBootstrap(); // لتفعيل البوتستراب 5
        Gate::define('view-dashboard', function ($user) {
        return $user->hasRole('admin')|| $user->hasRole('super-admin');
        });

        Gate::define('view-users', function ($user) {
        return $user->hasRole('super-admin');
        });
    }
}
