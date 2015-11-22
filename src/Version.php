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
use Composer\Semver\Semver;
use Composer\Semver\VersionParser;

/**
 * This is the version class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 * @author Jens Segers <hello@jenssegers.com>
 */
class Version extends Semver
{
    /**
     * Normalize the version number.
     *
     * @param string $version
     *
     * @return string
     */
    public static function normalize($version)
    {
        $version = preg_replace('/^(v|\^|~)/', '', $version);

        if (preg_match('/^\d\.\d$/', $version)) {
            $version .= '.0';
        }

        return $version;
    }

    /**
     * Get the last version number from a list of versions.
     *
     * @param array $versions
     *
     * @return string
     */
    public static function latest(array $versions)
    {
        // Normalize version numbers.
        $versions = array_map(function ($version) {
            return static::normalize($version);
        }, $versions);

        // Get the highest version number.
        $latest = array_reduce($versions, function ($carry, $item) {
            // Skip unstable versions.
            if (VersionParser::parseStability($item) !== 'stable') {
                return $carry;
            }

            return Comparator::greaterThan($carry, $item) ? $carry : $item;
        }, '0.0.0');

        return $latest;
    }

    /**
     * Get the diff between the current and latest version.
     *
     * @param string $current
     * @param string $latest
     *
     * @return string
     */
    public static function diff($current, $latest)
    {
        $needle = 0;

        while ($needle < strlen($current) && $needle < strlen($latest)) {
            if ($current[$needle] !== $latest[$needle]) {
                break;
            }

            $needle++;
        }

        return substr($latest, 0, $needle).'<green>'.substr($latest, $needle).'</green>';
    }
}
