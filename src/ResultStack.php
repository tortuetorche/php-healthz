<?php
namespace Gentux\Healthz;

use Gentux\Healthz\Support\Stack;

/**
 * Manages a set of results from running a stack of health checks
 *
 * @package Gentux\Healthz
 */
class ResultStack
{

    use Stack {
        Stack::push as stackPush;
    }

    /**
     * ResultStack constructor.
     *
     * @param array $results
     */
    public function __construct(array $results=[])
    {
        $this->items = $results;
    }

    /**
     * @param HealthResult $result
     *
     * @return Stack
     */
    public function push(HealthResult $result): ResultStack
    {
        return $this->stackPush($result);
    }

    /**
     * Determine if any results in the stack have failed
     *
     * @return bool
     */
    public function hasFailures(): bool
    {
        $hasFailure = false;
        foreach ($this->all() as $result) {
            if ($result->failed()) {
                $hasFailure = true;
                break;
            }
        }

        return $hasFailure;
    }

    /**
     * Determine if any results in the stack have warnings
     *
     * @return bool
     */
    public function hasWarnings(): bool
    {
        $hasWarning = false;
        foreach($this->all() as $result) {
            if ($result->warned()) {
                $hasWarning = true;
                break;
            }
        }

        return $hasWarning;
    }
}
