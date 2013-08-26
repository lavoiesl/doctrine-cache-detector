<?php

namespace Lavoiesl\Doctrine\CacheDetector\Detector;

class ZendDataDetector extends FileDetector
{
    protected function getInitFunction()
    {
        return 'zend_shm_cache_fetch';
    }
}
