<?php

namespace Rizeway\Anchour\Step;

use Symfony\Component\Console\Output\OutputInterface;

use jubianchi\Adapter\AdaptableInterface;

interface StepInterface extends AdaptableInterface
{
    /**
     * Run the step
     */
    public function run(OutputInterface $output);
}