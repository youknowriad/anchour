<?php
namespace Rizeway\Anchour\Config\Resolvers;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;

use Rizeway\Anchour\Config\ConfigurableInterface;
use Rizeway\Anchour\Config\Resolver;

class InteractiveCliResolver extends Resolver {
    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    private $output;

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \Symfony\Component\Console\Helper\DialogHelper    $dialog
     */
    public function __construct(OutputInterface $output, DialogHelper $dialog) {
        $this->output = $output;
        $this->dialog = $dialog;
    }

    /**
     * Get Required Parameters From Prompt
     *
     * @param Command $command
     */
    public function resolve(ConfigurableInterface $command)
    {
        $values = array();

        foreach ($this->getVariablesToAskInArray($command->getConfig()) as $key => $var) {
            $question = sprintf('Entrer the <info>%s (%s)</info> : ', $key, $var);

            if(preg_match('/password|pwd|passwd?/', $key) > 0) {
                $this->output->write($question);
                $values[$var] = exec('stty -echo; read PASSWORD; stty echo; echo $PASSWORD');
                $this->output->writeln('');
            } else {
                $values[$var] = $this->dialog->ask($this->output, $question);
            }
        }

        return $this->replaceValuesInRecursiveArray($command->getConfig(), $values);
    }
}
