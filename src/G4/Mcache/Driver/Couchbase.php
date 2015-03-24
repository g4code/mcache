<?php

namespace G4\Mcache\Driver;

use G4\Mcache\Driver\DriverAbstract;

class Couchbase extends DriverAbstract
{

    /**
     * @var array
     */
    private $servers = array();

    /**
     * @var string
     */
    private $bucket;

    /**
     * @var string
     */
    private $user;

    private $pass;

    private $persistent;

    /**
     * @param string $host
     * @param int $port
     * @param int $weight
     *
     * @return \G4\Mcache\Driver\Couchbase
     */
    private function processOptions()
    {
        $options = $this->getOptions();

        if(empty($options)) {
            throw new \Exception('Options must be set');
        }

        if(!isset($options['bucket']) || empty($options['bucket'])) {
            throw new \Exception('Bucket name must be set for Couchbase driver');
        }

        foreach($options['servers'] as $server) {
            if(empty($server) || !is_string($server)) {
                continue;
            }
            $this->servers[] = $server;
        }
        var_dump($this->servers);
        die;
        $this->bucket     = $options['bucket'];
        $this->user       = isset($options['user']) ? $options['user']        : '';
        $this->pass       = isset($options['pass']) ? $options['pass']        : '';
        $this->persistent = isset($options['pass']) ? (bool) $options['pass'] : false;

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
        if(! $this->driver instanceof \Couchbase) {
            $this->driverFactory();
        }
        return $this->driver;
    }

    private function driverFactory()
    {
        $this->processOptions();

        $this->driver = new \Couchbase(
            $this->servers,
            $this->user,
            $this->pass,
            $this->bucket,
            $this->persistent
        );
    }
}