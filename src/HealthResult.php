<?php
namespace Gentux\Healthz;

/**
 * DTO representing the result of a health check
 *
 * @package Gentux\Healthz
 */
class HealthResult
{
    const RESULT_SUCCESS = 0;
    const RESULT_WARNING = 1;
    const RESULT_FAILURE = 2;

    /** @var int Should be one of RESULT constants above */
    protected $result;

    /** @var HealthCheck */
    protected $check;

    /**
     * HealthResult constructor.
     *
     * @param             $result
     * @param HealthCheck $check
     */
    public function __construct($result, HealthCheck $check)
    {
        $this->result = $result;
        $this->check = $check;
    }

    /**
     * Determine if the result is a failure
     *
     * @return bool
     */
    public function failed(): bool
    {
        return $this->result() === self::RESULT_FAILURE;
    }

    /**
     * Determine if the result is a success
     *
     * @return bool
     */
    public function passed(): bool
    {
        return $this->result() === self::RESULT_SUCCESS;
    }

    /**
     * Determine if the result is a warning
     *
     * @return bool
     */
    public function warned(): bool
    {
        return $this->result() === self::RESULT_WARNING;
    }

    /** Getters: information about the health check */

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->check->title();
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return $this->check->description();
    }

    /**
     * @return string|null
     */
    public function status(): ?string
    {
        return $this->check->status();
    }

    /**
     * @return int
     */
    public function result(): int
    {
        return $this->result;
    }
}
