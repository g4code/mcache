<?php

namespace G4\Mcache\Driver;

use G4\ValueObject\Pathname;
use G4\ValueObject\RelativePath;

class File extends DriverAbstract
{

    /**
     * @var Pathname
     */
    private $cachePath;


    public function delete($key)
    {
        return unlink((string) $this->formatCacheFilename($key));
    }

    public function get($key)
    {
        $realCachePath = $this->formatCacheFilename($key);

        if(!is_readable($realCachePath)) {
            return null;
        }

        return require($realCachePath);
    }

    public function replace($key, $value, $expiration)
    {
        return $this->set($key, $value, $expiration);
    }

    public function set($key, $value, $expiration)
    {
        $realCachePath = $this->formatCacheFilename($key);

        $toSave = "<?php 
/**
Config filename: " . json_decode($value, true)['pathname'] . "
Environment: " . APPLICATION_ENV . "
Date created: " . date("Y-m-d H:i:s") . "
KEY ( md5 value of  usersOnline.UserId and driver prefix ) : " . $key . "
*/
return \n" . var_export($value, true) . ';';

        if(!touch($realCachePath)) {
            throw new \Exception('Cache file path is not writable');
        }

        return file_put_contents($realCachePath, $toSave);
    }

    private function formatCacheFilename($key)
    {
        $this->processOptions();

        $segments = [
            $this->cachePath,
            __NAMESPACE__,
            __CLASS__,
            $key,
        ];

        // section can be empty, so remove it
        $filename = md5(implode('~', array_filter($segments)));

        return new RelativePath($this->cachePath, $filename);
    }

    private function processOptions()
    {
        $options = $this->getOptions();

        if(empty($options)) {
            throw new \Exception('Options must be set');
        }

        if(!isset($options['path']) || empty($options['path'])) {
            throw new \Exception('Cach path must be set for file driver');
        }

        $this->cachePath = new Pathname($options['path']);

        if(!is_writable($this->cachePath)) {
            throw new \Exception('Cache file path is not writable');
        }
    }
}