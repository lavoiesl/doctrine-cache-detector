<?php

namespace Lavoiesl\Doctrine\CacheDetector\Detector;

class WinCacheDetector extends FileDetector
{
    protected function getInitFunction()
    {
        return 'wincache_ucache_get';
    }
}
