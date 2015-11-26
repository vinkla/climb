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
    protected function getFileContents($file)
    {
        $composer = new Composer(getcwd());
        $rc = new ReflectionClass(Composer::class);
        $method = $rc->getMethod('getFileContents');
        $method->setAccessible(true);
        return $method->invokeArgs($composer, [$file]);
    }

    public function testGetFileContents()
    {
        $json = $this->getFileContents('composer.json');
        $this->assertSame('vinkla/climb', $json['name']);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFileNotFoundException()
    {
        $this->getFileContents('marty.mcfly');
    }
}
