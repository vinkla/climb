<?php

namespace Vinkla\Climb;

use League\CLImate\CLImate;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * This is the output style class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 */
class OutputStyle extends SymfonyStyle
{
    /**
     * The climate instance.
     *
     * @var \League\CLImate\CLImate
     */
    protected $climate;

    /**
     * Create a new output style instance.
     *
     * @param \Symfony\Component\Console\Input\InputInterface  $input
     * @param \Symfony\Component\Console\Output\OutputInterface  $output
     *
     * @return void
     */
    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->climate = new CLImate();

        parent::__construct($input, $output);
    }

    /**
     * Columize an array.
     *
     * @param array $data
     *
     * @return void
     */
    public function columns(array $data = [])
    {
        $this->climate->columns($data);
        $this->newLine();
    }

    /**
     * Get the diff between the current and latest version.
     *
     * @param string $current
     * @param string $latest
     *
     * @return string
     */
    public static function versionDiff($current, $latest)
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
