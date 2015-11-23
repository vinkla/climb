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

use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Vinkla\Climb\OutputStyle;

/**
 * This is the command class.
 *
 * @author Vincent Klaiber <vincent@schimpanz.com>
 */
class Command extends BaseCommand
{
    /**
     * Run the command.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @throws \Exception
     *
     * @return int|null|void
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        $output = new OutputStyle($input, $output);

        parent::run($input, $output);
    }
}
