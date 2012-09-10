<?php
namespace Rizeway\Anchour\Config;

use \Rizeway\Anchour\Config\ConfigurableInterface;

interface ResolverInterface
{
    function getValues(ConfigurableInterface $command);
    function resolve(ConfigurableInterface $command);
}
