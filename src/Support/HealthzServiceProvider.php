<?php
namespace GenTux\Healthz\Support;

use Gentux\Healthz\Healthz;
use Illuminate\Support\Facades\Route;
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
        $this->registerRoutes();

        if ($this->app->runningInConsole()) {
            $this->commands([HealthzArtisanCommand::class]);
        }
    }

    protected function registerRoutes()
    {
        Route::get('/healthz', function() {
            $healthz = app(Healthz::class);
            $results = $healthz->run();
            if ($results->hasFailures()) {
                return 'fail';
            }

            return 'ok';
        });

        Route::get('/healthz/ui', function() {
            $healthz = app(Healthz::class);
            $html = $healthz->html();

            return response($html)->header('Content-Type', 'text/html');
        });
    }
}
