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
use Vinkla\Climb\Packagist;

/**
 * This is the Packagist test class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 */
class PackagistTest extends AbstractTestCase
{
    public function testGetLatestVersion()
    {
        $packagist = Mockery::mock(Packagist::class);

        $packagist->shouldReceive('getLatestVersion')
            ->with('vinkla/climb')
            ->andReturn('1.0.0');

        $this->assertEquals('1.0.0', $packagist->getLatestVersion('vinkla/climb'));
    }
}
