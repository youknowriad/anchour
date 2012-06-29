<?php

namespace Rizeway\Anchour\Step;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

use jubianchi\Adapter\AdaptableInterface;

interface StepInterface extends AdaptableInterface
{
    /**
     * Run the step
     */
    public function run(InputInterface $input, OutputInterface $output);
}
