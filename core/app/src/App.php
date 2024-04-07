<?php

namespace core\app;

use core\di\components\BaseComponent;
use core\di\ComponentContainer;
use core\di\scanner\ComponentScanner;
use core\di\scanner\IComponentScanner;
use Exception;

class App extends BaseComponent
{
    private ComponentContainer $container;
    private ComponentScanner $scanner;
    private array $params;

    protected function init(): void
    {
        $this->params['components'] = $this->scanner->scanComponents();
        if (!array_key_exists('components', $this->params)) {
            throw new Exception();
        }
        $this->container = new ComponentContainer($this->params['components']);
    }

    public function run(): void
    {
        $router = $this->container->get('router');
        $this->container->get('userController');
        $controller = $this->container->get('testController');
        $controller->test();
//        $router->run();
    }

    public function __construct(array $params, IComponentScanner $scanner = null)
    {
        $this->params = $params;
        $this->scanner = $scanner ?? new ComponentScanner($params);
        parent::__construct();
    }
}