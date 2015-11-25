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

use Vinkla\Tests\Climb\AbstractTestCase;

/**
 * This is the abstract command test case class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 */
abstract class AbstractCommandTestCase extends AbstractTestCase
{
    public function testConfiguration()
    {
        $this->assertNotEmpty($this->getCommand()->getName());
        $this->assertNotEmpty($this->getCommand()->getDescription());
    }

    /**
     * @return \Vinkla\Climb\Commands\Command
     */
    abstract public function getCommand();
}
