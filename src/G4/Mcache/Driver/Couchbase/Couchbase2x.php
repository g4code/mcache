<?php

namespace G4\Mcache\Driver\Couchbase;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 * @psalm-suppress UndefinedDocblockClass
 */
class Couchbase2x implements CouchbaseInterface
{

    /**
     * @var \CouchbaseCluster
     */
    private $clientCluster;

    /**
     * @var \CouchbaseBucket
     */
    private $clientBucket;

    /**
     * @var array
     */
    private $servers;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $pass;

    /**
     * @var string
     */
    private $bucket;

    private $persistent;

    private $timeout;

    /**
     * @param array $servers
     * @param string $user
     * @param string $pass
     * @param string $bucket
     * @param bool $persistent
     * @param int $timeout
     */
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
        if ($this->clientBucket instanceof \CouchbaseBucket) {
            return $this->clientBucket;
        }
        $this->connect();
        return $this->clientBucket;
    }

    /**
     * @return void
     */
    private function connect()
    {
        // randomize servers
        $servers = $this->servers;
        shuffle($servers);

        foreach ($servers as $server) {
            try {
                $this->clientCluster = new \CouchbaseCluster($server);

                if ($this->user && $this->pass) {
                    $this->clientCluster->authenticateAs($this->user, $this->pass);
                }

                $this->clientBucket = $this->clientCluster->openBucket($this->bucket);
                $this->clientBucket->operationTimeout = $this->timeout;
                break;
            } catch (\Exception $e) {
                trigger_error(
                    __METHOD__ . ' : ' . $server . ' does not answer "' . $e->getMessage() . '" (' . $e->getCode(
                    ) . '), trying another one ...', E_USER_WARNING
                );
                continue;
            }
        }
    }

}
