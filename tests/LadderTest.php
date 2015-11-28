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

use Vinkla\Climb\Ladder;

/**
 * This is the ladder test class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 */
class LadderTest extends AbstractTestCase
{
    public function testGetOutdatedPackages()
    {
        $ladder = new Ladder(__DIR__.'/stubs');
        $packages = $ladder->getOutdatedPackages();
        $this->assertTrue(is_array($packages));
    }
}
