<?php
namespace GenTux\Healthz;

use GenTux\Healthz\Support\Stack;

/**
 * Manages a set of results from running a stack of health checks
 *
 * @package GenTux\Healthz
 */
class ResultStack
{

    use Stack {
        Stack::push as stackPush;
    }

    public function __construct(array $results=[])
    {
        $this->items = $results;
    }

    public function push(HealthResult $result)
    {
        return $this->stackPush($result);
    }

    /**
     * Determine if any results in the stack have failed
     *
     * @return bool
     */
    public function hasFailures()
    {
        $hasFailure = false;
        foreach ($this->all() as $result) {
            if ($result->failed()) {
                $hasFailure = true;
            }
        }

        return $hasFailure;
    }
}
