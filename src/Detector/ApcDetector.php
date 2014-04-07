<?php

namespace Lavoiesl\Doctrine\CacheDetector\Detector;

use Lavoiesl\APCPolyfill\APCPolyfill;

class ApcDetector extends AbstractDetector
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
        return 'apc_fetch';
    }

    public static function createAPCuAliases()
    {
        if (class_exists('Lavoiesl\APCPolyfill\APCPolyfill')) {
            APCPolyfill::createAliases();
        } else {
            throw new \RuntimeException(__METHOD__ . ' requires lavoiesl/apc-polyfill');
        }
    }
}
