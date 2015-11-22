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

use Composer\Semver\Comparator;

/**
 * This is the package class.
 *
 * @author Vincent Klaiber <vincent@schimpanz.com>
 */
class Package
{
    /**
     * The package name.
     *
     * @var string
     */
    protected $name;

    /**
     * The package version.
     *
     * @var string
     */
    protected $version;

    /**
     * The package latest version.
     *
     * @var string
     */
    protected $latest;

    /**
     * Create a new package instance.
     *
     * @param string $name
     * @param string $version
     *
     * @return void
     */
    public function __construct($name, $version)
    {
        $this->name = $name;
        $this->version = $version;
        $this->packagist = new Packagist();
    }

    /**
     * Get latest version.
     *
     * @return string|void
     */
    public function getLatestVersion()
    {
        if (!empty($this->latest)) {
            return $this->latest;
        }

        $this->latest = $this->packagist->getLatestVersion($this->name);

        return $this->latest;
    }

    /**
     * Check if the package is outdated.
     *
     * @return bool
     */
    public function isOutdated()
    {
        return Comparator::lessThan($this->version, $this->getLatestVersion());
    }
}
