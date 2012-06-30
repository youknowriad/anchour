<?php

namespace Rizeway\Anchour\Step\Steps;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

use Rizeway\Anchour\Step\Step;

use jubianchi\Ftp\Ftp;
use jubianchi\Output\Symfony\ConsoleOutputAdapter;
use Rizeway\Anchour\Step\Definition\Definition;

class StepFtp extends Step
{
    public function initialize()
    {
        if (false === $this->getAdapter()->extension_loaded('ftp')) {
            throw new \RuntimeException('FTP extension is not loaded');
        }
    }

    protected function setDefaultOptions()
    {
        $this->addOption('local_dir', Definition::TYPE_OPTIONAL);
        $this->addOption('remote_dir', Definition::TYPE_OPTIONAL);
    }

    protected function setDefaultConnections()
    {
        $this->addConnection('connection', Definition::TYPE_REQUIRED);
    }

    public function run(InputInterface $input, OutputInterface $output)
    {
        error_reporting(($level = error_reporting()) ^ E_WARNING);

        $connection = $this->getConnection('connection');
        $connection->connect($output);
        $connection->setOutput(new ConsoleOutputAdapter($output));
        $connection->uploadDirectory(getcwd() . '/' . $this->getOption('local_dir'), $this->getOption('remote_dir'));

        error_reporting($level);
    }
}
