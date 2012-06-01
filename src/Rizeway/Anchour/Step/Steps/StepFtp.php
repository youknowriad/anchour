<?php

namespace Rizeway\Anchour\Step\Steps;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Rizeway\Anchour\Step\Step;

use jubianchi\Ftp\Ftp;

class StepFtp extends Step
{
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'host',
            'username',
            'password'
        ));

        $resolver->setDefaults(array(
            'local_dir' => '',
            'remote_dir' => ''
        ));
    }

    public function run(OutputInterface $output)
    {
        error_reporting(($level = error_reporting()) ^ E_WARNING);

        $ftp = new Ftp($this->options['host'], $this->options['username'], $this->options['password']);
        $ftp->setOutput($output);
        $ftp->uploadDirectory(getcwd() . '/' . $this->options['local_dir'], $this->options['remote_dir']);     

        error_reporting($level);
    }    
}