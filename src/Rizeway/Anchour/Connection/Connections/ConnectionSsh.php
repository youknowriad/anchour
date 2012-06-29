<?php

namespace Rizeway\Anchour\Connection\Connections;

use Rizeway\Anchour\Connection\Connection;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Console\Output\OutputInterface;

use OOSSH\SSH2\Connection as SshConnection;
use OOSSH\SSH2\Authentication\Password;

class ConnectionSsh extends Connection
{
    /**
     * @var \OOSSH\SSH2\Connection
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
            'port' => '22',
        ));
    }

    public function connect(OutputInterface $output)
    {
        if (false === $this->isConnected())
        {
            $output->writeln(sprintf('Opening <info>SSH</info> connection to <info>%s</info> <comment>(%s)</comment>', $this->options['host'], (string)$this));

            $this->connection = new SshConnection($this->options['host'], $this->options['port']);
            $this->connection
                ->connect()
                ->authenticate(new Password($this->options['username'], $this->options['password']));
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

    public function getHost()
    {
        return $this->options['host'];
    }

    public function getUsername()
    {
        return $this->options['username'];
    }

    public function getPassword()
    {
        return $this->options['password'];
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