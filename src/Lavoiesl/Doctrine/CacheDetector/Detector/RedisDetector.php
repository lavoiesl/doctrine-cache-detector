<?php

namespace Lavoiesl\Doctrine\CacheDetector\Detector;

use \Redis;

class RedisDetector extends ServerDetector
{
    protected $performance = array(
        'read_throughput'  => 6,
        'read_latency'     => 6,
        'write_throughput' => 6,
        'write_latency'    => 6,
    );

    protected $redis;

    protected $config = array(
        'host'    => 'localhost',
        'port'    => 6379,
        'timeout' => 2.5,
        'socket'  => null,
    );

    protected function getInitClass()
    {
        return 'Redis';
    }

    protected function connect()
    {
        $this->redis = new Redis;

        if (!empty($this->config['socket'])) {
            return $this->redis->connect($this->config['socket']);
        } else {
            return $this->redis->connect($this->config['host'], $this->config['port'], $this->config['timeout']);
        }
    }

    public function getCache()
    {
        $class = static::getCacheClass();
        $cache = new $class;

        $cache->setRedis($this->redis);

        return $cache;
    }
}

