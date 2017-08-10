<?php

namespace G4\Mcache;

class McacheFactory
{
    const DRIVER_MEMCACHED  = 'Libmemcached';
    const DRIVER_COUCHBASE  = 'Couchbase';
    const DRIVER_FILE       = 'File';

    private static $_validDrivers = array(
        self::DRIVER_MEMCACHED,
        self::DRIVER_COUCHBASE,
        self::DRIVER_FILE,
    );

    /**
     * Create new instance of G4\Mcache\Mcache
     * @param string $driver
     * @param array $options
     * @param string $prefix
     * @throws \Exception
     * @return \G4\Mcache\Mcache
     */
    public static function createInstance($driver, $options, $prefix = '')
    {
        if(!in_array($driver, self::$_validDrivers)) {
            throw new \Exception("Driver '{$driver}' not implemented");
        }

        $class = __NAMESPACE__ . '\\Driver\\' . $driver;

        $driver = new $class;
        $driver
            ->setOptions($options)
            ->setPrefix($prefix);

        return new Mcache($driver);
    }
}