<?php
namespace Rizeway\Anchour\Step\Definition;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Definition 
{
    const TYPE_OPTIONAL = 1;
    const TYPE_REQUIRED = 2;

    private $options = array();
    private $requiredOptions = array();

    private $connections = array();
    private $requiredConnections = array();

    public function bindOptions(array $options)
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults($this->options); 
        $resolver->setRequired(array_values($this->requiredOptions)); 

        return $resolver->resolve($options);
    }

    public function bindConnections(array $connections)
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults($this->connections); 
        $resolver->setRequired(array_values($this->requiredConnections));

        return $resolver->resolve($connections);
    }

    public function addConnection($connection, $type, $default = null) 
    {
        if(self::TYPE_REQUIRED === $type)
        {
            $this->requiredConnections[] = $connection;
        } 
        else 
        {
            $this->connections[$connection] = $default;
        }

        return $this;
    }

    public function addOption($option, $type, $default = null) 
    {
        if(self::TYPE_REQUIRED === $type)
        {
            $this->requiredOptions[] = $option;
        } 
        else 
        {
            $this->options[$option] = $default;
        }

        return $this;
    }
}