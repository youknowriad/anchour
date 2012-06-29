<?php

namespace Rizeway\Anchour\Connection;

use Symfony\Component\Console\Output\OutputInterface;

interface ConnectionInterface
{

    /**
     * Open the connection
     */
    public function connect(OutputInterface $output);

    /**
     * @abstract
     *
     * @return bool
     */
    public function isConnected();
}
