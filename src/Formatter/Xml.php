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
use DOMElement;
use League\CLImate\CLImate;

/**
 * This is the XML formatter.
 *
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */
class Xml implements Format
{
    /**
     * @inheritDoc
     */
    public function render(CLImate $climate, array $outdated, array $upgradable)
    {
        $dom = new DOMDocument('1.0', 'UTF-8');

        $climb = $dom->createElement('climb');

        $outdatedElement = $dom->createElement('outdated');
        if (count($outdated)) {
            $outdatedElement = $this->formatPackage($dom, $outdatedElement, $outdated);
        }
        $climb->appendChild($outdatedElement);

        $upgradableElement = $dom->createElement('upgradable');
        if (count($upgradable)) {
            $upgradableElement = $this->formatPackage($dom, $upgradableElement, $upgradable);
        }
        $climb->appendChild($upgradableElement);

        $dom->appendChild($climb);

        $climate->write(rtrim($dom->saveXML(), "\n"));
    }

    /**
     * Format package information.
     *
     * @param DOMDocument $dom
     * @param DOMElement $element
     * @param array $packages
     */
    private function formatPackage(DOMDocument $dom, DOMElement $element, array $packages)
    {
        foreach ($packages as $package) {
            $pack = $dom->createElement('package');

            $pack->setAttribute('name', $package[0]);
            $pack->setAttribute('current', $package[1]);
            $pack->setAttribute('update', $package[2]);

            $element->appendChild($pack);
        }

        return $element;
    }
}
