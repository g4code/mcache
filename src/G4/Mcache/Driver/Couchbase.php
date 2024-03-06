<?php

namespace G4\Mcache\Driver;

use G4\Mcache\Driver\Couchbase\Couchbase1x;
use G4\Mcache\Driver\Couchbase\Couchbase2x;
use G4\Mcache\Driver\Couchbase\Couchbase4x;
use G4\Mcache\Driver\Couchbase\CouchbaseInterface;

class Couchbase extends DriverAbstract
{

    private const DEFAULT_TIMEOUT_VALUE = 500000; // time in microseconds

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

    /**
     * @var string
     */
    private $pass;

    /**
     * @var bool
     */
    private $persistent;

    /**
     * @var int
     */
    private $timeout;

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
        $this->bucket       = $options['bucket'];
        $this->user         = $options['user'] ?? '';
        $this->pass         = $options['pass'] ?? '';
        $this->persistent   = isset($options['persistent']) && (bool)$options['persistent'];
        $this->timeout      = isset($options['timeout']) ? (int) $options['timeout'] : self::DEFAULT_TIMEOUT_VALUE;

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

    private function connect()
    {
        if ($this->driver instanceof CouchbaseInterface) {
            return $this->driver;
        }

        $this->processOptions();

        if (class_exists('\Couchbase')) {
            $this->driver = new Couchbase1x(
                $this->servers,
                $this->user,
                $this->pass,
                $this->bucket,
                $this->persistent,
                $this->timeout
            );
            return $this->driver;
        }
        if (class_exists('\CouchbaseCluster')) {
            $this->driver = new Couchbase2x(
                $this->servers,
                $this->user,
                $this->pass,
                $this->bucket,
                $this->persistent,
                $this->timeout
            );
            return $this->driver;
        }

        if (class_exists('\Couchbase\Cluster')) {
            $this->driver = new Couchbase4x(
                $this->servers,
                $this->user,
                $this->pass,
                $this->bucket,
                $this->persistent,
                $this->timeout
            );
            return $this->driver;
        }

        throw new \Exception('Couchbase client missing!', 601);
    }
}
