<?php
namespace jubianchi\Adapter;

class Adapter implements AdapterInterface {
    public function invoke($name, array $args = array()) {
        if(is_callable($name)) {
            return call_user_func_array($name, $args);
        }

        throw new \RuntimeException(sprintf('%s is not callable', var_export($name)));
    }

    public function __call($name, $args) {
        return $this->invoke($name, $args);
    }
}
