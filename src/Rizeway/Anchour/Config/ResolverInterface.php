<?php
namespace Rizeway\Anchour\Config;

use \Rizeway\Anchour\Config\ConfigurableInterface;

interface ResolverInterface
{
    public function resolve(ConfigurableInterface $command);
}
