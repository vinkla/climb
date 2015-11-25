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

use Mockery;
use Packagist\Api\Result\Package;
use Packagist\Api\Result\Package\Version;
use Vinkla\Climb\Packagist;

/**
 * This is the Packagist test class.
 *
 * @author Mikael Mattsson <mikael@weblyan.se>
 */
class PackagistTest extends AbstractTestCase
{
    public function testGetLatestVersion()
    {
        $v1 = new Version();
        $v1->fromArray(['version' => '1.0.0']);
        $v2 = new Version();
        $v2->fromArray(['version' => '0.3.0']);
        $package = new Package();
        $package->fromArray(['name' => 'vinkla/climb', 'versions' => ['1.0.0' => $v1, '0.3.0' => $v2]]);
        $packagist = Mockery::mock(Packagist::class)->makePartial();
        $packagist->shouldReceive('get')->with('vinkla/climb')->andReturn($package);
        $this->assertSame('1.0.0', $packagist->getLatestVersion('vinkla/climb'));
    }
}
