<?php

namespace Lavoiesl\Doctrine\CacheDetector\Detector;

abstract class FileDetector extends AbstractDetector
{
    protected static $persistance_level = AbstractDetector::PERSISTANCE_LOCAL_PERMANENT;

    protected $performance = array(
        'read_throughput'  => 1,
        'read_latency'     => 1,
        'write_throughput' => 1,
        'write_latency'    => 1,
    );

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
