<?php
namespace GenTux\Healthz\Bundles\Laravel;

use GenTux\Healthz\HealthCheck;
use GenTux\Healthz\Exceptions\HealthWarningException;

/**
 * This will check if Laravel is running in debug mode.
 *
 * If it is, we will set the health check to fail with a warning
 *
 * @package GenTux\Healthz
 */
class DebugHealthCheck extends HealthCheck
{

    /** @var string */
    protected $title = 'Debug';

    /** @var string */
    protected $description = 'Check if Laravel is running in debug mode.';

    /**
     * Check if the app is in debug mode
     *
     * @return mixed
     *
     * @throws HealthWarningException
     */
    public function run()
    {
        $debug = getenv('APP_DEBUG') == 'true';

        if ($debug) {
            throw new HealthWarningException('on');
        }

        $this->setStatus('off');
    }
}
