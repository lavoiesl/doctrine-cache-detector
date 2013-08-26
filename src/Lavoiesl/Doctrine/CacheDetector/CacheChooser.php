<?php

namespace Lavoiesl\Doctrine\CacheDetector;

use Lavoiesl\Doctrine\CacheDetector\Detector\AbstractDetector;
use \ReflectionClass;

class CacheChooser
{
    protected $configs = array();

    public function setConfig($type, array $config)
    {
        $this->configs[$type] = $config;
    }

    public function setConfigs(array $configs)
    {
        foreach ($configs as $type => $config) {
            $this->setConfig($type, $config);
        }
    }

    /**
     * List of all detectors (without checking if installed and supported)
     * @return array
     */
    public function getAllDetectors()
    {
        $detectors = array();

        foreach (glob(__DIR__ . '/Detector/*.php') as $file) {
            $class = __NAMESPACE__ . '\\Detector\\' . basename($file, '.php');
            $reflector = new ReflectionClass($class);

            if (!$reflector->isAbstract()) {
                $type = basename($file, 'Detector.php');
                $detector = new $class;

                if (!empty($this->configs[$type])) {
                    $detector->setConfig($this->configs[$type]);
                }

                $detectors[$type] = $detector;
            }
        }

        return $detectors;
    }

    public function getSupportedDetectors($persistance_level = AbstractDetector::PERSISTANCE_LOCAL_SERVICE)
    {
        return array_filter($this->getAllDetectors(), function (AbstractDetector $detector) use ($persistance_level) {
            return $detector->getPersistanceLevel() >= $persistance_level && $detector->isSupported();
        });
    }

    /**
     * Selects the best cache service depending on desired metrics and persistance_level
     * @param  array  $metrics
     * @param  int $persistance_level
     * @return AbstractDetector
     */
    public function selectBest(array $metrics = array(), $persistance_level = AbstractDetector::PERSISTANCE_LOCAL_SERVICE)
    {
        static $def = array(
            'read_throughput'  => 5,
            'read_latency'     => 5,
            'write_throughput' => 5,
            'write_latency'    => 5,
        );

        $metrics = array_merge($def, $metrics);

        $detectors = $this->getSupportedDetectors($persistance_level);
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
        $type = current(array_keys($scores));

        return $detectors[$type];
    }
}
