mcache
======

> mcache - [php](http://php.net) cache wrapper library

## Install

Install through  [composer](https://getcomposer.org/) package manager.
Find it on [packagist](https://packagist.org/packages/g4/mcache).

    require: "g4/mcache": "*"

## Supported caching systems

* [Memcached](http://us2.php.net/manual/en/book.memcached.php)
* [Couchbase](http://www.couchbase.com/communities/php/getting-started)

## Usage

### Memcached instance

``` php
<?php
    
$driverName = 'Libmemcached';
$options = array(
    'servers' => array(
        array(
            'host' => 127.0.0.1
            'port' => 11211
        )
    );
);
$prefix = 'my_prefix';
    
$mcache = \G4\Mcache\McacheFactory::createInstance($driverName, $options, $prefix);
```

### Couchbase instance

``` php
<?php
    
$driverName = 'Couchbase';
$options = array(
    'bucket' => 'my_bucket',
    'servers' => array(
        '127.0.0.1:8091'
    );
);
$prefix = 'my_prefix';
    
$mcache = \G4\Mcache\McacheFactory::createInstance($driverName, $options, $prefix);
```    
    
### Methods

``` php
<?php
    
// Get from cache
$value = $mcache
    ->key('my_key')
    ->get();
    
// Save to cache
$mcache
    ->key('my_key')
    ->value('my_value')
    ->set();
    
// Sava to cache with expiration
$mcache
    ->key('my_key')
    ->value('my_value')
    ->expiration(3600) // in seconds (default 0)
    ->set();
    
// Delete from cache
$mcache
    ->key('my_key')
    ->delete();
    
// Replace a value
$mcache
    ->key('my_key')
    ->value('my_value')
    ->replace();
```

## Development

### Install dependencies

    $ make install

### Run tests

    $ make test

## License

(The MIT License)
see [LICENSE](https://github.com/g4code/mcache/blob/master/LICENSE) file for details...
