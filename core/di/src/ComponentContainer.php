<?php

namespace core\di;

use core\di\components\BaseComponent;
use core\di\exception\ComponentNotFoundException;
use Psr\Container\ContainerInterface;
use ReflectionClass;

class ComponentContainer extends BaseComponent implements ContainerInterface {
    private array $components;
    private array $config;

    public function get(string $id) {
        if (array_key_exists($id, $this->components)) {
            return $this->components[$id];
        }
        if (!isset($this->config[$id])) {
            throw new ComponentNotFoundException("Components with id '$id' not found");
        }

        $class = $this->config[$id];

//        print_r($this->config);

        if (!class_exists($class)) {
            throw new ComponentNotFoundException("Class '$class' not found");
        }
        $ref = new ReflectionClass($class);
        $constructor = $ref->getConstructor();
        $parameters = [];
        if ($constructor != null) {
            $constructorParameters = $constructor->getParameters();
            $index = 0;
            foreach ($constructorParameters as $param) {
                $obj = new ($param->getType()->getName())();
                $array = preg_split("#\\\#", get_class($obj));
                $paramName = lcfirst(end($array));
                $component = $this->get($paramName);
                $parameters[$index] = $component;
                $index++;
            }
        }

        $component = $ref->newInstanceArgs($parameters);
        $this->components[$id] = $component;
        return $component;
    }


    public function has(string $id): bool {
        if(array_key_exists($id, $this->components)) {
            return true;
        }
        if (array_key_exists($id, $this->config)) {
            return true;
        }
        return false;
    }

    public function __construct(array $params)
    {
        parent::__construct();
        $this->config = $params;
        $this->components = [];
    }
}