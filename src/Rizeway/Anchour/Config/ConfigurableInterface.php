<?php
namespace Rizeway\Anchour\Config;

use \Rizeway\Anchour\Config\ResolverInterface;

interface ConfigurableInterface
{
    public function getConfig();
    public function resolveConfiguration(ResolverInterface $resolver);
}
