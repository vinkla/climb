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
use Vinkla\Climb\Ladder;
use Vinkla\Climb\OutputStyle;

/**
 * This is the outdated command class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 * @author Jens Segers <hello@jenssegers.com>
 */
final class OutdatedCommand extends Command
{
    /**
     * Configure the outdated command.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('outdated');
        $this->setDescription('Find newer versions of dependencies than what your composer.json allows');
        $this->addOption('directory', null, InputOption::VALUE_REQUIRED, 'Composer files directory');
        $this->addOption('global', 'g', InputOption::VALUE_NONE, 'Run on globally installed packages');
        $this->addOption('outdated', null, InputOption::VALUE_NONE, 'Only check outdated dependencies');
        $this->addOption('upgradable', null, InputOption::VALUE_NONE, 'Only check upgradable dependencies');
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

        $statusCode = 0;

        if (!count($packages)) {
            $io->writeln('All dependencies match the latest package versions <info>:)</info>');
            $io->newLine();

            return $statusCode;
        }

        $outdated = [];
        $upgradable = [];

        foreach ($packages as $package) {
            $diff = $io->versionDiff($package->getVersion(), $package->getLatestVersion());

            if ($package->isUpgradable()) {
                $upgradable[] = [$package->getName(), $package->getVersion(), '→', $diff];
            } else {
                $outdated[] = [$package->getName(), $package->getVersion(), '→', $diff];
            }
        }

        if ($outdated && !$input->getOption('upgradable')) {
            $statusCode = 1;

            $io->columns($outdated);
            $io->newLine();
        }

        if ($upgradable && !$input->getOption('outdated')) {
            $statusCode = 1;

            $io->writeln('The following dependencies are satisfied by their declared version constraint, but the installed versions are behind. You can install the latest versions without modifying your composer.json file by using <fg=blue>composer update</>.');
            $io->newLine();
            $io->columns($upgradable);
            $io->newLine();
        }

        return $statusCode;
    }
}
