<?php

namespace Lavoiesl\Doctrine\CacheDetector\Detector;

abstract class AbstractDetector
{
    /**
     * Options for the detector.
     * Will most likely be used to initialize the underlying server.
     * i.e.: Connect to memcache server
     *
     * @var array
     */
    protected $config = array();

    /**
     * Used to determine the best cache to use.
     * Higher is better.
     * Maximum is 10
     *
     * @var integer
     */
    protected $performance = array(
        'read_throughput'  => 5,
        'read_latency'     => 5,
        'write_throughput' => 5,
        'write_latency'    => 5,
    );

    protected static $persistance_level = AbstractDetector::PERSISTANCE_REQUEST;

    /**
     * Persistance levels
     * Request: will live only for this request
     * Service: will live until a service is restarted
     * Service: will live until the OS is rebooted
     * Permanent: will live until cleared
     */
    const PERSISTANCE_REQUEST         = 0;
    const PERSISTANCE_LOCAL_SERVICE   = 1;
    const PERSISTANCE_LOCAL_REBOOT    = 2;
    const PERSISTANCE_LOCAL_PERMANENT = 3;
    const PERSISTANCE_DISTRIBUTED     = 4;

    const DOCTRINE_NAMESPACE = 'Doctrine\Common\Cache';

    /**
     * Test if the the Doctrine library exists
     *
     * @return bool
     */
    public function isSupported()
    {
        if (!class_exists(static::getCacheClass())) {
            return false;
        }

        return $this->featureExists();
    }

    /**
     * Test if the required PHP libraries exist
     *
     * @return bool
     */
    protected function featureExists()
    {
        if ($extension = $this->getExtension()) {
            return extension_loaded($extension);
        }

        if ($function = $this->getInitClass()) {
            return class_exists($function);
        }

        if ($class = $this->getInitFunction()) {
            return function_exists($class);
        }

        return true;
    }

    /**
     * Name of the extension to test in `featureExists`
     *
     * @return string
     */
    protected function getExtension()
    {
        return null;
    }

    /**
     * Name of the class to test in `featureExists`
     *
     * @return string
     */
    protected function getInitClass()
    {
        return null;
    }

    /**
     * Name of the function to test in `featureExists`
     *
     * @return string
     */
    protected function getInitFunction()
    {
        return null;
    }

    public function setConfig(array $config)
    {
        $this->config = array_merge($this->config, $config);
    }

    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Corresponding class name of the Doctrine CacheProvider
     *
     * @return string
     */
    public static function getCacheClass()
    {
        $class = get_called_class();
        $class = preg_replace('/^'.addslashes(__NAMESPACE__).'\\\\([a-z]+)Detector/i', self::DOCTRINE_NAMESPACE . '\\\\' . '$1Cache', $class);

        return $class;
    }

    /**
     * Corresponding Doctrine CacheProvider
     *
     * @return Doctrine\Common\Cache\CacheProvider
     */
    public function getCache()
    {
        $class = static::getCacheClass();

        return new $class;
    }

    public function getPerformanceData()
    {
        return $this->performance;
    }

    public static function getPersistanceLevel()
    {
        return static::$persistance_level;
    }
}
