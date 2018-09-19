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

    private $timeout;

    public function __construct($servers, $user, $pass, $bucket, $persistent, $timeout)
    {
        $this->server       = $servers[array_rand($servers)];
        $this->user         = $user;
        $this->pass         = $pass;
        $this->bucket       = $bucket;
        $this->persistent   = $persistent;
        $this->timeout      = $timeout;
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
        $value = false;
        if ($metaDoc instanceof \CouchbaseMetaDoc) {
            //Drasko: "In case the passed string is not unserializeable, FALSE is returned and E_NOTICE is issued."
            $unserializedValue = @unserialize($metaDoc->value);
            $value = is_string($metaDoc->value) && $unserializedValue !== false
                ? $unserializedValue
                : $metaDoc->value;
        }
        return $value;
    }

    public function replace($key, $value, $expiration)
    {
        try {
            $metaDoc = $this->clientFactory()->replace($key, serialize($value), ['expiry' => $expiration]);
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
            $metaDoc = $this->clientFactory()->upsert($key, serialize($value), ['expiry' => $expiration]);
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
            //TODO: Drasko - add timeout option !
        }
        return $this->clientBucket;
    }

}