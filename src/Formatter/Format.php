<?php

/*
 * This file is part of Climb.
 *
 * (c) Vincent Klaiber <hello@vinkla.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vinkla\Climb\Formatter;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Climb formatter interface.
 *
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */
interface Format
{
    /**
     * Produce final output.
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param array $outdated
     * @param array $upgradable
     */
    public function render(OutputInterface $output, array $outdated = [], array $upgradable = []);
}
