<?php
namespace Rizeway\Anchour\Step\Steps;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

use Rizeway\Anchour\Step\Step;
use Rizeway\Anchour\Step\Definition\Definition;

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
        $this->addOption('depth', Definition::TYPE_OPTIONAL, false);
    }

    public function run(InputInterface $input, OutputInterface $output)
    {
        if (true === $this->getOption('remove_existing')) {
            $this->exec(sprintf('rm -rf %s', $this->getOption('remote_dir')), $output);
        }

        $options = array();
        if ($this->getOption('depth')) {
            $options[] = sprintf('--depth %d', $this->getOption('depth'));
        }
        $options[] = $this->getOption('repository');
        $options[] = $this->getOption('remote_dir');

        $this->exec(sprintf('git clone %s', implode(' ', $options)), $output);

        if (true === $this->getOption('clean_scm')) {
            $this->exec(sprintf('rm -rf %s/.git', $this->getOption('remote_dir')), $output);
        }
    }
}
