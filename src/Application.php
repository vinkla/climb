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

/**
 * This is the application class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 */
class Application extends Console
{
    /**
     * The version number.
     *
     * @var string
     */
    const VERSION = '1.0@dev';

    /**
     * Create a new application instance.
     */
    public function __construct()
    {
        parent::__construct('Climb', self::VERSION);

        $this->add(new OutdatedCommand());

        $this->setDefaultCommand('outdated');
    }
}
