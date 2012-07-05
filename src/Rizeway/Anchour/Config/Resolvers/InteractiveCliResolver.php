<?php
namespace Rizeway\Anchour\Config\Resolvers;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;

use jubianchi\Adapter\AdapterInterface;

use Rizeway\Anchour\Config\ConfigurableInterface;
use Rizeway\Anchour\Config\Resolver;

class InteractiveCliResolver extends Resolver
{
    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    private $output;

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \Symfony\Component\Console\Helper\DialogHelper    $dialog
     */
    public function __construct(OutputInterface $output, DialogHelper $dialog, AdapterInterface $adapter = null)
    {
        $this->setAdapter($adapter);

        $this->output = $output;
        $this->dialog = $dialog;
    }

    public function getValues(ConfigurableInterface $command, $exclude = array()) {
        $values = array();

        foreach ($this->getVariablesToAskInArray($command->getConfig()) as $key => $var) {
            if(false === in_array($var, $exclude)) {
                $question = sprintf('Entrer the <info>%s (%s)</info> : ', $key, $var);

                if (preg_match('/password|pwd|passwd?/', $key) > 0) {
                    $this->output->write($question);
                    $values[$var] = $this->getAdapter()->exec('stty -echo; read PASSWORD; stty echo; echo $PASSWORD');
                    $this->output->writeln('');
                } else {
                    $values[$var] = $this->dialog->ask($this->output, $question);
                }
            }
        }

        return $values;
    }
}
