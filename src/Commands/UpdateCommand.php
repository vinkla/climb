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

        $io->newLine();

        if (!count($packages)) {
            $io->write('All dependencies match the latest package versions <fg=green>:)</>');
            $io->newLine();

            return 1;
        }

        $outdated = [];
        $upgradable = [];

        foreach ($packages as $package) {
            if ($package->isUpgradable()) {
                $upgradable[$package->getName()] = $package;
            } else {
                $outdated[$package->getName()] = $package;
            }
        }

        if ($input->getOption('all')) {
            $upgradable = array_merge($upgradable, $outdated);
        }

        if (empty($upgradable)) {
            $io->warning('Nothing to install or update, did you forget the flag --all?');

            return 1;
        }

        foreach ($upgradable as $package) {
            $command = $input->getOption('global') ? 'composer global require' : 'composer require';

            $command .= sprintf(' %s=^%s', $package->getName(), $package->getLatestVersion());

            if ($package->getDevDependency()) {
                $command .= ' --dev';
            }

            $process = new Process($command, $composerPath, array_merge($_SERVER, $_ENV), null, null);

            $process->run(function ($type, $line) use ($io) {
                $io->write($line);
            });

            $io->newLine();
        }

        return 0;
    }
}
