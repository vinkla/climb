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

use Symfony\Component\Console\Command\Command;

/**
 * This is the global command class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 * @author Jens Segers <hello@jenssegers.com>
 */
class GlobalCommand extends OutdatedCommand
{
    /**
     * Command configuration.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('global');
        $this->setDescription('Find newer versions of dependencies than what your global composer.json allows');

        $this->ladder = new Ladder(getenv('HOME').'/.composer');
    }
}
