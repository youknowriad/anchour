<?php
namespace Rizeway\Anchour\Step\Steps;

use Symfony\Component\Console\Output\OutputInterface;

use Rizeway\Anchour\Step\Step;
use Rizeway\Anchour\Step\Definition\Definition;

use jubianchi\Adapter\AdapterInterface;

class StepGit extends StepSsh
{
    protected function setDefaultOptions()
    {
        parent::setDefaultOptions();

        $this->addOption('repository', Definition::TYPE_REQUIRED);
        $this->addOption('remote_dir', Definition::TYPE_REQUIRED);

        $this->addOption('clean_scm', Definition::TYPE_OPTIONAL, true);
        $this->addOption('remove_existing', Definition::TYPE_OPTIONAL, false);
        $this->addOption('commands', Definition::TYPE_OPTIONAL, array());
    }

    public function run(OutputInterface $output)
    {
        if (true === $this->options['remove_existing'])
        {
            $this->options['commands'][] = sprintf('rm -rf %s', $this->options['remote_dir']);
        }

        $this->options['commands'][] = sprintf('git clone %s %s', $this->options['repository'], $this->options['remote_dir']);

        if (true === $this->options['clean_scm'])
        {
            $this->options['commands'][] = sprintf('rm -rf %s/.git', $this->options['remote_dir']);
        }

        parent::run($output);
    }
}