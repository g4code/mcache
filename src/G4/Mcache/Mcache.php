<?php

namespace G4\Mcache;

class Mcache
{

    const KEY_SEPARATOR = '|';

    /**
     * @var \G4\Mcache\Driver\DriverInterface
     */
    private $_driver = null;

    private $_expiration;

    private $_id;

    /**
     * @var mixed (object|string)
     */
    private $_object;


    public function __construct(\G4\Mcache\Driver\DriverInterface $driver)
    {
        $this->_driver     = $driver;
        $this->_expiration = 0;
    }

    public function delete()
    {
        return $this->_driver->delete($this->_getKey());
    }

    /**
     * @param int $expiration
     * @return \G4\Mcache\Mcache
     */
    public function expiration($expiration)
    {
        $this->_expiration = $expiration;
        return $this;
    }

    /**
     * Obsolete: use key() method
     * @param mixed $id
     * @return \G4\Mcache\Mcache
     */
    public function id($id)
    {
        $this->_id = $id;
        return $this;
    }

    /**
     * @param mixed $key
     * @return \G4\Mcache\Mcache
     */
    public function key($key)
    {
        $this->_id = $key;
        return $this;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->_driver->get($this->_getKey());
    }

    /**
     * Obsolete: use value() method
     * @param mixed $object
     * @return \G4\Mcache\Mcache
     */
    public function object($object)
    {
        $this->_object = $object;
        return $this;
    }

    /**
     * @param mixed $value
     * @return \G4\Mcache\Mcache
     */
    public function value($value)
    {
        $this->_object = $value;
        return $this;
    }

    public function set()
    {
        return $this->_driver->set(
            $this->_getKey(),
            $this->_object,
            $this->_expiration);
    }

    private function _concatKeyParts()
    {
        return join(self::KEY_SEPARATOR , array(
            $this->_driver->getPrefix(),
            $this->_id));
    }

    private function _getKey()
    {
        return md5($this->_concatKeyParts());
    }
}