<?php

namespace Rizeway\Anchour\Step;

class StepRunner
{

    /**
     * The steps
     * @var Step[]
     */
    protected $steps;

    public function __construct($steps)
    {
        $this->steps = $steps;
    }

    public function run()
    {
        foreach ($this->steps as $step) {
            $step->run();
        }
    }
}