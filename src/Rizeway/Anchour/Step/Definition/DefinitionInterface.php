<?php
namespace Rizeway\Anchour\Step\Definition;

interface DefinitionInterface
{
    public function bindOptions(array $options);
    public function bindConnections(array $connections);
    public function addConnection($connection, $type, $default = null);
    public function addOption($option, $type, $default = null);
}
