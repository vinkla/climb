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
use Symfony\Component\Console\Application as Console;
use Vinkla\Climb\Application;

/**
 * This is the application test class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 */
class ApplicationTest extends AbstractTestCase
{
    public function testApplication()
    {
        $application = new Application();
        $this->assertInstanceOf(Console::class, $application);
        $this->assertSame('Climb', $application->getName());
    }

    public function testDefaultCommand()
    {
        $application = new Application();
        $rc = new ReflectionClass(Console::class);
        $property = $rc->getProperty('defaultCommand');
        $property->setAccessible(true);
        $this->assertSame('outdated', $property->getValue($application));
    }
}
