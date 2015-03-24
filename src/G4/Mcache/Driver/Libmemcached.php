<?php

namespace G4\Mcache\Driver;

use G4\Mcache\Driver\DriverAbstract;

class Libmemcached extends DriverAbstract
{

    const DELIMITER = ':';

    /**
     * @var bool
     */
    private $compression;

    /**
     * @var array
     */
    private $servers = array();

    /**
     * @param string $host
     * @param int $port
     * @param int $weight
     *
     * @return \G4\Mcache\Driver\Libmemcached
     */
    private function processOptions()
    {
        $options = $this->getOptions();

        if(empty($options)) {
            throw new \Exception('Options must be set');
        }

        foreach($options['servers'] as $server) {

            $serverData = explode(self::DELIMITER, $server);

            if (count($serverData) != 2) {
                continue;
            }
            $this->servers[] = array(
                'host'   => $serverData[0],
                'port'   => $serverData[1],
            );
            if(!empty($server['weight'])) {
                $this->servers['weight'] = $server['weight'];
            }
        }

        if(isset($options['compression'])) {
            $this->compression = $options['compression'];
        }

        return $this;
    }

    public function get($key)
    {
        return $this->connect()->get($key);
    }

    public function set($key, $value, $expiration)
    {
        return $this->connect()->set($key, $value, $expiration);
    }

    public function delete($key)
    {
        return $this->connect()->delete($key);
    }

    public function replace($key, $value, $expiration)
    {
        return $this->connect()->replace($key, $value, $expiration);
    }

    /**
     * @return \Memcached
     */
    protected function connect()
    {
        if(!$this->driver instanceof \Memcached) {
            $this->driverFactory();
        }
        return $this->driver;
    }

    private function driverFactory()
    {
        $this->processOptions();

        $this->driver = new \Memcached();
        $this->driver->addServers($this->servers);

        if (isset($this->compression)) {
            $this->driver->setOption(\Memcached::OPT_COMPRESSION, $this->compression);
        }
    }
}