<?php

namespace Lavoiesl\Doctrine\CacheDetector\Detector;

use \Memcached;

class MemcachedDetector extends MemcacheDetector
{
    protected $performance = array(
        'read_throughput'  => 4,
        'read_latency'     => 4,
        'write_throughput' => 4,
        'write_latency'    => 4,
    );

    protected function getInitClass()
    {
        return 'Memcached';
    }

    protected function connect()
    {
        $this->memcache = new Memcached;
        $this->memcache->addServer($this->config['host'], $this->config['port']);
        
        $stats = $this->memcache->getStats();

        foreach ($stats as $server => $stat) {
            if (!empty($stat['version'])) {
                return true;
            }
        }

        return false;
    }

    public function getCache()
    {
        $class = static::getCacheClass();
        $cache = new $class;

        $cache->setMemcached($this->memcache);

        return $cache;
    }
}
