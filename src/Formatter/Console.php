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

use League\CLImate\CLImate;

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
    public function render(CLImate $climate, array $outdated, array $upgradable)
    {
        $climate->br();

        if (!$outdated && !$upgradable) {
            $climate->write('All dependencies match the latest package versions <green>:)</green>')->br();

            return;
        }

        if ($outdated) {
            $outdated = array_map([$this, 'diff'], $outdated);

            $climate->columns($outdated, 3)->br();
        }

        if ($upgradable) {
            $upgradable = array_map([$this, 'diff'], $upgradable);

            $climate->write('The following dependencies are satisfied by their declared version constraint, but the installed versions are behind. You can install the latest versions without modifying your composer.json file by using \'composer update\'.')->br();

            $climate->columns($upgradable, 3)->br();
        }
    }

    /**
     * Get the diff between the current and latest version.
     *
     * @param array $package
     *
     * @return array
     */
    private function diff(array $package)
    {
        $current = $package[1];
        $latest = $package[2];

        $needle = 0;

        while ($needle < strlen($current) && $needle < strlen($latest)) {
            if ($current[$needle] !== $latest[$needle]) {
                break;
            }

            $needle++;
        }

        $package[2] = '→';
        $package[3] = substr($latest, 0, $needle).'<green>'.substr($latest, $needle).'</green>';

        return $package;
    }
}
