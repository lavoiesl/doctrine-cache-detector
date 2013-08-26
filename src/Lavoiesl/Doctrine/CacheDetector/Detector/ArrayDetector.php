<?php

namespace Lavoiesl\Doctrine\CacheDetector\Detector;

class ArrayDetector extends AbstractDetector
{
    protected static $persistance_level = AbstractDetector::PERSISTANCE_REQUEST;

    protected $performance = array(
        'read_throughput'  => 10,
        'read_latency'     => 10,
        'write_throughput' => 10,
        'write_latency'    => 10,
    );
}
