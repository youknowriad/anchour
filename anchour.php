<?php
require __DIR__.'/vendor/autoload.php';

use Rizeway\Anchour\Console\Application;

if(!defined('ANCHOUR_VERSION'))
{
    define('ANCHOUR_VERSION', 'source');
}

$console = new Application(new \Rizeway\Anchour\Console\Initializer());
$console->run();