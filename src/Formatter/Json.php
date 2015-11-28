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

use Symfony\Component\Console\Output\OutputInterface;

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
    public function render(OutputInterface $output, array $outdated = [], array $upgradable = [])
    {
        $output->writeln(json_encode([
            'outdated' => $this->formatPackages($outdated),
            'upgradable' => $this->formatPackages($upgradable),
        ]));
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
                    'current' => $package[1],
                    'latest' => $package[2],
                ];
            },
            $packages
        );
    }
}
