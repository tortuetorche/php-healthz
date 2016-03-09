<?php
namespace GenTux\Healthz;

use Exception;
use GenTux\Healthz\Support\Stack;
use GenTux\Healthz\Exceptions\HealthWarningException;

/**
 * Collection of health checks to run.
 *
 * @package GenTux\Healthz
 */
class Healthz
{

    use Stack {
        Stack::push as stackPush;
    }

    /**
     * @param HealthCheck[] $healthChecks
     */
    public function __construct($healthChecks = [])
    {
        $this->items = $healthChecks;
    }

    public function push(HealthCheck $healthCheck)
    {
        return $this->stackPush($healthCheck);
    }

    /**
     * Run the health checks in the stack
     */
    public function run()
    {
        $results = [];

        foreach($this->all() as $check) {
            $resultCode = HealthResult::RESULT_SUCCESS;

            try {
                $check->run();
            } catch (Exception $e) {
                $check->setStatus($e->getMessage());
                $resultCode = $e instanceof HealthWarningException ? HealthResult::RESULT_WARNING : HealthResult::RESULT_FAILURE;
            }

            $results[] = new HealthResult($resultCode, $check);
        }

        return new ResultStack($results);
    }
}
