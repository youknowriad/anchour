<?php

namespace Rizeway\Anchour\Connection\Connections;

use Rizeway\Anchour\Connection\Connection;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConnectionMysql extends Connection
{
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'host',
            'username',
            'password',
            'database'
        ));

        $resolver->setDefaults(array(
            'port' => '3306'
        ));
    }

    public function connect(OutputInterface $output)
    {
    }

    public function isConnected()
    {
        return false;
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

    public function getDatabase()
    {
        return $this->options['database'];
    }

    public function __toString()
    {
        return sprintf(
            '%s@%s%s/%s',
            $this->options['username'],
            $this->options['host'],
            $this->options['port'] ? ':' . $this->options['port'] : '',
            $this->options['database']
        );
    }
}