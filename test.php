<?php require 'vendor/autoload.php';

use Vinkla\Climb\Ladder;

$ladder = new Ladder();

var_dump($ladder->getOutdatedPackages());
