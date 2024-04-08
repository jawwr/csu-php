<?php

namespace core\app;

use core\di\components\BaseComponent;
use core\di\ComponentContainer;
use core\di\scanner\ComponentScanner;
use core\di\scanner\IComponentScanner;
use core\router\route\Route;
use Exception;

class App extends BaseComponent
{
    private ComponentContainer $container;
    private IComponentScanner $scanner;
    private array $params;

    protected function init(): void
    {
        $this->params['components'] = $this->scanner->scanComponents();
        if (!array_key_exists('components', $this->params)) {
            throw new Exception();
        }
        $this->container = new ComponentContainer($this->params['components']);
        $this->loadRoutes();
    }

    private function loadRoutes() {
        $routes = &Route::$routes;
        foreach($routes as &$route) {
            foreach($route as &$method) {
                $array = preg_split("#\\\#", $method['class']);
                $name = lcfirst(end($array));
                $method['class'] = $this->container->get($name);
            }
        }
    }

    public function run(): void
    {
        $router = $this->container->get('router');
       $router->start();
    }

    public function __construct(array $params, IComponentScanner $scanner = null)
    {
        $this->params = $params;
        $this->scanner = $scanner ?? new ComponentScanner($params);
        parent::__construct();
    }
}