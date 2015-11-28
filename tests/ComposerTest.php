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
        $composer = new Composer(__DIR__.'/stubs');
        $rc = new ReflectionClass(Composer::class);
        $method = $rc->getMethod('getFileContents');
        $method->setAccessible(true);

        return $method->invokeArgs($composer, [$file]);
    }

    public function testGetFileContents()
    {
        $data = $this->getFileContents('composer.json');
        $this->assertSame('vinkla/climb', $data['name']);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFileNotFoundException()
    {
        $this->getFileContents('marty.mcfly');
    }

    public function testGetInstalledPackages()
    {
        $composer = new Composer(__DIR__.'/stubs');
        $packages = $composer->getInstalledPackages();
        $this->assertArrayHasKey('composer/semver', $packages);
    }

    /**
     * @expectedException \LogicException
     */
    public function testGetInstalledPackagesException()
    {
        $composer = new Composer(__DIR__);
        $composer->getInstalledPackages();
    }

    public function testGetRequiredPackages()
    {
        $composer = new Composer(__DIR__.'/stubs');
        $packages = $composer->getRequiredPackages();
        $this->assertArrayHasKey('composer/semver', $packages);
    }

    /**
     * @expectedException \LogicException
     */
    public function testGetRequiredPackagesException()
    {
        $composer = new Composer(__DIR__);
        $composer->getRequiredPackages();
    }
}
