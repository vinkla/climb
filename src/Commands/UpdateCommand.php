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
use Symfony\Component\Process\Process;
use Vinkla\Climb\Ladder;
use Vinkla\Climb\Version;

/**
 * This is the update command class.
 *
 * @author Joseph Cohen <joe@alt-three.com>
 */
class UpdateCommand extends Command
{
    /**
     * The Ladder instance.
     *
     * @var \Vinkla\Climb\Ladder
     */
    protected $ladder;

    /**
     * The composer command to run.
     *
     * @return string
     */
    protected $command = 'composer require';

    /**
     * Configure the update command.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('update');
        $this->setDescription('Update composer.json dependencies versions');
        $this->addOption('all', null, InputOption::VALUE_NONE, 'Run the update on breaking versions');

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
        $climate->br();

        try {
            $packages = $this->ladder->getOutdatedPackages();

            if (!$packages) {
                $climate->write('All dependencies match the latest package versions <green>:)</green>')->br();

                return;
            }

            $outdated = [];
            $upgradable = [];

            foreach ($packages as $name => list($constraint, $version, $latest)) {
                if (Version::satisfies($latest, $constraint)) {
                    $upgradable[$name] = $this->diff($version, $latest);
                } else {
                    $outdated[$name] = $this->diff($version, $latest);
                }
            }

            if ($input->getOption('all')) {
                $upgradable = array_merge($upgradable, $outdated);
            }

            if (empty($upgradable)) {
                $climate->write('Nothing to install or update')->br();

                return;
            }

            foreach ($upgradable as $package => $version) {
                $this->command .= " {$package}=^$version";
            }

            $process = new Process($this->command, null, array_merge($_SERVER, $_ENV), null, null);

            $process->run(function ($type, $line) use ($output) {
                $output->write($line);
            });
        } catch (ClimbException $exception) {
            $climate->error($exception->getMessage())->br();
        }
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
        $needle = 0;

        while ($needle < strlen($current) && $needle < strlen($latest)) {
            if ($current[$needle] !== $latest[$needle]) {
                break;
            }

            $needle++;
        }

        return substr($latest, 0, $needle).substr($latest, $needle);
    }
}
