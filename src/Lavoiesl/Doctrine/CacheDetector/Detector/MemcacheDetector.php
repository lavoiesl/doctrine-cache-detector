<?php

namespace Lavoiesl\Doctrine\CacheDetector\Detector;

use \Memcache;

class MemcacheDetector extends ServerDetector
{
    protected $performance = array(
        'read_throughput'  => 3,
        'read_latency'     => 3,
        'write_throughput' => 3,
        'write_latency'    => 3,
    );

    protected $memcache;

    protected $config = array(
        'host' => 'localhost',
        'port' => 11211,
    );

    protected function getInitClass()
    {
        return 'Memcache';
    }

    protected function connect()
    {
        $this->memcache = new Memcache;

        return @$this->memcache->connect($this->config['host'], $this->config['port']);
    }

    public function getCache()
    {
        $class = static::getCacheClass();
        $cache = new $class;

        $cache->setMemcache($this->memcache);

        return $cache;
    }
}
