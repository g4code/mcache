<?php

namespace G4\Mcache\Driver\Couchbase;

class Couchbase2x implements CouchbaseInterface
{

    /**
     * @var \CouchbaseCluster
     */
    private $clientCluster;

    /**
     * @var
     */
    private $clientBucket;

    private $server;

    private $user;

    private $pass;

    private $bucket;

    private $persistent;


    public function __construct($servers, $user, $pass, $bucket, $persistent)
    {
        $this->server       = array_rand($servers);
        $this->user         = $user;
        $this->pass         = $pass;
        $this->bucket       = $bucket;
        $this->persistent   = $persistent;
    }


    public function delete($key)
    {
        try {
            $metaDoc = $this->clientFactory()->remove($key);
        } catch (\CouchbaseException $exception) {
            $metaDoc = false;
        }
        return $metaDoc instanceof \CouchbaseMetaDoc
            ? $metaDoc->cas
            : false;
    }

    public function get($key)
    {
        try {
            $metaDoc = $this->clientFactory()->get($key);
        } catch (\CouchbaseException $exception) {
            $metaDoc = false;
        }
        return $metaDoc instanceof \CouchbaseMetaDoc
            ? $metaDoc->value
            : false;
    }

    public function replace($key, $value, $expiration)
    {
        try {
            $metaDoc = $this->clientFactory()->replace($key, $value, ['expiry' => $expiration]);
        } catch (\CouchbaseException $exception) {
            $metaDoc = false;
        }
        return $metaDoc instanceof \CouchbaseMetaDoc
            ? $metaDoc->cas
            : false;
    }

    public function set($key, $value, $expiration)
    {
        try {
            $metaDoc = $this->clientFactory()->upsert($key, $value, ['expiry' => $expiration]);
        } catch (\CouchbaseException $exception) {
            $metaDoc = false;
        }
        return $metaDoc instanceof \CouchbaseMetaDoc
            ? $metaDoc->cas
            : false;
    }

    public function clientFactory()
    {
        if(! $this->clientBucket instanceof \CouchbaseBucket) {
            $this->clientCluster = new \CouchbaseCluster(
                $this->server,
                $this->user,
                $this->pass
            );
            $this->clientBucket = $this->clientCluster->openBucket($this->bucket);
        }
        return $this->clientBucket;
    }

}