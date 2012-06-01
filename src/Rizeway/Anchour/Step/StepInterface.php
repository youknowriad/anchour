<?php

namespace Rizeway\Anchour\Step;

use Symfony\Component\Console\Output\OutputInterface;

interface StepInterface
{

    /**
     * Run the step
     */
    public function run(OutputInterface $output);
}