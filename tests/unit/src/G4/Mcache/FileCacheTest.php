<?php

use G4\Mcache\FileCache;
use G4\ValueObject\StringLiteral;
use G4\ValueObject\Pathname;

class FileCacheTest extends \PHPUnit\Framework\TestCase
{

    private $pathname;
    private $key;

    protected function setUp(): void
    {
        $this->pathname = new Pathname('');
        $this->key = new StringLiteral('KEY');
    }

    protected function tearDown(): void
    {
        $this->pathname = null;
        $this->key = null;
    }

    public function testPrefix()
    {
        $aFileCache = new FileCache($this->pathname, $this->key);
        $this->assertFalse($aFileCache->hasPrefix());
        $this->assertEquals('file-cache', $aFileCache->getPrefix());
        $this->assertInstanceOf(FileCache::class, $aFileCache->setPrefix(new StringLiteral('RANDOM_PREFIX')));
        $this->assertTrue($aFileCache->hasPrefix());
        $this->assertEquals('RANDOM_PREFIX', $aFileCache->getPrefix());
    }

    public function testGetSetDeleteCache()
    {
        $aFileCache = new FileCache($this->pathname, $this->key);
        $aFileCache->set([1,2,3]);
        $this->assertEquals([1,2,3], $aFileCache->get());
        $aFileCache->delete();
    }

    public function testGetNoData()
    {
        $aFileCache = new FileCache($this->pathname, $this->key);
        $this->assertEquals(null, $aFileCache->get());
    }

    public function testHas()
    {
        $aFileCache = new FileCache($this->pathname, $this->key);
        $this->assertFalse($aFileCache->has());
        $aFileCache->set([1,2,3]);
        $this->assertTrue($aFileCache->has());
        $aFileCache->delete();
    }

    public function testAppendKey()
    {
        $aFileCache = new FileCache($this->pathname, $this->key);
        $this->assertNull($aFileCache->appendKey(new StringLiteral('APPENDED_KEY')));
    }


}