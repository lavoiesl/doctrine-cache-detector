<?php

namespace Lavoiesl\Doctrine\CacheDetector\Test;

use Lavoiesl\Doctrine\CacheDetector\CacheDetector;

class CacheChooserTest extends \PHPUnit_Framework_TestCase
{
    private $detector;

    public function setUp()
    {
        $this->detector = new CacheDetector;
    }

    public function testSelectBest()
    {
        $this->assertInstanceOf('Lavoiesl\Doctrine\CacheDetector\Detector\AbstractDetector', $this->detector->selectBest());
    }

    /**
     * @dataProvider getProviders
     */
    public function testDetector($detector)
    {
        if (!$detector->isSupported()) {
            $this->markTestSkipped($detector->getType() . ' is not supported');
            return;
        }

        if (!$detector->isAvailable()) {
            $this->markTestSkipped($detector->getType() . ' is not available');
            return;
        }

        $cache = $detector->getCache();
        $this->assertInstanceOf('Doctrine\Common\Cache\CacheProvider', $cache);
        $this->assertTrue($cache->save('foo', 'bar'));
        $this->assertEquals('bar', $cache->fetch('foo'));
    }

    public function getProviders()
    {
        $providers = array();
        $cacheDetector = new CacheDetector;

        foreach ($cacheDetector->getAllDetectors() as $detector) {
            $providers[] = array($detector);
        }

        return $providers;
    }
}
