<?php

namespace G4\Mcache\Profiler;

class Ticker extends \G4\Profiler\Ticker\TickerAbstract
{

    private static $instance;

    private function __construct() {}

    private function __clone() {}


    /**
     * @return \G4\Mcache\Profiler\Ticker
     */
    final public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * @return Formatter
     */
    public function getDataFormatterInstance()
    {
        return new Formatter();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mcache';
    }

    /**
     * @param string $uniqueId
     * @param bool $hit
     * @return \G4\Mcache\Profiler\Ticker
     */
    public function setHit($uniqueId, $hit)
    {
        $this->getDataPart($uniqueId)->setHit($hit);
        return static::$instance;
    }

    /**
     * @param string $uniqueId
     * @param string $key
     * @return \G4\Mcache\Profiler\Ticker
     */
    public function setKey($uniqueId, $key)
    {
        $this->getDataPart($uniqueId)->setKey($key);
        return static::$instance;
    }

    /**
     * @param string $uniqueId
     * @param string $type
     * @return \G4\Mcache\Profiler\Ticker
     */
    public function setType($uniqueId, $type)
    {
        $this->getDataPart($uniqueId)->setType($type);
        return static::$instance;
    }
}