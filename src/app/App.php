<?php

namespace csuPhp\Csu2024;

use csuPhp\Csu2024\components\BaseComponent;
use csuPhp\Csu2024\di\ComponentContainer;
use Exception;
use ReflectionClass;
use ReflectionException;

class App extends BaseComponent
{
    private ComponentContainer $container;
    private array $params;

    protected function init(): void {
        if (!array_key_exists('components', $this->params)) {
            throw new Exception();
        }
        $this->container = new ComponentContainer($this->params['components']);
    }

    public function run() {
         $router = $this->container->get('router');
//         $router->run();
    }

    public function __construct(array $params)
    {
        $this->params = $params;
        $this->scanComponents();
        parent::__construct();
//        $this->injectComponent();
    }

    private function scanComponents() {
        // echo "SCAN COMPONENTS";
        // $dir = scandir(__DIR__ . '/..');
        // $this->scanDir(__DIR__ . '/..');
        // echo "<pre>";
        // print_r($dir);
    }

    private function scanDir($target) {
        if(is_dir($target)){
            $files = glob( $target . '*', GLOB_MARK );
            foreach( $files as $file )
            {
                if(is_file($file)) {
                    echo $file;
                    $class = new ReflectionClass(str_replace('.php', '', $file));
                }
                $this->scanDir($file);
            }
        } 
    }

    /**
     * @throws ReflectionException
     */
    private function injectComponent(): void {
        $components = $this->params['components'];
        foreach($components as $name => $class) {
            $ref = new ReflectionClass($class);
            $constructor = $ref->getConstructor();
            $constructorParameters = $constructor->getParameters();
            $params = [];
            $index = 0;
            foreach($constructorParameters as $param) {
                $obj = new ($param->getType()->getName())();
                $array = preg_split('\\', get_class($obj));
                $getcls = end($array);
                echo $getcls;
                $paramName = lcfirst($getcls);
                echo $paramName;
                $component = $this->container->get($paramName);
                $params[$index] = $component;
                $index++;
            }
            new $ref($params);
        }
    }
}