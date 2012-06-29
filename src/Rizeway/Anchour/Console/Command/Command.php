<?php
namespace Rizeway\Anchour\Console\Command;

use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Rizeway\Anchour\Config\Loader;
use Rizeway\Anchour\Config\Resolvers;

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

    protected function getResolver() {
        return $this->resolver;
    }
}
