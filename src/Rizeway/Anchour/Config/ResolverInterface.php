<?php
namespace Rizeway\Anchour\Config;

use \Rizeway\Anchour\Config\ConfigurableInterface;

interface ResolverInterface {
    function resolve(ConfigurableInterface $command);
}
