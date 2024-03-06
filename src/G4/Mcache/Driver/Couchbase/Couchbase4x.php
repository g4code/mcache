<?php

namespace G4\Mcache\Driver\Couchbase;

use Couchbase\ClusterOptions;
use Couchbase\Cluster;
use Couchbase\UpsertOptions;
use Couchbase\ReplaceOptions;

class Couchbase4x implements CouchbaseInterface
{
    private const DEFAULT_CONNECT_TIMEOUT = 500000;
    private ?\Couchbase\Cluster $clientCluster;
    private ?\Couchbase\Bucket $clientBucket;

    public function __construct(
        private readonly array $servers,
        private readonly string $user,
        private readonly string $pass,
        private readonly string $bucket,
        private readonly bool $persistent,
        private int $timeout
    ) {
        $this->clientCluster = null;
        $this->clientBucket = null;
        $this->timeout = $timeout ?? self::DEFAULT_CONNECT_TIMEOUT;
    }

    public function delete($key)
    {
        if (!$this->clientFactory()) {
            return false;
        }
        try {
            $mutationResult = $this->clientFactory()->defaultCollection()->remove($key);
            return $mutationResult->cas();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function get($key)
    {
        if (!$this->clientFactory()) {
            return false;
        }
        try {
            return $this->clientFactory()->defaultCollection()->get($key)->content();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function replace($key, $value, $expiration)
    {
        if (!$this->clientFactory()) {
            return false;
        }
        $replaceOptions = (new ReplaceOptions())
            ->expiry($expiration);
        try {
            $mutationResult = $this->clientFactory()->defaultCollection()->replace($key, $value, $replaceOptions);
            return $mutationResult->cas();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function set($key, $value, $expiration)
    {
        if (!$this->clientFactory()) {
            return false;
        }
        $upsertOptions = (new UpsertOptions())
            ->expiry($expiration);
        try {
            $mutationResult = $this->clientFactory()->defaultCollection()->upsert($key, $value, $upsertOptions);
            return $mutationResult->cas();
        } catch (\Exception $e) {
            return false;
        }
    }

    private function clientFactory()
    {
        if ($this->clientBucket instanceof \Couchbase\Bucket) {
            return $this->clientBucket;
        }

        $this->connect();
        return $this->clientBucket;
    }

    private function connect()
    {
        $connectionString = 'couchbase://' . implode(',', $this->servers);
        $options = new ClusterOptions();
        $options->connectTimeout($this->timeout);
        $options->credentials($this->user, $this->pass);
        try {
            $this->clientCluster = new Cluster($connectionString, $options);
            $this->clientBucket = $this->clientCluster->bucket($this->bucket);
        } catch (\Exception $e) {
            $message = sprintf('Could not connect to couchbase cluster %s, bucket %s, message: %s', $connectionString, $this->bucket, $e->getMessage());
            trigger_error($message, E_USER_WARNING);
        }
    }
}
