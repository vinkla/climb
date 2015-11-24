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
 * @author Vincent Klaiber <hello@vinkla.com>
 */
class Package
{
    /**
     * The package's name.
     *
     * @var string
     */
    protected $name;

    /**
     * The package's version.
     *
     * @var string
     */
    protected $version;

    /**
     * The package's latest version.
     *
     * @var string
     */
    protected $latestVersion;

    /**
     * The package's non-normalized version.
     *
     * @var string
     */
    protected $prettyVersion;

    /**
     * Create a new package instance.
     *
     * @param string $name
     * @param string $version
     * @param string $prettyVersion
     *
     * @return void
     */
    public function __construct($name, $version, $prettyVersion)
    {
        $this->name = $name;
        $this->version = $version;
        $this->prettyVersion = $prettyVersion;
        $this->packagist = new Packagist();
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

    /**
     * Check if the package is upgradable.
     *
     * @return bool
     */
    public function isUpgradable()
    {
        return Version::satisfies($this->getLatestVersion(), $this->prettyVersion);
    }

    /**
     * Get the package's name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the package's version.
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Get the package's non-normalized version.
     *
     * @return string
     */
    public function getPrettyVersion()
    {
        return $this->prettyVersion;
    }

    /**
     * Get latest version.
     *
     * @return string|void
     */
    public function getLatestVersion()
    {
        if (!empty($this->latestVersion)) {
            return $this->latestVersion;
        }

        $this->latestVersion = $this->packagist->getLatestVersion($this->name);

        return $this->latestVersion;
    }
}
