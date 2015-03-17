<?php

namespace G4\Mcache\Profiler;

class Formatter extends \G4\Profiler\Ticker\Formatter
{

    /**
     * @var bool
     */
    private $hit;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $type;

    /**
     * @return array
     */
    public function getFormatted()
    {
        return parent::getFormatted()
        + [
            'query' => $this->key,
            'type'  => $this->type,
            'hit'   => $this->hit,
        ];
    }

    /**
     * @param bool $hit
     * @return \G4\Mcache\Profiler\Formatter
     */
    public function setHit($hit)
    {
        $this->hit = $hit;
        return $this;
    }

    /**
     * @param string $info
     * @return \G4\Mcache\Profiler\Formatter
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @param string $type
     * @return \G4\Mcache\Profiler\Formatter
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
}