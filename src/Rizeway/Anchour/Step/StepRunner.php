<?php

namespace Rizeway\Anchour\Step;

class StepRunner
{

    protected $steps;

    public function __construct($steps)
    {
        $this->steps = $steps;
    }
}