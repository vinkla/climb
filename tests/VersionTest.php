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

use Vinkla\Climb\Version;

/**
 * This is the version test class.
 *
 * @author Jens Segers <hello@jenssegers.com>
 */
class VersionTest extends AbstractTestCase
{
    public function testNormalize()
    {
        $versions = [
            'v2.0.1' => '2.0.1',
            '3.1' => '3.1.0',
            '3.1.5' => '3.1.5',
        ];

        foreach ($versions as $version => $expected) {
            $this->assertSame($expected, Version::normalize($version));
        }
    }

    public function testLatest()
    {
        $versions = ['v2.0.1', '3.1', '3.1.5', '3.2.0-dev'];

        $this->assertSame('3.1.5', Version::latest($versions));
    }
}
