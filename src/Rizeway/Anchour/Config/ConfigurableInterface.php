<?php
namespace Rizeway\Anchour\Config;

use \Rizeway\Anchour\Config\ResolverInterface;

interface ConfigurableInterface
{
    function getConfig();
    function setConfig(array $config);
    function resolveConfiguration(ResolverInterface $resolver);
}
