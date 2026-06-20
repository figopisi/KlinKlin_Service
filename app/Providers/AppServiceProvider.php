<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// app/Providers/AppServiceProvider.php

use App\Services\CloudinaryService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CloudinaryService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
