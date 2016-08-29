<?php

require_once __DIR__ . '/../vendor/autoload.php';

$driverName = 'Couchbase';
$options = array(
    'bucket' => 'mcache',
    'servers' => array(
        '127.0.0.1:8091'
    )
);
$prefix = 'my_prefix';

$mcache = \G4\Mcache\McacheFactory::createInstance($driverName, $options, $prefix);

$key = 'tralala';

$mcache->key($key)->value('data data')->set();

var_dump($mcache->key($key)->get());