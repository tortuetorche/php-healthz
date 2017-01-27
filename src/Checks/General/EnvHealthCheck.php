<?php
namespace Gentux\Healthz\Checks\General;

use Gentux\Healthz\HealthCheck;
use Gentux\Healthz\Exceptions\HealthWarningException;
use Gentux\Healthz\Exceptions\HealthFailureException;

/**
 * Check the current environment the app is running in
 *
 * @package \Gentux\Healthz
 */
class EnvHealthCheck extends HealthCheck
{

    /** @var string */
    protected $title = 'Environment';

    /** @var string */
    protected $description = 'Check the environment the app is running in.';

    /** @var string environment variable to look for */
    protected $env;

    public function __construct($env = 'APP_ENV')
    {
        $this->env = $env;
    }

    /**
     * Run the health check
     */
    public function run()
    {
        $env = getenv($this->env) ?: 'UNKNOWN';
        if ($env == 'UNKNOWN') {
            throw new HealthWarningException($env);
        }

        $this->setStatus($env);
    }
}
