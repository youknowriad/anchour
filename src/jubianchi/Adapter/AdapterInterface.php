<?php
namespace jubianchi\Adapter;

interface AdapterInterface {
	function invoke($name, array $args = array());	
}