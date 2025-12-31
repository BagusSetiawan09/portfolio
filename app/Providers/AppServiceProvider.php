<?php

namespace App\Providers;

use App\Models\Profile;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Models\Order;
use App\Models\Project;
use App\Observers\OrderObserver;
use App\Observers\ProjectObserver;


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
        Schema::defaultStringLength(191);

        Order::observe(OrderObserver::class);
        Project::observe(ProjectObserver::class);

        try {
            View::share('profile', Profile::first());
        } catch (\Exception $e) {
        }
    }
}
