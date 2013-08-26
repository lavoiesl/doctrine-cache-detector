<?php

namespace Lavoiesl\Doctrine\CacheDetector\Detector;

abstract class ServerDetector extends AbstractDetector
{
    /**
     * Initializes underlying cache interface
     *
     * @uses $this->config
     * @return bool success
     */
    abstract protected function connect();

    public function isSupported()
    {
        return parent::isSupported() && $this->connect();
    }
}