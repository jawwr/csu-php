<?php

namespace csuPhp\Csu2024;

use Exception;

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
        $router->run();
    }

    public function __construct(array $params)
    {
        $this->params = $params;
        parent::__construct($params);
    }
}