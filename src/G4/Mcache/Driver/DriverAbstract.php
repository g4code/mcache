<?php

namespace G4\Mcache\Driver;

abstract class DriverAbstract implements DriverInterface
{
    protected $_driver = null;

    /**
     * @var string
     */
    private $_prefix;

    /**
     * @var array
     */
    private $_options = array();
    
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

    /**
     * (non-PHPdoc)
     * @see \G4\Mcache\Driver\DriverInterface::setOptions()
     * @return \G4\Mcache\Driver\DriverAbstract
     */
    public function setOptions($options)
    {
 		$this->_options = $options;
 		return $this;
    }
    
    /**
     * (non-PHPdoc)
     * @see \G4\Mcache\Driver\DriverInterface::getOptions()
     * return $array
     */
    public function getOptions()
    {
    	return $this->_options;
    }

}
