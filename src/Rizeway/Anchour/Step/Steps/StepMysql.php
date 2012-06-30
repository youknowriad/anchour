<?php
namespace Rizeway\Anchour\Step\Steps;

use Rizeway\Anchour\Step\Step;
use Rizeway\Anchour\Step\Definition\Definition;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

class StepMysql extends Step
{
    public function initialize()
    {
        $output = $status = null;
        $this->getAdapter()->exec('which mysql && which mysqldump', $output, $status);

        if (0 !== $status) {
            throw new \RuntimeException('mysql and/or mysqldump command are not available');
        }
    }

    protected function setDefaultOptions()
    {
        $this->addOption('create_database', Definition::TYPE_OPTIONAL, true);
        $this->addOption('drop_database', Definition::TYPE_OPTIONAL, true);
    }

    protected function setDefaultConnections()
    {
        $this->addConnection('source', Definition::TYPE_REQUIRED);
        $this->addConnection('destination', Definition::TYPE_REQUIRED);
    }

    public function run(InputInterface $input, OutputInterface $output)
    {
        $file = $this->getAdapter()->tempnam(sys_get_temp_dir(), uniqid());

        $source = $this->getConnection('source');
        $destination = $this->getConnection('destination');

        if (true === $this->getOption('drop_database')) {
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

        if (true === $this->getOption('create_database')) {
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
