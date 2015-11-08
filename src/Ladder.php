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
use Guzzle\Http\Exception\ClientErrorResponseException;
use Packagist\Api\Client;

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
     */
    public function __construct($directory)
    {
        $this->packagist = new Client();
        $this->directory = $directory;
    }

    /**
     * Get installed package versions.
     *
     * @throws \Vinkla\Climb\ClimbException
     *
     * @return array
     */
    public function getInstalledPackages()
    {
        $packages = [];

        $content = $this->getFileContent('composer.lock');

        foreach (['packages', 'packages-dev'] as $key) {
            if (isset($content[$key])) {
                foreach ($content[$key] as $package) {
                    $packages[$package['name']] = Version::normalize($package['version']);
                }
            }
        }

        if (!$packages) {
            throw new ClimbException('We couldn\'t find any installed packages.');
        }

        return $packages;
    }

    /**
     * Get required package versions.
     *
     * @throws \Vinkla\Climb\ClimbException
     *
     * @return array
     */
    public function getRequiredPackages()
    {
        $packages = [];

        $content = $this->getFileContent('composer.json');

        foreach (['require', 'require-dev'] as $key) {
            if (isset($content[$key])) {
                foreach ($content[$key] as $package => $version) {
                    if (!strstr($package, '/')) {
                        continue;
                    }

                    $packages[$package] = $version;
                }
            }
        }

        if (!$packages) {
            throw new ClimbException('We couldn\'t find any required packages.');
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

        // Get the installed version number of the required packages.
        $packages = array_intersect_key($installed, $required);

        $outdated = [];
        foreach ($packages as $package => $version) {
            if (!$latest = $this->getLatestVersion($package)) {
                continue;
            }

            if (Comparator::lessThan($version, $latest)) {
                $constraint = $required[$package];
                $outdated[$package] = [$constraint, $version, $latest];
            }
        }

        return $outdated;
    }

    public function getLatestVersion($name)
    {
        try {
            // Get all package versions.
            $versions = array_map(function ($version) {
                return $version->getVersion();
            }, $this->packagist->get($name)->getVersions());

            return Version::latest($versions);
        } catch (ClientErrorResponseException $e) {
            return;
        }
    }

    /**
     * Normalize the version number.
     *
     * @param string $version
     *
     * @return string
     */
    private function normalize($version)
    {
        $version = preg_replace('/^(v|\^|~)/', '', $version);

        if (preg_match('/^\d\.\d$/', $version)) {
            $version .= '.0';
        }

        return $version;
    }

    /**
     * Get file content.
     *
     * @param string $file
     *
     * @throws \Vinkla\Climb\ClimbException
     *
     * @return array
     */
    private function getFileContent($file)
    {
        $filePath = $this->directory.'/'.$file;

        if (!file_exists($filePath)) {
            throw new ClimbException("We couldn't find any file [$filePath].");
        }

        return json_decode(file_get_contents($filePath), true);
    }
}
