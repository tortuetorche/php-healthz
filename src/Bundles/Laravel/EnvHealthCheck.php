<?php
namespace GenTux\Healthz\Bundles\Laravel;

use GenTux\Healthz\HealthCheck;
use Illuminate\Contracts\Foundation\Application;

/**
 * Check the current environment Laravel is running in
 *
 * Right now, this health check always passes and is more for
 * the purpose of displaying the current environment for debug purposes.
 *
 * @package \GenTux\Healthz
 */
class EnvHealthCheck extends HealthCheck
{

    /** @var string */
    protected $title = 'Environment';

    /** @var Application */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Run the health check
     *
     * This will simply set the current environment as the description
     * of the health check.
     */
    public function run()
    {
        $env = $this->app->environment();
        $this->setDescription($env);
    }
}
