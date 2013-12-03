<?php

namespace G4\Mcache\Driver;

abstract class DriverAbstract implements DriverInterface
{
    protected $_driver = null;

    /**
     * @var string
     */
    protected $_prefix;

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->_prefix;
    }

    /**
     * @param string $prefix
     *
     * @return \G4\Mcache\Driver\DriverAbstract
     */
    public function setPrefix($prefix)
    {
        $this->_prefix = $prefix;
        return $this;
    }

}