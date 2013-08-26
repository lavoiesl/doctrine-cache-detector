<?php

namespace Lavoiesl\Doctrine\CacheDetector\Detector;

class WinCacheDetector extends FileDetector
{
    protected static $persistance_level = AbstractDetector::PERSISTANCE_LOCAL_SERVICE;

    protected $performance = array(
        'read_throughput'  => 8,
        'read_latency'     => 8,
        'write_throughput' => 8,
        'write_latency'    => 8,
    );

    protected function getInitFunction()
    {
        return 'wincache_ucache_get';
    }
}
