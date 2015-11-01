<?php

/*
 * This file is part of Climb.
 *
 * (c) Vincent Klaiber <hello@vinkla.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vinkla\Climb;

use League\CLImate\CLImate;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This is the outdated command class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 */
class OutdatedCommand extends Command
{
    /**
     * Create a new outdated command instance.
     */
    public function __construct()
    {
        parent::__construct('outdated');

        $this->setDescription('Find newer versions of dependencies than what your composer.json allows');
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
        $climate = new CLImate();
        $ladder = new Ladder();

        try {
            $packages = $ladder->getOutdatedPackages();

            if (count($packages) <= 0) {
                return $climate->br()->line('All dependencies match the latest package versions <green>:)</green>')->br();
            }

            $lines = [];
            foreach ($packages as $name => list($version, $latest)) {
                $latest = $this->diff($version, $latest);
                $lines[] = [$name, $version, '→', $latest];
            }

            return $climate->br()->columns($lines, 3)->br();
        } catch (ClimbException $exception) {
            return $climate->br()->error($exception->getMessage())->br();
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
        $current = str_split($current);
        $latest = str_split($latest);

        $version = '';
        $new = false;

        foreach ($current as $i => $character) {
            if (!isset($latest[$i])) {
                break;
            }

            if ($character !== $latest[$i]) {
                $new = true;
            }

            $version .= $new ? '<green>'.$latest[$i].'</green>' : $latest[$i];
        }

        return $version;
    }
}
