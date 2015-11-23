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
 * This is the global outdated command class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 * @author Jens Segers <hello@jenssegers.com>
 */
class GlobalOutdatedCommand extends OutdatedCommand
{
    /**
     * Configure the global command.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('global');
        $this->setDescription('Find newer versions of dependencies than what your global composer.json allows');
        $this->addOption('no-outdated', null, InputOption::VALUE_NONE, 'Do not check outdated dependencies');
        $this->addOption('no-upgradable', null, InputOption::VALUE_NONE, 'Do not check upgradable dependencies');
        $this->addOption('fail', null, InputOption::VALUE_NONE, 'Fail when outdated and/or upgradable');

        $this->ladder = new Ladder(getenv('HOME').'/.composer');
    }
}
