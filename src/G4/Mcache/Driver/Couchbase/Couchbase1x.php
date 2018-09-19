<?php

namespace G4\Mcache\Driver\Couchbase;

class Couchbase1x implements CouchbaseInterface
{

    private $client;

    private $servers;

    private $user;

    private $pass;

    private $bucket;

    private $persistent;

    private $timeout;


    public function __construct($servers, $user, $pass, $bucket, $persistent, $timeout)
    {
        $this->servers      = $servers;
        $this->user         = $user;
        $this->pass         = $pass;
        $this->bucket       = $bucket;
        $this->persistent   = $persistent;
        $this->timeout      = $timeout;
    }


    public function delete($key)
    {
        return $this->clientFactory()->delete($key);
    }

    public function get($key)
    {
        return $this->clientFactory()->get($key);
    }

    public function replace($key, $value, $expiration)
    {
        return $this->clientFactory()->replace($key, $value, $expiration);
    }

    public function set($key, $value, $expiration)
    {
        return $this->clientFactory()->set($key, $value, $expiration);
    }

    public function clientFactory()
    {
        if(! $this->client instanceof \Couchbase) {
            $this->client = new \Couchbase(
                $this->servers,
                $this->user,
                $this->pass,
                $this->bucket,
                $this->persistent
            );
            $this->client->setTimeout($this->timeout);
        }
        return $this->client;
    }

}