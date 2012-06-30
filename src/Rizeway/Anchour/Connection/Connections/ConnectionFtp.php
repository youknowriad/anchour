<?php

namespace Rizeway\Anchour\Connection\Connections;

use Rizeway\Anchour\Connection\Connection;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Console\Output\OutputInterface;

use jubianchi\Ftp\Ftp;

class ConnectionFtp extends Connection
{
    /**
     * @var \jubianchi\Ftp\Ftp
     */
    private $connection;

    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'host',
            'username',
            'password'
        ));

        $resolver->setDefaults(array(
            'port' => '21',
            'timeout' => '90',
        ));
    }

    public function connect(OutputInterface $output)
    {
        if (false === $this->isConnected()) {
            $output->writeln(sprintf('Opening <info>FTP</info> connection to <info>%s</info>', $this->options['host']));

            $this->connection = new Ftp();
            $this->connection->connect($this->options['host'], $this->options['username'], $this->options['password'], $this->options['port'], $this->options['timeout']);
        }
    }

    public function isConnected()
    {
        return ($this->connection !== null && $this->connection->isConnected());
    }

    public function __call($name, $args)
    {
        return call_user_func_array(array($this->connection, $name), $args);
    }

    public function __toString()
    {
        return sprintf(
            '%s@%s%s',
            $this->options['username'],
            $this->options['host'],
            $this->options['port'] ? ':' . $this->options['port'] : ''
        );
    }
}
