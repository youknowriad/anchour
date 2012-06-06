<?php
namespace Rizeway\Anchour\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitCommand extends Command
{
    public function __construct($name = null)
    {
        parent::__construct('init');

        $this
            ->setDescription('Create a default .anchour file')
            ->addOption('force', 'f', InputOption::VALUE_NONE)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (file_exists('.anchour') && !$input->getOption('force'))
        {
            throw new \RuntimeException('File .anchour already exists. To replace it, use the --force/-f option');
        }

        $file = new \SplFileObject('.anchour', 'w+');
        $file->fwrite($this->getTemplate());
    }

    protected function getTemplate() {
        return <<<YAML
#Here you can define your targets
target:
    description: "A default target"
    connections:
        #Here you can define your connections

    steps:
        #Here you can define your steps
        -
            type: "echo"
            options:
                message: "This is a default <info>echo</info> step"
YAML;
    }
}
