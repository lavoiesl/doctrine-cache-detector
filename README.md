# Doctrine Cache Dectector

Detects all available cache layers using Doctrine\Common\Cache

Also contains some performance data to select the best available Cache system.

This can be useful for varying development environments.

## Usage

### List all supported caches

```php
<?php
use Lavoiesl\Doctrine\CacheDetector\CacheDetector;

$cache_detector = new CacheDetector;
$detectors = $cache_detector->getSupportedDetectors();

/**
 * [Apc, Filesystem, PhpFile, etc.]
 */
print_r(array_keys($detectors));

// Doctrine\Common\Cache\ApcCache
$cache = $detectors['Apc']->getCache();
?>
```

### Provide connection options

Provide options for the detector, see each detector for details.

```php
<?php
$cache_detector->setConfig('Redis', array('port' => 1234));

// or

$cache_detector->setConfigs(array(
    'Redis' => array('port' => 1234),
));
?>
```

### Select best cache system

This will automatically select the most performant cache system with a requirement on the persistance level.

On a local machine, ArrayCache will suffice. On a production environment, you may want to require a distributed one.

```php
<?php
use Lavoiesl\Doctrine\CacheDetector\Detector\AbstractDetector;

$array_cache    = $cache_detector->selectBest(AbstractDetector::PERSISTANCE_REQUEST)->getCache();
$apc_cache      = $cache_detector->selectBest(AbstractDetector::PERSISTANCE_LOCAL_SERVICE)->getCache();
$file_cache     = $cache_detector->selectBest(AbstractDetector::PERSISTANCE_LOCAL_PERMANENT)->getCache();
$memcache_cache = $cache_detector->selectBest(AbstractDetector::PERSISTANCE_DISTRIBUTED)->getCache();
?>
```

## Todo

 * Add support for MongoDB, Couchbase and Riak.
 * Add better data for performance

## Author

 * [Sébastien Lavoie](http://blog.lavoie.sl/)
 * [WeMakeCustom](http://www.wemakecustom.com/)