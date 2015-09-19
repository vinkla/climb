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

        $packages = $this->getPackages();

        if (count($packages) <= 0) {
            return $climate->br()->error('We couldn\'t find any required packages.')->br();
        }

        $versions = $this->getVersions($packages);

        if (count($versions) <= 0) {
            return $climate->br()->out('All dependencies match the latest package versions <green>:)</green>')->br();
        }

        return $climate->br()->columns($versions, 3)->br();
    }

    /**
     * Get the required packages.
     *
     * @return array
     */
    private function getPackages()
    {
        $file = getcwd().'/composer.lock';

        if (!file_exists($file)) {
            return [];
        }

        $json = json_decode(file_get_contents($file), true);

        $packages = $json['packages'];

        if (count($packages) <= 0) {
            return [];
        }

        $array = [];

        foreach ($packages as $package) {
            $name = $package['name'];

            $string = new Stringy($name);

            if ($string->startsWith('php') || $string->startsWith('ext')) {
                continue;
            }

            $array[$name] = $package['version'];
        }

        return $array;
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

                if (version_compare($version, $latest, '<') && $current !== $latest) {
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

        $versions = array_filter($versions, function ($version) {
            return preg_match('/^v?\d\.\d(\.\d)?$/', $version);
        });

        return array_reduce($versions, function ($carry, $item) {
            return version_compare($carry, $item, '>') ? $carry : $item;
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
        $version = preg_replace('/(v|\^|~)/', '', $version);

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
