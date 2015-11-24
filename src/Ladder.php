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

use InvalidArgumentException;
use LogicException;

/**
 * This is the ladder class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 * @author Jens Segers <hello@jenssegers.com>
 */
class Ladder
{
    /**
     * Packagist client instance.
     *
     * @var \Packagist\Api\Client
     */
    protected $packagist;

    /**
     * The project directory.
     *
     * @var string
     */
    protected $directory;

    /**
     * Create a new ladder instance.
     *
     * @param string|null $directory
     *
     * @return void
     */
    public function __construct($directory = null)
    {
        $this->directory = $directory ?: getcwd();
    }

    /**
     * Get installed package versions.
     *
     * @throws \LogicException
     *
     * @return array
     */
    protected function getInstalledPackages()
    {
        $packages = [];

        $content = $this->getFileContent('composer.lock');

        foreach (['packages', 'packages-dev'] as $key) {
            if (!isset($content[$key])) {
                continue;
            }

            foreach ($content[$key] as $package) {
                $packages[$package['name']] = $package['version'];
            }
        }

        if (empty($packages)) {
            throw new LogicException('We couldn\'t find any installed packages.');
        }

        return $packages;
    }

    /**
     * Get required package versions.
     *
     * @throws \LogicException
     *
     * @return array
     */
    protected function getRequiredPackages()
    {
        $packages = [];

        $content = $this->getFileContent('composer.json');

        foreach (['require', 'require-dev'] as $key) {
            if (!isset($content[$key])) {
                continue;
            }

            foreach ($content[$key] as $package => $version) {
                if (!strstr($package, '/')) {
                    continue;
                }

                $packages[$package] = $version;
            }
        }

        if (empty($packages)) {
            throw new LogicException('We couldn\'t find any required packages.');
        }

        return $packages;
    }

    /**
     * Get outdated packages with their current and latest version.
     *
     * @throws \Vinkla\Climb\ClimbException
     *
     * @return array
     */
    public function getOutdatedPackages()
    {
        // Get all installed and required packages.
        $installed = $this->getInstalledPackages();
        $required = $this->getRequiredPackages();

        $outdated = [];

        // Get the installed version number of the required packages.
        $packages = array_intersect_key($installed, $required);

        foreach ($packages as $name => $version) {
            $package = new Package($name, Version::normalize($version), $version);

            if ($package->isOutdated()) {
                $package->setConstraint($required[$name]);

                $outdated[] = $package;
            }
        }

        return $outdated;
    }

    /**
     * Get file content.
     *
     * @param string $file
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    private function getFileContent($file)
    {
        $filePath = $this->directory.'/'.$file;

        if (!file_exists($filePath)) {
            throw new InvalidArgumentException("We couldn't find any file [$filePath].");
        }

        return json_decode(file_get_contents($filePath), true);
    }
}
