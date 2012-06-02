<?php

namespace Rizeway\Anchour\Step\Steps;

use Rizeway\Anchour\Step\Step;
use Rizeway\Anchour\Connection\ConnectionHolder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StepMysql extends Step
{
    public function __construct(array $options = array())
    {
        $output = $status = null;
        exec('which mysql && which mysqldump', $output, $status);

        if(0 !== $status)
        {
            throw new \RuntimeException('mysql and/or mysqldump command are not available');
        }

        parent::__construct($options);
    }

    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'source',
            'destination'
        ));

        $resolver->setDefaults(array(
            'create_database' => true,
            'drop_database' => true,
        ));
    }

    public function run(OutputInterface $output, ConnectionHolder $connections)
    {
        $file = tempnam(sys_get_temp_dir(), uniqid());

        $source = $connections[$this->options['source']];
        $destination = $connections[$this->options['destination']];

        if(true === $this->options['drop_database'])
        {
            $cmd = sprintf(
                'mysql -h%s -u%s %s -e "DROP DATABASE \`%s\`"',
                $destination->getHost(),
                $destination->getUsername(),
                $destination->getPassword() ? '-p' . $source->getPassword() : '',
                $destination->getDatabase()
            );

            $output->writeln(sprintf('Dropping database <info>%s/%s</info>', $destination->getHost(), $destination->getDatabase()));
            passthru($cmd);
        }

        if(true === $this->options['create_database'])
        {
            $cmd = sprintf(
                'mysql -h%s -u%s %s -e "CREATE DATABASE IF NOT EXISTS \`%s\`"',
                $destination->getHost(),
                $destination->getUsername(),
                $destination->getPassword() ? '-p' . $source->getPassword() : '',
                $destination->getDatabase()
            );

            $output->writeln(sprintf('Creating database <info>%s/%s</info>', $destination->getHost(), $destination->getDatabase()));
            passthru($cmd);
        }

        $cmd = sprintf(
            'mysqldump -h%s -u%s %s %s > %s',
            $source->getHost(),
            $source->getUsername(),
            $source->getPassword() ? '-p' . $source->getPassword() : '',
            $source->getDatabase(),
            $file
        );

        $output->writeln(sprintf('Dumping database <info>%s/%s</info>', $source->getHost(), $source->getDatabase()));
        passthru($cmd);

        $cmd = sprintf(
            'mysql -h%s -u%s %s %s < %s',
            $destination->getHost(),
            $destination->getUsername(),
            $destination->getPassword() ? '-p' . $source->getPassword() : '',
            $destination->getDatabase(),
            $file
        );

        $output->writeln(sprintf('Loading data into database <info>%s/%s</info>', $destination->getHost(), $destination->getDatabase()));
        passthru($cmd);
    }
}