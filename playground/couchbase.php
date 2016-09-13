<?php

require_once __DIR__ . '/../vendor/autoload.php';

$driverName = 'Couchbase';
$options = array(
    'bucket' => 'mcache',
    'servers' => array(
        '127.0.0.1:8091',
        null,
    )
);
$prefix = 'my_prefix';

$mcache = \G4\Mcache\McacheFactory::createInstance($driverName, $options, $prefix);

$key = 'tralala';
$value = new \G4\Mcache\Mcache(new \G4\Mcache\Driver\Libmemcached());

var_dump($mcache->key($key)->value($value)->set());

var_dump($mcache->key($key)->get());

var_dump($mcache->key($key)->delete());

var_dump($mcache->key($key)->get());