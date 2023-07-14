<?php

namespace Devtvn\Social;

use Devtvn\Social\Http\Middleware\CoreAuthMiddleware;
use Devtvn\Social\Http\Middleware\CoreAuthShopifyMiddleware;
use Devtvn\Social\Http\Middleware\GlobalJwtMiddleware;
use Devtvn\Social\Http\Middleware\RefreshMiddleware;
use Devtvn\Social\Http\Middleware\SocialAuthMiddleware;
use Devtvn\Social\Http\Middleware\VerifyMiddleware;
use Devtvn\Social\Service\AppCoreService;
use Devtvn\Social\Service\Contracts\CoreContract;
use Devtvn\Social\Service\Contracts\PubSubContract;
use Devtvn\Social\Service\PubSub\PubSubService;
use Illuminate\Support\ServiceProvider;


class CoreServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {

        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/debug.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../views', 'core-social');
        app()->make('router')->aliasMiddleware('social.auth', SocialAuthMiddleware::class);
        app()->make('router')->aliasMiddleware('auth.verify', VerifyMiddleware::class);
        app()->make('router')->aliasMiddleware('core', CoreAuthMiddleware::class);
        app()->make('router')->aliasMiddleware('refresh', RefreshMiddleware::class);
        app()->make('router')->aliasMiddleware('global', GlobalJwtMiddleware::class);
        app()->make('router')->aliasMiddleware('core.shopify', CoreAuthShopifyMiddleware::class);
        $this->publishes([
            __DIR__ . '/../config/social.php' => config_path('social.php'),
            __DIR__ . '/../lang' => base_path('resources/lang'),
            __DIR__ . '/../views' => resource_path('views'),
        ], 'core-social');
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
    }

    /**
     * @return void
     */
    public function register()
    {
        foreach (glob(__DIR__ . '/../helpers/*.php') as $file) {
            require_once($file);
        }
        $this->app->singleton(
            CoreContract::class,
            AppCoreService::class);
        $this->app->singleton(
            PubSubContract::class,
            PubSubService::class);

    }
}
