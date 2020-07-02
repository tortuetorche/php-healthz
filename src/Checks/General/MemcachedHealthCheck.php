<?php
namespace Gentux\Healthz\Checks\General;

use Memcached;
use Gentux\Healthz\HealthCheck;
use Gentux\Healthz\Exceptions\HealthFailureException;

/**
 * Health check for memcached connection
 *
 * @package Gentux\Healthz
 */
class MemcachedHealthCheck extends HealthCheck
{

    protected $title = 'Memcached';

    protected $servers = [];
    protected $options = [];
    protected $username = null;
    protected $password = null;

    /** @var Memcached */
    protected $memcached;

    public function __construct($memcached = null)
    {
        $this->memcached = $memcached ?: new Memcached();
    }

    /**
     * Check for connection to memcached servers
     *
     * @return void
     *
     * @throws HealthFailureException
     */
    public function run(): void
    {
        if (count($this->servers())) {
            $this->memcached->addServers($this->servers());
        }

        if (count($this->options())) {
            $this->memcached->setOptions($this->options());
        }

        if (!is_null($this->username())) {
            $this->memcached->setSaslAuthData($this->username(), $this->password());
        }

        $result = $this->memcached->set('test.connection', 'success', 1);
        if (!$result) {
            throw new HealthFailureException('Unable to set test value in memcache');
        }

        $this->setStatus('able to set test value in memcache');
    }

    /**
     * Add server to check
     *
     * @param string $server
     * @param int    $port
     * @param int    $weight
     *
     * @return self
     */
    public function addServer($server, $port = 11211, $weight = 0): HealthCheck
    {
        $this->servers[] = [$server, $port, $weight];

        return $this;
    }

    /**
     * Get servers
     *
     * @return array
     */
    public function servers(): array
    {
        return $this->servers;
    }

    /**
     * Set memcached options
     *
     * @param array $options
     *
     * @return self
     */
    public function setOptions(array $options): HealthCheck
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function options(): array
    {
        return $this->options;
    }

    /**
     * Set username and password for servers
     *
     * @param string $username
     * @param string $password
     *
     * @return self
     */
    public function setAuth($username, $password): HealthCheck
    {
        $this->username = $username;
        $this->password = $password;

        return $this;
    }

    /**
     * Get username
     *
     * @return string|null
     */
    public function username(): ?string
    {
        return $this->username;
    }

    /**
     * Get password
     *
     * @return string|null
     */
    public function password(): ?string
    {
        return $this->password;
    }
}
