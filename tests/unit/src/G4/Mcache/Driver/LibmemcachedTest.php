<?php

use G4\Mcache\Mcache;

class LibmemcachedTest extends \PHPUnit_Framework_TestCase
{

    private $_driver;


    public function setUp()
    {
        $this->_driver = new \G4\Mcache\Driver\Libmemcached();

        parent::setUp();
    }


    public function tearDown()
    {
        unset(
            $this->_driver
        );

        parent::tearDown();
    }

    public function testGet()
    {
        $this->assertTrue(true);
    }

}