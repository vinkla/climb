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

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Vinkla\Climb\Ladder;

/**
 * This is the update command class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 * @author Joseph Cohen <joe@alt-three.com>
 */
class UpdateCommand extends Command
{
    /**
     * The composer command to run.
     *
     * @return string
     */
    protected $command = 'composer require';

    /**
     * Configure the update command.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('update');
        $this->setDescription('Update composer.json dependencies versions');
        $this->addOption('all', null, InputOption::VALUE_NONE, 'Run update on breaking versions');
        $this->addOption('global', 'g', InputOption::VALUE_NONE, 'Run on globally installed packages');
    }

    /**
     * Execute the command.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $ladder = new Ladder($input->getOption('global') ? getenv('HOME').'/.composer' : null);

        $packages = $ladder->getOutdatedPackages();

        if (!$packages) {
            $output->writeln('All dependencies match the latest package versions <green>:)</green>');
            $output->newLine();

            return 0;
        }

        $outdated = [];
        $upgradable = [];

        foreach ($packages as $package) {
            if ($package->isUpgradable()) {
                $upgradable[$package->getName()] = $package->getLatestVersion();
            } else {
                $outdated[$package->getName()] = $package->getLatestVersion();
            }
        }

        if ($input->getOption('all')) {
            $upgradable = array_merge($upgradable, $outdated);
        }

        if (empty($upgradable)) {
            $output->write('<comment>Nothing to install or update, did you forget the flag --all?</comment>');
            $output->newLine();

            return 0;
        }

        foreach ($upgradable as $package => $version) {
            $this->command .= " {$package}=^$version";
        }

        $output->newLine();

        $command = $input->getOption('global') ? 'composer global require' : 'composer require';

        $process = new Process($command, null, array_merge($_SERVER, $_ENV), null, null);

        $process->run(function ($type, $line) use ($output) {
            $output->write($line);
        });

        return 0;
    }
}
