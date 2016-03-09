<?php
namespace GenTux\Healthz\Bundles\Laravel;

use Mockery;
use GenTux\Healthz\HealthCheck;
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
    public function run_sets_the_current_environment_as_the_description()
    {
        $this->app->shouldReceive('environment')->andReturn('staging');

        $this->env->run();
        $this->assertSame('staging', $this->env->description());
    }
}
