<?php

namespace csuPhp\Csu2024;

use csuPhp\Csu2024\components\BaseComponent;
use csuPhp\Csu2024\di\ComponentContainer;
use csuPhp\Csu2024\request\RequestFactoryImpl;
use csuPhp\Csu2024\response\ResponseFactoryImpl;
use csuPhp\Csu2024\router\Router;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class App extends BaseComponent
{
    private ComponentContainer $container;
    private array $params;

    protected function init(): void
    {
        if (!array_key_exists('components', $this->params)) {
            throw new Exception();
        }
        $this->container = new ComponentContainer($this->params['components']);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function run(): void
    {
        $router = $this->container->get('router');
        $this->container->get('userController');
//        $router->run();
    }

    public function __construct(array $params)
    {
        $this->params = $params;
        $this->scanComponents();
        parent::__construct();
    }

    private function scanComponents(): void
    {
        if (!isset($this->params['componentScan'])) {
            $this->params['componentScan'] = __DIR__;
        }
        $classes = $this->scanClasses($this->params['componentScan']);
        $this->addComponentsToConfig($classes);
        $this->addStaticComponents();
    }

    private function addStaticComponents(): void
    {
        $components = &$this->params['components'];
        $components['router'] = Router::class;
        $components['requestFactoryImpl'] = RequestFactoryImpl::class;
        $components['responseFactoryImpl'] = ResponseFactoryImpl::class;
    }

    private function addComponentsToConfig(array $classes): void
    {
        if (!isset($this->params['components'])) {
            $this->params['components'] = [];
        }
        if (!is_array($this->params['components'])) {
            $this->params['components'] = [];
        }
        foreach ($classes as $_ => $class) {
            $array = explode("\\", $class);
            $name = lcfirst(end($array));
            $this->params['components'][$name] = $class;
        }
    }

    private function scanClasses($dir): array
    {
        $classes = [];

        $files = scandir($dir);

        foreach ($files as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            $path = $dir . '/' . $file;

            if (is_dir($path)) {
                $classes = array_merge($classes, $this->scanClasses($path));
            } elseif ($file == 'App.php' || str_contains($path, 'components') || str_contains($path, 'di')) {
                continue;
            } else {
                $content = file_get_contents($path);

                if (preg_match_all('/\bclass\s+([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\b/', $content, $matches)) {
                    $name = preg_replace('/\.php/', '', $path);
                    $name = preg_replace('#' . $this->params['componentScan'] . '#', __NAMESPACE__, $name);
                    $name = preg_replace("#/#", "\\", $name);
                    $classes[] = $name;
                }
            }
        }

        return $classes;
    }
}