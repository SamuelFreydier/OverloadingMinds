<?php

namespace App\Src\ServiceContainer;

class ServiceContainer
{
    private $container = array();

    public function get(string $serviceName) {
        return $this->container[$serviceName];
    }

    public function set(string $name, $assigned) {
        $this->container[$name] = $assigned;
    }

    public function unset(string $name) {
        unset($this->container[$name]);
    }
}