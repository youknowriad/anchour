<?php
namespace Rizeway\Anchour\Step\Definition;

interface DefinitionInterface 
{
    function bindOptions(array $options);
    function bindConnections(array $connections);
    function addConnection($connection, $type, $default = null);
    function addOption($option, $type, $default = null); 
}