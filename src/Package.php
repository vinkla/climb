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
     * The package dev dependency boolean.
     *
     * @var string
     */
    protected $devDependency;

    /**
     * The Packagist instance.
     *
     * @var \Vinkla\Climb\Packagist
     */
    protected $packagist;

    /**
     * Create a new package instance.
     *
     * @param string $name
     * @param string $version
     * @param string $prettyVersion
     * @param bool $devDependency
     *
     * @return void
     */
    public function __construct($name, $version, $prettyVersion, $devDependency)
    {
        $this->name = $name;
        $this->version = $version;
        $this->prettyVersion = $prettyVersion;
        $this->devDependency = $devDependency;
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

    /**
     * Get the package dev dependency boolean.
     *
     * @return string
     */
    public function getDevDependency()
    {
        return $this->devDependency;
    }

    /**
     * Set the packagist instance.
     *
     * @param $packagist
     */
    public function setPackagist($packagist)
    {
        $this->packagist = $packagist;
    }
}
