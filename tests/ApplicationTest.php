<?php

namespace Vinkla\Tests\Climb;

use Symfony\Component\Console\Application as Console;
use Vinkla\Climb\Console\Application;

class ApplicationTest extends AbstractTestCase
{
    public function testApplication()
    {
        $app = new Application();

        $this->assertInstanceOf(Console::class, $app);
    }
}
