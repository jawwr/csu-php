<?php

namespace csuPhp\Csu2024\components;

abstract class BaseComponent implements Component {
    public function __construct() {
        $this->init();
    }

    protected function init(): void {}
}