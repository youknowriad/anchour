<?php

namespace Rizeway\Anchour\Console;

use Rizeway\Anchour\Config\Loader;

class Initializer
{
    public function initialize(Application $application, Loader $loader)
    {
        foreach ($loader->getCommands() as $command) {
            $application->add($command);
        }
    }
}
