<?php
namespace Gentux\Healthz\Support;

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
            $this->app->get('/healthz', $this->healthzHandler());
            $this->app->get('/healthz/ui', $this->healthzUIHandler());
        } else {
            \Route::get('/healthz', $this->healthzHandler());
            \Route::get('/healthz/ui', $this->healthzUIHandler());
        }

        if ($this->app->runningInConsole()) {
            $this->commands([HealthzArtisanCommand::class]);
        }
    }

    protected function healthzHandler()
    {
        return function() {
            $healthz = app(Healthz::class);
            $results = $healthz->run();
            if ($results->hasFailures()) {
                return response('fail', 500);
            }

            return response('ok', 200);
        };
    }

    protected function healthzUIHandler()
    {
        return function() {
            $username = getenv('HEALTHZ_USERNAME');
            $password = getenv('HEALTHZ_PASSWORD');
            if ($username != "") {
                if (
                    request()->getUser() !== $username ||
                    request()->getPassword() !== $password
                ) {
                    return response('Invalid credentials', 401, ['WWW-Authenticate' => 'Basic']);
                }
            }

            $healthz = app(Healthz::class);
            $results = $healthz->run();
            $html = $healthz->html($results);

            $status = 200;
            if ($results->hasFailures()) {
                $status = 500;
            }

            return response($html, $status)->header('Content-Type', 'text/html');
        };
    }
}
