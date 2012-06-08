<?php

namespace Rizeway\Anchour\Config;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Console\Output\OutputInterface;

use Rizeway\Anchour\Console\Application;
use Rizeway\Anchour\Step\StepFactory;
use Rizeway\Anchour\Connection\ConnectionFactory;

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

    /**
     * config Loader Constructor
     * @param Application $console
     * @param string      $filename
     */
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
        if (!isset($this->config['commands'])) {
            throw new \Exception('No commands defined');
        }

        $commands = array();
        foreach ($this->config['commands'] as $name => $command_config)
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
        $command_config = $this->getCommand($command_name);
        $steps_config = isset($command_config['steps']) ? $command_config['steps'] : array();
        $connections = $this->getCommandConnections($command_name);
        $factory = new StepFactory();
        $steps = array();
        foreach ($steps_config as $step_config)
        {
            $steps[] = $factory->build($step_config, $connections);
        }

        return $steps;
    }

    /**
     * Get Connections
     * @param  string $command_name
     * @return Rizeway\Anchour\Connection\ConnectionInterface[]
     */
    protected function getCommandConnections($command_name) 
    {
        $command_config = $this->getCommand($command_name); 
        $steps_config = isset($command_config['steps']) ? $command_config['steps'] : array();   
        // Building Connections
        $factory = new ConnectionFactory();
        $connection_objects = array();
        foreach ($steps_config as $step) {
            $connections_config =  isset($step['connections']) ? $step['connections'] : array();
            foreach ($connections_config as $name => $connection) {
                if (!isset($connection_objects[$connection])) {
                    $connection_objects[$connection] = $factory->build($this->getConnection($connection));
                }
            }
        }

        return $connection_objects;
    }

    /**
     * Get Required Parameters From Prompt
     * @param  string          $command_name
     * @param  OutputInterface $ouput        
     */
    public function resolveRequiredVariablesForCommand($command_name, OutputInterface $output)
    {
        $command_config = $this->getCommand($command_name);
        $variables_to_ask = array();

        // Get The Steps Required Variables
        $steps = isset($command_config['steps']) ? $command_config['steps']: array();
        foreach ($steps as $step) {
            $connections = isset($step['connections']) ?$step['connections'] : array();
            foreach ($connections as $connection) {
                $connection_config = $this->getConnection($connection);
                $variables_to_ask += $this->getVariablesToAskInArray($connection_config['options'] ? $connection_config['options'] : array());
            }
            $variables_to_ask += $this->getVariablesToAskInArray($step['options'] ? $step['options'] : array());
        }

        // Ask For The Required Variables
        $required_variables_values = array();
        foreach ($variables_to_ask as $var) {
            $required_variables_values[$var] = $this->console->getHelperSet()->get('dialog')->ask($output, sprintf('Entrer the <info>%s</info> : ', $var));
        }

        // Replace variables with values
        foreach ($steps as $key => $step) {
            $connections = isset($step['connections']) ? $step['connections'] : array();
            foreach ($connections as $connection) {
                if (isset($this->config['connections'][$connection]['options'])) {
                    $this->config['connections'][$connection]['options'] = 
                        $this->replaceValuesInRecursiveArray($this->config['connections'][$connection]['options'], $required_variables_values);
                }
            }
            if (isset($this->config['commands'][$command_name]['steps'][$key]['options'])) {
                $this->config['commands'][$command_name]['steps'][$key]['options'] = 
                    $this->replaceValuesInRecursiveArray($this->config['commands'][$command_name]['steps'][$key]['options'], $required_variables_values);
            }
        }
    }

    /**
     * @return mixed[]
     */
    protected function getCommand($name)
    {
        if (!isset($this->config['commands']) || !isset($this->config['commands'][$name])) {
            throw new \Exception(sprintf('The command %s was not found', $name));
        }

        return $this->config['commands'][$name];
    }

    /**
     * @return mixed[]
     */
    protected function getConnection($name)
    {
        if (!isset($this->config['connections']) || !isset($this->config['connections'][$name])) {
            throw new \Exception(sprintf('The connection %s was not defined', $name));
        }

        return $this->config['connections'][$name];
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
                if (preg_match('/^%([^0-9\-]+[a-zA-Z0-9_]*)%$/', $value)) {
                    $key_value = substr($value, 1, strlen($value) - 2);
                    if (isset($values[$key_value])) {
                        $result[$key] = $values[$key_value];
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Get The variables to ask %var% from a recursive array
     * @param  mixed[] $array 
     * @return string[]
     */
    protected function getVariablesToAskInArray($array)
    {
        $variables = array();
        foreach ($array as $value) {
            if (is_array($value)) {
                $variables += $this->getVariablesToAskInArray($value);
            } elseif (preg_match('/%([^0-9\-]+[a-zA-Z0-9_]*)%/', $value)) {
                $key = substr($value, 1, strlen($value) - 2);
                $variables[$key] = $key;
            }
        }

        return $variables;
    }
}