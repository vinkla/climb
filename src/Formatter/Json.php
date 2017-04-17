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
 * This is the JSON formatter.
 *
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */
class Json implements Format
{
    /**
     * {@inheritdoc}
     */
    public function render(OutputStyle $output, array $outdated = [], array $upgradable = [])
    {
        $packages = [];

        if (count($outdated)) {
            $packages['outdated'] = $this->formatPackages($outdated);
        }

        if (count($upgradable)) {
            $packages['upgradable'] = $this->formatPackages($upgradable);
        }

        $output->writeln(count($packages) ? json_encode($packages) : '{}');
    }

    /**
     * Format packages information.
     *
     * @param array $packages
     *
     * @return array
     */
    private function formatPackages(array $packages)
    {
        return array_map(
            function (array $package) {
                return [
                    $package[0] => [
                        'current' => $package[1],
                        'latest' => $package[2],
                    ],
                ];
            },
            $packages
        );
    }
}
