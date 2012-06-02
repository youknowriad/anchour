<?php

namespace Rizeway\Anchour\Step\Steps;

use Rizeway\Anchour\Step\Step;
use Rizeway\Anchour\Connection\ConnectionHolder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StepEcho extends Step
{
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'message'
        ));
    }

    public function run(OutputInterface $output, ConnectionHolder $connections)
    {
        $output->writeln($this->options['message']);
    }
}