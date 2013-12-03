<?php

namespace G4\Mcache;

class Mcache
{

    const KEY_SEPARATOR = '|';

    /**
     * @var \G4\Mcache\Driver\DriverInterface
     */
    private $_driver = null;

    private $_id;

    /**
     * @var mixed (object|string)
     */
    private $_object;


    public function __construct(\G4\Mcache\Driver\DriverInterface $driver)
    {
        $this->_driver = $driver;
    }

    public function delete()
    {
        return $this->_driver->delete($this->_getKey());
    }

    /**
     * @param mixed (string|int) $id
     *
     * @return \G4\Mcache\Mcache
     */
    public function id($id)
    {
        $this->_id = $id;

        return $this;
    }

    public function get()
    {
        return $this->_driver->get($this->_getKey());
    }

    /**
     * @param mixed (string|object) $object
     * @return \G4\Mcache\Mcache
     */
    public function object($object)
    {
        $this->_object = $object;

        return $this;
    }

    public function set()
    {
        return $this->_driver->set($this->_getKey(), $this->_object);
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