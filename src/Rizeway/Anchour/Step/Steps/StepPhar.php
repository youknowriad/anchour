<?php

namespace Rizeway\Anchour\Step\Steps;

use Rizeway\Anchour\Step\Step;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StepPhar extends Step
{
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'directory',
            'name',
            'stub'
        ));

        $resolver->setDefaults(array(
            'index' => null,
            'output' => '.',
            'regexp' => null
        ));
    }

    public function run(OutputInterface $output)
    {
        $path = realpath($this->options['output']) . DIRECTORY_SEPARATOR . $this->options['name'];
        $output->writeln(sprintf('Creating Phar archive <info>%s</info>', $path));
        $phar = new \Phar($path);

        $directory = realpath($this->options['directory']);
        $output->writeln(sprintf('Adding directory <info>%s</info>', $directory));
        $phar->buildFromDirectory($directory, $this->options['regexp']);

        $stub = realpath($this->options['stub']);
        $index = null;
        $output->writeln(sprintf('Adding stub <info>%s</info>', $stub));
        if ($this->options['index'])
        {
            $index = realpath($this->options['index']);
            $output->writeln(sprintf('Adding index <info>%s</info>', $index));
        }

        $phar->setDefaultStub($this->options['stub'], $index);
    }
}