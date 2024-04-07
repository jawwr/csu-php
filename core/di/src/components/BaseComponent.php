<?php

namespace core\di\components;

abstract class BaseComponent implements Component {
    public function __construct() {
        $this->init();
    }

    protected function init(): void {}
}