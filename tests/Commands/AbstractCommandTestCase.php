<?php

/*
 * This file is part of Climb.
 *
 * (c) Vincent Klaiber <hello@vinkla.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vinkla\Tests\Climb\Commands;

use ReflectionClass;
use Vinkla\Climb\Commands\Command;
use Vinkla\Tests\Climb\AbstractTestCase;

/**
 * This is the abstract command test case class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 */
abstract class AbstractCommandTestCase extends AbstractTestCase
{
    public function testClassIsFinal()
    {
        $command = new ReflectionClass($this->getCommand());
        $this->assertTrue($command->isFinal());
    }

    public function testConfiguration()
    {
        $this->assertNotEmpty($this->getCommand()->getName());
        $this->assertNotEmpty($this->getCommand()->getDescription());
    }

    public function testParent()
    {
        $this->assertInstanceOf(Command::class, $this->getCommand());
    }

    /**
     * @return \Vinkla\Climb\Commands\Command
     */
    abstract public function getCommand();
}
