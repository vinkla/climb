<?php

/*
 * This file is part of Climb.
 *
 * (c) Vincent Klaiber <hello@vinkla.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vinkla\Climb;

use Symfony\Component\Console\Application as Console;
use Vinkla\Climb\Commands\GlobalOutdatedCommand;
use Vinkla\Climb\Commands\GlobalUpdateCommand;
use Vinkla\Climb\Commands\OutdatedCommand;
use Vinkla\Climb\Commands\UpdateCommand;

/**
 * This is the application class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 */
class Application extends Console
{
    /**
     * Create a new application instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct('Climb', '0.7.0');

        $this->add(new GlobalOutdatedCommand());
        $this->add(new GlobalUpdateCommand());
        $this->add(new OutdatedCommand());
        $this->add(new UpdateCommand());

        $this->setDefaultCommand('outdated');
    }
}
