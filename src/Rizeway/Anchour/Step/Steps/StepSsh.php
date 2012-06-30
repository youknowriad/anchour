<?php

namespace Rizeway\Anchour\Step\Steps;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

use Rizeway\Anchour\Step\Step;
use Rizeway\Anchour\Step\Definition\Definition;

use OOSSH\SSH2\Connection;

class StepSsh extends Step
{
    public function initialize()
    {
        if (false === $this->getAdapter()->extension_loaded('ssh2')) {
            throw new \RuntimeException('SSH2 extension is not loaded');
        }

        parent::initialize();
    }

    protected function setDefaultOptions()
    {
        $this->addOption('commands', Definition::TYPE_REQUIRED);
    }

    protected function setDefaultConnections()
    {
        $this->addConnection('connection', Definition::TYPE_REQUIRED);
    }

    protected function exec($command, OutputInterface $output)
    {
        $this->getConnection('connection')->exec($command, function($stdio, $stderr) use($output) {
            $output->write($stdio);

            if ('' !== $stderr) {
                throw new \RuntimeException($stderr);
            }
        });
    }

    public function run(InputInterface $input, OutputInterface $output)
    {
        error_reporting(($level = error_reporting()) ^ E_WARNING);

        $this->getConnection('connection')->connect($output);

        foreach ($this->getOption('commands') as $command) {
            $this->exec($command, $output);
        }

        error_reporting($level);
    }
}
