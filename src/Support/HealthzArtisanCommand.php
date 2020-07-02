<?php
namespace Gentux\Healthz\Support;

use Gentux\Healthz\HealthResult;
use Gentux\Healthz\Healthz;
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
    public function handle(): int
    {
        if (count($this->checks->all()) == 0) {
            $this->comment("No health checks registered. Be sure to register Gentux\Healthz\Healthz in a service provider. See github.com/generationtux/php-healthz for more info.");
            return 0;
        }

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
     *
     * @return void
     */
    protected function outputCheckResult(HealthResult $result): void
    {
        $message = $result->title() . ": " . $result->status();

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
