<?php

namespace Lavoiesl\Doctrine\CacheDetector;

use \ReflectionClass;

class CacheDetector
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

    public function getSupportedDetectors()
    {
        return array_filter($this->getAllDetectors(), function ($detector) {
            return $detector->isSupported();
        });
    }
}
