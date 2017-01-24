<?php
namespace GenTux\Healthz\Support;

use GenTux\Healthz\HealthResult;
use GenTux\Healthz\Healthz;
use Illuminate\Console\Command;

class HealthzArtisanCommand extends Command
{

    /** @var string */
    protected $signature = "healthz";

    /** @var string */
    protected $description = "Run application health checks";

    /** @var Healthz */
    protected $checks;

    public function __construct(Healthz $checks)
    {
        parent::__construct();
        $this->checks = $checks;
    }

    /**
     * Execute the console command
     *
     * @return int
     */
    public function handle()
    {
        $results = $this->checks->run();
        foreach ($results->all() as $result) {
            $this->outputCheckResult($result);
        }

        if ($results->hasFailures()) {
            return 1;
        }

        return 0;
    }

    /**
     * Output message about health check result
     *
     * @param HealthResult $result
     */
    protected function outputCheckResult(HealthResult $result)
    {
        $message = $result->title() . ": " . $result->status() . "\n" . $result->description();

        switch ($result->result()) {
            case HealthResult::RESULT_SUCCESS:
                $this->info($message);
                break;
            case HealthResult::RESULT_WARNING:
                $this->warn($message);
                break;
            case HealthResult::RESULT_FAILURE:
                $this->error($message);
                break;
            default:
                $this->comment($message);
        }
    }
}