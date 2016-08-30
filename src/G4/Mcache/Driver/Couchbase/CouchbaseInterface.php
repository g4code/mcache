<?php

namespace G4\Mcache\Driver\Couchbase;

interface CouchbaseInterface
{
    public function delete($key);

    public function get($key);

    public function replace($key, $value, $expiration);

    public function set($key, $value, $expiration);
}