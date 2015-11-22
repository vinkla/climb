<?php

/*
 * This file is part of Climb.
 *
 * (c) Vincent Klaiber <hello@vinkla.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vinkla\Climb\Commands;

use League\CLImate\CLImate;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Vinkla\Climb\ClimbException;
use Vinkla\Climb\Ladder;
use Vinkla\Climb\Version;

/**
 * This is the outdated command class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 * @author Jens Segers <hello@jenssegers.com>
 */
class OutdatedCommand extends Command
{
    /**
     * The Ladder instance.
     *
     * @var \Vinkla\Climb\Ladder
     */
    protected $ladder;

    /**
     * Configure the outdated command.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('outdated');
        $this->setDescription('Find newer versions of dependencies than what your composer.json allows');
        $this->addOption('format', null, InputOption::VALUE_OPTIONAL, 'Output format', 'console');

        $this->ladder = new Ladder();
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
            $format = $input->getOption('format');
            $formatClass = '\\Vinkla\\Climb\\Formatter\\'.ucfirst($format);
            if (!class_exists($formatClass)) {
                throw new ClimbException(sprintf('Output format "%s" is not valid', $format));
            }

            $packages = $this->ladder->getOutdatedPackages();

            $outdated = [];
            $upgradable = [];

            if ($packages) {
                foreach ($packages as $name => list($constraint, $version, $latest)) {
                    if (Version::satisfies($latest, $constraint)) {
                        $upgradable[] = [$name, $version, $latest];
                    } else {
                        $outdated[] = [$name, $version, $latest];
                    }
                }
            }

            $outputHandler = new $formatClass();
            $outputHandler->render($climate, $outdated, $upgradable);
        } catch (ClimbException $exception) {
            $climate->br()->error($exception->getMessage())->br();
        }
    }
}
