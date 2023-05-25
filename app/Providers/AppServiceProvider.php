<?php

namespace App\Providers;

use App\Facades\Core;
use App\Service\AppCoreService;
use App\Service\Contracts\UserContract;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            UserContract::class,
            AppCoreService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
