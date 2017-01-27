<?php
namespace Gentux\Healthz\Checks\General;

use Gentux\Healthz\HealthCheck;

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
     * @expectedException \Gentux\Healthz\Exceptions\HealthWarningException
     */
    public function run_throws_warning_exception_if_debug_is_on()
    {
        $this->debug = new DebugHealthCheck('DEBUG_CUSTOM');
        putenv('DEBUG_CUSTOM=true');

        $this->debug->run();
        $this->assertSame('on', $this->debug->status());
    }
}
