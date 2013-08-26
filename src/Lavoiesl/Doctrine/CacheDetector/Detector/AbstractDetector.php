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

        return preg_replace('/^'.__NAMESPACE__.'\\\\([a-z]+)Detector/i', self::DOCTRINE_NAMESPACE . '\\Cache', $class);
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
}
