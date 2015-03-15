<?php

namespace G4\Mcache\Driver;

use G4\Mcache\Driver\DriverAbstract;

class Couchbase extends DriverAbstract
{

    /**
     * @var array
     */
    private $_servers = array();

    private $_bucket;

    private $_user;

    private $_pass;

    private $_persistent;

    /**
     * @param string $host
     * @param int $port
     * @param int $weight
     *
     * @return \G4\Mcache\Driver\Couchbase
     */
    private function _processOptions()
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
                throw new \Exception('Server host is invalid');
            }

            $this->_servers[] = $server;
        }

        $this->_bucket     = $options['bucket'];
        $this->_user       = isset($options['user']) ? $options['user']        : '';
        $this->_pass       = isset($options['pass']) ? $options['pass']        : '';
        $this->_persistent = isset($options['pass']) ? (bool) $options['pass'] : false;

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

    public function replace($key, $value, $expiration)
    {
        return $this->_connect()->replace($key, $value, $expiration);
    }

    /**
     * @return \Memcached
     */
    protected function _connect()
    {
        if(! $this->_driver instanceof \Couchbase) {
            $this->_driverFactory();
        }

        return $this->_driver;
    }

    private function _driverFactory()
    {
        $this->_processOptions();

        $this->_driver = new \Couchbase(
            $this->_servers,
            $this->_user,
            $this->_pass,
            $this->_bucket,
            $this->_persistent
        );
    }
}