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
use Symfony\Component\Console\Output\OutputInterface;
use Vinkla\Climb\Ladder;
use Vinkla\Climb\Version;

/**
 * This is the outdated command class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 * @author Jens Segers <hello@jenssegers.com>
 */
class OutdatedCommand extends AbstractCommand
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

        if (!count($packages)) {
            $output->writeln('All dependencies match the latest package versions <info>:)</info>');

            return;
        }

        $outdated = [];
        $upgradable = [];

        foreach ($packages as $name => list($constraint, $version, $latest)) {
            if (Version::satisfies($latest, $constraint)) {
                $latest = $this->diff($version, $latest);
                $upgradable[] = [$name, $version, '→', $latest];
            } else {
                $latest = $this->diff($version, $latest);
                $outdated[] = [$name, $version, '→', $latest];
            }
        }

        $output->newLine();

        if ($outdated) {
            $output->columns($outdated);
        }

        if ($upgradable) {
            $output->writeln('The following dependencies are satisfied by their declared version constraint, but the installed versions are behind. You can install the latest versions without modifying your composer.json file by using \'composer update\'.');
            $output->columns($upgradable);
        }
    }

    /**
     * Get the diff between the current and latest version.
     *
     * @param string $current
     * @param string $latest
     *
     * @return string
     */
    private function diff($current, $latest)
    {
        $needle = 0;

        while ($needle < strlen($current) && $needle < strlen($latest)) {
            if ($current[$needle] !== $latest[$needle]) {
                break;
            }

            $needle++;
        }

        return substr($latest, 0, $needle).'<green>'.substr($latest, $needle).'</green>';
    }
}
