<?php

namespace G4\Mcache\Driver;

use G4\Mcache\Driver\DriverAbstract;

class Libmemcached extends DriverAbstract
{

    /**
     * @var bool
     */
    private $_compression;

    /**
     * @var array
     */
    private $_servers = array();


    /**
     * @param string $host
     * @param int $port
     * @param int $weight
     *
     * @return \G4\Mcache\Driver\Libmemcached
     */
    public function addServer($host, $port, $weight = 0)
    {
        $this->_servers[] = array(
            'host' => $host,
            'port' => $port
        );

        return $this;
    }

    public function get($key)
    {
        return $this->_connect()->get($key);
    }

    public function set($key, $value, $expiration)
    {
        return $this->_connect()->set($key, $value, $expiration);
    }

    public function delete($key)
    {
        return $this->_connect()->delete($key);
    }

    public function replace($key, $value)
    {
        return $this->_connect()->replace($key, $value);
    }

    /**
     * @param bool $compression
     * @return \G4\Mcache\Driver\Libmemcached
     */
    public function setCompression($compression)
    {
        $this->_compression = $compression;
        return $this;
    }

    /**
     * @return \Memcached
     */
    protected function _connect()
    {
        if(! $this->_driver instanceof \Memcached) {

            $this->_driverFactory();
        }

        return $this->_driver;
    }

    private function _driverFactory()
    {
        $this->_driver = new \Memcached();
        $this->_driver->addServers($this->_servers);

        if (isset($this->_compression)) {
            $this->_driver->setOption(\Memcached::OPT_COMPRESSION, $this->_compression);
        }
    }
}