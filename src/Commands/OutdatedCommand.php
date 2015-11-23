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
use Vinkla\Climb\Version;

/**
 * This is the outdated command class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 * @author Jens Segers <hello@jenssegers.com>
 */
class OutdatedCommand extends Command
{
    /**
     * The Ladder instance.
     *
     * @var \Vinkla\Climb\Ladder
     */
    protected $ladder;

    /**
     * Configure the outdated command.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('outdated');
        $this->setDescription('Find newer versions of dependencies than what your composer.json allows');
        $this->addOption('no-outdated', null, InputOption::VALUE_NONE, 'Do not check outdated dependencies');
        $this->addOption('no-upgradable', null, InputOption::VALUE_NONE, 'Do not check upgradable dependencies');
        $this->addOption('fail', null, InputOption::VALUE_NONE, 'Fail when outdated and/or upgradable');

        $this->ladder = new Ladder();
    }

    /**
     * Execute the command.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return mixed
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $packages = $this->ladder->getOutdatedPackages();

        $output->newLine();

        if (!count($packages)) {
            $output->writeln('All dependencies match the latest package versions <info>:)</info>');

            return 0;
        }

        $outdated = [];
        $upgradable = [];

        foreach ($packages as $package) {
            $diff = Version::diff($package->getVersion(), $package->getLatestVersion());

            if ($package->isUpgradable() && !$input->getOption('no-upgradable')) {
                $upgradable[] = [$package->getName(), $package->getVersion(), '→', $diff];
            } elseif (!$input->getOption('no-outdated')) {
                $outdated[] = [$package->getName(), $package->getVersion(), '→', $diff];
            }
        }

        if ($outdated) {
            $output->columns($outdated);
        }

        if ($upgradable) {
            $output->writeln('The following dependencies are satisfied by their declared version constraint, but the installed versions are behind. You can install the latest versions without modifying your composer.json file by using \'composer update\'.');
            $output->columns($upgradable);
        }

        if ($input->getOption('fail') && ($outdated || $upgradable)) {
            return 1;
        }

        return 0;
    }
}
