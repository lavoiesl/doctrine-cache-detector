<?php

namespace Lavoiesl\Doctrine\CacheDetector\Detector;

class XcacheDetector extends FileDetector
{
    protected static $persistance_level = AbstractDetector::PERSISTANCE_LOCAL_SERVICE;

    protected $performance = array(
        'read_throughput'  => 7,
        'read_latency'     => 7,
        'write_throughput' => 7,
        'write_latency'    => 7,
    );

    protected function getInitFunction()
    {
        return 'xcache_get';
    }
}
