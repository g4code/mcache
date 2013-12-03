<?php

use G4\Mcache\Mcache;

class McacheTest extends \PHPUnit_Framework_TestCase
{

    private $_driverStub;

    private $_mcache;


    public function setUp()
    {
        $this->_driverStub = $this->getMock("\G4\Mcache\Driver\Libmemcached");
        $this->_mcache     = new \G4\Mcache\Mcache($this->_driverStub);

        parent::setUp();
    }


    public function tearDown()
    {
        unset(
            $this->_driverStub,
            $this->_mcache
        );

        parent::tearDown();
    }


    public function testDelete()
    {
        $this->_driverStub
            ->expects($this->any())
            ->method('delete')
            ->will($this->returnValue(true));

        $this->assertTrue($this->_mcache->delete());
    }

    public function testGet()
    {
        $this->_driverStub
            ->expects($this->any())
            ->method('get')
            ->will($this->returnValue(true));

        $this->assertTrue($this->_mcache->get());
    }

    public function testSet()
    {
        $this->_driverStub
            ->expects($this->any())
            ->method('set')
            ->will($this->returnValue(true));

        $this->assertTrue($this->_mcache->set());
    }

}