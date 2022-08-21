<?php

namespace G4\Mcache\Driver;

interface DriverInterface
{

    public function delete($key);

    public function get($key);

    public function replace($key, $value, $expiration);

    public function set($key, $value, $expiration);

    public function setPrefix($prefix);

    public function getPrefix();

    public function setOptions($options);

    public function getOptions();

    public function setKeyParts($keyParts);
}
