<?php

namespace Lavoiesl\Doctrine\CacheDetector\Detector;

abstract class FileDetector extends AbstractDetector
{
    protected $config = array(
        'extension' => null
    );

    public function __construct()
    {
        $this->config['directory'] = sys_get_temp_dir() . '/php-file-cache';
    }

    public function getCache()
    {
        $class = static::getCacheClass();
        $cache = new $class($this->config['directory'], $this->config['extension']);

        return $cache;
    }
}
