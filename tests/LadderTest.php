<?php

namespace Vinkla\Tests\Climb;

use Mockery;

class LadderTest extends AbstractTestCase
{
    public function testDetectsOutdated()
    {
        $ladder = Mockery::mock('Vinkla\Climb\Ladder[getInstalledPackages,getRequiredPackages]');

        $ladder->shouldReceive('getInstalledPackages')->andReturn([
            'monolog/monolog' => '1.5.0',
        ]);

        $ladder->shouldReceive('getRequiredPackages')->andReturn([
            'monolog/monolog' => '^1.0.0',
        ]);

        $outdated = $ladder->getOutdatedPackages();

        $this->assertArrayHasKey('monolog/monolog', $outdated);

        $latest = $ladder->getLatestVersion('monolog/monolog');

        $this->assertEquals('1.5.0', $outdated['monolog/monolog'][0]);
        $this->assertEquals($latest, $outdated['monolog/monolog'][1]);
    }

    public function testSkipsNonOutdated()
    {
        $ladder = Mockery::mock('Vinkla\Climb\Ladder[getInstalledPackages,getRequiredPackages]');

        $latest = $ladder->getLatestVersion('monolog/monolog');

        $ladder->shouldReceive('getInstalledPackages')->andReturn([
            'monolog/monolog' => $latest,
        ]);

        $ladder->shouldReceive('getRequiredPackages')->andReturn([
            'monolog/monolog' => '^1.0.0',
        ]);

        $outdated = $ladder->getOutdatedPackages();

        $this->assertArrayNotHasKey('monolog/monolog', $outdated);
    }
}
