<?php

require_once __DIR__ . '/../vendor/autoload.php';

$driverName = 'Libmemcached';
$options = array(
    'servers' => array(
        '127.0.0.1:11211'
    )
);
$prefix = 'my_prefix';

$mcache = \G4\Mcache\McacheFactory::createInstance($driverName, $options, $prefix);

$key = 'tralala';

$mcache->key($key)->value('data data')->set();

var_dump($mcache->key($key)->get());