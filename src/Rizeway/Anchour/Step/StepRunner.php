<?php

namespace Rizeway\Anchour\Step;

use Symfony\Component\Console\Output\OutputInterface;

class StepRunner
{

    /**
     * The steps
     * @var Step[]
     */
    protected $steps;

    /**
     * @param Step[] $steps
     */
    public function __construct(array $steps)
    {
        $this->steps = $steps;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    public function run(OutputInterface $output)
    {
        foreach ($this->steps as $step) {
            $step->run($output);
        }
    }
}