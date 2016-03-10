<?php
namespace GenTux\Healthz;

use Mockery;

class ResultStackTest extends \TestCase
{

    /** @var ResultStack */
    protected $stack;

    /** @var HealthResult */
    protected $checkPassed;

    /** @var HealthResult */
    protected $checkFailed;

    public function setUp()
    {
        $this->checkPassed = Mockery::mock(HealthResult::class);
        $this->checkPassed->shouldReceive('failed')->andReturn(false);

        $this->checkFailed = Mockery::mock(HealthResult::class);
        $this->checkFailed->shouldReceive('failed')->andReturn(true);

        $this->stack = new ResultStack();
        parent::setUp();
    }

    /** @test */
    public function push_and_check_for_failures()
    {
        $this->stack->push($this->checkPassed);
        $this->assertSame([$this->checkPassed], $this->stack->all());
        $this->assertFalse($this->stack->hasFailures());

        $this->stack->merge([$this->checkFailed]);
        $this->assertSame([$this->checkPassed, $this->checkFailed], $this->stack->all());
        $this->assertTrue($this->stack->hasFailures());
    }
}
