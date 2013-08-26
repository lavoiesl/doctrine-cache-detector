<?php

namespace Lavoiesl\Doctrine\CacheDetector\Detector;

class XcacheDetector extends FileDetector
{
    protected function getInitFunction()
    {
        return 'xcache_get';
    }
}
