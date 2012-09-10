<?php
namespace jubianchi\Output\Symfony\Adapter;

use Symfony\Component\Console\Output\ConsoleOutput;
use jubianchi\Output\OutputInterface;

class ConsoleOutputAdapter implements OutputInterface
{
    private $output;

    public function __construct(ConsoleOutput $output)
    {
        $this->output = $output;
    }

    public function writeln($message)
    {
        return $this->output->writeln($message);
    }
}
