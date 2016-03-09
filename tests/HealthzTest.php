<?php
namespace GenTux\Healthz;

use Mockery;

class HealthzTest extends \TestCase
{

    /** @var HealthCheck | Mockery\Mock */
    protected $check1;

    /** @var HealthCheck | Mockery\Mock */
    protected $check2;

    /** @var Healthz */
    protected $healthz;

    public function setUp()
    {
        parent::setUp();
        $this->check1 = Mockery::mock(HealthCheck::class);
        $this->check2 = Mockery::mock(HealthCheck::class);
        $this->healthz = new Healthz([$this->check1, $this->check2]);
    }

    /** @test */
    public function get_set_of_health_checks()
    {
        $result = $this->healthz->all();
        $this->assertCount(2, $result);
        $this->assertSame($this->check1, $result[0]);
        $this->assertSame($this->check2, $result[1]);
    }

    /** @test */
    public function push_new_health_checks_onto_the_stack()
    {
        $newCheck = Mockery::mock(HealthCheck::class);

        $result = $this->healthz->push($newCheck);
        $this->assertSame($this->healthz, $result);

        $this->assertCount(3, $this->healthz->all());
    }
}
