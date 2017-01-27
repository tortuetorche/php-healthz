<?php
namespace Gentux\Healthz\Checks\General;

use Gentux\Healthz\HealthCheck;
use Gentux\Healthz\Exceptions\HealthWarningException;

/**
 * This will check if the app is running in debug mode.
 *
 * @package Gentux\Healthz
 */
class DebugHealthCheck extends HealthCheck
{

    /** @var string */
    protected $title = 'Debug';

    /** @var string */
    protected $description = 'Check if Laravel is running in debug mode.';

    /** @var string environment variable to look for */
    protected $env;

    public function __construct($env = 'APP_DEBUG')
    {
        $this->env = $env;
    }

    /**
     * Check if the app is in debug mode
     *
     * @return mixed
     *
     * @throws HealthWarningException
     */
    public function run()
    {
        $debug = getenv($this->env) == 'true';

        if ($debug) {
            throw new HealthWarningException('on');
        }

        $this->setStatus('off');
    }
}
