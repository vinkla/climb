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

use DOMDocument;
use Vinkla\Climb\OutputStyle;

/**
 * This is the XML formatter.
 *
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */
class Xml implements Format
{
    /**
     * {@inheritdoc}
     */
    public function render(OutputStyle $output, array $outdated = [], array $upgradable = [])
    {
        $dom = new DOMDocument('1.0', 'UTF-8');

        $climb = $dom->createElement('climb');

        if (count($outdated)) {
            $outdatedElement = $dom->createElement('outdated');
            foreach ($this->formatPackages($outdated, $dom) as $package) {
                $outdatedElement->appendChild($package);
            }
            $climb->appendChild($outdatedElement);
        }

        if (count($upgradable)) {
            $upgradableElement = $dom->createElement('upgradable');
            foreach ($this->formatPackages($upgradable, $dom) as $package) {
                $upgradableElement->appendChild($package);
            }
            $climb->appendChild($upgradableElement);
        }

        $dom->appendChild($climb);

        $output->writeln(rtrim($dom->saveXML(), "\n"));
    }

    /**
     * Format packages information.
     *
     * @param array $packages
     * @param \DOMDocument $dom
     *
     * @return array
     */
    private function formatPackages(array $packages, DOMDocument $dom)
    {
        return array_map(
            function (array $package) use ($dom) {
                $pack = $dom->createElement('package');

                $pack->setAttribute('name', $package[0]);
                $pack->setAttribute('current', $package[1]);
                $pack->setAttribute('latest', $package[2]);

                return $pack;
            },
            $packages
        );
    }
}
