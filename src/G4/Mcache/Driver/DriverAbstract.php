<?php

namespace G4\Mcache\Driver;

abstract class DriverAbstract implements DriverInterface
{

    protected $driver = null;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @var array
     */
    private $options = array();

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     *
     * @return \G4\Mcache\Driver\DriverAbstract
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see \G4\Mcache\Driver\DriverInterface::setOptions()
     * @return \G4\Mcache\Driver\DriverAbstract
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see \G4\Mcache\Driver\DriverInterface::getOptions()
     * return $array
     */
    public function getOptions()
    {
        return $this->options;
    }
}