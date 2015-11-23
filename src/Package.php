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
    protected $latestVersion;

    /**
     * The package version constraint.
     *
     * @var string
     */
    protected $constraint;

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
        return Version::satisfies($this->latestVersion, $this->constraint);
    }

    /**
     * Get the package name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the package version.
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
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
     * Set the version constraint.
     *
     * @param string $constraint
     *
     * @return void
     */
    public function setConstraint($constraint)
    {
        $this->constraint = $constraint;
    }
}
