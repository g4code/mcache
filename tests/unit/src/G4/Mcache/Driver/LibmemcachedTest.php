<?php

use G4\Mcache\Mcache;

class LibmemcachedTest extends \PHPUnit\Framework\TestCase
{

    private $driver;


    public function setUp(): void
    {
        $this->driver = new \G4\Mcache\Driver\Libmemcached();
    }


    public function tearDown(): void
    {
        unset(
            $this->driver
        );
    }

    public function testGet()
    {
        $this->assertTrue(true);
    }

}