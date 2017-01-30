<?php
namespace GenTux\Healthz\Support;

use Gentux\Healthz\Healthz;
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
        if (method_exists($this->app, 'post')) {
            $this->registerLumenRoutes();
        } else {
            $this->registerLaravelRoutes();
        }

        if ($this->app->runningInConsole()) {
            $this->commands([HealthzArtisanCommand::class]);
        }
    }

    protected function registerLumenRoutes()
    {
        $this->app->get('/healthz', function() {
            $healthz = app(Healthz::class);
            $results = $healthz->run();
            if ($results->hasFailures()) {
                return 'fail';
            }

            return 'ok';
        });

        $this->app->get('/healthz/ui', function() {
            $healthz = app(Healthz::class);
            $html = $healthz->html();

            return response($html)->header('Content-Type', 'text/html');
        });
    }

    protected function registerLaravelRoutes()
    {
        \Route::get('/healthz', function() {
            $healthz = app(Healthz::class);
            $results = $healthz->run();
            if ($results->hasFailures()) {
                return 'fail';
            }

            return 'ok';
        });

        \Route::get('/healthz/ui', function() {
            $healthz = app(Healthz::class);
            $html = $healthz->html();

            return response($html)->header('Content-Type', 'text/html');
        });
    }
}
