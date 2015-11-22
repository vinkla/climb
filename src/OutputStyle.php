<?php
namespace Vinkla\Climb;

use League\CLImate\CLImate;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This is the output style class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 */
class OutputStyle extends SymfonyStyle
{
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
}
