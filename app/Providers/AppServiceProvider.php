<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;

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
        if ($this->app->bound('router')) {
            $this->app['router']->aliasMiddleware('permission', \Spatie\Permission\Middleware\PermissionMiddleware::class);
            $this->app['router']->aliasMiddleware('role', \Spatie\Permission\Middleware\RoleMiddleware::class);
        }
        
        Route::macro('isWith', function (...$parameters) {
            foreach ($parameters as $parameter) {
                if (url()->current() == (!is_array($parameter)
                    ? route($parameter)
                    : route($parameter[0], $parameter[1] ?? []))
                ) {
                    return true;
                }
            }
            return false;
        });

        Scramble::afterOpenApiGenerated(function (OpenApi $openApi) {
            $openApi->secure(
                SecurityScheme::http('bearer')
            );
        });
    }
}
