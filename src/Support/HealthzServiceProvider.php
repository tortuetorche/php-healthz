<?php
namespace GenTux\Healthz\Support;

use Illuminate\Support\ServiceProvider;

class HealthzServiceProvider extends ServiceProvider
{

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }
}
