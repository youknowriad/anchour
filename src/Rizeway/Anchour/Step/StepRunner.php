<?php

namespace Rizeway\Anchour\Step;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

class StepRunner
{

    /**
     * The steps
     * @var Step[]
     */
    protected $steps;

    protected $application;

    /**
     * @param Step[] $steps
     */
    public function __construct($application, array $steps)
    {
        $this->application = $application;
        $this->steps = $steps;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->steps as $step) {
            if ($step instanceof \Rizeway\Anchour\Step\StepApplicationAware) {
                $step->setApplication($this->application);
            }

            $step->run($input, $output);
        }
    }
}
