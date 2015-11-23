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

use Guzzle\Http\Exception\ClientErrorResponseException;
use Packagist\Api\Client;

/**
 * This is the packagist class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 */
class Packagist extends Client
{
    /**
     * Get a package's latest version.
     *
     * @param string $name
     *
     * @return string|void
     */
    public function getLatestVersion($name)
    {
        try {
            $package = $this->get($name);

            $versions = array_map(function ($version) {
                return $version->getVersion();
            }, $package->getVersions());

            return Version::latest($versions);
        } catch (ClientErrorResponseException $e) {
            return;
        }
    }
}
