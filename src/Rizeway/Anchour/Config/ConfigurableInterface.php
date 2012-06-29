<?php
namespace Rizeway\Anchour\Config;

use \Rizeway\Anchour\Config\ResolverInterface;

interface ConfigurableInterface {
	function getConfig();
	function resolveConfiguration(ResolverInterface $resolver);
}