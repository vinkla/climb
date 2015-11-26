<?php

namespace Vinkla\Climb;

use InvalidArgumentException;
use LogicException;

/**
 * This is the composer class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 */
class Composer
{
    /**
     * The directory path.
     *
     * @var string
     */
    protected $directory;

    /**
     * Create a new composer instance.
     *
     * @param string $directory
     *
     * @return void
     */
    public function __construct($directory)
    {
        $this->directory = $directory;
    }

    /**
     * Get installed package versions.
     *
     * @throws \LogicException
     *
     * @return array
     */
    public function getInstalledPackages()
    {
        $packages = [];

        $content = $this->getFileContents('composer.lock');

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
    public function getRequiredPackages()
    {
        $packages = [];

        $content = $this->getFileContents('composer.json');

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
     * Get file content.
     *
     * @param string $file
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    protected function getFileContents($file)
    {
        $filePath = $this->directory.'/'.$file;

        if (!file_exists($filePath)) {
            throw new InvalidArgumentException("The file [$filePath] does not exist.");
        }

        return json_decode(file_get_contents($filePath), true);
    }
}
