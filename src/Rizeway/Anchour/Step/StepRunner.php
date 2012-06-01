<?php

namespace Rizeway\Anchour\Step;

use Symfony\Component\Console\Output\OutputInterface;
use Rizeway\Anchour\Connection\ConnectionHolder;

class StepRunner
{

    /**
     * The steps
     * @var Step[]
     */
    protected $steps;

    /**
     * The connections
     * @var \Rizeway\Anchour\Connection\ConnectionHolder
     */
    protected $connections;


    /**
     * @param Step[] $steps
     */
    public function __construct(array $steps, ConnectionHolder $connections)
    {
        $this->steps = $steps;
        $this->connections = $connections;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    public function run(OutputInterface $output)
    {
        foreach ($this->steps as $step) {
            $step->run($output, $this->connections);
        }
    }
}