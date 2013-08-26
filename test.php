<?php

require 'vendor/autoload.php';

use Lavoiesl\Doctrine\CacheDetector\CacheDetector;
use Lavoiesl\Doctrine\CacheDetector\Detector\ApcDetector;

ApcDetector::createAPCuAliases();

$detector = new CacheDetector;

print_r($detector->getSupportedDetectors());
