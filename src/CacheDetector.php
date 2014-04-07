<?php

namespace Lavoiesl\Doctrine\CacheDetector;

use Lavoiesl\Doctrine\CacheDetector\Detector\AbstractDetector;
use Lavoiesl\Doctrine\CacheDetector\Detector;
use \ReflectionClass;

class CacheDetector implements \ArrayAccess
{
    private $detectors = array();

    public function __construct($loadDefaults = true)
    {
        if ($loadDefaults) {
            $this->loadDefaultDetectors();
        }
    }

    public function setConfig($type, array $config)
    {
        if (isset($this->detectors[$type])) {
            $this->detectors[$type]->setConfig($config);
        }
    }

    public function setConfigs(array $configs)
    {
        foreach ($configs as $type => $config) {
            $this->setConfig($type, $config);
        }
    }

    public function offsetSet($type, $detector)
    {
        $this->addDetector($detector);
    }

    public function offsetExists($type)
    {
        return isset($this->detectors[$type]);
    }

    public function offsetUnset($type)
    {
        unset($this->detectors[$type]);
    }

    public function offsetGet($type)
    {
        return isset($this->detectors[$type]) ? $this->detectors[$type] : null;
    }

    public function addDetector(AbstractDetector $detector)
    {
        $type = $detector->getType();
        $this->detectors[$type] = $detector;
    }

    protected function loadDefaultDetectors()
    {
        $this->addDetector(new Detector\ApcDetector);
        $this->addDetector(new Detector\ArrayDetector);
        $this->addDetector(new Detector\DoctrineDetector);
        $this->addDetector(new Detector\FilesystemDetector);
        $this->addDetector(new Detector\MemcachedDetector);
        $this->addDetector(new Detector\MemcacheDetector);
        $this->addDetector(new Detector\PhpFileDetector);
        $this->addDetector(new Detector\RedisDetector);
        $this->addDetector(new Detector\WinCacheDetector);
        $this->addDetector(new Detector\XcacheDetector);
        $this->addDetector(new Detector\ZendDataDetector);
    }

    /**
     * List of all detectors (without checking if installed and supported)
     * @return array
     */
    public function getAllDetectors()
    {
        return $this->detectors;
    }

    public function getSupportedDetectors($persistanceLevel = AbstractDetector::PERSISTANCE_LOCAL_SERVICE, $isAvailable = true)
    {
        return array_filter($this->getAllDetectors(), function (AbstractDetector $detector) use ($persistanceLevel, $isAvailable) {
            return $detector->getPersistanceLevel() >= $persistanceLevel
                && $detector->isSupported()
                && (!$isAvailable || $detector->isAvailable());
        });
    }

    /**
     * Selects the best cache service depending on desired metrics and persistanceLevel
     * @param  array  $metrics
     * @param  int $persistanceLevel
     * @return AbstractDetector
     */
    public function selectBest(array $metrics = array(), $persistanceLevel = AbstractDetector::PERSISTANCE_LOCAL_SERVICE)
    {
        static $def = array(
            'read_throughput'  => 5,
            'read_latency'     => 5,
            'write_throughput' => 5,
            'write_latency'    => 5,
        );

        $metrics = array_merge($def, $metrics);

        $detectors = $this->getSupportedDetectors($persistanceLevel, false);
        if (empty($detectors)) {
            throw new \RuntimeException("No available cache layer");
        }

        $scores = array();

        foreach ($detectors as $key => $detector) {
            $scores[$key] = 0;
            $data = $detector->getPerformanceData();

            foreach ($metrics as $metric => $desired) {
                $scores[$key] += $data[$metric] - $desired;
            }
        }

        arsort($scores);

        foreach ($scores as $type => $score) {
            if ($detectors[$type]->isAvailable()) {
                return $detectors[$type];
            }
        }

        throw new \RuntimeException("No available cache layer");
    }
}
