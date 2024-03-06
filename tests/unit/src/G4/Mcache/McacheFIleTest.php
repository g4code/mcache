<?php

use G4\Mcache\Mcache;
use G4\Mcache\Driver\File;
use G4\ValueObject\Pathname;
use G4\Mcache\McacheFactory;

//TODO: Drasko - move to functional tests !
class McacheFIleTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var Mcache
     */
    private $mcache;

    private $key;

    private $data;

    protected function setUp(): void
    {
        $driverName = 'File';
        $options = [
            'path' => (string) (new Pathname(__DIR__, '..', '..', '..', 'fixtures')),
        ];
        $prefix = 'my_prefix';

        $this->mcache = McacheFactory::createInstance($driverName, $options, $prefix);
        $this->key = 'tralala';
        $this->data = [
            'a' => 'b'
        ];
    }

    protected function tearDown(): void
    {
        $this->mcache   = null;
        $this->key      = null;
        $this->data     = null;
    }

    public function testInstance()
    {
        $this->assertInstanceOf(Mcache::class, $this->mcache);
    }

    public function testSet()
    {
        $this->mcache
            ->key($this->key)
            ->value($this->data)
            ->set();
        $this->assertEquals($this->data, $this->mcache->key($this->key)->get());
    }

    public function testReplace()
    {
        $this->mcache
            ->key($this->key)
            ->value($this->data)
            ->set();
        $this->assertEquals($this->data, $this->mcache->key($this->key)->get());

        $this->mcache
            ->key($this->key)
            ->value(123)
            ->replace();
        $this->assertEquals(123, $this->mcache->key($this->key)->get());
    }

    public function testDelete()
    {
        $this->mcache
            ->key($this->key)
            ->value($this->data)
            ->set();

        $this->mcache->key($this->key)->delete();

        $this->assertNull($this->mcache->key($this->key)->get());
    }
}