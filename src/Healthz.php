<?php
namespace GenTux\Healthz;

/**
 * Collection of health checks to run.
 *
 * @package GenTux\Healthz
 */
class Healthz
{

    /** @var HealthCheck[] */
    protected $healthChecks = [];

    /**
     * @param HealthCheck[] $healthChecks
     */
    public function __construct($healthChecks = [])
    {
        $this->healthChecks = $healthChecks;
    }

    /**
     * Get all health checks in the stack
     *
     * @return HealthCheck[]
     */
    public function all()
    {
        return $this->healthChecks;
    }

    public function push(HealthCheck $healthCheck)
    {
        $this->healthChecks[] = $healthCheck;

        return $this;
    }
}
