<?php

namespace Rizeway\Anchour\Step;

use Symfony\Component\Console\Output\OutputInterface;
use Rizeway\Anchour\Connection\ConnectionHolder;

interface StepInterface
{

    /**
     * Run the step
     */
    public function run(OutputInterface $output, ConnectionHolder $connections);
}