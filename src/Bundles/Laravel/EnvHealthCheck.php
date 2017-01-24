<?php
namespace Gentux\Healthz\Bundles\Laravel;

use Gentux\Healthz\HealthCheck;
use Illuminate\Contracts\Foundation\Application;
use Gentux\Healthz\Exceptions\HealthFailureException;

/**
 * Check the current environment Laravel is running in
 *
 * Right now, this health check always passes and is more for
 * the purpose of displaying the current environment for debug purposes.
 *
 * @package \Gentux\Healthz
 */
class EnvHealthCheck extends HealthCheck
{

    /** @var string */
    protected $title = 'Environment';

    /** @var string */
    protected $description = 'Check the environment Laravel is running in.';

    /** @var Application */
    protected $app;

    public function __construct($app = null)
    {
        $this->app = $app;

        if (!$app) {
            try { $this->app = app(); } catch (\Exception $e) {
                throw new HealthFailureException('Unable to resolve instance of application for Laravel.');
            }
        }
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
        $this->setStatus($env);
    }
}
