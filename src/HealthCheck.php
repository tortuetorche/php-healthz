<?php
namespace Gentux\Healthz;

/**
 * Base class for new health checks
 *
 * @package Gentux\Healthz
 */
abstract class HealthCheck
{

    /** @var string|null Title for the health check */
    protected $title;

    /** @var string|null Brief description of the health check */
    protected $description;

    /** @var string|null Status message for the health check */
    protected $status;

    /**
     * Run the health check.
     *
     * If no exception is thrown we will consider the health check successful.
     * If you want the health check to fail with a warning, throw an
     * instance of Gentux\Healthz\Exceptions\HealthWarningException
     *
     * @return mixed
     *
     * @throws \Gentux\Healthz\Exceptions\HealthWarningException | mixed
     */
    abstract public function run();

    /**
     * Get the title of the health check.
     *
     * If not $title is set on the class, the class name
     * will be used as a default.
     *
     * @return string
     */
    public function title()
    {
        $title = $this->title;

        if (!$title) {
            $classTitle = explode('\\', get_class($this));
            $title = array_pop($classTitle);
        }

        return $title;
    }

    /**
     * Set the title for the health check
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get description for the health check.
     *
     * @return null|string
     */
    public function description()
    {
        return $this->description;
    }

    /**
     * Set the description for the health check
     *
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the status of the health check
     *
     * NOTE: If an exception is thrown, the status message for a health
     * check will be replaced with the exceptions message.
     *
     * @return null|string
     */
    public function status()
    {
        return $this->status;
    }

    /**
     * Set the status of the health check
     *
     * @param string $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $status;
    }
}
