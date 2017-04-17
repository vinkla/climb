<?php

/*
 * This file is part of Climb.
 *
 * (c) Vincent Klaiber <hello@vinkla.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vinkla\Climb\Formatter;

use Vinkla\Climb\OutputStyle;

/**
 * This is the console formatter.
 *
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */
class Console implements Format
{
    /**
     * {@inheritdoc}
     */
    public function render(OutputStyle $output, array $outdated = [], array $upgradable = [])
    {
        $output->newLine();

        if (!count($outdated) && !count($upgradable)) {
            $output->writeln('All dependencies match the latest package versions <fg=green>:)</>');
            $output->newLine();

            return;
        }

        if (count($outdated)) {
            $output->columns($this->formatPackages($outdated, $output));
            $output->newLine();
        }

        if (count($upgradable)) {
            $output->writeln('The following dependencies are satisfied by their declared version constraint, but the installed versions are behind. You can install the latest versions without modifying your composer.json file by using <fg=blue>composer update</>.');
            $output->newLine();
            $output->columns($this->formatPackages($upgradable, $output));
            $output->newLine();
        }
    }

    /**
     * Format packages information.
     *
     * @param array $packages
     * @param Vinkla\Climb\OutputStyle $output
     *
     * @return array
     */
    private function formatPackages(array $packages, OutputStyle $output)
    {
        return array_map(
            function (array $package) use ($output) {
                $package[3] = $output->versionDiff($package[1], $package[2]);
                $package[2] = '→';

                return $package;
            },
            $packages
        );
    }
}
