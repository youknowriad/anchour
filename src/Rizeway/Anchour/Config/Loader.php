<?php

namespace Rizeway\Anchour\Config;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Console\Output\OutputInterface;

use Rizeway\Anchour\Console\Application;
use Rizeway\Anchour\Step\StepFactory;
use Rizeway\Anchour\Connection\ConnectionFactory;
use Rizeway\Anchour\Connection\ConnectionHolder;

class Loader
{
    /**
     * Yaml Config
     * @var mixed[]
     */
    protected $config;

    /**
     * The anchour console
     * @var Application
     */
    protected $console;

    /**
     * the required values
     * @var string[]
     */
    protected $required_values = array();

    public function __construct(Application $console, $filename)
    {
        $this->config = Yaml::parse($filename);
        $this->console = $console;
    }

    /**
     * Get Commands
     * @return string[]
     */
    public function getCommands() 
    {
        $commands = array();
        foreach ($this->config as $name => $command_config)
        {
            $description = isset($command_config['description']) ? $command_config['description'] : $name;
            $commands[$name] = $description;
        }

        return $commands;
    }

    /**
     * Get Steps
     * @param  string $command_name
     * @return string[]
     */
    public function getCommandSteps($command_name) 
    {
        if (!isset($this->config[$command_name])) {
            throw new \Exception(sprintf('The command %s was not found', $command_name));
        }
        $command_config = $this->config[$command_name];
        $steps_config = isset($command_config['steps']) ? $command_config['steps'] : $command_name;
        $factory = new StepFactory();
        $steps = array();
        foreach ($steps_config as $step_config)
        {
            $steps[] = $factory->build($step_config);
        }

        return $steps;
    }

    /**
     * Get Connections
     * @param  string $command_name
     * @return string[]
     */
    public function getCommandConnections($command_name, $output) 
    {
        if (!isset($this->config[$command_name])) {
            throw new \Exception(sprintf('The command %s was not found', $command_name));
        }
        $command_config = $this->config[$command_name];
        $connections_config = isset($command_config['connections']) ? $command_config['connections'] : array();
        
        // Fillign the required values in the connections array
        $connections = array();
        foreach ($connections_config as $name => $connection)
        {
            if (isset($connection['options'])) {
                $connection['options'] = $this->replaceValuesInRecursiveArray($connection['options'], $this->required_values);
            }

            $connections[$name] = $connection;
        }

        // Building Connections
        $factory = new ConnectionFactory();
        $connection_objects = new ConnectionHolder();
        foreach ($connections as $name => $connection) {
            $connection_objects[$name] = $factory->build($connection);
        }

        return $connection_objects;
    }

    /**
     * Get Required Parameters From Prompt
     * @param  string          $command_name
     * @param  OutputInterface $ouput        
     */
    public function resolveRequiredParametersForCommand($command_name, OutputInterface $output)
    {
        if (!isset($this->config[$command_name])) {
            throw new \Exception(sprintf('The command %s was not found', $command_name));
        }
        $command_config = $this->config[$command_name];
        $requires = isset($command_config['require']) ? $command_config['require'] : array();
        $required_values = array();
        foreach ($requires as $key => $name) {
            $required_values[$key] = $this->console->getHelperSet()->get('dialog')->ask($output, sprintf('Entrer the "%s" : ', $name));
        }

        $this->required_values = $required_values;
    }

    /**
     * Replace all %option% in the $array with their values in $values
     * @param  mixed[] $array 
     * @param  string[] values 
     * @return mixed[]
     */
    protected function replaceValuesInRecursiveArray($array, $values) 
    {
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result[$key] = $this->replaceValuesInRecursiveArray($value, $values);
            } else {
                $result[$key] = $value;
                if (substr($value, 0, 1) == '%' && substr($value, -1, 1) == '%') {
                    $key_value = substr($value, 1, strlen($value) - 2);
                    if (isset($values[$key_value])) {
                        $result[$key] = $values[$key_value];
                    }
                }
            }
        }

        return $result;
    }
}