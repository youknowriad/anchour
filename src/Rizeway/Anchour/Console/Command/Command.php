<?php
namespace Rizeway\Anchour\Console\Command;

use Symfony\Component\Console\Command\Command as BaseCommand;

class Command extends BaseCommand
{
    /**
     * @var array
     */
    private $config = array();

    /**
     * @var \Rizeway\Anchour\Config\Resolver
     */
    private $resolver;

    /**
     * @param array $config
     *
     * @return \Rizeway\Anchour\Console\Command\Command
     */
    public function setConfig(array $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    protected function getResolver()
    {
        return $this->resolver;
    }
}
