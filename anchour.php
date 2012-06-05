<?php

require __DIR__.'/vendor/autoload.php';

use Rizeway\Anchour\Console\Application;

$console = new Application(new \Rizeway\Anchour\Console\Initializer());
$console->run();