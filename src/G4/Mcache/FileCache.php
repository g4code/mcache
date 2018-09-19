<?php

namespace G4\Mcache;

use G4\ValueObject\Pathname;
use G4\ValueObject\StringInterface;

class FileCache
{

    const MCACHE_DRIVER     = 'File';
    const MCACHE_PREFIX     = 'file-cache';
    const MCACHE_PARAM_PATH = 'path';

    const NO_DATA = 'no-data';

    /**
     * @var Mcache
     */
    private $mcache;

    /**
     * @var array
     */
    private $data;

    /**
     * @var StringInterface
     */
    private $key;

    /**
     * @var StringInterface
     */
    private $prefix;

    /**
     * FileCache constructor.
     * @param Pathname $cachePath
     * @param StringInterface $key
     */
    public function __construct(Pathname $cachePath, StringInterface $key)
    {
        $this->data     = self::NO_DATA;
        $this->key      = $key;
        $this->mcache   = $this->makeMcache($cachePath);
    }


    /**
     * @param StringInterface $prefix
     * @return $this
     */
    public function setPrefix(StringInterface $prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasPrefix()
    {
        return $this->prefix instanceof StringInterface;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->hasPrefix() ? (string)$this->prefix : self::MCACHE_PREFIX;
    }

    /**
     * @param StringInterface $keyPart
     */
    public function appendKey(StringInterface $keyPart)
    {
        $key = $this->key->append($keyPart);
        $this->key = $key;
        $this->mcache->key((string) $key);
    }

    public function delete()
    {
        $this->mcache->delete();
    }

    public function has()
    {
        $data = $this->get();
        return $data !== self::NO_DATA
            && $data !== null;
    }

    public function get()
    {
        if ($this->data === self::NO_DATA) {
            $this->data = $this->getFromCache();
        }
        return $this->data;
    }

    public function makeMcache(Pathname $cachePath)
    {
        if ($this->mcache === null) {
            $options = [
                self::MCACHE_PARAM_PATH => (string) $cachePath,
            ];
            $this->mcache = McacheFactory::createInstance(self::MCACHE_DRIVER, $options, $this->getPrefix());
            $this->mcache->key((string) $this->key);
        }
        return $this->mcache;
    }

    public function set($data)
    {
        $this->data = $data;
        $this->setToCache();
    }

    private function getFromCache()
    {
        return json_decode($this->mcache->get(), true);
    }

    private function setToCache()
    {
        $this->mcache->value(json_encode($this->data, true))->set();
    }
}
