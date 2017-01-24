<?php
namespace Gentux\Healthz\Bundles\Laravel;

use Mockery;
use Gentux\Healthz\HealthCheck;
use Illuminate\Contracts\Foundation\Application;

class EnvHealthCheckTest extends \TestCase
{

    /** @var Application | Mockery\Mock */
    protected $app;

    /** @var EnvHealthCheck */
    protected $env;

    public function setUp()
    {
        parent::setUp();
        $this->app = Mockery::mock(Application::class);
        $this->env = new EnvHealthCheck($this->app);
    }

    /** @test */
    public function instance_of_health_check()
    {
        $this->assertInstanceOf(HealthCheck::class, $this->env);
    }

    /** @test */
    public function sets_the_status_to_the_current_environment()
    {
        $this->app->shouldReceive('environment')->andReturn('staging');

        $this->env->run();
        $this->assertSame('staging', $this->env->status());
    }
}
