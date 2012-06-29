<?php
namespace jubianchi\Output\Symfony\Adapter;

use Symfony\Component\Console\Output\ConsoleOutput;

class ConsoleOutputAdapter implements OutputInterface
{
    private $output;

    public function __construct(ConsoleOutput $output)
    {
        $this->output = $output;
    }

    public function __call($name, $args)
    {
        return call_user_func_array(array($this->output, $name), $args);
    }
}
