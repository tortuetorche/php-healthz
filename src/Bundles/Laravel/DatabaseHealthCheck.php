<?php
namespace GenTux\Healthz\Bundles\Laravel;

use Exception;
use GenTux\Healthz\HealthCheck;
use Illuminate\Database\DatabaseManager;
use GenTux\Healthz\Exceptions\HealthFailureException;

/**
 * This will check the connection to a database using Laravel db services.
 *
 * @package GenTux\Healthz
 */
class DatabaseHealthCheck extends HealthCheck
{

    /** @var string */
    protected $title = 'Database';

    /** @var string|null Laravel database connection name to use */
    protected $connection;

    /** @var DatabaseManager */
    protected $db;

    /**
     * @param DatabaseManager|null $db Will use Laravel's container to make an instance if null
     *
     * @throws HealthFailureException if unable to resolve database manager
     */
    public function __construct(DatabaseManager $db = null)
    {
        $this->db = $db;

        if (!$this->db) {
            try { $this->db = app('db'); } catch (Exception $e) {
                throw new HealthFailureException('Cannot create instance of Laravel database manager.');
            }
        }
    }

    /**
     * Check database connection
     *
     * @return mixed
     *
     * @throws HealthFailureException
     */
    public function run()
    {
        try {
            $name = $this->connection();
            $this->db->connection($name);
        } catch (Exception $e) {
            throw new HealthFailureException($e->getMessage());
        }

        $this->setStatus('connected');
    }

    /**
     * Get the connection name
     *
     * @return null|string
     */
    public function connection()
    {
        return $this->connection;
    }

    /**
     * Set the connection name
     *
     * @param string $connection
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;
    }

    /**
     * If no description property is defined, use the connection
     * name instead ('default' if connection is also null).
     */
    public function description()
    {
        $description = $this->description;

        if (!$description) {
            $description = $this->connection() ?: 'default';
        }

        return $description;
    }
}
