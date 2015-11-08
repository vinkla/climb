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

use Symfony\Component\Console\Application as Console;
use Vinkla\Climb\Console\Application;

/**
 * This is the application test class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 */
class ApplicationTest extends AbstractTestCase
{
    public function testApplication()
    {
        $app = new Application();

        $this->assertInstanceOf(Console::class, $app);
    }
}
