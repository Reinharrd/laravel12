<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

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
        $this->mapModuleRoutes();
    }

    protected function mapModuleRoutes(): void
    {
        $routePath = base_path('Modules/Api/Config/routes.php');

        if (file_exists($routePath)) {
            Route::prefix('api') // API prefix
                ->middleware('api')
                ->group($routePath);
        }
    }
}
