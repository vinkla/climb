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

class Ladder
{
    /**
     * Packagist client instance.
     *
     * @var \Packagist\Api\Client
     */
    protected $packagist;

    /**
     * Create a new ladder instance.
     */
    public function __construct()
    {
        $this->packagist = new Client();
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
                    $packages[$package['name']] = $this->normalize($package['version']);
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
            $latest = $this->getLatestVersion($package);

            // Check if the latest version is higher than the current one.
            if (Comparator::lessThan($version, $latest)) {
                $outdated[$package] = [$version, $latest];
            }
        }

        return $outdated;
    }

    public function getLatestVersion($name)
    {
        try {
            // Get all package versions.
            $versions = $this->packagist->get($name)->getVersions();

            // Normalize version numbers.
            $versions = array_map(function ($version) {
                return $this->normalize($version->getVersion());
            }, $versions);

            // Get the highest version number.
            $latest = array_reduce($versions, function ($carry, $item) {
                return Comparator::greaterThan($carry, $item) ? $carry : $item;
            }, '0.0.0');

            return $latest;
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
        $filePath = getcwd().'/'.$file;

        if (!file_exists($filePath)) {
            throw new ClimbException('We couldn\'t find any '.$file.' file.');
        }

        return json_decode(file_get_contents($filePath), true);
    }
}
