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

use Exception;
use League\CLImate\CLImate;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Vinkla\Climb\OutputStyle;

/**
 * This is the abstract command class.
 *
 * @author Vincent Klaiber <vincent@schimpanz.com>
 */
abstract class AbstractCommand extends Command
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
    public function run(InputInterface $input, OutputInterface $output) {
        try {
            $output = new OutputStyle($input, $output);

            parent::run($input, $output);
        } catch(Exception $exception) {
            $output->writeln(sprintf('<error>%s</error>', $exception->getMessage()));
        }
    }
}
