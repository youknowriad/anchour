<?php

namespace Rizeway\Anchour\Step\Steps;

use Rizeway\Anchour\Step\Step;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use OOSSH\SSH2\Connection;
use OOSSH\SSH2\Authentication\Password;

class StepSsh extends Step
{
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'host',
            'username',
            'password',
            'commands'
        ));

        $resolver->setDefaults(array(
            'port' => '22',
        ));
    }

    public function run()
    {
        $connection = new Connection($this->options['host'], $this->options['port']);
        $connection
            ->connect()
            ->authenticate(new Password($this->options['username'], $this->options['password']));

        foreach ($this->options['commands'] as $command)
        {
            $connection->exec($command, function($stdio, $stderr) { echo $stdio; });
        }
    }
}