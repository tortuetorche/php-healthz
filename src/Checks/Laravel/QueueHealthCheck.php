<?php
namespace Gentux\Healthz\Checks\Laravel;

use Exception;
use Illuminate\Queue\SqsQueue;
use Gentux\Healthz\HealthCheck;
use Illuminate\Queue\SyncQueue;
use Illuminate\Queue\QueueManager;
use Gentux\Healthz\Exceptions\HealthWarningException;
use Gentux\Healthz\Exceptions\HealthFailureException;

/**
 * This will check the connection to a queue provider.
 *
 * @package Gentux\Healthz
 */
class QueueHealthCheck extends HealthCheck
{

    /** @var string */
    protected $title = 'Queue Service';

    /** @var string|null Laravel queue name to use */
    protected $name;

    /** @var QueueManager */
    protected $queue;

    /**
     * @param QueueManager|null $queue Will use Laravel's container to make an instance if null
     *
     * @throws HealthFailureException if unable to resolve queue manager
     */
    public function __construct(QueueManager $queue = null)
    {
        $this->queue = $queue;

        if (!$this->queue) {
            try { $this->queue = app('queue'); } catch (Exception $e) {
                throw new HealthFailureException('Cannot create instance of Laravels queue manager.');
            }
        }
    }

    /**
     * Check database connection
     *
     * @return mixed
     *
     * @throws HealthFailureException
     * @throws HealthWarningException
     */
    public function run()
    {
        $name = $this->name();
        $queue = $this->queue->connection($name);

        if ($queue instanceof SqsQueue) {
            $this->runSqsCheck($queue);
        } elseif ($queue instanceof SyncQueue) {
            $this->runSyncCheck($queue);
        } else {
            throw new HealthWarningException('Only SQS and Sync queue drivers supported at this time.');
        }
    }

    /**
     * Run the health check against an sqs queue
     *
     * @param SqsQueue $queue
     */
    protected function runSqsCheck(SqsQueue $queue)
    {
        $url = $queue->getQueue(null);
        $queue->getSqs()->getQueueAttributes(['QueueUrl' => $url]);

        $this->setStatus('connected to SQS');
    }

    /**
     * Nothing to really check with a sync queue, will
     * just set the status.
     *
     * @param SyncQueue $queue
     */
    protected function runSyncCheck(SyncQueue $queue)
    {
        $this->setStatus('connected to Sync queue');
    }

    /**
     * Get the queue name
     *
     * @return null|string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Set the queue name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * If no description property is defined, use the queue name instead.
     *
     * @return string
     */
    public function description()
    {
        $description = $this->description ?: $this->name();

        return $description;
    }
}
