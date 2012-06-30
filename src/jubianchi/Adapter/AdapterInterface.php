<?php
namespace jubianchi\Adapter;

interface AdapterInterface
{
    public function invoke($name, array $args = array());
}
