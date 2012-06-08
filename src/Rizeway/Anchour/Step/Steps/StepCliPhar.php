<?php

namespace Rizeway\Anchour\Step\Steps;

use Rizeway\Anchour\Step\Step;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StepCliPhar extends Step
{
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'directory',
            'name',
            'stub'
        ));

        $resolver->setDefaults(array(
            'output' => '.',
            'regexp' => null,
            'chmod' => false
        ));
    }

    public function run(OutputInterface $output)
    {
        $path = realpath($this->options['output']) . DIRECTORY_SEPARATOR . $this->options['name'];

        if($this->getAdapter()->file_exists($path)) {
            $this->getAdapter()->unlink($path);
        }

        
        $output->writeln(sprintf('Creating Cli Phar archive <info>%s</info>', $path));
        $phar = new \Phar($path);

        $directory = realpath($this->options['directory']);
        $output->writeln(sprintf('Adding directory <info>%s</info>', $directory));
        if(null !== $this->options['regexp'])
        {
            $output->writeln(sprintf('Filtering with regexp <info>`%s`</info>', $this->options['regexp']));
        }
        $phar->buildFromDirectory($directory, sprintf('`%s`', $this->options['regexp']));        

        $stub = realpath($this->options['stub']);
        $index = null;
        $output->writeln(sprintf('Adding stub <info>%s</info>', $stub));

        if(true === $this->options['chmod'])
        {
            $output->writeln(sprintf('Adding execution permission to Phar archive <info>%s</info>', $path));
            $this->getAdapter()->exec(sprintf('chmod a+x %s', $path));
        }

        $phar->setStub(file_get_contents($this->options['stub']));
    }
}