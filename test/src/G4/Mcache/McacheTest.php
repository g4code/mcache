<?php

use G4\Mcache\Mcache;

class McacheTest extends \PHPUnit_Framework_TestCase
{

    private $_mcache;


    public function setUp()
    {
        $driver = $this->getMock("\G4\Mcache\Driver\Libmemcached");

        $driver->expects($this->any())
               ->method('delete')
               ->will($this->returnValue(true));

        $this->_mcache = new \G4\Mcache\Mcache($driver);

        parent::setUp();
    }


    public function tearDown()
    {
        unset($this->_mcache);

        parent::tearDown();
    }


    public function testDelete()
    {
        $this->assertTrue($this->_mcache->delete());
    }

}