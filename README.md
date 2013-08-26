# Doctrine Cache Dectector

Detects all available cache layers using Doctrine\Common\Cache

This can be useful for varying development environments.

## Usage

```php
<?php
use Lavoiesl\Doctrine\CacheDetector\CacheDetector;

$cache_detector = new CacheDetector;

// Provide options for the detector, see each detector for details.
$cache_detector->setConfig('Redis', array('port' => 1234));

$detectors = $cache_detector->getSupportedDetectors();

if (isset($detectors['Apc'])) {
    $cache = $detectors['Apc']->getCache();
}

?>
```

## Todo

 * Add support for MongoDB, Couchbase and Riak.
 * Add feature detection to select the best available driver.

## Author

 * [Sébastien Lavoie](http://blog.lavoie.sl/)
 * [WeMakeCustom](http://www.wemakecustom.com/)