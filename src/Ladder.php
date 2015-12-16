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

/**
 * This is the ladder class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 * @author Jens Segers <hello@jenssegers.com>
 */
class Ladder
{
    /**
     * The composer instance.
     *
     * @var \Vinkla\Climb\Composer
     */
    protected $composer;

    /**
     * Create a new ladder instance.
     *
     * @param string|null $directory
     *
     * @return void
     */
    public function __construct($directory = null)
    {
        $this->composer = new Composer($directory ?: getcwd());
    }

    /**
     * Get outdated packages with their current and latest version.
     *
     * @param array $excluded
     *
     * @return array
     */
    public function getOutdatedPackages(array $excluded = [])
    {
        // Get all installed and required packages.
        $installed = $this->composer->getInstalledPackages();
        $required = $this->composer->getRequiredPackages();

        $outdated = [];

        // Get the installed version number of the required packages.
        $packages = array_intersect_key($installed, $required);

        foreach ($packages as $name => $version) {
            if (in_array($name, $excluded)) {
                continue;
            }

            $package = new Package($name, Version::normalize($version), $required[$name]);

            if ($package->isOutdated()) {
                $outdated[] = $package;
            }
        }

        return $outdated;
    }
}
