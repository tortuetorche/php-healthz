<?php
namespace Gentux\Healthz\Checks\Laravel;

use Gentux\Healthz\Checks\Laravel\DatabaseHealthCheck;
use Mockery;
use Gentux\Healthz\HealthCheck;
use Illuminate\Database\Connection;
use Illuminate\Database\DatabaseManager;

class DatabaseHealthCheckTest extends \TestCase
{

    /** @var Mockery\Mock | DatabaseManager */
    protected $manager;

    /** @var DatabaseHealthCheck */
    protected $db;

    public function setUp()
    {
        parent::setUp();
        $this->manager = Mockery::mock(DatabaseManager::class);
        $this->db = new DatabaseHealthCheck($this->manager);
    }

    /** @test */
    public function instance_of_health_check()
    {
        $this->assertInstanceOf(HealthCheck::class, $this->db);
    }

    /** @test */
    public function sets_connection_name()
    {
        $this->assertNull($this->db->connection());

        $this->db->setConnection('custom');
        $this->assertSame('custom', $this->db->connection());
    }

    /** @test */
    public function if_no_description_is_set_use_the_connection_name()
    {
        $description = $this->db->description();
        $this->assertSame('default', $description); # if connection is also null

        $this->db->setConnection('mysql');
        $description = $this->db->description();
        $this->assertSame('mysql', $description);
    }

    /** @test */
    public function uses_the_connection_name_set_to_resolve_a_laravel_db_connection()
    {
        $this->db->setConnection('custom');

        $conn = Mockery::mock(Connection::class);
        $this->manager->shouldReceive('connection')->with('custom')->once()->andReturn($conn);

        $this->db->run();
        $status = $this->db->status();
        $this->assertSame('connected', $status);
    }

    /**
     * @test
     * @expectedException \Gentux\Healthz\Exceptions\HealthFailureException
     */
    public function throws_health_failure_when_laravel_runs_into_trouble()
    {
        $this->manager->shouldReceive('connection')->andThrow(new \Exception());
        $this->db->run();
    }
}
