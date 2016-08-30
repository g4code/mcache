<?php

namespace G4\Mcache\Driver\Couchbase;

class Couchbase1x
{

    private $client;

    private $servers;

    private $user;

    private $pass;

    private $bucket;

    private $persistent;


    public function __construct($servers, $user, $pass, $bucket, $persistent)
    {
        $this->servers      = $servers;
        $this->user         = $user;
        $this->pass         = $pass;
        $this->bucket       = $bucket;
        $this->persistent   = $persistent;
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
        }
        return $this->client;
    }

}