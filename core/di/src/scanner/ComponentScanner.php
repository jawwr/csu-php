<?php

namespace core\di\scanner;

use core\di\components\BaseComponent;
use core\router\request\RequestFactoryImpl;
use core\router\response\ResponseFactoryImpl;
use core\router\router\Router;

class ComponentScanner extends BaseComponent implements IComponentScanner {
    private array $params;

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function scanComponents(): array
    {
        if (!isset($this->params['componentScan'])) {
            $this->params['componentScan'] = __DIR__;
        }
        $classes = $this->scanClasses($this->params['componentScan']);
        $this->addComponentsToConfig($classes);
        $this->addStaticComponents();
        return $this->params['components'];
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
                    $className = $matches[1][0];
                    preg_match_all('/\bnamespace\s+([^;]+)\b/', $content, $matches);
                    $namespace = $matches[1][0];
                    $name = $namespace . '/' . $className;
                    $name = preg_replace("#/#", "\\", $name);
                    $classes[] = $name;
                }
            }
        }

        return $classes;
    }
}