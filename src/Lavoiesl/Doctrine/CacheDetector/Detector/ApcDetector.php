<?php

namespace Lavoiesl\Doctrine\CacheDetector\Detector;

class ApcDetector extends AbstractDetector
{
    protected function getInitFunction()
    {
        return 'apc_fetch';
    }

    /**
     * Dirty hack to create missing APC functions provided by APCu (PHP 5.5)
     */
    public static function createAPCuAliases()
    {
        if (!function_exists('apc_fetch') && function_exists('apcu_fetch')) {
            require dirname(__DIR__) . '/apcu_aliases.php';
        }
    }
}
