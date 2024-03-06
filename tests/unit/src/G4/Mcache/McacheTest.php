<?php

use G4\Mcache\Mcache;

class McacheTest extends \PHPUnit\Framework\TestCase
{

    private $_driverStub;

    private $_mcache;


    public function setUp(): void
    {
        $this->_driverStub = $this->createMock(\G4\Mcache\Driver\Libmemcached::class);
        $this->_mcache     = new \G4\Mcache\Mcache($this->_driverStub);
    }


    public function tearDown(): void
    {
        unset(
            $this->_driverStub,
            $this->_mcache
        );
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