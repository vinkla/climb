<?php

/*
 * This file is part of Climb.
 *
 * (c) Vincent Klaiber <hello@vinkla.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vinkla\Tests\Climb;

use Vinkla\Climb\Package;

use Mockery;

/**
 * This is the package test class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 */
class PackageTest extends AbstractTestCase
{
    public function testName()
    {
        $package = new Package('vinkla/climb', '1.0.0', '^1.0.0');
        $this->assertEquals('vinkla/climb', $package->getName());
    }

    public function testVersion()
    {
        $package = new Package('vinkla/climb', '1.0.0', '^1.0.0');
        $this->assertEquals('1.0.0', $package->getVersion());
    }

    public function testPrettyVersion()
    {
        $package = new Package('vinkla/climb', '1.0.0', '^1.0.0');
        $this->assertEquals('^1.0.0', $package->getPrettyVersion());
    }

    public function testLatestVersion()
    {
        $package = Mockery::mock(Package::class);
        $package->shouldReceive('getLatestVersion')->andReturn('2.0.0');
        $this->assertEquals('2.0.0', $package->getLatestVersion());
    }
}
