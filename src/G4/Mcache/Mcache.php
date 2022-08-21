<?php

namespace G4\Mcache;

class Mcache
{

    const TYPE_DELETE  = 'delete';
    const TYPE_GET     = 'get';
    const TYPE_REPLACE = 'replace';
    const TYPE_SET     = 'set';

    const EMPTY_VALUE = 'MCACHE|EMPTY|VALUE';

    const KEY_SEPARATOR = '|';

    /**
     * @var \G4\Mcache\Driver\DriverInterface
     */
    private $driver;

    /**
     * @var int
     */
    private $expiration;

    /**
     * @var string
     */
    private $id;

    /**
     * @var \G4\Mcache\Profiler\Ticker
     */
    private $profiler;

    /**
     * @var mixed (object|string)
     */
    private $value;


    public function __construct(\G4\Mcache\Driver\DriverInterface $driver)
    {
        $this->driver     = $driver;
        $this->expiration = 0;
        $this->profiler   = \G4\Mcache\Profiler\Ticker::getInstance();
    }

    public function delete()
    {
        return $this->execute(self::TYPE_DELETE);
    }

    /**
     * @param int $expiration
     * @return \G4\Mcache\Mcache
     */
    public function expiration($expiration)
    {
        $this->expiration = $expiration;
        return $this;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->execute(self::TYPE_GET);
    }

    /**
     * Obsolete: use key() method
     * @param mixed $id
     * @return \G4\Mcache\Mcache
     */
    public function id($id)
    {
        return $this->key($id);
    }

    /**
     * @param mixed $key
     * @return \G4\Mcache\Mcache
     */
    public function key($key)
    {
        $this->id = $key;
        return $this;
    }

    /**
     * Obsolete: use value() method
     * @param mixed $object
     * @return \G4\Mcache\Mcache
     */
    public function object($object)
    {
        return $this->value($object);
    }

    public function replace()
    {
        return $this->execute(self::TYPE_REPLACE);
    }

    public function set()
    {
        return $this->execute(self::TYPE_SET);
    }

    /**
     * @param mixed $value
     * @return \G4\Mcache\Mcache
     */
    public function value($value)
    {
        $this->value = $value;
        return $this;
    }

    private function concatKeyParts()
    {
        return join(self::KEY_SEPARATOR , array(
            $this->driver->getPrefix(),
            $this->id));
    }

    //TODO: Drasko: Extract this to new class!
    private function execute($type)
    {
        $uniqueId = $this->profiler->start();
        $this->driver->setKeyParts($this->concatKeyParts());
        switch ($type) {
            case self::TYPE_GET:
                $response = $this->transformValue($this->driver->get($this->getKey()));
                break;
            case self::TYPE_DELETE:
                $response = $this->driver->delete($this->getKey());
                break;
            case self::TYPE_REPLACE:
                $response = $this->driver->replace($this->getKey(), $this->getValue(), $this->expiration);
                break;
            case self::TYPE_SET:
                $response = $this->driver->set($this->getKey(), $this->getValue(), $this->expiration);
                break;
        }
        $this->profiler
            ->setKey($uniqueId, $this->concatKeyParts())
            ->setType($uniqueId, $type)
            ->setHit($uniqueId, $response !== false)
            ->end($uniqueId);
        return $response;
    }

    private function getKey()
    {
        return md5($this->concatKeyParts());
    }

    private function getValue()
    {
        return $this->value;
    }

    private function transformValue($value)
    {
        return $value;
    }
}
