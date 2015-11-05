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

/**
 * This is the ladder test class.
 *
 * @author Jens Segers <hello@jenssegers.com>
 */
class LadderTest extends AbstractTestCase
{
    public function testDetectsOutdated()
    {
        $ladder = Mockery::mock('Vinkla\Climb\Ladder[getInstalledPackages,getRequiredPackages,getLatestVersion]');

        $ladder->shouldReceive('getInstalledPackages')->andReturn([
            'vinkla/climb' => '1.5.0',
        ]);

        $ladder->shouldReceive('getRequiredPackages')->andReturn([
            'vinkla/climb' => '^1.0.0',
        ]);

        $ladder->shouldReceive('getLatestVersion')->with('vinkla/climb')->andReturn('1.6.1');

        $outdated = $ladder->getOutdatedPackages();

        $this->assertArrayHasKey('vinkla/climb', $outdated);
        $this->assertEquals('^1.0.0', $outdated['vinkla/climb'][0]);
        $this->assertEquals('1.5.0', $outdated['vinkla/climb'][1]);
        $this->assertEquals('1.6.1', $outdated['vinkla/climb'][2]);
    }

    public function testSkipsNonOutdated()
    {
        $ladder = Mockery::mock('Vinkla\Climb\Ladder[getInstalledPackages,getRequiredPackages,getLatestVersion]');

        $ladder->shouldReceive('getInstalledPackages')->andReturn([
            'vinkla/climb' => '1.5.0',
        ]);

        $ladder->shouldReceive('getRequiredPackages')->andReturn([
            'vinkla/climb' => '^1.0.0',
        ]);

        $ladder->shouldReceive('getLatestVersion')->with('vinkla/climb')->andReturn('1.5.0');

        $outdated = $ladder->getOutdatedPackages();

        $this->assertArrayNotHasKey('vinkla/climb', $outdated);
    }

    public function testSupportsTags()
    {
        $ladder = Mockery::mock('Vinkla\Climb\Ladder[getInstalledPackages,getRequiredPackages,getLatestVersion]');

        $ladder->shouldReceive('getInstalledPackages')->andReturn([
            'vinkla/climb' => '2.0.0-beta',
        ]);

        $ladder->shouldReceive('getRequiredPackages')->andReturn([
            'vinkla/climb' => '^2.0.0',
        ]);

        $ladder->shouldReceive('getLatestVersion')->with('vinkla/climb')->andReturn('2.0.0-rc');

        $outdated = $ladder->getOutdatedPackages();

        $this->assertArrayHasKey('vinkla/climb', $outdated);
        $this->assertEquals('^2.0.0', $outdated['vinkla/climb'][0]);
        $this->assertEquals('2.0.0-beta', $outdated['vinkla/climb'][1]);
        $this->assertEquals('2.0.0-rc', $outdated['vinkla/climb'][2]);
    }
}
