<?php

namespace Rizeway\Anchour\Step\Steps;

use Rizeway\Anchour\Step\Step;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StepEcho extends Step
{
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'message'
        ));
    }

    public function run()
    {
        fwrite(STDOUT, $this->options['message'] . PHP_EOL);
    }
}