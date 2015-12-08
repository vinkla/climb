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
use Vinkla\Climb\OutputStyle;

/**
 * This is the update command class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 * @author Joseph Cohen <joe@alt-three.com>
 */
final class UpdateCommand extends Command
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
        $this->addOption('directory', null, InputOption::VALUE_REQUIRED, 'Composer files directory');
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
        $io = new OutputStyle($input, $output);

        $composerPath = $this->getComposerPathFromInput($input);

        $ladder = new Ladder($composerPath);

        $packages = $ladder->getOutdatedPackages();

        if (!count($packages)) {
            $io->writeln('All dependencies match the latest package versions <green>:)</green>');
            $io->newLine();

            return 1;
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
            $io->write('<comment>Nothing to install or update, did you forget the flag --all?</comment>');
            $io->newLine();

            return 1;
        }

        foreach ($upgradable as $package => $version) {
            $this->command .= " {$package}=^$version";
        }

        $command = $input->getOption('global') ? 'composer global require' : 'composer require';

        $process = new Process($command, $composerPath, array_merge($_SERVER, $_ENV), null, null);

        $io->newLine();

        $process->run(function ($type, $line) use ($io) {
            $io->write($line);
        });

        return 0;
    }
}
