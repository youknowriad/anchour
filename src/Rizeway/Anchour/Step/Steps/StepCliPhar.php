<?php

namespace Rizeway\Anchour\Step\Steps;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

use Rizeway\Anchour\Step\Step;
use Rizeway\Anchour\Step\Definition\Definition;

class StepCliPhar extends Step
{
    protected function setDefaultOptions()
    {
        $this->addOption('directory', Definition::TYPE_REQUIRED);
        $this->addOption('name', Definition::TYPE_REQUIRED);
        $this->addOption('stub', Definition::TYPE_REQUIRED);

        $this->addOption('output', Definition::TYPE_OPTIONAL, '.');
        $this->addOption('regexp', Definition::TYPE_OPTIONAL);
        $this->addOption('chmod', Definition::TYPE_OPTIONAL, false);
    }

    public function run(InputInterface $input, OutputInterface $output)
    {
        $path = rtrim($this->getOption('output'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $this->getOption('name');

        if ($this->getAdapter()->file_exists($path)) {
            $this->getAdapter()->unlink($path);
        }

        $output->writeln(sprintf('Creating Cli Phar archive <info>%s</info>', $path));
        $phar = new \Phar($path);

        $directory = realpath($this->getOption('directory'));
        $output->writeln(sprintf('Adding directory <info>%s</info>', $directory));
        if (null !== $this->getOption('regexp')) {
            $output->writeln(sprintf('Filtering with regexp <info>`%s`</info>', $this->getOption('regexp')));
        }
        $phar->buildFromDirectory($directory, sprintf('`%s`', $this->getOption('regexp')));

        $stub = realpath($this->getOption('stub'));
        $index = null;
        $output->writeln(sprintf('Adding stub <info>%s</info>', $stub));

        if (true === $this->getOption('chmod')) {
            $output->writeln(sprintf('Adding execution permission to Phar archive <info>%s</info>', $path));
            $this->getAdapter()->exec(sprintf('chmod a+x %s', $path));
        }

        $phar->setStub(file_get_contents($this->getOption('stub')));
    }
}
