<?php

namespace Rizeway\Anchour\Config;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Console\Output\OutputInterface;

use Rizeway\Anchour\Step\StepFactory;
use Rizeway\Anchour\Connection\ConnectionFactory;
use Rizeway\Anchour\Config\Validator;
use Rizeway\Anchour\Console\Command\TargetCommand;

class Loader
{
    /**
     * Yaml Config
     * @var mixed[]
     */
    protected $config = array();

    /**
     * the required values
     * @var string[]
     */
    protected $required_values = array();

    /**
     * config Loader Constructor
     *
     * @param string      $filename
     */
    public function __construct($filename)
    {
        if(file_exists($filename)) {
            $this->config = Yaml::parse($filename);

            $validator = new Validator();
            $validator->validate((array)$this->config);
        }
    }

    /**
     * Get Commands
     * @return string[]
     */
    public function getCommands() 
    {
        $commands = array();
        foreach ($this->config['anchour']['commands'] as $name => $config)
        {
            $description = isset($command_config['description']) ? $command_config['description'] : $name;

            $command = new TargetCommand($name);
            $command->setDescription($description);
            $command->setConfig($config);
            $command->setSteps($this->getCommandSteps($config, $this->getCommandConnections($config)));

            $commands[$name] = $command;
        }

        return $commands;
    }

    /**
     * Get Steps
     * @param  array $config
     * @return string[]
     */
    public function getCommandSteps($config, $connections) 
    {
        $steps_config = isset($config['steps']) ? $config['steps'] : array();        
        $factory = new StepFactory();
        $steps = array();
        
        foreach ($steps_config as $name => $config)
        {
            $step = $factory->build($config, $connections);

            $steps[$name] = $step;
        }

        return $steps;
    }

    /**
     * Get Connections
     * @param  string $command_name
     * @return Rizeway\Anchour\Connection\ConnectionInterface[]
     */
    protected function getCommandConnections($config) 
    {
        $steps_config = isset($config['steps']) ? $config['steps'] : array();   
        $factory = new ConnectionFactory();
        $connection_objects = array();

        foreach ($steps_config as $step) {
            $connections_config = isset($step['connections']) ? $step['connections'] : array();
            
            foreach ($connections_config as $name => $connection) {
                if (!isset($connection_objects[$connection])) {
                    $connection_objects[$connection] = $factory->build($this->getConnection($connection));
                }
            }
        }

        return $connection_objects;
    }

    /**
     * @return mixed[]
     */
    protected function getConnection($name)
    {
        if (!isset($this->config['anchour']['connections']) || !isset($this->config['anchour']['connections'][$name])) {
            throw new \Exception(sprintf('The connection %s was not defined', $name));
        }

        return $this->config['anchour']['connections'][$name];
    }
}