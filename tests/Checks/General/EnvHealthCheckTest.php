<?php
namespace Gentux\Healthz\Checks\General;

use Mockery;
use Gentux\Healthz\HealthCheck;

class EnvHealthCheckTest extends \TestCase
{

    /** @var EnvHealthCheck */
    protected $env;

    public function setUp()
    {
        parent::setUp();
        $this->env = new EnvHealthCheck('CUSTOM_ENV');
    }

    /** @test */
    public function instance_of_health_check()
    {
        $this->assertInstanceOf(HealthCheck::class, $this->env);
    }

    /** @test */
    public function sets_the_status_to_the_current_environment()
    {
        putenv('CUSTOM_ENV=staging');
        $this->env->run();
        $this->assertSame('staging', $this->env->status());
    }

    /**
     * @test
     * @expectedException \Gentux\Healthz\Exceptions\HealthWarningException
     */
    public function unknown_environment_emits_a_warning()
    {
        putenv('CUSTOM_ENV=');
        $this->env->run();
    }
}
