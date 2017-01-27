<?php
namespace GenTux\Healthz\Support;

use Illuminate\Support\ServiceProvider;

class HealthzServiceProvider extends ServiceProvider
{

    public function register()
    {
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        if (! $this->app->routesAreCached()) {
            require __DIR__.'/laravelRoutes.php';
        }

        if ($this->app->runningInConsole()) {
            $this->commands([HealthzArtisanCommand::class]);
        }
    }
}
