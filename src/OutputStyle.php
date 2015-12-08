<?php

namespace Vinkla\Climb;

use League\CLImate\CLImate;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * This is the output style class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 */
class OutputStyle extends SymfonyStyle
{
    /**
     * Columize an array.
     *
     * @param array $data
     *
     * @return void
     */
    public function columns(array $data = [])
    {
        $climate = new CLImate();
        $climate->columns($data);
    }

    /**
     * Get the diff between the current and latest version.
     *
     * @param string $current
     * @param string $latest
     *
     * @return string
     */
    public function versionDiff($current, $latest)
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
