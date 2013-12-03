<?php

namespace G4\Mcache\Driver;

interface DriverInterface
{
    public function getPrefix();

    public function setPrefix($prefix);

    public function get($key);

    public function set($key, $value);

    public function delete($key);

    public function replace($key, $value);

}