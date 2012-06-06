<?php

namespace Rizeway\Anchour\Step\Steps;

use Rizeway\Anchour\Step\Step;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StepMysql extends Step
{
    public function __construct(array $options = array(), $connections = array(), 
        OptionsResolverInterface $options_resolver = null, OptionsResolverInterface $connections_resolver = null, AdapterInterface $adapter = null)
    {
        $output = $status = null;
        exec('which mysql && which mysqldump', $output, $status);

        if(0 !== $status)
        {
            throw new \RuntimeException('mysql and/or mysqldump command are not available');
        }

        parent::__construct($options, $connections, $options_resolver, $connections_resolver, $adapter);
    }

    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'create_database' => true,
            'drop_database' => true,
        ));
    }

    protected function setDefaultConnections(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'source',
            'destination'
        ));
    }

    public function run(OutputInterface $output)
    {
        $file = $this->getAdapter()->tempnam(sys_get_temp_dir(), uniqid());

        $source = $this->connections['source'];
        $destination = $this->connections['destination'];

        if(true === $this->options['drop_database'])
        {
            $cmd = sprintf(
                'mysql -h%s -u%s%s -e "DROP DATABASE \`%s\`"',
                $destination->getHost(),
                $destination->getUsername(),
                $destination->getPassword() ? ' -p' . $source->getPassword() : '',
                $destination->getDatabase()
            );

            $output->writeln(sprintf('Dropping database <info>%s/%s</info>', $destination->getHost(), $destination->getDatabase()));
            $this->getAdapter()->passthru($cmd);
        }

        if(true === $this->options['create_database'])
        {
            $cmd = sprintf(
                'mysql -h%s -u%s%s -e "CREATE DATABASE IF NOT EXISTS \`%s\`"',
                $destination->getHost(),
                $destination->getUsername(),
                $destination->getPassword() ? ' -p' . $source->getPassword() : '',
                $destination->getDatabase()
            );

            $output->writeln(sprintf('Creating database <info>%s/%s</info>', $destination->getHost(), $destination->getDatabase()));
            $this->getAdapter()->passthru($cmd);
        }

        $cmd = sprintf(
            'mysqldump -h%s -u%s%s %s > %s',
            $source->getHost(),
            $source->getUsername(),
            $source->getPassword() ? ' -p' . $source->getPassword() : '',
            $source->getDatabase(),
            $file
        );

        $output->writeln(sprintf('Dumping database <info>%s/%s</info>', $source->getHost(), $source->getDatabase()));
        $this->getAdapter()->passthru($cmd);

        $cmd = sprintf(
            'mysql -h%s -u%s%s %s < %s',
            $destination->getHost(),
            $destination->getUsername(),
            $destination->getPassword() ? ' -p' . $source->getPassword() : '',
            $destination->getDatabase(),
            $file
        );

        $output->writeln(sprintf('Loading data into database <info>%s/%s</info>', $destination->getHost(), $destination->getDatabase()));
        $this->getAdapter()->passthru($cmd);
    }
}