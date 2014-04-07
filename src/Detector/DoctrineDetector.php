<?php

namespace Lavoiesl\Doctrine\CacheDetector\Detector;

class DoctrineDetector extends ServerDetector
{
    protected $performance = array(
        'read_throughput'  => 3,
        'read_latency'     => 6,
        'write_throughput' => 6,
        'write_latency'    => 6,
    );

    protected $cache;

    protected $config = array(
        'doctrine' => null,
        'table'    => null,
        'fields'   => array(),
    );

    protected function getInitClass()
    {
        return 'Lavoiesl\Doctrine\CacheProvider\DoctrineCache';
    }

    protected function connect()
    {
        if (!$this->config['doctrine'] instanceof Doctrine\DBAL\Connection) {
            return false;
        }

        if (strlen($this->config['table']) == 0) {
            return false;
        }
    }

    public static function getCacheClass()
    {
        return 'Lavoiesl\Doctrine\CacheProvider\DoctrineCache';
    }

    public function getCache()
    {
        $class = static::getCacheClass();
        $cache = new $class($this->config['doctrine'], $this->config['table'], $this->config['fields']);

        return $cache;
    }
}
