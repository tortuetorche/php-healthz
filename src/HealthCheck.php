<?php
namespace GenTux\Healthz;

/**
 * Base class for new health checks
 *
 * @package GenTux\Healthz
 */
abstract class HealthCheck
{

    /** @var string|null */
    protected $title;

    /** @var string|null */
    protected $description;

    /**
     * Run the health check.
     *
     * If no exception is thrown we will consider the health check successful.
     * If you want the health check to fail with a warning, throw an
     * instance of GenTux\Healthz\Exceptions\HealthWarningException
     *
     * @return mixed
     *
     * @throws \GenTux\Healthz\Exceptions\HealthWarningException | mixed
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
}
