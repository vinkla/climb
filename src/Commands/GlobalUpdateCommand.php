<?php

/*
 * This file is part of Climb.
 *
 * (c) Vincent Klaiber <hello@vinkla.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vinkla\Climb\Commands;

use Symfony\Component\Console\Input\InputOption;
use Vinkla\Climb\Ladder;

/**
 * This is the global update command class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 * @author Joseph Cohen <joe@alt-three.com>
 */
class GlobalUpdateCommand extends UpdateCommand
{
    /**
     * The Composer command to run.
     *
     * @return string
     */
    protected $command = 'composer global require';

    /**
     * Configure the global update command.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('global-update');
        $this->setDescription('Update global composer.json dependencies versions');
        $this->addOption('all', null, InputOption::VALUE_NONE, 'Run update on breaking versions');

        $this->ladder = new Ladder(getenv('HOME').'/.composer');
    }
}
