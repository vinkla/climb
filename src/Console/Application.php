<?php

namespace Vinkla\Climb\Console;

use Symfony\Component\Console\Application as Console;

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
