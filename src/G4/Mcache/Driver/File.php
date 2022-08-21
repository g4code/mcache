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

    /**
     * @var string
     */
    private $keyParts;


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

    public function setKeyParts($keyParts)
    {
        $this->keyParts = $keyParts;
    }

    public function set($key, $value, $expiration)
    {
        $realCachePath = $this->formatCacheFilename($key);

        $tpl =<<<EOF
<?php
/**
g4/mcache file driver metadata
%s
Date created: %s
Key: %s
Key parts: %s
Expiration: %s
*/

return %s;
EOF;
        $toSave = sprintf($tpl,
            $this->getEnvironment(),
            date("Y-m-d H:i:s"),
            $key,
            $this->keyParts,
            $expiration,
            var_export($value, true)
        );
        
        if(!touch($realCachePath)) {
            throw new \Exception('Cache file path is not writable');
        }

        return file_put_contents($realCachePath, $toSave);
    }

    private function getEnvironment()
    {
        $envVars = ['APPLICATION_ENV', 'APP_ENV', 'ENV'];
        $env = [];
        foreach ($envVars as $var) {
            if (defined($var)) {
                $env[] = 'constant('. $var .'): ' . constant($var);
            }
            if (getenv($var)) {
                $env[] = 'getenv(' . $var . '): ' . getenv($var);
            }
        }
        return 'Environment: ' . (count($env) ? implode(', ', $env) : 'null');
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
