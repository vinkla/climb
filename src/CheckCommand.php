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
use League\CLImate\CLImate;
use Packagist\Api\Client;
use Stringy\Stringy;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This is the check command class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 */
class CheckCommand extends Command
{
    /**
     * Create a new check command instance.
     */
    public function __construct()
    {
        parent::__construct('check');

        $this->setDescription('Find newer versions of dependencies than what your composer.json allows');
    }

    /**
     * Execute the command.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return mixed
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $climate = new CLImate();

        try {
            $packages = $this->getUpdates();

            if (count($packages) <= 0) {
                return $climate->br()->line('All dependencies match the latest package versions <green>:)</green>')->br();
            }

            return $climate->br()->columns($packages, 3)->br();
        } catch (ClimbException $exception) {
            return $climate->br()->error($exception->getMessage())->br();
        }
    }

    /**
     * Get installed packages.
     *
     * @throws \Vinkla\Climb\ClimbException
     *
     * @return array
     */
    private function getInstalledPackages()
    {
        $packages = [];

        $content = $this->getFileContent('composer.lock');

        foreach (['packages', 'packages-dev'] as $key) {
            if (isset($content[$key])) {
                $packages = array_merge($packages, $content[$key]);
            }
        }

        if (count($packages) <= 0) {
            throw new ClimbException('We couldn\'t find any installed packages.');
        }

        return $packages;
    }

    /**
     * Get required packages.
     *
     * @throws \Vinkla\Climb\ClimbException
     *
     * @return array
     */
    private function getRequiredPackages()
    {
        $packages = [];

        $content = $this->getFileContent('composer.json');

        foreach (['require', 'require-dev'] as $key) {
            if (isset($content[$key])) {
                $packages = array_merge($packages, $content[$key]);
            }
        }

        if (count($packages) <= 0) {
            throw new ClimbException('We couldn\'t find any required packages.');
        }

        return $packages;
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

    /**
     * Get new versions.
     *
     * @throws \Vinkla\Climb\ClimbException
     *
     * @return array
     */
    private function getUpdates()
    {
        $packages = $this->getInstalledPackages();
        $required = array_keys($this->getRequiredPackages());

        $array = [];

        foreach ($packages as $package) {
            $name = $package['name'];

            $string = new Stringy($name);

            if ($string->startsWith('php') || $string->startsWith('ext') || !in_array($name, $required)) {
                continue;
            }

            $array[$name] = $package['version'];
        }

        $versions = $this->getVersions($array);

        return $versions;
    }

    /**
     * Get the versions.
     *
     * @param array $packages
     *
     * @return array
     */
    private function getVersions(array $packages)
    {
        $client = new Client();

        $versions = [];

        foreach ($packages as $name => $version) {
            try {
                $package = $client->get($name);

                $current = $this->normalize($version);
                $latest = $this->getLatest($package->getVersions());

                if (Comparator::lessThan($current, $latest)) {
                    $latest = $this->diff($current, $latest);

                    array_push($versions, [$name, $current, '→', $latest]);
                }
            } catch (ClientErrorResponseException $e) {
                continue;
            }
        }

        return $versions;
    }

    /**
     * Get the latest version.
     *
     * @param array $versions
     *
     * @return string
     */
    private function getLatest(array $versions)
    {
        $versions = array_map(function ($version) {
            return $this->normalize($version->getVersion());
        }, $versions);

        return array_reduce($versions, function ($carry, $item) {
            return Comparator::greaterThan($carry, $item) ? $carry : $item;
        }, '0.0.0');
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
     * Get the diff between the current and latest version.
     *
     * @param string $current
     * @param string $latest
     *
     * @return string
     */
    private function diff($current, $latest)
    {
        $current = str_split($current);
        $latest = str_split($latest);

        $version = '';
        $new = false;

        foreach ($current as $i => $character) {
            if (!isset($latest[$i])) {
                break;
            }

            if ($character !== $latest[$i]) {
                $new = true;
            }

            $version .= $new ? '<green>'.$latest[$i].'</green>' : $latest[$i];
        }

        return $version;
    }
}
