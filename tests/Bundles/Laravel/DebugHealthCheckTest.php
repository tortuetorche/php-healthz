<?php
namespace GenTux\Healthz\Bundles\Laravel;

use GenTux\Healthz\HealthCheck;

class DebugHealthCheckTest extends \TestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->debug = new DebugHealthCheck();
    }

    /** @test */
    public function instance_of_health_check()
    {
        $this->assertInstanceOf(HealthCheck::class, $this->debug);
    }

    /** @test */
    public function run_sets_the_description_to_off()
    {
        putenv('APP_DEBUG=false');

        $this->debug->run();
        $this->assertSame('off', $this->debug->status());
    }

    /**
     * @test
     * @expectedException \GenTux\Healthz\Exceptions\HealthWarningException
     */
    public function run_throws_warning_exception_if_debug_is_on()
    {
        putenv('APP_DEBUG=true');

        $this->debug->run();
    }
}
