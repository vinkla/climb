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

/**
 * This is the outdated command class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 * @author Jens Segers <hello@jenssegers.com>
 */
class OutdatedCommand extends Command
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
        $this->addOption('no-outdated', null, InputOption::VALUE_NONE, 'Do not check outdated dependencies');
        $this->addOption('no-upgradable', null, InputOption::VALUE_NONE, 'Do not check upgradable dependencies');
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
        $composerPath = $this->getComposerPathFromInput($input);

        $ladder = new Ladder($composerPath);

        $packages = $ladder->getOutdatedPackages();

        $output->newLine();

        if (!count($packages)) {
            $output->writeln('All dependencies match the latest package versions <info>:)</info>');
            $output->newLine();

            return 0;
        }

        $outdated = [];
        $upgradable = [];

        foreach ($packages as $package) {
            $diff = $output->versionDiff($package->getVersion(), $package->getLatestVersion());

            if ($package->isUpgradable()) {
                if (!$input->getOption('no-upgradable')) {
                    $upgradable[] = [$package->getName(), $package->getVersion(), '→', $diff];
                }
            } elseif (!$input->getOption('no-outdated')) {
                $outdated[] = [$package->getName(), $package->getVersion(), '→', $diff];
            }
        }

        if ($outdated) {
            $output->columns($outdated);
        }

        if ($upgradable) {
            $output->writeln('The following dependencies are satisfied by their declared version constraint, but the installed versions are behind. You can install the latest versions without modifying your composer.json file by using <fg=blue>composer update</>.');
            $output->newLine();
            $output->columns($upgradable);
        }

        if ($outdated || $upgradable) {
            return 1;
        }

        return 0;
    }
}
