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

use ReflectionClass;
use Vinkla\Climb\Composer;

/**
 * This is the composer test class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 */
class ComposerTest extends AbstractTestCase
{
    public function testGetFileContents()
    {
        $composer = new Composer(getcwd());
        $rc = new ReflectionClass(Composer::class);
        $method = $rc->getMethod('getFileContents');
        $method->setAccessible(true);
        $json = $method->invokeArgs($composer, ['composer.json']);
        $this->assertEquals('vinkla/climb', $json['name']);
    }
}
