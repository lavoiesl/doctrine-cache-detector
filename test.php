<?php

require 'vendor/autoload.php';

use Lavoiesl\Doctrine\CacheDetector\CacheChooser;
use Lavoiesl\Doctrine\CacheDetector\Detector\ApcDetector;

ApcDetector::createAPCuAliases();

$chooser = new CacheChooser;

print_r($chooser->selectBest());
