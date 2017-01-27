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
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->commands([HealthzArtisanCommand::class]);
        }
    }
}
