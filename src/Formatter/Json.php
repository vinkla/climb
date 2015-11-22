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
 * This is the JSON formatter.
 *
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */
class Json implements Format
{
    /**
     * {@inheritdoc}
     */
    public function render(CLImate $climate, array $outdated, array $upgradable)
    {
        $output = [
            'outdated' => [],
            'upgradable' => [],
        ];

        if ($outdated || $upgradable) {
            if ($outdated) {
                $output['outdated'] = $this->formatPackage($outdated);
            }

            if ($upgradable) {
                $output['upgradable'] = $this->formatPackage($upgradable);
            }
        }

        $climate->write(json_encode($output));
    }

    /**
     * Format package information.
     *
     * @param array $packages
     */
    private function formatPackage(array $packages)
    {
        $output = [];

        foreach ($packages as $package) {
            $output[] = [
                $package[0] => [
                    'current' => $package[1],
                    'update' => $package[2],
                ],
            ];
        }

        return $output;
    }
}
